<?php namespace bot\object;

/**
 * This object represents a portion of the
 * price for goods or services.
 *
 * @method string getLabel()
 * @method int getAmount()
 *
 * @author Mehdi Khodayari <mehdi.khodayari.khoram@gmail.com>
 * @since 3.0.1
 *
 * Class LabeledPrice
 * @package bot\object
 * @link https://core.telegram.org/bots/api#labeledprice
 */
class LabeledPrice extends Object
{

    /**
     * Every object have relations with other object,
     * in this method we introduce all object we have relations.
     *
     * @return array of objects
     */
    protected function relations()
    {
        return [];
    }
}