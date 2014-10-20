<?php
namespace VisioCrudModelerTest\Generator;

use VisioCrudModeler\Generator\Dependency;

/**
 * Dependency test case.
 */
class DependencyTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     * @var Dependency
     */
    private $Dependency;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        
        // TODO Auto-generated DependencyTest::setUp()
        
        $this->Dependency = new Dependency(array(
            'view' => array(
                'controller'
            ),
            'controller' => array(
                'inputFilter',
                'form',
                'model'
            ),
            'form' => array(
                'module',
                'inputFilter'
            ),
            'model' => array(
                'module',
                'inputFilter'
            ),
            'inputFilter' => array(
                'module'
            ),
            'all' => array(
                'module',
                'inputFilter',
                'model',
                'form',
                'controller',
                'view'
            )
        ));
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        // TODO Auto-generated DependencyTest::tearDown()
        $this->Dependency = null;
        
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
     * Tests Dependency->dependencyListFor()
     */
    public function testDependencyListFor()
    {
        $this->assertInternalType('array', $this->Dependency->dependencyListFor('form'));
        $this->assertEquals(array(
            'module',
            'inputFilter',
            'form'
        ), $this->Dependency->dependencyListFor('form'));
        $this->assertEquals(array(
            0 => 'module',
            1 => 'inputFilter',
            2 => 'model',
            3 => 'form',
            4 => 'controller',
            5 => 'view',
            6 => 'all'
        ), $this->Dependency->dependencyListFor('all'));
    }
}

