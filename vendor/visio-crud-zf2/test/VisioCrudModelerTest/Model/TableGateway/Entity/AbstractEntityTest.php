<?php

namespace VisioCrudModelerTest\Model\TableGateway;
/**
 * Description of AbstractTableTest
 *
 * @author  pduda001 Piotr Duda (dudapiotrek@gmail.com)
 */
class AbstractEntityTest extends \PHPUnit_Framework_TestCase
{
    
    /**
     * @var \PHPUnit_Framework_MockObject_Generator
     */
    protected $mockAdapter = null;

    /**
     * @var \PHPUnit_Framework_MockObject_Generator
     */
    protected $mockSql = null;

    /**
     * @var AbstractTableGateway
     */
    protected $table;
    
     /**
     * @var \VisioCrudModeler\Model\TableGateway\Entity\AbstractEntity
     */
    protected $entity;
    
    
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->entity = $this->getMockForAbstractClass('VisioCrudModeler\Model\TableGateway\Entity\AbstractEntity');
    }
    
    public function testExchangeArray()
    {
        $this->entity->exchangeArray(array(
            'id' => 5,
            'foo' => 'bar'
        ));
    }
    
    
    public function testGetArrayCopy()
    {
        $arrayCopy = $this->entity->getArrayyCopy();
        $this->assertEmpty($arrayCopy);
    }
    
    public function testGetInputFilter()
    {
        $this->assertInstanceOf('\Zend\InputFilter\InputFilterInterface', $this->entity->getInputFilter()); 
    }
    
}