<?php

namespace PhpCentroid\Query;

class QueryEntity {

    function __construct(string $name, ?string $alias = NULL)
    {
        if ($alias == NULL) {
            $this->{$name} = 1;
        } else {
            $this->{$alias} = $name;
        }
    }

    /**
     * Gets collection alias
     * @return string|null
     */
    public function getAlias(): ?string {
        foreach ($this as $key => $value) {
            if ($value == 1) {
                return NULL;
            }
            return $key;
        }
        return NULL;
    }

    /**
     * Gets collection name
     * @return string|null
     */
    public function getCollection(): ?string {
        foreach ($this as $key => $value) {
            if ($value == 1) {
                return $key;
            }
            return $value;
        }
        return NULL;
    }

}