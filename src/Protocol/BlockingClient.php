<?php

declare(strict_types=1);

namespace EdgeDB\Protocol;

use InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use RuntimeException;
use Symfony\Component\OptionsResolver\OptionsResolver;
use function getenv;
use function parse_url;
use function urldecode;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
final class BlockingClient implements ClientInterface
{
    /**
     * @var array<string, mixed>
     */
    private array $options;
    private LoggerInterface $logger;

    private function __construct(string $dsn, array $options = [], LoggerInterface $logger = null)
    {
        $configuration = $this->parseDsn($dsn);

        $this->handleConfiguration($configuration);
        $this->logger = $logger ?: new NullLogger();
    }

    /**
     * {@inheritdoc}
     */
    public static function connect(string $dsn, array $options = [], LoggerInterface $logger = null): ClientInterface
    {
        if (!function_exists('pg_connection_status')) {
            throw new RuntimeException('The pgsql extension must be enabled (not PDO one)');
        }

        return new self($dsn, $options, $logger);
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    private function parseDsn(string $dsn): array
    {
        if (false === $parsedDsn = parse_url($dsn)) {
            throw new InvalidArgumentException('The given DSN is not valid');
        }

        if (!isset($parsedDsn['scheme'])) {
            throw new InvalidArgumentException('The scheme must be equal to "edgedb" or a custom instance name');
        }

        if (!isset($parsedDsn['host'])) {
            throw new InvalidArgumentException('The host must be defined');
        }

        return [
            'scheme' => $parsedDsn['scheme'],
            'host' => $parsedDsn['host'],
            'user' => isset($parsedDsn['user']) ? urldecode($parsedDsn['user']) : null,
            'password' => isset($parsedDsn['pass']) ? urldecode($parsedDsn['pass']) : null,
            'port' => $parsedDsn['port'] ?? 5656,
            'database' => $parsedDsn['path'] ?? null,
        ];
    }

    private function handleConfiguration(array $configuration): void
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            'scheme' => 'edgedb',
            'host' => '127.0.0.1',
            'port' => 5656,
            'user' => 'edgedb',
            'password' => null,
            'database' => 'edgedb',
            'admin' => false,
            'timeout' => null,
        ]);

        $resolver->setAllowedTypes('scheme', ['string']);
        $resolver->setAllowedTypes('host', ['string']);
        $resolver->setAllowedTypes('port', ['int']);
        $resolver->setAllowedTypes('user', ['string', 'null']);
        $resolver->setAllowedTypes('password', ['string', 'null']);
        $resolver->setAllowedTypes('database', ['string', 'null']);

        $resolver->setAllowedValues('host', function ($host) use ($configuration): bool {
            if (null !== $host) {
                return true;
            }

            if (false !== getenv('EDGEDB_HOST')) {
                return true;
            }

            if (null !== $configuration['host']) {
                return true;
            }

            return false;
        });

        $resolver->setAllowedValues('port', function ($port) use ($configuration): bool {
            if (null !== $port) {
                return true;
            }

            if (false !== getenv('EDGEDB_PORT')) {
                return true;
            }

            if (null !== $configuration['port']) {
                return true;
            }

            return false;
        });

        $resolver->setAllowedValues('user', function ($user) use ($configuration): bool {
            if (null !== $user) {
                return true;
            }

            if (false !== getenv('EDGEDB_USER')) {
                return true;
            }

            if (null !== $configuration['user']) {
                return true;
            }

            return false;
        });

        $resolver->setAllowedValues('database', function ($database) use ($configuration): bool {
            if (null !== $database) {
                return true;
            }

            if (false !== getenv('EDGEDB_DATABASE')) {
                return true;
            }

            if (null !== $configuration['database']) {
                return true;
            }

            return false;
        });

        $resolver->setAllowedValues('password', function ($password) use ($configuration): bool {
            if (null === $password) {
                return true;
            }

            if (false !== getenv('EDGEDB_PASSWORD')) {
                return true;
            }

            if (null !== $configuration['password']) {
                return true;
            }

            return false;
        });

        $this->options = $resolver->resolve($configuration);
    }
}
