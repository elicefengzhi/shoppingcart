<?php
namespace VisioCrudModeler\JQueryValidator;

/**
 * Validator interface
 *
 * @author Piotr Duda <piotr.duda@dentsuaegis.com, dudapiotrek@gmail.com>
 * @link https://github.com/HyPhers/hyphers-visio-crud-zf2
 * @copyright Copyright (c) 2014 HyPHPers Isobar Poland (Piotr Duda , Przemysław Wlodkowski, Bartlomiej Wereszczynski , Jacek Pawelec , Robert Bodych)
 * @license New BSD License
 *
 */
interface ValidatorInterface
{

    /**
     *
     * Get rule string for Jquery Validate
     *
     * @return string
     */
    public function getRule();
}
