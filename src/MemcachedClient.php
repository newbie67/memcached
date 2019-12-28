<?php

declare(strict_types=1);

namespace newbie67\memcached;

use newbie67\memcached\Domain\ClientInterface;

final class MemcachedClient implements ClientInterface
{
    /**
     * @var false|resource
     */
    private $connection;

    /**
     * Client constructor.
     *
     * @param string $addr
     * @param int    $port
     */
    public function __construct(string $addr, int $port)
    {
        $this->connection = fsockopen($addr, $port);
    }

    /**
     * @param string $request
     *
     * @return false|int
     */
    public function write($request)
    {
        return fwrite($this->connection, $request . PHP_EOL);
    }

    /**
     * @param int|null $length
     *
     * @return string
     */
    public function readNextString(?int $length = null): string
    {
        return null !== $length ? fgets($this->connection, $length) : fgets($this->connection);
    }

    /**
     *
     */
    public function __destruct()
    {
        if ($this->connection !== false) {
            fclose($this->connection);
        }
    }
}
