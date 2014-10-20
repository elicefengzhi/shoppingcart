<?php
namespace VisioCrudModelerTest\Descriptor\Db;

use VisioCrudModeler\Descriptor\Db\DbFieldDescriptor;
use VisioCrudModeler\Descriptor\Db\DbDataSetDescriptor;

/**
 * DbFieldDescriptor test case.
 */
class DbFieldDescriptorTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     * @var DbFieldDescriptor
     */
    private $DbFieldDescriptor;

    /**
     *
     * @var DbFieldDescriptor
     */
    private $DbFieldDescriptorReferenced;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        
        $datasourcedescriptor = new DbDataSourceDescriptorFake();
        $datasetdescriptor = new DbDataSetDescriptor($datasourcedescriptor, array(
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
        $this->DbFieldDescriptor = new DbFieldDescriptor($datasetdescriptor, array(
            'name' => 'last_update',
            'type' => 'timestamp',
            'character_maximum_length' => NULL,
            'numeric_precision' => NULL,
            'numeric_scale' => NULL,
            'null' => false,
            'default' => 'CURRENT_TIMESTAMP',
            'key' => '',
            'reference' => false
        ));
        
        $this->DbFieldDescriptorReferenced = new DbFieldDescriptor($datasetdescriptor, array(
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
        ));
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        $this->DbFieldDescriptor = null;
        $this->DbFieldDescriptorReferenced = null;
        
        parent::tearDown();
    }

    /**
     * Constructs the test case.
     */
    public function __construct()
    {}

    /**
     * Tests DbFieldDescriptor->getReferencedField()
     */
    public function testGetReferencedField()
    {
        $this->assertInstanceOf('\VisioCrudModeler\Descriptor\Db\DbFieldDescriptor', $this->DbFieldDescriptorReferenced->getReferencedField());
        $this->assertInstanceOf('\VisioCrudModeler\Descriptor\FieldDescriptorInterface', $this->DbFieldDescriptorReferenced->getReferencedField());
        $this->assertInstanceOf('\VisioCrudModeler\Descriptor\ReferenceFieldInterface', $this->DbFieldDescriptorReferenced->getReferencedField());
        $this->assertNull($this->DbFieldDescriptor->getReferencedField());
    }

    /**
     * Tests DbFieldDescriptor->isReference()
     */
    public function testIsReference()
    {
        $this->assertInternalType('boolean', $this->DbFieldDescriptor->isReference());
        $this->assertFalse($this->DbFieldDescriptor->isReference());
        $this->assertInternalType('boolean', $this->DbFieldDescriptorReferenced->isReference());
        $this->assertTrue($this->DbFieldDescriptorReferenced->isReference());
    }

    /**
     * Tests DbFieldDescriptor->referencedDataSetName()
     */
    public function testReferencedDataSetName()
    {
        $this->assertInternalType('string', $this->DbFieldDescriptorReferenced->referencedDataSetName());
        $this->assertNotEmpty($this->DbFieldDescriptorReferenced->referencedDataSetName());
        $this->assertNull($this->DbFieldDescriptor->referencedDataSetName());
    }

    /**
     * Tests DbFieldDescriptor->referencedFieldName()
     */
    public function testReferencedFieldName()
    {
        $this->assertInternalType('string', $this->DbFieldDescriptorReferenced->referencedFieldName());
        $this->assertNotEmpty($this->DbFieldDescriptorReferenced->referencedFieldName());
        $this->assertNull($this->DbFieldDescriptor->referencedFieldName());
    }

    /**
     * Tests DbFieldDescriptor->info()
     */
    public function testInfo()
    {
        $this->assertInternalType('array', $this->DbFieldDescriptor->info());
        $this->assertInternalType('string', $this->DbFieldDescriptor->info('type'));
        $this->assertInternalType('string', $this->DbFieldDescriptor->info('name'));
    }
}

