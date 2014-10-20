<?php
namespace VisioCrudModeler\JQueryValidator;

/**
 * Standard DateTime strategy
 *
 * @author Piotr Duda <piotr.duda@dentsuaegis.com, dudapiotrek@gmail.com>
 * @link https://github.com/HyPhers/hyphers-visio-crud-zf2
 * @copyright Copyright (c) 2014 HyPHPers Isobar Poland (Piotr Duda , Przemysław Wlodkowski, Bartlomiej Wereszczynski , Jacek Pawelec , Robert Bodych)
 * @license New BSD License
 *
 */
abstract class AbstractValidator implements ValidatorInterface
{

    /**
     * ZF2 validator object
     *
     * @var \Zend\Validator\ValidatorInterface
     */
    protected $zendValidator = null;

    /**
     * Value for special validation eg.
     * requierd
     *
     * @var mixed
     */
    protected $value = null;

    /**
     * constructor
     *
     * @param mixed $validator
     */
    public function __construct($validator = null)
    {
        if ($validator instanceof \Zend\Validator\ValidatorInterface) {
            $this->setZendValidator($validator);
        } else {
            $this->setValue($validator);
        }
    }

    /**
     * Get Zend validator instance
     *
     * @return \Zend\Validator\ValidatorInterface
     */
    public function getZendValidator()
    {
        return $this->zendValidator;
    }

    /**
     * Get value for defined validator (eg true/false for required)
     *
     * @return type
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set Zend validator instance
     *
     * @param \Zend\Validator\ValidatorInterface $zendValidator
     */
    public function setZendValidator(\Zend\Validator\ValidatorInterface $zendValidator)
    {
        $this->zendValidator = $zendValidator;
    }

    /**
     * Set value for defined validator (eg true/false for required)
     *
     * @param
     *            mixed
     */
    public function setValue($value)
    {
        $this->value = $value;
    }
}
