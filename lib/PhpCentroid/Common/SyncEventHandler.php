<?php

namespace PhpCentroid\Common;

class SyncEventHandler
{
    public mixed $handler;
    function __construct(callable $handler)
    {
        $this->handler = $handler;
    }

    function execute(mixed ...$args): void {
        ($this->handler)(...$args);
    }
}