<?php namespace bot\object;

/**
 * This object represents a telegram error.
 *
 * @method bool hasErrorCode()
 * @method bool hasParameters()
 * @method bool hasDescription()
 * @method int getErrorCode()
 * @method ResponseParameters getParameters($default = null)
 * @method int getDescription()
 *
 * @author Mehdi Khodayari <mehdi.khodayari.khoram@gmail.com>
 * @since 3.0.1
 *
 * Class Error
 * @package bot\object
 */
class Error extends Object
{

    /**
     * Every object have relations with other object,
     * in this method we introduce all object we have relations.
     *
     * @return array of objects
     */
    protected function relations()
    {
        return [
            'parameters' => ResponseParameters::className()
        ];
    }
}