<?php namespace bot\object;

/**
 * This object represents a chat photo.
 *
 * @method bool getSmallFileId()
 * @method bool getBigFileId()
 *
 * @author Mehdi Khodayari <mehdi.khodayari.khoram@gmail.com>
 * @since 3.0.1
 *
 * Class ChatPhoto
 * @package bot\object
 * @link https://core.telegram.org/bots/api#chatphoto
 */
class ChatPhoto extends Object
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