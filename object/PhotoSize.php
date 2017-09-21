<?php namespace bot\object;

/**
 * This object represents one size of a photo or a file / sticker thumbnail.
 *
 * @method bool hasFileSize()
 * @method string getFileId()
 * @method int getWidth()
 * @method int getHeight()
 * @method int getFileSize($default = null)
 *
 * @author Mehdi Khodayari <mehdi.khodayari.khoram@gmail.com>
 * @since 3.0.1
 *
 * Class PhotoSize
 * @package bot\object
 * @link https://core.telegram.org/bots/api#photosize
 */
class PhotoSize extends Object
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