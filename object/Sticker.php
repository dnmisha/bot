<?php namespace bot\object;

/**
 * This object represents a sticker.
 *
 * @method bool hasThumb()
 * @method bool hasEmoji()
 * @method bool hasSetName()
 * @method bool hasMaskPosition()
 * @method bool hasFileSize()
 * @method string getFileId()
 * @method int getWidth()
 * @method int getHeight()
 * @method PhotoSize getThumb($default = null)
 * @method string getEmoji($default = null)
 * @method string getSetName($default = null)
 * @method MaskPosition getMaskPosition($default = null)
 * @method int getFileSize($default = null)
 *
 * @author Mehdi Khodayari <mehdi.khodayari.khoram@gmail.com>
 * @since 3.0.1
 *
 * Class Sticker
 * @package bot\object
 * @link https://core.telegram.org/bots/api#sticker
 */
class Sticker extends Object
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
            'thumb' => PhotoSize::className(),
            'mask_position' => MaskPosition::className()
        ];
    }
}