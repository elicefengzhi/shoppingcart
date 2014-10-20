<?php
namespace VisioCrudModeler\Hydrator;

use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\Stdlib\Hydrator\Filter\FilterProviderInterface;
use Zend\Stdlib\Hydrator\Filter\FilterComposite;
use Zend\Stdlib\Hydrator\Filter\MethodMatchFilter;

/**
 * Class methods hydartors.
 * Base On ZF2 ClassMethods, but concerns only public methods.
 *
 * @author Piotr Duda <piotr.duda@dentsuaegis.com, dudapiotrek@gmail.com>
 * @link https://github.com/HyPhers/hyphers-visio-crud-zf2
 * @copyright Copyright (c) 2014 HyPHPers Isobar Poland (Piotr Duda , Przemysław Wlodkowski, Bartlomiej Wereszczynski , Jacek Pawelec , Robert Bodych)
 * @license New BSD License
 *
 */
class ClassMethodsPublicProperty extends ClassMethods
{

    /**
     * Extract values from an object with class methods
     *
     * Extracts the getter/setter of the given $object.
     *
     * @param object $object
     * @return array
     * @throws Exception\BadMethodCallException for a non-object $object
     */
    public function extract($object)
    {
        if (! is_object($object)) {
            throw new \BadMethodCallException(sprintf('%s expects the provided $object to be a PHP object)', __METHOD__));
        }

        $filter = null;
        if ($object instanceof FilterProviderInterface) {
            $filter = new FilterComposite(array(
                $object->getFilter()
            ), array(
                new MethodMatchFilter("getFilter")
            ));
        } else {
            $filter = $this->filterComposite;
        }

        $attributes = array();
        $methods = get_class_methods($object);

        $publicParams = call_user_func('get_object_vars', $object);

        foreach ($methods as $method) {

            if (! $filter->filter(get_class($object) . '::' . $method)) {
                continue;
            }

            $attribute = $method;

            if (preg_match('/^get/', $method)) {
                $attribute = substr($method, 3);
                if (! property_exists($object, $attribute)) {
                    $attribute = lcfirst($attribute);
                }
            }

            if (! array_key_exists($attribute, $publicParams)) {
                continue;
            }

            $attribute = $this->extractName($attribute, $object);

            $attributes[$attribute] = $this->extractValue($attribute, $object->$method(), $object);
        }

        return $attributes;
    }
}
