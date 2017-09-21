<?php namespace bot\object;

/**
 * This object contains information about an
 * incoming shipping query.
 *
 * @method string getId()
 * @method User getFrom()
 * @method string getInvoicePayload()
 * @method ShippingAddress getShippingAddress()
 *
 * @author Mehdi Khodayari <mehdi.khodayari.khoram@gmail.com>
 * @since 3.0.1
 *
 * Class ShippingQuery
 * @package bot\object
 * @link https://core.telegram.org/bots/api#shippingquery
 */
class ShippingQuery extends Object
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
            'from' => User::className(),
            'shipping_address' => ShippingAddress::className()
        ];
    }
}