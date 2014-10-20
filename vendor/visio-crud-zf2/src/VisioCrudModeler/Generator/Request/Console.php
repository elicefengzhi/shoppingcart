<?php
namespace VisioCrudModeler\Generator\Request;

use VisioCrudModeler\Generator\Params;
use Zend\Console\Request;

/**
 * Console request generator params
 * 
 * wrapper for console request
 *
 * @author Bartlomiej Wereszczynski <bartlomiej.wereszczynski@isobar.com>
 * @link      https://github.com/HyPhers/hyphers-visio-crud-zf2
 * @copyright Copyright (c) 2014 HyPHPers Isobar Poland  (Piotr Duda , Przemysław Wlodkowski, Bartlomiej Wereszczynski , Jacek Pawelec , Robert Bodych)
 * @license New BSD License
 *        
 */
class Console extends Params
{

    /**
     * holds console request
     *
     * @var Request
     */
    protected $request = null;

    /**
     * constructor, accepts default params and one from request
     *
     * @param array $params            
     * @param Request $request            
     */
    public function __construct(array $params, Request $request)
    {
        $this->request = $request;
        $this->storage = new \ArrayObject(array_merge($params, $request->getParams()->toArray()));
    }

    /**
     * returns request object
     *
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }
}