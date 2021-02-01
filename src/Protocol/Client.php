<?php

declare(strict_types=1);

namespace EdgeDB\Protocol;

use InvalidArgumentException;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Symfony\Component\OptionsResolver\OptionsResolver;
use function getenv;
use function parse_url;
use function urldecode;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
final class Client
{
    /**
     * @var ConnectionPool
     */
    private $pool;

    /**
     * @var array<string, mixed>
     */
    private $options;

    private function __construct(string $dsn, array $options = [], LoggerInterface $logger = null)
    {
        $configuration = $this->parseDsn($dsn);

        $this->handleConfiguration($configuration);

        $this->pool = new ConnectionPool();
        $this->pool->add(new Connection());

        $connection = pg_connect(sprintf('host=127.0.0.1 dbname=foo port=10700 user=edgedb'));
        $result = pg_prepare($connection, 'select_movies', 'SELECT Movies;');
        dump(pg_connect_poll($connection));die;
    }

    public static function connect(string $dsn, array $options = []): self
    {
        if (!function_exists('pg_connection_status')) {
            throw new RuntimeException('The pgsql extension must be enabled (not PDO one)');
        }

        return new self($dsn, $options);
    }

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

        $user = isset($parsedDsn['user']) ? urldecode($parsedDsn['user']) : null;
        $password = isset($parsedDsn['pass']) ? urldecode($parsedDsn['pass']) : null;
        $port = $parsedDsn['port'] ?? 5656;
        $path = $parsedDsn['path'] ?? null;

        return [
            'scheme' => $parsedDsn['scheme'],
            'host' => $parsedDsn['host'],
            'user' => $user,
            'password' => $password,
            'port' => $port,
            'database' => $path,
        ];
    }

    private function handleConfiguration(array $configuration): void
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            'scheme' => 'edgedb',
            'host' => 'localhost',
            'port' => 5656,
            'user' => null,
            'password' => null,
            'database' => null,
            'admin' => false,
            'timeout' => null,
        ]);

        $resolver->setAllowedTypes('scheme', 'string');
        $resolver->setAllowedTypes('host', 'string');
        $resolver->setAllowedTypes('port', 'int');
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
