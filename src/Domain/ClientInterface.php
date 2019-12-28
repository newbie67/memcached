<?php

declare(strict_types=1);

namespace newbie67\memcached\Domain;

interface ClientInterface
{
    /**
     * @param string $request
     *
     * @return false|int
     */
    public function write($request);

    /**
     * @param int|null $length
     *
     * @return string
     */
    public function readNextString(?int $length = null): string;
}
