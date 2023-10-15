<?php

namespace PhpCentroid\Query;

class QueryValue
{

    public const LITERAL_PROPERTY='$literal';

    function __construct(mixed $value)
    {
        $this->{QueryValue::LITERAL_PROPERTY} = $value;
    }

    public function getValue(): mixed {
        return $this->{QueryValue::LITERAL_PROPERTY};
    }

}