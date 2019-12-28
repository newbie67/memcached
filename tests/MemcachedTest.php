<?php

declare(strict_types=1);

namespace newbie67\memcached\Tests;

use newbie67\memcached\MemcachedClient;
use newbie67\memcached\Memcached;
use PHPUnit\Framework\TestCase;

class MemcachedTest extends TestCase
{
    /**
     * @var Memcached
     */
    private ?Memcached $memcached = null;

    public function testDelete()
    {
        $this->expectExceptionMessage('Key "key" is not enabled.');
        $this->memcached->delete('key', true);

        self::assertFalse($this->memcached->delete('key2'));
    }

    public function testGet()
    {
        $this->expectExceptionMessage('Key "key" is not enabled.');
        $this->memcached->get('key', true);

        $key = 'Some Incorrect ' . "\r\n" . 'key';

        $this->expectExceptionMessage('Key is not valid.');
        $this->memcached->get($key);

        $val = $this->memcached->get('key2');
        self::assertFalse($val);
    }

    public function testBaseSet()
    {
        $this->memcached->set('key', 'value');
        self::assertEquals('value', $this->memcached->get('key'));

        $this->expectExceptionMessage('Key "key" already setted.');
        $this->memcached->set('key', 'value', 0, true);

        $this->memcached->delete('key');
    }

    public function testRealRelate()
    {
        $this->memcached->set('key', 'value');
        self::assertEquals('value', $this->memcached->get('key'));
        $this->memcached->delete('key');
        self::assertFalse($this->memcached->get('key'));
    }

    public function extendedSet()
    {
        $value = "\r\n" . 'someString' . "\r\n";
        $this->memcached->set('key', $value);
        self::assertEquals($value, $this->memcached->get('key'));

        $this->memcached->delete('key');
    }

    public function testExpired()
    {
        $value = "\r\n" . 'someString' . "\r\n";
        $this->memcached->set('key', $value);
        self::assertEquals($value, $this->memcached->get('key'));

        $this->memcached->delete('key');
    }

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $config = require __DIR__ . '/config.php';
        $client = new MemcachedClient($config['memcached']['host'], $config['memcached']['port']);
        $this->memcached = new Memcached($client);

        parent::setUp();
    }

    /**
     * @inheritDoc
     */
    protected function tearDown(): void
    {
        $this->memcached = null;
        parent::tearDown();
    }
}
