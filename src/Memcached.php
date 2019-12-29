<?php

declare(strict_types=1);

namespace newbie67\memcached;

class Memcached
{
    /**
     * @var false|resource
     */
    private $connection;

    /**
     * Memcached constructor.
     *
     * @param string $addr
     * @param int    $port
     */
    public function __construct(string $addr, int $port)
    {
        $this->connection = fsockopen($addr, $port);
    }

    /**
     * @param string $key
     * @param bool   $throwsException
     *
     * @return string|bool Value string or false if there's no this key
     *
     * @throws MemcachedException
     */
    public function get(string $key, bool $throwsException = false)
    {
        $this->validateKey($key);

        $this->write('get ' . $key);

        $response = fgets($this->connection);

        if (strpos($response, 'VALUE ' . $key) === 0) {
            // get value
            $response = trim($response);
            $tmp = explode(' ', $response);
            $valueSize = end($tmp);
            $value = fread($this->connection, (int)$valueSize + 2);
            $nextResponse = fgets($this->connection);

            if ($nextResponse === "END\r\n") {
                return substr($value, 0, -2);
            } else {
                $this->unknownResponse($response);
            }
        } elseif ($response === "END\r\n") {
            if ($throwsException) {
                throw new MemcachedException('Key "' . $key . '" is not enabled.');
            }
            return false;
        }

        $this->unknownResponse($response);
    }

    /**
     * @param string $key
     * @param string $value
     * @param int    $expired
     *
     * @return bool
     *
     * @throws MemcachedException
     */
    public function set(string $key, string $value, int $expired = 0): bool
    {
        $this->validateKey($key);

        $setString = 'set ' . $key . ' 0 ' . $expired . ' ' . strlen($value);
        $this->write($setString);
        $this->write($value);

        $response = fgets($this->connection);
        if ($response === "STORED\r\n") {
            return true;
        } else {
            $this->unknownResponse($response);
        }
    }

    /**
     * @param string $key
     * @param bool   $throwsException
     *
     * @return bool
     * @throws MemcachedException
     */
    public function delete(string $key, bool $throwsException = false): bool
    {
        $this->validateKey($key);

        $this->write('delete ' . $key);

        $response = fgets($this->connection);
        if ($response === "DELETED\r\n") {
            return true;
        } elseif ($response === "NOT_FOUND\r\n") {
            if ($throwsException) {
                throw new MemcachedException('Key "' . $key . '" is not enabled.');
            }
            return false;
        }

        $this->unknownResponse($response);
    }

    /**
     * @param string $key
     *
     * @throws MemcachedException
     */
    private function validateKey(string $key)
    {
        if (
            strlen($key) > 250
            || false !== strpos($key, "\n")
            || false !== strpos($key, " ")
            || false !== strpos($key, "\0")
        ) {
            throw new MemcachedException('Key is not valid.');
        }
    }

    /**
     * @param string $string
     *
     * @return false|int
     */
    private function write(string $string)
    {
        $string = $string . "\r\n";
        return fwrite($this->connection, $string, strlen($string));
    }

    /**
     * @param string $response
     *
     * @throws MemcachedException
     */
    private function unknownResponse(string $response)
    {
        throw new MemcachedException('Unknown response ' . $response . '.');
    }
}
