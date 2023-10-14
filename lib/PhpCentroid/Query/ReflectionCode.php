<?php
require 'vendor/autoload.php';

use PhpParser\Error;
use PhpParser\NodeDumper;
use PhpParser\ParserFactory;

class ReflectionCode extends ReflectionFunction
{
    /** @noinspection PhpMissingFieldTypeInspection */
    protected $sz_func_name = NULL;
    /** @noinspection PhpMissingFieldTypeInspection */
    protected $bExists = FALSE;

    function __construct($func_name)
    {
        $this->sz_func_name = $func_name;
        if (!function_exists($func_name)) return;
        try {
            parent::__construct($func_name);
            $this->bExists = TRUE;
        } catch (Exception $e) {
            $this->bExists = FALSE;
            return;
        }
    }

    function function_valid(): bool
    {
        return $this->bExists == TRUE;
    }

    function get_code(): string
    {   //  Returns Function's source code
        if (!$this->bExists) {
            return "/* function does not exist */";
        }
        $line_start = $this->getStartLine() - 1;
        $line_end = $this->getEndLine();
        $line_count = $line_end - $line_start;
        $line_array = file($this->getFileName());
        return implode("", array_slice($line_array, $line_start, $line_count));
    }   //  End Function

    function func_get_args(): array
    {   //  Returns a fairly detailed description of function arguments
        $aParameters = array();
        if (!$this->bExists) return $aParameters;
        foreach ($Params = $this->getParameters() as $k => $v) {
            $item = array();
            /** @noinspection PhpElementIsNotAvailableInCurrentPhpVersionInspection */
            $array = $a = (array)$v->export($this->getName(), $v->getName(), TRUE);
            $s_export = array_pop($array);
            preg_match('/[^#]+[#]([0-9]+)/', $s_export, $m, PREG_OFFSET_CAPTURE);
            $item["position"] = $m[1][0];
            $item["name"] = $v->getName();
            $item["default"] = $item["type"] = NULL;
            if (($item["optional"] = intVal($v->isOptional())) == 1)
                if (preg_match('/[^[]+[^=]+=([^\]]+)/', $s_export, $m, PREG_OFFSET_CAPTURE)) {
                    $item["default"] = ($ev = trim($m[1][0]));
                    $ev = ("\$a=$ev;");
                    eval($ev);
                    $item["type"] = gettype($ev);
                }
            if ($item["type"] == NULL)
                if (preg_match('/[^[]+[\[][^>]+[> ]+([^&$]+)/', $s_export, $m, PREG_OFFSET_CAPTURE)) $item["type"] = ($ev = trim($m[1][0]));
            $item["byreference"] = intVal(str_contains($s_export, '&$'));
            $aParameters[$v->getName()] = $item;
        }
        return $aParameters;
    }

    function func_num_args()
    {
        if (!$this->bExists) return FALSE;
        return $this->getNumberOfParameters();
    }

    function func_num_args_required()
    {
        if (!$this->bExists) return FALSE;
        return $this->getNumberOfRequiredParameters();
    }
}
