<?php namespace bot\object;

use bot\base\Object as baseObject;
use yii\helpers\ArrayHelper as AH;
use yii\base\UnknownClassException;

/**
 * Available types
 * All types used in the Bot API responses are represented as JSON-objects.
 * It is safe to use 32-bit signed integers for storing all Integer
 * fields unless otherwise noted.
 *
 * -- Optional fields may be not returned
 *    when irrelevant.
 *
 * @author Mehdi Khodayari <mehdi.khodayari.khoram@gmail.com>
 * @since 3.0.1
 *
 * Class Object
 * @package bot\object
 * @link https://core.telegram.org/bots/api#available-types
 */
abstract class Object extends baseObject
{

    /**
     * Initializes the object.
     * This method is invoked at the end of the constructor after
     * the object is initialized with the given configuration.
     */
    public function init()
    {
        $relations = $this->relations();
        foreach ($relations as $property => $className) {
            if (
                class_exists($className) &&
                $this->__isset($property)
            ) {
                $value = $this->__get($property);
                $relation = $this->__createRelation($className, $value);

                // set property by relation
                $this->__set($property, $relation);
            }

            else if (!class_exists($className)) {
                $message = 'Not found relation: ' . $className;
                throw new UnknownClassException($message);
            }
        }

        parent::init();
    }

    /**
     * Finding the relationships and creating them and
     * attach to this object.
     *
     * @param string $className the relation class object
     * @param array $params all properties of object
     * @return array
     * @throws UnknownClassException
     */
    private function __createRelation($className, $params)
    {
        if (AH::isAssociative($params)) {
            $class = new $className($params);
            if ($class instanceof Object) {
                return $class;
            }

            $message = $className . ' isn\'t a response object.';
            throw new UnknownClassException($message);
        }

        if (AH::isIndexed($params)) {
            $output = [];
            foreach ($params as $name => $value) {
                $relation = $this->__createRelation($className, $value);
                $output[$name] = $relation;
            }

            return $output;
        }

        return $params;
    }

    /**
     * Every object have relations with other object,
     * in this method we introduce all object we have relations.
     *
     * @return array of objects
     */
    abstract protected function relations();
}