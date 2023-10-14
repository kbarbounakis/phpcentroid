<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PhpCentroid\Query\QueryEntity;

final class QueryEntityTest extends TestCase
{
    public function testCreateInstance(): void
    {
        $entity = new QueryEntity('ProductBase');
        $this->assertNotNull($entity->getCollection());
    }

    public function testGetAlias(): void
    {
        $entity = new QueryEntity('ProductBase', 'Products');
        $this->assertEquals($entity->getCollection(), 'ProductBase');
        $this->assertEquals($entity->getAlias(), 'Products');
    }
}