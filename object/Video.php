<?php namespace bot\object;

/**
 * This object represents a video file.
 *
 * @method bool hasThumb()
 * @method bool hasMimeType()
 * @method bool hasFileSize()
 * @method string getFileId()
 * @method int getWidth()
 * @method int getHeight()
 * @method int getDuration()
 * @method PhotoSize getThumb($default = null)
 * @method string getMimeType($default = null)
 * @method int getFileSize($default = null)
 *
 * @author Mehdi Khodayari <mehdi.khodayari.khoram@gmail.com>
 * @since 3.0.1
 *
 * Class Video
 * @package bot\object
 * @link https://core.telegram.org/bots/api#video
 */
class Video extends Object
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
            'thumb' => PhotoSize::className()
        ];
    }
}