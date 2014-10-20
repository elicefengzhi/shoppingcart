<?php
namespace VisioCrudModelerTest\Descriptor\Db;

use VisioCrudModelerTest\Descriptor\Db\DbDataSourceDescriptorFake;
use VisioCrudModeler\Descriptor\Db\DbDataSetDescriptor;

/**
 * DbDataSetDescriptor test case.
 */
class DbDataSetDescriptorTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     * @var DbDataSetDescriptor
     */
    private $DbDataSetDescriptor;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        
        // TODO Auto-generated DbDataSetDescriptorTest::setUp()
        $datasource = new DbDataSourceDescriptorFake();
        $this->DbDataSetDescriptor = new DbDataSetDescriptor($datasource, array(
            'type' => 'table',
            'name' => 'store',
            'updateable' => true,
            'fields' => array(
                'store_id' => array(
                    'name' => 'store_id',
                    'type' => 'tinyint',
                    'character_maximum_length' => NULL,
                    'numeric_precision' => '3',
                    'numeric_scale' => '0',
                    'null' => false,
                    'default' => NULL,
                    'key' => 'PRI',
                    'reference' => false
                ),
                'manager_staff_id' => array(
                    'name' => 'manager_staff_id',
                    'type' => 'tinyint',
                    'character_maximum_length' => NULL,
                    'numeric_precision' => '3',
                    'numeric_scale' => '0',
                    'null' => false,
                    'default' => NULL,
                    'key' => 'UNI',
                    'reference' => array(
                        'dataset' => 'staff',
                        'field' => 'staff_id'
                    )
                ),
                'address_id' => array(
                    'name' => 'address_id',
                    'type' => 'smallint',
                    'character_maximum_length' => NULL,
                    'numeric_precision' => '5',
                    'numeric_scale' => '0',
                    'null' => false,
                    'default' => NULL,
                    'key' => 'MUL',
                    'reference' => array(
                        'dataset' => 'address',
                        'field' => 'address_id'
                    )
                ),
                'last_update' => array(
                    'name' => 'last_update',
                    'type' => 'timestamp',
                    'character_maximum_length' => NULL,
                    'numeric_precision' => NULL,
                    'numeric_scale' => NULL,
                    'null' => false,
                    'default' => 'CURRENT_TIMESTAMP',
                    'key' => '',
                    'reference' => false
                )
            )
        ));
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        // TODO Auto-generated DbDataSetDescriptorTest::tearDown()
        $this->DbDataSetDescriptor = null;
        
        parent::tearDown();
    }

    /**
     * Constructs the test case.
     */
    public function __construct()
    {
        // TODO Auto-generated constructor
    }

    /**
     * Tests DbDataSetDescriptor->getName()
     */
    public function testGetName()
    {
        $this->assertInternalType('string', $this->DbDataSetDescriptor->getName());
        $this->assertEquals('store', $this->DbDataSetDescriptor->getName());
    }

    /**
     * Tests DbDataSetDescriptor->getType()
     */
    public function testGetType()
    {
        $this->assertInternalType('string', $this->DbDataSetDescriptor->getType());
        $this->assertEquals('table', $this->DbDataSetDescriptor->getType());
    }

    /**
     * Tests DbDataSetDescriptor->listFields()
     */
    public function testListFields()
    {
        $this->assertInternalType('array', $this->DbDataSetDescriptor->listFields(), 'test return type');
        $this->assertCount(4, $this->DbDataSetDescriptor->listFields(), 'test item count');
        foreach ($this->DbDataSetDescriptor->listFields() as $name) {
            $this->assertInternalType('string', $name);
        }
    }

    /**
     * Tests DbDataSetDescriptor->getFields()
     */
    public function testGetFields()
    {
        $this->assertInternalType('array', $this->DbDataSetDescriptor->getFields(), 'test return type');
        $this->assertCount(4, $this->DbDataSetDescriptor->getFields(), 'test item count');
        foreach ($this->DbDataSetDescriptor->getFields() as $definition) {
            $this->assertInternalType('array', $definition);
        }
    }

    /**
     * Tests DbDataSetDescriptor->getFieldDescriptor()
     */
    public function testGetFieldDescriptor()
    {
        $fields = $this->DbDataSetDescriptor->listFields();
        $name = array_shift($fields);
        $descriptor = $this->DbDataSetDescriptor->getFieldDescriptor($name);
        $this->assertInstanceOf('\VisioCrudModeler\Descriptor\Db\DbFieldDescriptor', $descriptor);
        $this->assertInstanceOf('\VisioCrudModeler\Descriptor\FieldDescriptorInterface', $descriptor);
        $this->assertInstanceOf('\VisioCrudModeler\Descriptor\ReferenceFieldInterface', $descriptor);
    }

    /**
     * Tests DbDataSetDescriptor->info()
     */
    public function testInfo()
    {
        $this->assertInternalType('array',$this->DbDataSetDescriptor->info());
        $this->assertInternalType('string',$this->DbDataSetDescriptor->info('type'));
        $this->assertInternalType('string',$this->DbDataSetDescriptor->info('name'));
        $this->assertInternalType('boolean',$this->DbDataSetDescriptor->info('updateable'));
        $this->assertInternalType('array',$this->DbDataSetDescriptor->info('fields'));
    }

    /**
     * Tests DbDataSetDescriptor->listGenerator()
     */
    public function testListGenerator()
    {
        $this->assertInstanceOf('\Generator', $this->DbDataSetDescriptor->listGenerator(), 'returning generator instance');
        foreach ($this->DbDataSetDescriptor->listGenerator() as $key => $value) {
            $this->assertInternalType('string', $key, 'field name');
            $this->assertInstanceOf('\VisioCrudModeler\Descriptor\Db\DbFieldDescriptor', $value, ' implementation of db field descriptor');
            $this->assertInstanceOf('\VisioCrudModeler\Descriptor\FieldDescriptorInterface', $value, ' implementation of field descriptor interface');
            $this->assertInstanceOf('\VisioCrudModeler\Descriptor\ReferenceFieldInterface', $value, ' implementation of reference field descriptor interface');
        }
    }
}

