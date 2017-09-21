<?php namespace bot\object;

/**
 * This object represent a user's profile pictures.
 *
 * @method int getTotalCount()
 * @method PhotoSize[] getPhotos()
 *
 * @author Mehdi Khodayari <mehdi.khodayari.khoram@gmail.com>
 * @since 3.0.1
 *
 * Class UserProfilePhotos
 * @package bot\object
 * @link https://core.telegram.org/bots/api#userprofilephotos
 */
class UserProfilePhotos extends Object
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
            'photos' => PhotoSize::className()
        ];
    }
}