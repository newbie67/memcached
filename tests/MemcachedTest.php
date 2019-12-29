<?php

declare(strict_types=1);

namespace newbie67\memcached\Tests;

use newbie67\memcached\Memcached;
use PHPUnit\Framework\TestCase;
use newbie67\memcached\MemcachedException;

class MemcachedTest extends TestCase
{
    /**
     *
     */
    public function testInvalidKey()
    {
        $client = $this->buildClient();
        try {
            $key = 'Some Incorrect ' . "\r\n" . 'key';
            $client->get($key);

            self::fail('Exception not thrown');
        } catch (\Exception $exception) {
            self::assertEquals(MemcachedException::class, get_class($exception));
            self::assertEquals(
                'Key is not valid.',
                $exception->getMessage()
            );
        }

        try {
            $key = 'Some Incorrect key';
            $client->delete($key);

            self::fail('Exception not thrown');
        } catch (\Exception $exception) {
            self::assertEquals(MemcachedException::class, get_class($exception));
            self::assertEquals(
                'Key is not valid.',
                $exception->getMessage()
            );
        }

        try {
            $key = 'Some Incorrect ' . "\t" . 'key';
            $client->set($key, 'val');

            self::fail('Exception not thrown');
        } catch (\Exception $exception) {
            self::assertEquals(MemcachedException::class, get_class($exception));
            self::assertEquals(
                'Key is not valid.',
                $exception->getMessage()
            );
        }
    }

    /**
     *
     */
    public function testThrowExceptions()
    {
        $client = $this->buildClient();

        try {
            $client->delete('key', true);
            self::fail('Exception not thrown');
        } catch (\Exception $exception) {
            self::assertEquals(MemcachedException::class, get_class($exception));
            self::assertEquals(
                'Key "key" is not enabled.',
                $exception->getMessage()
            );
        }

        try {
            $client->get('key', true);
            $this->fail('Exception not thrown');
        } catch (\Exception $exception) {
            self::assertEquals(MemcachedException::class, get_class($exception));
            self::assertEquals(
                'Key "key" is not enabled.',
                $exception->getMessage()
            );
        }
    }

    /**
     * @throws MemcachedException
     */
    public function testDelete()
    {
        $client = $this->buildClient();

        self::assertFalse($client->delete('key'));
    }

    /**
     * @throws MemcachedException
     */
    public function testSet()
    {
        $client = $this->buildClient();
        $client->set('key', 'value');
        self::assertEquals('value', $client->get('key'));

        $client->delete('key');
    }

    /**
     * @throws MemcachedException
     */
    public function testReal()
    {
        $client = $this->buildClient();
        self::assertFalse($client->get('key2'));

        $client->set('key', 'value');
        self::assertEquals('value', $client->get('key'));
        $client->delete('key');

        $value = "\r\n" . 'someString' . "\r\n";
        $client->set('key2', $value);
        self::assertEquals($value, $client->get('key2'));
        $client->delete('key2');

        $value = "END\r\n";
        $client->set('key3', $value);
        self::assertEquals($value, $client->get('key3'));
        $client->delete('key3');

        $value = "\r\n
        \r\n";
        $client->set('key4', $value);
        self::assertEquals($value, $client->get('key4'));
        $client->delete('key4');
    }

    /**
     * @throws MemcachedException
     */
    public function testExpired()
    {
        $client = $this->buildClient();

        $value = "\r\n" . 'someString' . "\r\n";
        $client->set('key', $value, 1);

        self::assertEquals($value, $client->get('key'));
        sleep(2);
        self::assertFalse($client->get('key'));
    }

    /**
     * @inheritDoc
     */
    private function buildClient(): Memcached
    {
        $config = require __DIR__ . '/config.php';
        return new Memcached($config['memcached']['host'], $config['memcached']['port']);
    }
}
