<?php
namespace VisioCrudModeler\Generator;

use VisioCrudModeler\Exception\InvalidArgumentException;

/**
 * base params class used in executing generators
 *
 * @author Bartlomiej Wereszczynski <bartlomiej.wereszczynski@isobar.com>
 * @link https://github.com/HyPhers/hyphers-visio-crud-zf2
 * @copyright Copyright (c) 2014 HyPHPers Isobar Poland (Piotr Duda , Przemysław Wlodkowski, Bartlomiej Wereszczynski , Jacek Pawelec , Robert Bodych)
 * @license New BSD License
 *
 */
class Params implements ParamsInterface
{

    /**
     * holds params
     *
     * @var \ArrayObject
     */
    protected $storage = null;

    /**
     * constructor accepts array of params
     *
     * @param array $params
     */
    public function __construct(array $params)
    {
        $this->storage = new \ArrayObject($params);
    }

    /*
     * (non-PHPdoc)
     * @see \VisioCrudModeler\Generator\ParamsInterface::getParam()
     */
    public function getParam($name)
    {
        if (! $this->storage->offsetExists($name)) {
            return null;
        }
        return $this->storage->offsetGet($name);
    }

    /*
     * (non-PHPdoc)
     * @see \VisioCrudModeler\Generator\ParamsInterface::getParams()
     */
    public function getParams()
    {
        return $this->storage;
    }

    /*
     * (non-PHPdoc)
     * @see \VisioCrudModeler\Generator\ParamsInterface::setParam()
     */
    public function setParam($name, $value)
    {
        $this->storage->offsetSet($name, $value);
    }

    /*
     * (non-PHPdoc)
     * @see \VisioCrudModeler\Generator\ParamsInterface::setParams()
     */
    public function setParams($params)
    {
        $this->storage = $params;
    }

    /**
     * creates params object form passed $requestParams
     *
     * @param \Zend\Console\Request|\Zend\Http\Request|\ArrayObject|Array $requestParams
     * @throws InvalidArgumentException
     * @return \VisioCrudModeler\Generator\Request\Console|\VisioCrudModeler\Generator\Request\Http|\VisioCrudModeler\Generator\Params|\VisioCrudModeler\Generator\ParamsInterface
     */
    public static function factory($requestParams, array $defaultParams = array())
    {
        if ($requestParams instanceof \Zend\Console\Request) {
            return new Request\Console($defaultParams, $requestParams);
        }
        if ($requestParams instanceof \Zend\Http\Request) {
            return new Request\Http($defaultParams, $requestParams);
        }
        if ($requestParams instanceof \ArrayObject) {
            return new static(array_merge($defaultParams, $requestParams->getArrayCopy()));
        }
        if (is_array($requestParams)) {
            return new static(array_merge($defaultParams, $requestParams));
        }
        throw new InvalidArgumentException('argumnet passed to method "' . __METHOD__ . '" is not resolvable to any params adapter, try simple array or ArrayObject');
    }
}