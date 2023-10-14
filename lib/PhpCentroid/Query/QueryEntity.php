<?php

namespace PhpCentroid\Query;

class QueryEntity {

    function __construct(string $name, ?string $alias = NULL)
    {
        if ($alias !== NULL) {
            $this->{$name} = 1;
        } else {
            $this->{$alias} = $name;
        }
    }

    public function getAlias(): ?string {
        foreach ($this as $key => $value) {
            if ($value == 1) {
                return NULL;
            }
            return $value;
        }
    }

    public function getCollection(): ?string {
        foreach ($this as $key => $value) {
            if ($value == 1) {
                return $key;
            }
            return $value;
        }
    }

}