<?php namespace bot\object;

/**
 * This object contains basic information about a
 * successful payment.
 *
 * @method bool hasShippingOptionId()
 * @method bool hasOrderInfo()
 * @method string getCurrency()
 * @method int getTotalAmount()
 * @method string getInvoicePayload()
 * @method string getShippingOptionId($default = null)
 * @method OrderInfo getOrderInfo($default = null)
 * @method string getTelegramPaymentChargeId()
 * @method string getProviderPaymentChargeId()
 *
 * @author Mehdi Khodayari <mehdi.khodayari.khoram@gmail.com>
 * @since 3.0.1
 *
 * Class SuccessfulPayment
 * @package bot\object
 * @link https://core.telegram.org/bots/api#successfulpayment
 */
class SuccessfulPayment extends Object
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
            'order_info' => OrderInfo::className()
        ];
    }
}