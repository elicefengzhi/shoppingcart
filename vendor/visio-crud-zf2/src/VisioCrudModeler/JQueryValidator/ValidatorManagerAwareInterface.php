<?php
namespace VisioCrudModeler\JQueryValidator;

/**
 * Aware Interface of ValidatorManager - dedicated for form objects
 *
 * @author Piotr Duda <piotr.duda@dentsuaegis.com, dudapiotrek@gmail.com>
 * @link https://github.com/HyPhers/hyphers-visio-crud-zf2
 * @copyright Copyright (c) 2014 HyPHPers Isobar Poland (Piotr Duda , Przemysław Wlodkowski, Bartlomiej Wereszczynski , Jacek Pawelec , Robert Bodych)
 * @license New BSD License
 *
 */
interface ValidatorManagerAwareInterface
{

    /**
     * Get validator manager
     *
     * @param \VisioCrudModeler\JQueryValidator\ValidatorManager $validatorManager
     */
    public function getValidatorManager();

    /**
     * Set validator manager
     *
     * @param \VisioCrudModeler\JQueryValidator\ValidatorManager $validatorManager
     */
    public function setValidatorManager(\VisioCrudModeler\JQueryValidator\ValidatorManager $validatorManager);
}
