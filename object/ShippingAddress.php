<?php namespace bot\object;

/**
 * This object represents a shipping address.
 *
 * @method bool getCountryCode()
 * @method bool getState()
 * @method bool getCity()
 * @method bool getStreetLine1()
 * @method bool getStreetLine2()
 * @method bool getPostCode()
 *
 * @author Mehdi Khodayari <mehdi.khodayari.khoram@gmail.com>
 * @since 3.0.1
 *
 * Class ShippingAddress
 * @package bot\object
 * @link https://core.telegram.org/bots/api#shippingaddress
 */
class ShippingAddress extends Object
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