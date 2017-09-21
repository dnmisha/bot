<?php namespace bot\object;

/**
 * Represents a result of an inline query that was chosen by
 * the user and sent to their chat partner.
 *
 * Note:
 * It is necessary to enable inline feednack via @Botfather
 * in order to receive these objects in updates.
 *
 * @method bool hasLocation()
 * @method bool hasInlineMessageId()
 * @method string getResultId()
 * @method User getFrom()
 * @method Location getLocation($default = null)
 * @method string getInlineMessageId($default = null)
 * @method string getQuery()
 *
 * @author Mehdi Khodayari <mehdi.khodayari.khoram@gmail.com>
 * @since 3.0.1
 *
 * Class ChosenInlineResult
 * @package bot\object
 * @link https://core.telegram.org/bots/api#choseninlineresult
 */
class ChosenInlineResult extends Object
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