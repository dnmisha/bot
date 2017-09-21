<?php namespace bot\base;

use yii\helpers\Json;
use yii\helpers\Inflector;
use yii\helpers\ArrayHelper as AH;
use yii\base\Object as baseObject;
use yii\base\InvalidParamException;
use yii\base\UnknownMethodException;

/**
 * Object is the base class that implements the *property* feature.
 * property is defined by a getter method (e.g. `getLabel`),
 * and/or a setter method (e.g. `setLabel`).
 *
 * Property names are *case-insensitive*.
 * A property can be accessed like a member variable of an object.
 * Reading or writing a property will cause the invocationof
 * the corresponding getter or setter method.
 *
 * If a property has only a getter method and has no setter method,
 * it is considered as *read-only*.
 *
 * In this case, trying to modify the property
 * value will cause an exception.
 *
 * @author Mehdi Khodayari <mehdi.khodayari.khoram@gmail.com>
 * @since 3.0.1
 *
 * Class Object
 * @package bot\base
 */
abstract class Object extends baseObject
{

    /**
     * Each object consists of several properties.
     * These features make it possible to distinguish objects
     * from one another.
     *
     * @var array
     */
    protected $properties = [];

    /**
     * Get empty of all properties that set in the past,
     * returning an object to its base state.
     *
     * @return $this
     */
    public function _empty()
    {
        $this->properties = [];
        return $this;
    }

    /**
     * Converting an object to a string when the object
     * needs to be treated like a string.
     *
     * @return string
     */
    public function _json()
    {
        $array = $this->_array();
        return Json::encode($array);
    }

    /**
     * Converting an object to a string when the object
     * needs to be treated like a array.
     *
     * @return array
     */
    public function _array()
    {
        $properties = $this->properties;
        return $this->__arrayMap($properties);
    }

    /**
     * Checks if a property is set, i.e. defined and not null.
     * Do not call this method directly as it is a PHP magic method that
     * will be implicitly called when executing
     * `isset($object->property)`.
     *
     * Note that if the property is not defined,
     * false will be returned.
     *
     * @param string $name the property name or the event name
     * @return bool whether the named property is set (not null).
     * @see http://php.net/manual/en/function.isset.php
     */
    public function __isset($name)
    {
        $properties = $this->properties;
        return AH::keyExists($name, $properties);
    }

    /**
     * Sets an object property to null.
     * Do not call this method directly as it is a PHP magic method that
     * will be implicitly called when executing
     * `unset($object->property)`.
     *
     * Note that if the property is not defined,
     * this method will do nothing.
     *
     * If the property is read-only, it will throw an exception.
     * @param string $name the property name
     * @return mixed the property last value
     * @see http://php.net/manual/en/function.unset.php
     */
    public function __unset($name)
    {
        $properties = $this->properties;
        AH::remove($properties, $name);

        return true;
    }

    /**
     * Sets value of an object property.
     * Do not call this method directly as it is a PHP magic method that
     * will be implicitly called when executing
     * `$object->property = $value;`.
     *
     * @param string $name the property name or the event name
     * @param mixed $value the property value
     * @return mixed the property value
     */
    public function __set($name, $value)
    {
        $setter = 'set' . $name;
        if (method_exists($this, $setter)) {
            return parent::__set($name, $value);
        }

        $this->properties[$name] = $value;
        return $value;
    }

    /**
     * Returns the value of an object property.
     * Do not call this method directly as it is a PHP magic method that
     * will be implicitly called when executing
     * `$value = $object->property;`.
     *
     * @param string $name the property name
     * @return mixed the property value
     */
    public function __get($name)
    {
        $getter = 'get' . $name;
        if (method_exists($this, $getter)) {
            return parent::__get($name);
        }

        if ($this->__isset($name)) {
            $properties = $this->properties;
            return AH::getValue($properties, $name);
        }

        return null;
    }

    /**
     * Calls the named method which is not a class method.
     *
     * Do not call this method directly as it is a PHP magic method that
     * will be implicitly called when an unknown method is being invoked.
     * @param string $name the method name
     * @param array $params method parameters
     * @throws UnknownMethodException when calling unknown method
     * @throws InvalidParamException when didn't sent params
     * @return mixed the method return value
     */
    public function __call($name, $params)
    {
        $action = substr($name, 0, 3);
        $actions = ['set', 'get', 'has', 'rem'];
        if (array_search($action, $actions) === false) {
            parent::__call($name, $params);
        }

        $restName = substr($name, 3);
        $key = Inflector::camel2id($restName, '_');

        // remove
        if ($action == 'rem') {
            $this->__unset($key);
            return $this;
        }

        // checkout
        if ($action == 'has') {
            $has = $this->__isset($key);
            return $has;
        }

        // setter method
        if ($action == 'set') {
            if (sizeof($params) > 0) {
                $this->__set($key, $params[0]);
                return $this;
            }
            else {
                $info = $this->className() . '::' . $key . '($value)';
                $message = 'You must set property value in ' . $info;
                throw new InvalidParamException($message);
            }
        }

        // getter method
        if ($action == 'get') {
            $default = sizeof($params) > 0 ? $params[0] : null;
            return AH::getValue($this->properties, $key, $default);
        }
    }

    /**
     * Checking each level of the array to convert
     * an object to an array.
     *
     * @param array $array
     * @return array
     */
    private function __arrayMap(array $array)
    {
        $output = [];

        foreach ($array as $item => $value) {
            if ($value instanceof Object) {
                $output[$item] = $value->_array();
            }
            else if (is_array($value)) {
                $output[$item] = $this->__arrayMap($value);
            }
            else {
                $output[$item] = $value;
            }
        }

        return $output;
    }
}