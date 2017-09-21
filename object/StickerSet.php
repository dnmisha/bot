<?php namespace bot\object;

/**
 * This object represents a sticker set.
 *
 * @method string getName()
 * @method string getTitle()
 * @method bool getContainsMasks()
 * @method Sticker[] getStickers()
 *
 * @author Mehdi Khodayari <mehdi.khodayari.khoram@gmail.com>
 * @since 3.0.1
 *
 * Class StickerSet
 * @package bot\object
 * @link https://core.telegram.org/bots/api#stickerset
 */
class StickerSet extends Object
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
            'stickers' => Sticker::className()
        ];
    }
}