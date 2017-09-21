<?php namespace bot\object;

/**
 * This object represents a venue.
 *
 * @method bool hasFoursquareId()
 * @method Location getLocation()
 * @method string getTitle()
 * @method string getAddress()
 * @method string getFoursquareId($default = null)
 *
 * @author Mehdi Khodayari <mehdi.khodayari.khoram@gmail.com>
 * @since 3.0.1
 *
 * Class Venue
 * @package bot\object
 * @link https://core.telegram.org/bots/api#venue
 */
class Venue extends Object
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
            'location' => Location::className()
        ];
    }
}