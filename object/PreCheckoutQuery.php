<?php namespace bot\object;

/**
 * This object contains information about an
 * incoming pre-checkout query.
 *
 * @method bool hasShippingOptionId()
 * @method bool hasOrderInfo()
 * @method string getId()
 * @method User getFrom()
 * @method string getCurrency()
 * @method int getTotalAmount()
 * @method string getInvoicePayload()
 * @method string getShippingOptionId($default = null)
 * @method OrderInfo getOrderInfo($default = null)
 *
 * @author Mehdi Khodayari <mehdi.khodayari.khoram@gmail.com>
 * @since 3.0.1
 *
 * Class PreCheckoutQuery
 * @package bot\object
 * @link https://core.telegram.org/bots/api#precheckoutquery
 */
class PreCheckoutQuery extends Object
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
            'order_info' => OrderInfo::className()
        ];
    }
}