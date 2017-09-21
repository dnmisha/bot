<?php namespace bot\object;

/**
 * This object represents one special entity in a text message.
 * For example, hashtags, usernames, URLs, etc.
 *
 * @method bool hasUrl()
 * @method bool hasUser()
 * @method string getType()
 * @method int getOffset()
 * @method int getLength()
 * @method string getUrl($default = null)
 * @method User getUser($default = null)
 *
 * @author Mehdi Khodayari <mehdi.khodayari.khoram@gmail.com>
 * @since 3.0.1
 *
 * Class MessageEntity
 * @package bot\object
 * @link https://core.telegram.org/bots/api#messageentity
 */
class MessageEntity extends Object
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
            'user' => User::className()
        ];
    }
}