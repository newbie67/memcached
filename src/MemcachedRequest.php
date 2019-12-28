<?php

declare(strict_types=1);

namespace newbie67\memcached;

class MemcachedRequest
{
    public function createGet(string $key, bool $throwException = false): self
    {

    }

    public function createDelete(string $key, bool $throwException = false): self
    {

    }

    public function createSet(string $key, string $value, int $expire = 0, bool $throwException = false): self
    {

    }

    private function __construct()
    {

    }
}
