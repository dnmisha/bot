<?php namespace bot\object;

/**
 * This object represents one row of the high scores table for a game.
 *
 * @method int getPosition()
 * @method User getUser()
 * @method int getScore()
 *
 * @author Mehdi Khodayari <mehdi.khodayari.khoram@gmail.com>
 * @since 3.0.1
 *
 * Class GameHighScore
 * @package bot\object
 * @link https://core.telegram.org/bots/api#gamehighscore
 */
class GameHighScore extends Object
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
            'user' => User::className()
        ];
    }
}