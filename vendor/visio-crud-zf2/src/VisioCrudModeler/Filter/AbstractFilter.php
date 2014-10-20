<?php

namespace VisioCrudModeler\Filter;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterInterface;

/**
 * Abstract filter class mainly store inputFactory and inputFilter objects
 *
 * @author Piotr Duda <piotr.duda@dentsuaegis.com, dudapiotrek@gmail.com>
 * @link https://github.com/HyPhers/hyphers-visio-crud-zf2
 * @copyright Copyright (c) 2014 HyPHPers Isobar Poland (Piotr Duda , Przemysław Wlodkowski, Bartlomiej Wereszczynski , Jacek Pawelec , Robert Bodych)
 * @license New BSD License
 *         
 */
class AbstractFilter
{

    /**
     *
     * @var \Zend\InputFilter\Factory 
     */
    protected $inputFactory;

    /**
     *
     * @var \Zend\InputFilter\InputFilterInterface
     */
    protected $inputFilter;

    /**
     * 
     * @param \Zend\InputFilter\InputFilterInterface $inputFilter
     * @throws \Exception
     */
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        $this->inputFilter = $inputFilter;
    }

    /**
     * 
     * @return \Zend\InputFilter\InputFilter
     */
    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $this->inputFilter = new InputFilter();
        }
        return $this->inputFilter;
    }

    /**
     * Get InputFactory
     * 
     * @return \Zend\InputFilter\Factory
     */
    public function getInputFactory()
    {
        if (!$this->inputFactory) {
            $this->inputFactory = new InputFactory();
        }
        return $this->inputFactory;
    }

    /**
     * 
     * @param \Zend\InputFilter\Factory $inputFactory
     */
    public function setInputFactory(\Zend\InputFilter\Factory $inputFactory)
    {
        $this->inputFactory = $inputFactory;
    }

}
