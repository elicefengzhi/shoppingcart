<?php
namespace VisioCrudModeler\Generator\Request;

use VisioCrudModeler\Generator\Params;
use Zend\Http\Request;

/**
 * Http request generator params class
 * 
 * wrapper for http request
 *
 * @author Bartlomiej Wereszczynski <bartlomiej.wereszczynski@isobar.com>
 * @link      https://github.com/HyPhers/hyphers-visio-crud-zf2
 * @copyright Copyright (c) 2014 HyPHPers Isobar Poland  (Piotr Duda , Przemysław Wlodkowski, Bartlomiej Wereszczynski , Jacek Pawelec , Robert Bodych)
 * @license New BSD License
 *        
 */
class Http extends Params
{

    /**
     * holds request object
     *
     * @var Request
     */
    protected $request = null;

    /**
     * constructor, accepts default params and also those of http request
     *
     * @param array $params            
     * @param Request $request            
     */
    public function __construct(array $params, Request $request)
    {
        $this->request = $request;
        $this->storage = new \ArrayObject(array_merge($params, $request->getPost()->toArray()));
    }

    /**
     * returns http request object
     *
     * @return \Zend\Http\Request
     */
    public function getRequest()
    {
        return $this->request;
    }
}