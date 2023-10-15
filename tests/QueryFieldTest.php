<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PhpCentroid\Query\QueryField;

final class QueryFieldTest extends TestCase
{
    public function testCreateInstance(): void
    {
        $field = new QueryField('name');
        $this->assertEquals(1, $field['name']);
    }

    /**
     * @throws Exception
     */
    public function testFieldFrom(): void
    {
        $field = (new QueryField('name'))->from('ProductBase');
        $this->assertEquals(1, $field['ProductBase.name']);
    }

    /**
     * @throws Exception
     */
    public function testFieldAs(): void
    {
        $field = QueryField::create('name')->from('ProductBase')->as('title');
        $expected = (array)[
            'title' => [
                'ProductBase.name' => 1
            ]
        ];
        $actual = (array)$field;
        $this->assertSame($expected, $actual);
    }

    public function testConvertFromArray(): void
    {
        $expected = (array)[
            'title' => [
                'ProductBase.name' => 1
            ]
        ];
        $field = new QueryField($expected);
        $this->assertEquals($expected, (array)$field);
    }

    public function testUseYearFunction(): void
    {
        $expected = (array)[
            '$year' => [
                'date' => '$dateCreated',
                'timezone' => NULL
            ]
        ];
        $field = (new QueryField('dateCreated'))->year();
        $this->assertEquals($expected, (array)$field);

        $field = (new QueryField('dateCreated'))->year()->as('yearDateCreated');
        $this->assertEquals([
            'yearDateCreated' => [
                '$year' => [
                    'date' => '$dateCreated',
                    'timezone' => NULL
                ]
            ]

        ], (array)$field);
    }

    /**
     * @throws Exception
     */
    public function testUseMonthFunction(): void
    {
        $field = (new QueryField('dateCreated'))->month('Europe/Athens');
        $this->assertEquals([
            '$month' => [
                'date' => '$dateCreated',
                'timezone' => 'Europe/Athens'
            ]
        ], (array)$field);

        $field = (new QueryField('dateCreated'))->month()->as('monthCreated');
        $this->assertEquals([
            'monthCreated' => [
                '$month' => [
                    'date' => '$dateCreated',
                    'timezone' => NULL
                ]
            ]

        ], (array)$field);
    }

    /**
     * @throws Exception
     */
    public function testUseDateFunction(): void
    {
        $field = (new QueryField('dateCreated'))->date('Europe/Athens');
        $this->assertEquals([
            '$dayOfMonth' => [
                'date' => '$dateCreated',
                'timezone' => 'Europe/Athens'
            ]
        ], (array)$field);

        $field = (new QueryField('dateCreated'))->date()->as('monthCreated');
        $this->assertEquals([
            'monthCreated' => [
                '$dayOfMonth' => [
                    'date' => '$dateCreated',
                    'timezone' => NULL
                ]
            ]

        ], (array)$field);
    }

}