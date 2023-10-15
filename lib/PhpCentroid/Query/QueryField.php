<?php

namespace PhpCentroid\Query;

use Exception;
use ArrayObject;
use TypeError;

function get_first_key(mixed $any): ?string {
    foreach ($any as $key => $value) {
        return $key;
    }
    return NULL;
}

class QueryField extends ArrayObject
{
    /**
     * Trims a formatted field reference by removing dollar sign
     * @param string $name
     * @return string
     */
    static function trim_field_reference(string $name): string {
        return preg_replace('/\$(\w+)/','${1}', $name);
    }

    /**
     * Formats a string to a field reference e.g. $givenName
     * @param string $name
     * @return string
     */
    static function format_field_reference(string $name): string {
        return preg_replace('/\$?(\w+)/','$${1}', $name);
    }

    static function format_any_field_reference(string $name): string {
        return preg_replace('/^\$?(\w+)/','$${1}', $name);
    }

    static function is_qualified_reference(string $str): bool {
        return preg_match('/(\$\w+)\.((\w+)+)?/',$str) === 1;
    }

    public static function create(string $name): QueryField {
        return new QueryField($name);
    }

    function __construct(string|array $input)
    {
        parent::__construct();
        if (is_string($input)) {
            $this[QueryField::trim_field_reference($input)] = 1;
        } elseif (is_array($input)) {
            foreach ($input as $key => $value) {
                $this[$key] = $value;
                break;
            }
        } else {
            throw new TypeError('Expected array or string');
        }
    }

    /**
     * @throws Exception
     */
    function from(mixed $collection): self {
        $key = get_first_key($this);
        if ($key == NULL) {
            throw new Exception('Field name cannot be empty when defining collection', 1001);
        }
        if (str_starts_with($key, '$'))
            throw new Exception('Unsupported field expression. Define collection before assigning an expression', 1002);
        unset($this[$key]);
        $key = QueryField::trim_field_reference($collection) . '.' . $key;
        $this[$key] = 1;
        return $this;
    }

    /**
     * @throws Exception
     */
    function as(string $alias): self {
        $key = get_first_key($this);
        if ($key == NULL) {
            throw new Exception('Field name cannot be empty when defining an alias', 1003);
        }
        $value = $this[$key];
        unset($this[$key]);
        $this[$alias] = [
            $key => $value
        ];
        return $this;
    }

    private function use_method_call(string $method, mixed ...$args): self
    {
        $key = get_first_key($this);
        $value = $this[$key];
        if ($value == 1) {
            $value = QueryField::format_field_reference($key);
        }
        unset($this[$key]);
        $arguments = [
            $value
        ];
        foreach ($args as $arg) {
            //
            if ($arg instanceof QueryField) {
                $arg_key = get_first_key($arg);
                $arg_value = $arg[$arg_key];
                if (is_int($arg_value) && $arg_value == 1) {
                    $arguments[] = QueryField::format_field_reference($arg_key);
                } else {
                    $arguments[] = $arg;
                }
            } else {
                $arguments[] = $arg;
            }
        }
        $this[$method] = $arguments;
        return $this;
    }

    private function use_date_function(string $method, mixed $timezone=NULL): self
    {
        $key = get_first_key($this);
        $value = $this[$key];
        if ($value == 1) {
            $value = QueryField::format_field_reference($key);
        }
        unset($this[$key]);
        $this[$method] = [
            'date' => $value,
            'timezone' => $timezone
        ];
        return $this;
    }

    function year(mixed $timezone=NULL): self {
        return $this->use_date_function('$year', $timezone);
    }

    function month(mixed $timezone=NULL): self {
        return $this->use_date_function('$month', $timezone);
    }

    function date(mixed $timezone=NULL): self {
        return $this->use_date_function('$dayOfMonth', $timezone);
    }

    function hours(mixed $timezone=NULL): self {
        return $this->use_date_function('$hour', $timezone);
    }

    function minutes(mixed $timezone=NULL): self {
        return $this->use_date_function('$minute', $timezone);
    }

    function seconds(mixed $timezone=NULL): self {
        return $this->use_date_function('$seconds', $timezone);
    }

    function length(): self {
        return $this->use_method_call('$size');
    }

    function trim(): self {
        return $this->use_method_call('$τrim');
    }

    function ceil(): self {
        return $this->use_method_call('$ceil');
    }

    function floor(): self {
        return $this->use_method_call('$floor');
    }

    function round(int $digits = 0): self {
        return $this->use_method_call('$round', $digits);
    }

    function add(mixed $value): self {
        return $this->use_method_call('$add', $value);
    }

    function subtract(mixed $value): self {
        return $this->use_method_call('$subtract', $value);
    }

    function divide(mixed $value): self {
        return $this->use_method_call('$divide', $value);
    }

    function multiply(mixed $value): self {
        return $this->use_method_call('$multiply', $value);
    }

    function modulo(mixed $value = 2): self {
        return $this->use_method_call('$mod', $value);
    }

    function concat(mixed ...$args): self {
        return $this->use_method_call('$concat', ...$args);
    }

    function substring(mixed $start, mixed $length): self {
        return $this->use_method_call('$substr', $start, $length);
    }

    function indexOf(mixed $search): self {
        return $this->use_method_call('$indexOfBytes', $search);
    }

    function min(): self {
        return $this->use_method_call('$min');
    }

    function max(): self {
        return $this->use_method_call('$max');
    }

    function count(): self {
        return $this->use_method_call('$count');
    }

    function sum(): self {
        return $this->use_method_call('$sum');
    }

    function average(): self {
        return $this->use_method_call('$avg');
    }

    function toLower(): self {
        return $this->use_method_call('$toLower');
    }

    function toUpper(): self {
        return $this->use_method_call('$toUpper');
    }

    private function search(string $pattern): self {
        $key = get_first_key($this);
        $value = $this[$key];
        unset($this[$key]);
        if ($value == 1) {
            $value = QueryField::format_any_field_reference($key);
            $key = NULL;
        }
        $regex_match = [
            'input' => $value,
            'regex' => $pattern
        ];
        if ($key == NULL) {
            $this['$regexMatch'] = $regex_match;
        } else {
            $this[$key] = $regex_match;
        }
        return $this;
    }

    function startsWith(string $needle): self {
        return $this->search('^' . preg_quote($needle));
    }

    function endsWith(string $needle): self {
        return $this->search(preg_quote($needle) . '$');
    }

    function contains(string $needle): self {
        return $this->search(preg_quote($needle));
    }


}