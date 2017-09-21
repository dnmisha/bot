<?php namespace bot\object;

/**
 * This object contains basic information about an invoice.
 *
 * @method string getTitle()
 * @method string getDescription()
 * @method string getStartParameter()
 * @method string getCurrency()
 * @method int getTotalAmount()
 *
 * @author Mehdi Khodayari <mehdi.khodayari.khoram@gmail.com>
 * @since 3.0.1
 *
 * Class Invoice
 * @package bot\object
 * @link https://core.telegram.org/bots/api#invoice
 */
class Invoice extends Object
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