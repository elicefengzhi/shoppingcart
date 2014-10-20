<?php
namespace VisioCrudModelerTest\Descriptor\Db;

use VisioCrudModeler\Descriptor\Db\DbDataSourceDescriptor;

/**
 * DbDataSourceDescriptor test case.
 */
class DbDataSourceDescriptorTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     * @var DbDataSourceDescriptor
     */
    private $DbDataSourceDescriptor;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        
        $this->DbDataSourceDescriptor = new DbDataSourceDescriptorFake();
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        $this->DbDataSourceDescriptor = null;
        
        parent::tearDown();
    }

    /**
     * Constructs the test case.
     */
    public function __construct()
    {
        
    }

    /**
     * Tests DbDataSourceDescriptor->listDataSets()
     */
    public function testListDataSets()
    {
        $this->assertInternalType('array', $this->DbDataSourceDescriptor->listDataSets(), 'test return type');
        $this->assertCount(23, $this->DbDataSourceDescriptor->listDataSets(), 'test item count');
    }

    /**
     * Tests DbDataSourceDescriptor->getDataSetDescriptor()
     */
    public function testGetDataSetDescriptor()
    {
        $dataSets = $this->DbDataSourceDescriptor->listDataSets();
        $name = array_shift($dataSets);
        
        $dataSetDescriptor = $this->DbDataSourceDescriptor->getDataSetDescriptor($name);
        $this->assertInstanceOf('\VisioCrudModeler\Descriptor\Db\DbDataSetDescriptor', $dataSetDescriptor, ' implementation of db dataset descriptor');
        $this->assertInstanceOf('\VisioCrudModeler\Descriptor\DataSetDescriptorInterface', $dataSetDescriptor, ' implementation of dataset descriptor interface');
        $this->assertInternalType('string', $dataSetDescriptor->getName());
        $this->assertInternalType('string', $dataSetDescriptor->getType());
        $this->assertInternalType('array', $dataSetDescriptor->getFields());
    }

    /**
     * Tests DbDataSourceDescriptor->getName()
     */
    public function testGetName()
    {
        $this->assertEquals('mocked_database', $this->DbDataSourceDescriptor->getName(), 'getName test');
    }

    /**
     * Tests DbDataSourceDescriptor->listGenerator()
     */
    public function testListGenerator()
    {
        $this->assertInstanceOf('\Generator', $this->DbDataSourceDescriptor->listGenerator(), 'returning generator instance');
        foreach ($this->DbDataSourceDescriptor->listGenerator() as $key => $value) {
            $this->assertInternalType('string', $key, 'dataset name');
            $this->assertInstanceOf('\VisioCrudModeler\Descriptor\Db\DbDataSetDescriptor', $value, ' implementation of db dataset descriptor');
            $this->assertInstanceOf('\VisioCrudModeler\Descriptor\DataSetDescriptorInterface', $value, ' implementation of dataset descriptor interface');
        }
    }
}

