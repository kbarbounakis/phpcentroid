<?php

namespace PhpCentroid\Common;

class SyncEventEmitter
{
    /**
     * @var SyncEventHandler[]
     */
    private array $handlers = array();
    function __construct()
    {
        // place your code here
    }

    function subscribe(callable $handler): EventSubscription {
        $add = new SyncEventHandler($handler);
        $this->handlers[] = $add;
        return new EventSubscription($this, $add);
    }

    function unsubscribe(callable $handler): self {
        $filter = fn(SyncEventHandler $a) => $a->handler == $handler;
        $offset = array_search($filter, $this->handlers);
        if ($offset >= 0) {
            array_splice($this->handlers, $offset, 1);
        }
        return $this;
    }

    function emit(mixed $value): void {
        foreach ($this->handlers as $handler) {
            $handler->execute($value);
        }
    }
}