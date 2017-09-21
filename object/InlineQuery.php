<?php namespace bot\object;

/**
 * This object represents an incoming inline query. When the
 * user sends an empty query, your bot could return some default
 * or trending results.
 *
 * @method bool hasLocation()
 * @method string getId()
 * @method User getFrom()
 * @method Location getLocation($default = null)
 * @method string getQuery()
 * @method string getOffset()
 *
 * @author Mehdi Khodayari <mehdi.khodayari.khoram@gmail.com>
 * @since 3.0.1
 *
 * Class InlineQuery
 * @package bot\object
 * @link https://core.telegram.org/bots/api#inlinequery
 */
class InlineQuery extends Object
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
            'from' => User::className(),
            'location' => Location::className()
        ];
    }
}