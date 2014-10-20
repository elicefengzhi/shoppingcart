<?php

/**
 * Description of DateTimeStrategy
 *
 * @author Piotr Duda (dudapiotrek@gmail.com)
 */
namespace VisioCrudModelerTest\Hydrator\Strategy;

use DateTime;
use VisioCrudModeler\Hydrator\Strategy\DateTimeStrategy;

class DateTimeStrategyTest extends \PHPUnit_Framework_TestCase
{
    
  
    /**
     * Verifies that hydration can happen
     */
    public function testHydrateProperDateTime()
    {
        $dateTimeStrategy = new DateTimeStrategy();
        $this->assertEquals(new DateTime('2014-01-01 00:00:00'), $dateTimeStrategy->hydrate('2014-01-01 00:00:00'));
    }
    
    /**
     * Verifies that extraction can happen
     */
    public function testExtractProperDateTime()
    {
        $dateTimeStrategy = new DateTimeStrategy();
        $this->assertEquals('2014-01-01 00:00:00', $dateTimeStrategy->extract(new DateTime('2014-01-01 00:00:00')));
    }
    
}