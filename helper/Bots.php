<?php namespace bot\helper;

use Bot;
use yii\base\Object;
use yii\db\Connection;
use yii\base\InvalidParamException;

/**
 * Bots are third-party applications that run inside Telegram.
 * Users can interact with bots by sending them messages,
 * commands and inline requests.
 *
 * @property string $path
 *
 * @author Mehdi Khodayari <mehdi.khodayari.khoram@gmail.com>
 * @since 3.0.1
 *
 * Class Bots
 * @package bot\helper
 */
class Bots extends Object
{

    /**
     * @var string
     */
    private $_path;

    /**
     * Bots constructor.
     * @param string $path of path we add all bots in there.
     */
    public function __construct($path = '@app/bots')
    {
        $this->_path = \Yii::getAlias($path, true);
        return parent::__construct();
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->_path;
    }

    /**
     * Create a new Bot object by Id, key or username
     * can help to us, create Bot event without token,
     * and get its easy.
     *
     * @param int|string|array $arg
     * @return bool|Bot|false|static
     */
    public function create($arg)
    {
        if (is_int($arg)) {
            $id = $arg;
            return $this->__byID($id);
        }

        if (is_string($arg)) {
            try {
                (new Token($arg, true));
                return $this->__byToken($arg);
            }
            catch (InvalidParamException $e) {
                $username = $this->__byUsername($arg);
                if ($username !== false) return $username;
                else return $this->__byKey($arg);
            }
        }

        if (is_array($arg)) {
            $args = $arg;
            return new Bot($args);
        }

        return false;
    }

    /**
     * @param int $id
     * @return Bot|false
     */
    private function __byID($id)
    {
        $id = intval($id);
        $bots = $this->_allBots();
        foreach ($bots as $username => $bot) {
            if (intval($bot['id']) == $id) {
                return new Bot($bot);
            }
        }

        return false;
    }

    /**
     * @param string $key
     * @return Bot|false
     */
    private function __byKey($key)
    {
        $bots = $this->_allBots();
        foreach ($bots as $username => $bot) {
            if ($bot['key'] == $key) {
                return new Bot($bot);
            }
        }

        return false;
    }

    /**
     * @param string $token
     * @return Bot|false
     */
    private function __byToken($token)
    {
        $bots = $this->_allBots();
        foreach ($bots as $username => $bot) {
            if ($bot['token'] == $token) {
                return new Bot($bot);
            }
        }

        return false;
    }

    /**
     * @param string $username
     * @return Bot|false
     */
    private function __byUsername($username)
    {
        $username = strtolower($username);
        if (strpos($username, '@') === 0) {
            $username = substr($username, 1);
        }

        $bots = $this->_allBots();
        foreach ($bots as $_username => $bot) {
            if ($_username == $username) {
                return new Bot($bot);
            }
        }

        return false;
    }

    /**
     * @return array
     */
    public function _allBots()
    {
        $bots = [];
        $usernames = scandir($this->_path);
        foreach ($usernames as $username) {
            if (strlen($username) <= 5) {
                continue;
            }

            $bot = [];
            $bot['username'] = $username;
            $path = $this->_path . '/' . $username;

            $bot['path'] = $path;
            $languages_path = $path . '/messages';
            if (file_exists($languages_path)) {
                $languages = scandir($languages_path);
                if (sizeof($languages) === 3) {
                    $language = end($languages);
                    $language_path = $languages_path . '/' . $language;
                    if (file_exists($language_path . '/bot.php')) {
                        $bot['language'] = $language;
                    }
                }
            }

            $db_path = $path . '/db.php';
            if (file_exists($db_path)) {
                $db_configs = require $db_path;
                $bot['db'] = new Connection($db_configs);
            }

            $token_path = $path . '/.token';
            if (file_exists($token_path)) {
                $token = file_get_contents($token_path);

                $token_object = new Token($token);
                $bot['id'] = $token_object->id;
                $bot['key'] = $token_object->key;
                $bot['token'] = $token_object->token;

                $bots[$username] = $bot;
            }
        }

        return $bots;
    }
}