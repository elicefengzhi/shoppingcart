<?php
namespace VisioCrudModeler\Generator;

/**
 * interface for generator class
 *
 * @author Bartlomiej Wereszczynski <bartlomiej.wereszczynski@isobar.com>
 * @link      https://github.com/HyPhers/hyphers-visio-crud-zf2
 * @copyright Copyright (c) 2014 HyPHPers Isobar Poland  (Piotr Duda , Przemysław Wlodkowski, Bartlomiej Wereszczynski , Jacek Pawelec , Robert Bodych)
 * @license New BSD License
 *        
 */
interface GeneratorInterface
{

    /**
     * method starts generator logic
     *
     * return configuration can be used in other generators to link to elements created by implementing generator
     *
     * @param \VisioCrudModeler\Generator\ParamsInterface $params            
     * @return array
     */
    public function generate(\VisioCrudModeler\Generator\ParamsInterface $params);
}