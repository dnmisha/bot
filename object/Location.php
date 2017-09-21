<?php namespace bot\object;

/**
 * This object represents a point on the map.
 *
 * @method float getLongitude()
 * @method float getLatitude()
 *
 * @author Mehdi Khodayari <mehdi.khodayari.khoram@gmail.com>
 * @since 3.0.1
 *
 * Class Location
 * @package bot\object
 * @link https://core.telegram.org/bots/api#location
 */
class Location extends Object
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