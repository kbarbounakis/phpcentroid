<?php

namespace PhpCentroid\Query;

use PhpParser\Error;
use PhpParser\NodeDumper;
use PhpParser\ParserFactory;
use Opis\Closure\SerializableClosure;
use Opis\Closure\ReflectionClosure;

class ClosureParser {

    function __construct()
    {
        // place your code here
    }

    function parse($closure) {
        // get closure code
        $reflector = new ReflectionClosure($closure);
        $code = '<?php $body = ' . $reflector->getCode() . ';';
        // and parse
        $ast = $parser->parse($code);
        return $ast;
    }

}