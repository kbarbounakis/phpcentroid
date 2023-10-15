<?php

use PhpCentroid\Common\SyncEventEmitter;
use PHPUnit\Framework\TestCase;

class SyncEventEmitterTest extends TestCase
{
    public function testCreateInstance(): void
    {
        $emitter = new SyncEventEmitter();
        $on_event = fn($event) => $event->counter = $event->counter + 1;
        $subscription = $emitter->subscribe($on_event);
        $new_event = (object)[
            'counter' => 0
        ];
        $emitter->emit($new_event);
        $this->assertEquals(1, $new_event->counter);
        $subscription->unsubscribe();
        $emitter->emit($new_event);
        $this->assertEquals(1, $new_event->counter);
    }

    public function testSubscribe(): void
    {
        $emitter = new SyncEventEmitter();
        $on_event1 = fn($event) => $event->counter = $event->counter + 1;
        $on_event2 = fn($event) => $event->counter = $event->counter + 2;
        $subscription1 = $emitter->subscribe($on_event1);
        $subscription2 = $emitter->subscribe($on_event2);
        $new_event = (object)[
            'counter' => 0
        ];
        $emitter->emit($new_event);
        $this->assertEquals(3, $new_event->counter);
        $subscription1->unsubscribe();
        $emitter->emit($new_event);
        $this->assertEquals(5, $new_event->counter);
        $subscription2->unsubscribe();
        $emitter->emit($new_event);
        $this->assertEquals(5, $new_event->counter);
    }
}