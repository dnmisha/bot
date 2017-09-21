<?php namespace bot\object;

/**
 * This object represents a Telegram user or bot.
 *
 * @method bool hasLastName()
 * @method bool hasUsername()
 * @method bool hasLanguageCode()
 * @method int getId()
 * @method bool getIsBot()
 * @method string getFirstName()
 * @method string getLastName($default = null)
 * @method string getUsername($default = null)
 * @method string getLanguageCode($default = null)
 *
 * @author Mehdi Khodayari <mehdi.khodayari.khoram@gmail.com>
 * @since 3.0.1
 *
 * Class User
 * @package bot\object
 * @link https://core.telegram.org/bots/api#user
 */
class User extends Object
{

    /**
     * @return bool True, if this user is a bot
     */
    public function isBot()
    {
        return $this->getIsBot() == true;
    }

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
