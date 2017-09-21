<?php namespace bot\object;

/**
 * This object represents a phone contact.
 *
 * @method bool hasLastName()
 * @method bool hasUserId()
 * @method string getPhoneNumber()
 * @method string getFirstName()
 * @method string getLastName($default = null)
 * @method int getUserId($default = null)
 *
 * @author Mehdi Khodayari <mehdi.khodayari.khoram@gmail.com>
 * @since 3.0.1
 *
 * Class Contact
 * @package bot\object
 * @link https://core.telegram.org/bots/api#contact
 */
class Contact extends Object
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