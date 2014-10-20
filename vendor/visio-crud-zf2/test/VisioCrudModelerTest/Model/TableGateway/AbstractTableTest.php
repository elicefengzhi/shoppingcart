<?php

namespace VisioCrudModelerTest\Model\TableGateway;
/**
 * Description of AbstractTableTest
 *
 * @author  pduda001 Piotr Duda (dudapiotrek@gmail.com)
 */
class AbstractTableTest extends \PHPUnit_Framework_TestCase
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
        // mock the adapter, driver, and parts
        $mockResult = $this->getMock('Zend\Db\Adapter\Driver\ResultInterface');
        $mockResult->expects($this->any())->method('getAffectedRows')->will($this->returnValue(5));
        $mockResult->expects($this->any())->method('current')->will($this->returnValue(5));
        
        
        $mockStatement = $this->getMock('Zend\Db\Adapter\Driver\StatementInterface');
        $mockStatement->expects($this->any())->method('execute')->will($this->returnValue($mockResult));
        
        $mockConnection = $this->getMock('Zend\Db\Adapter\Driver\ConnectionInterface');
        $mockConnection->expects($this->any())->method('getLastGeneratedValue')->will($this->returnValue(10));

        $mockDriver = $this->getMock('Zend\Db\Adapter\Driver\DriverInterface');
        $mockDriver->expects($this->any())->method('createStatement')->will($this->returnValue($mockStatement));
        $mockDriver->expects($this->any())->method('getConnection')->will($this->returnValue($mockConnection));

        $this->mockAdapter = $this->getMock('Zend\Db\Adapter\Adapter', null, array($mockDriver));
        $this->mockSql = $this->getMock('Zend\Db\Sql\Sql', array('select', 'insert', 'update', 'delete'), array($this->mockAdapter, 'foo'));
        
        $this->mockSql->expects($this->any())->method('select')->will($this->returnValue($this->getMock('Zend\Db\Sql\Select', array('where', 'getRawSate'), array('foo'))));
        $this->mockSql->expects($this->any())->method('insert')->will($this->returnValue($this->getMock('Zend\Db\Sql\Insert', array('prepareStatement', 'values'), array('foo'))));
        $this->mockSql->expects($this->any())->method('update')->will($this->returnValue($this->getMock('Zend\Db\Sql\Update', array('where'), array('foo'))));
        $this->mockSql->expects($this->any())->method('delete')->will($this->returnValue($this->getMock('Zend\Db\Sql\Delete', array('where'), array('foo'))));
        
        $this->table = $this->getMockForAbstractClass('VisioCrudModeler\Model\TableGateway\AbstractTable', array($this->mockAdapter , 'test'));
        
        
        $tgReflection = new \ReflectionClass('VisioCrudModeler\Model\TableGateway\AbstractTable');
        foreach ($tgReflection->getProperties() as $tgPropReflection) {
            $tgPropReflection->setAccessible(true);
            switch ($tgPropReflection->getName()) {
                case 'adapter':
                    $tgPropReflection->setValue($this->table, $this->mockAdapter);
                    break;
                case 'sql':
                    $tgPropReflection->setValue($this->table, $this->mockSql);
                    break;
                case 'keyName':
                    $tgPropReflection->setValue($this->table, 'id');
                    break;
                case 'table':
                    $tgPropReflection->setValue($this->table, 'foo');
                    break;
            }
        }
        
        $this->entity = $this->getMock('VisioCrudModeler\Model\TableGateway\Entity\AbstractEntity');
        $this->entity->expects($this->any())->method('getArrayCopy')->will($this->returnValue(array('id' => 5 ,'foo' => 'bar')));
        
    }
        
    
     /**
     * @covers VisioCrudModeler\Model\TableGateway\AbstractTable:getAdapter
     */
    public function testGetAdapter()
    {
        $this->assertSame($this->mockAdapter, $this->table->getAdapter());
    }
    
    /**
     * @covers VisioCrudModeler\Model\TableGateway\AbstractTable:getSql
     */
    public function testGetSql()
    {
        $this->assertInstanceOf('Zend\Db\Sql\Sql', $this->table->getSql());
    }
    
    /**
     * @covers VisioCrudModeler\Model\TableGateway\AbstractTable:findRow
     */
    public function testFindRow()
    {
        $this->assertEquals($this->table->findRow(5) , 5);
        
    }
    
    /**
     * @covers VisioCrudModeler\Model\TableGateway\AbstractTable:getRow
     */
    public function testGetRow()
    {
        $this->assertEquals($this->table->getRow(5) , 5);
        
    }
    
    /**
     * @covers VisioCrudModeler\Model\TableGateway\AbstractTable:fetchRow
     */
    public function testFetchRow()
    {
        $select = $this->mockSql->select();
        
        $this->assertEquals($this->table->fetchRow() , 5);
        $this->assertEquals($this->table->fetchRow($select  ) , 5);
    }
    
     /**
     * @covers VisioCrudModeler\Model\TableGateway\AbstractTable:fetchAll
     */
    public function testFetchAll()
    {
        $select = $this->mockSql->select();
        
        $this->assertInstanceOf('\Zend\Db\ResultSet\ResultSet' ,$this->table->fetchAll());
        $this->assertInstanceOf('\Zend\Db\ResultSet\ResultSet' ,$this->table->fetchAll($select));
        
    }
    
    /**
     * @covers VisioCrudModeler\Model\TableGateway\AbstractTable:fetchAll
     */
    public function testGetBaseQuery()
    {
        $this->assertInstanceOf('Zend\Db\Sql\Select' ,$this->table->getBaseQuery());
    }
    
    /**
     * @covers VisioCrudModeler\Model\TableGateway\AbstractTable:update
     */
    public function testUpdate()
    {
        $this->entity->id = 5;
        $this->table->update($this->entity);
        
    }
    
    /**
     * @covers VisioCrudModeler\Model\TableGateway\AbstractTable:insert
     */
    public function testInsert()
    {
        $this->table->insert(array('foo' => 'bar'));
        $this->table->insert($this->entity);
        
    }
    
    
    /**
     * @covers VisioCrudModeler\Model\TableGateway\AbstractTable:delete
     */
    public function testDelete()
    {
        $this->entity->id = 5;
        $this->table->delete($this->entity);
    }
    
    
    
    
    
}