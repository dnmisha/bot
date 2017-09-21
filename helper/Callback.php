<?php namespace bot\helper;

use yii\helpers\ArrayHelper as AH;

/**
 * Callbacks / Callables
 * Callbacks can be denoted by callable type hint as of PHP 5.4.
 * This documentation used callback type information for the same purpose.
 *
 * Some functions like call_user_func() or usort() accept user-defined callback
 * functions as a parameter. Callback functions can not only be simple functions,
 * but also object methods, including static class methods.
 *
 *
 * @author Mehdi Khodayari <mehdi.khodayari.khoram@gmail.com>
 * @since 3.0.1
 *
 * Class Callback
 * @package bot\helper
 * @link http://php.net/manual/en/language.types.callable.php
 */
abstract class Callback
{

    /**
     * Call callback function by special parameters.
     *
     * @param string|array|callable $callback
     * @param array $params
     * @return mixed
     */
    public static function call($callback, $params = [])
    {
        return self::apply($callback, $params);
    }

    /**
     * Apply callback function by special parameters.
     *
     * @param string|array|callable $callback
     * @param array $params
     * @return mixed
     */
    public static function apply($callback, $params = [])
    {
        if (is_array($callback)) {
            $class = $callback[0];
            $method = $callback[1];
            $reflection = new \ReflectionMethod($class, $method);
        }
        else {
            $reflection = new \ReflectionFunction($callback);
        }

        $arguments = self::__arguments($reflection, $params);
        return call_user_func_array($callback, $arguments);
    }

    /**
     * @param \ReflectionFunction|\ReflectionMethod $reflection
     * @param array $params
     * @return array
     */
    private static function __arguments($reflection, $params = [])
    {
        $oldParams = (array) $params;

        $params = [];
        $arguments = $reflection->getParameters();
        foreach ($arguments as $argument) {
            $name = $argument->getName();

            $default = null;
            if ($argument->isDefaultValueAvailable()) {
                $default = $argument->getDefaultValue();
            }

            $value = AH::getValue($oldParams, $name, $default);
            $params[$name] = $value;
        }

        $params['_params'] = $params;
        return $params;
    }
}