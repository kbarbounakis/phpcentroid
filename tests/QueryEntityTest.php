<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PhpCentroid\Query\QueryEntity;

final class QueryEntityTest extends TestCase
{
    public function testCreateInstance(): void
    {
        $entity = new QueryEntity('ProductBase');
        $this->assertNotNull($entity->getCollection());
        $this->assertEquals('ProductBase', $entity->getCollection());
    }

    public function testGetAlias(): void
    {
        $entity = new QueryEntity('ProductBase', 'Products');
        var_dump(json_encode($entity));
        $this->assertEquals('ProductBase', $entity->getCollection());
        $this->assertEquals('Products', $entity->getAlias());
    }
}