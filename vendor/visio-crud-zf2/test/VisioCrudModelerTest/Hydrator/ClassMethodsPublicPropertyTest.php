<?php

/**
 * Description of ClassMethodsPublicProperty
 *
 * @author  pduda001 Piotr Duda (dudapiotrek@gmail.com)
 */
namespace VisioCrudModelerTest\Hydrator;

use VisioCrudModeler\Hydrator\ClassMethodsPublicProperty;
use VisioCrudModelerTest\Hydrator\TestAsset\ClassMethodsOptionalParameters;

/**
 */
class ClassMethodsPublicPropertyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ClassMethods
     */
    protected $hydrator;

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        $this->hydrator = new ClassMethodsPublicProperty();
    }

    /**
     * Verifies that extraction can happen
     */
    public function testCanExtractFromMethodsWithOptionalParameters()
    {
        $this->assertSame(array('foo' => 'bar'), $this->hydrator->extract(new ClassMethodsOptionalParameters()));
    }
}