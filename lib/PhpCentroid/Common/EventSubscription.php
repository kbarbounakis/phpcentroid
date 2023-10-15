<?php

namespace PhpCentroid\Common;

class EventSubscription
{
    private mixed $emitter;
    private mixed $handler;
    function __construct(mixed $emitter, mixed $handler)
    {
        // place your code here
        $this->emitter = $emitter;
        $this->handler = $handler;
    }

    function unsubscribe(): void {
        $this->emitter->unsubscribe($this->handler->handler);
    }
}