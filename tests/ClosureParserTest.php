<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PhpCentroid\Query\ClosureParser;



final class ClosureParserTest extends TestCase
{
    public function testCreateParser(): void
    {
        $parser = new ClosureParser();
        $this->assertNotNull($parser);
    }
}