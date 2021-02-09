<?php

declare(strict_types=1);

namespace EdgeDB\Protocol;

use EdgeDB\Exception\RuntimeException;
use EdgeDB\Exception\InvalidArgumentException;
use function array_key_exists;
use function file_exists;
use function file_get_contents;
use function is_int;
use function is_string;
use function json_decode;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
final class Credentials
{
    private int $port;
    private string $host;
    private string $database;
    private string $username;
    private string $password;

    public static function load(string $path): self
    {
        $self = new self();

        if (!file_exists($path)) {
            throw new InvalidArgumentException('The credentials file does not exist');
        }

        $credentials = json_decode(file_get_contents($path), true);

        if (!$self->validateCredentials($credentials)) {
            throw new RuntimeException('The credentials are not valid');
        }

        $self->port = $credentials['port'];
        $self->host = $credentials['host'];
        $self->database = $credentials['database'];
        $self->username = $credentials['username'];
        $self->password = $credentials['password'];

        return $self;
    }

    public function getPort(): int
    {
        return $this->port;
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getDatabase(): string
    {
        return $this->database;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    private function validateCredentials(array $credentials = []): bool
    {
        $credentials['port'] = $credentials['port'] ?? 5656;

        if (!is_int($credentials['port']) && ($credentials['port'] < 1 || $credentials['port'] > 65535)) {
            throw new RuntimeException('The port is not valid');
        }

        if (array_key_exists('host', $credentials) && !is_string($credentials['host'])) {
            throw new RuntimeException('The host is not valid');
        }

        if (array_key_exists('database', $credentials) && !is_string($credentials['database'])) {
            throw new RuntimeException('The database is not valid');
        }

        if (array_key_exists('user', $credentials) && !is_string($credentials['user'])) {
            throw new RuntimeException('The user is not valid');
        }

        if (array_key_exists('password', $credentials) && !is_string($credentials['password'])) {
            throw new RuntimeException('The password is not valid');
        }
    }
}
