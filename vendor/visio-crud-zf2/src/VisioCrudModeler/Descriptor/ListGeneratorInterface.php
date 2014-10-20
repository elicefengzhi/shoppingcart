<?php
namespace VisioCrudModeler\Descriptor;

/**
 * interface for object implementing generator
 *
 * object implementing this interface will yield its child objects as value
 * while key will hold its coresponding key
 *
 * @author Bartlomiej Wereszczynski <bartlomiej.wereszczynski@isobar.com>
 * @link      https://github.com/HyPhers/hyphers-visio-crud-zf2
 * @copyright Copyright (c) 2014 HyPHPers Isobar Poland  (Piotr Duda , Przemysław Wlodkowski, Bartlomiej Wereszczynski , Jacek Pawelec , Robert Bodych)
 * @license New BSD License
 *
 */
interface ListGeneratorInterface
{

    /**
     * list generator
     *
     * @return \Generator
     */
    public function listGenerator();
}