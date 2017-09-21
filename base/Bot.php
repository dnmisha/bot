<?php

use bot\helper\API;
use yii\base\Object;
use bot\object\User;
use bot\object\Chat;
use bot\helper\Token;
use yii\helpers\Json;
use bot\helper\RegEx;
use bot\object\Update;
use yii\db\Connection;
use yii\log\FileTarget;
use bot\base\Translator;
use bot\helper\Callback;
use yii\caching\FileCache;
use yii\helpers\ArrayHelper as AH;
use yii\base\InvalidParamException;

/**
 * Bots are third-party applications that run inside Telegram.
 * Users can interact with bots by sending them messages,
 * commands and inline requests.
 *
 * You control your bots using HTTPS requests to our bot API.
 *
 * @author Mehdi Khodayari <mehdi.khodayari.khoram@gmail.com>
 * @since 3.0.1
 *
 * Class Bot
 * @package bot
 * @link https://core.telegram.org/bots
 */
class Bot extends Object
{

    /**
     * add-ons: This helps developers to create
     * bot with multi-languages.
     */
    use Translator;

    /**
     * @var int
     */
    public static $id;

    /**
     * @var string
     */
    public static $key;

    /**
     * @var string
     */
    public static $token;

    /**
     * @var API
     */
    public static $api;

    /**
     * @var string
     */
    public static $username;

    /**
     * @var string of the Bot path that show where is
     * the Bot in yii2 project.
     */
    public static $path;

    /**
     * @var string of the Bot link in telegram server
     */
    public static $link;

    /**
     * @var Update that sent from telegram server
     */
    public static $update;

    /**
     * @var User who sent update
     */
    public static $user;

    /**
     * @var Chat that user sent update from that
     */
    public static $chat;

    /**
     * @var Connection
     */
    public static $db;

    /**
     * @var FileCache
     */
    public static $cache;

    /**
     * @var array of Bot properties
     */
    public static $configs = [];

    /**
     * set the database connection.
     * By default, the "db" application component is used
     * as the database connection. You may override this method if you
     * want to use a different database connection.
     *
     * @param Connection $db
     */
    public static function setDb(Connection $db)
    {
        self::$db = $db;
    }

    /**
     * Returns the database connection
     * component.
     *
     * @return \yii\db\Connection the database
     * connection.
     */
    public static function getDb()
    {
        if (self::$db instanceof Connection) {
            return self::$db;
        }

        $dbs = Yii::$app->getDb();
        if (is_array($dbs) && sizeof($dbs) > 0) {
            return $dbs[0];
        }

        return $dbs;
    }

    /**
     * Checks if a property is set, i.e.
     * defined and not null.
     *
     * Note:
     * that if the property is not defined,
     * false will be returned.
     *
     * @param string $key the property name or the event name
     * @return bool whether the named property is set (not null).
     */
    public static function has($key)
    {
        $configs = self::$configs;
        return AH::keyExists($key, $configs);
    }

    /**
     * Sets an object property to null.
     *
     * Note:
     * that if the property is not defined,
     * this method will do nothing.
     *
     * If the property is read-only,
     * it will throw an exception.
     *
     * @param string $key the property name
     * @return true
     */
    public static function rem($key)
    {
        AH::remove(self::$configs, $key);
        return true;
    }

    /**
     * Sets value of an object property.
     *
     * @param string $key the property name or the event name
     * @param mixed $value the property value
     * @return $this
     */
    public static function set($key, $value)
    {
        self::$configs[$key] = $value;
        return $value;
    }

    /**
     * Returns the value of an object property.
     *
     * @param string $key the property name
     * @param mixed $default the default value to be returned if
     * the specified array key does not exist. Not used when getting
     * value from an object.
     *
     * @return mixed
     */
    public static function get($key, $default = null)
    {
        $configs = self::$configs;
        return AH::getValue($configs, $key, $default);
    }

    /**
     * Logs an error message.
     * An error message is typically logged when an unrecoverable error occurs
     * during the execution of an application.
     * @param string|array $message the message to be logged. This can be a simple string or a more
     * complex data structure, such as array.
     */
    public static function error($message)
    {
        \Yii::error($message, 'bot');
    }

    /**
     * Logs a warning message.
     * A warning message is typically logged when an error occurs while the execution
     * can still continue.
     * @param string|array $message the message to be logged. This can be a simple string or a more
     * complex data structure, such as array.
     */
    public static function warning($message)
    {
        \Yii::warning($message, 'bot');
    }

    /**
     * Logs an informative message.
     * An informative message is typically logged by an application to keep record of
     * something important (e.g. an administrator logs in).
     * @param string|array $message the message to be logged. This can be a simple string or a more
     * complex data structure, such as array.
     */
    public static function info($message)
    {
        \Yii::info($message, 'bot');
    }

    /**
     * Marks the beginning of a code block for profiling.
     * This has to be matched with a call to [[endProfile]] with the same category name.
     * The begin- and end- calls must also be properly nested. For example,
     *
     * ```php
     * \Yii::beginProfile('block1');
     * // some code to be profiled
     *     \Yii::beginProfile('block2');
     *     // some other code to be profiled
     *     \Yii::endProfile('block2');
     * \Yii::endProfile('block1');
     * ```
     * @param string $token token for the code block
     * @see endProfile()
     */
    public static function beginProfile($token)
    {
        \Yii::beginProfile($token, 'bot');
    }

    /**
     * Marks the end of a code block for profiling.
     * This has to be matched with a previous call to
     * [[beginProfile]] with the same category name.
     *
     * @param string $token token for the code block
     * @see beginProfile()
     */
    public static function endProfile($token)
    {
        \Yii::endProfile($token, 'bot');
    }

    /**
     * New message
     *
     * @param string $pattern
     * @param string|array|callable $callback
     * @return bool
     */
    public static function text($pattern, $callback)
    {
        if (
            self::$update instanceof Update &&
            self::$update->hasMessage()
        ) {
            $message = self::$update->getMessage();
            if ($message->hasText()) {
                $params = self::$configs;
                $text = $message->getText();
                if (RegEx::compare($pattern, $text, $params)) {
                    Callback::apply($callback, $params);
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * New version of a message that is known to
     * the bot and was edited.
     *
     * @param string $pattern
     * @param string|array|callable $callback
     * @return bool
     */
    public static function editedText($pattern, $callback)
    {
        if (
            self::$update instanceof Update &&
            self::$update->hasEditedMessage()
        ) {
            $message = self::$update->getEditedMessage();
            if ($message->hasText()) {
                $params = self::$configs;
                $text = $message->getText();
                if (RegEx::compare($pattern, $text, $params)) {
                    Callback::apply($callback, $params);
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * New incoming channel post of text.
     *
     * @param string $pattern
     * @param string|array|callable $callback
     * @return bool
     */
    public static function channelText($pattern, $callback)
    {
        if (
            self::$update instanceof Update &&
            self::$update->hasChannelPost()
        ) {
            $message = self::$update->getChannelPost();
            if ($message->hasText()) {
                $params = self::$configs;
                $text = $message->getText();
                if (RegEx::compare($pattern, $text, $params)) {
                    Callback::apply($callback, $params);
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * New version of a channel post that is known to
     * the bot and was edited.
     *
     * @param string $pattern
     * @param string|array|callable $callback
     * @return bool
     */
    public static function editedChannelText($pattern, $callback)
    {
        if (
            self::$update instanceof Update &&
            self::$update->hasEditedChannelPost()
        ) {
            $message = self::$update->getEditedChannelPost();
            if ($message->hasText()) {
                $params = self::$configs;
                $text = $message->getText();
                if (RegEx::compare($pattern, $text, $params)) {
                    Callback::apply($callback, $params);
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * New incoming inline query.
     *
     * @param string $pattern
     * @param string|array|callable $callback
     * @return bool
     */
    public static function query($pattern, $callback)
    {
        if (
            self::$update instanceof Update &&
            self::$update->hasInlineQuery()
        ) {
            $inline = self::$update->getInlineQuery();
            $params = self::$configs;
            $query = $inline->getQuery();
            if (RegEx::compare($pattern, $query, $params)) {
                Callback::apply($callback, $params);
                return true;
            }
        }

        return false;
    }

    /**
     * The result of an inline query that was chosen
     * by a user and sent to their chat partner.
     *
     * @param string $pattern
     * @param string|array|callable $callback
     * @return bool
     */
    public static function result($pattern, $callback)
    {
        if (
            self::$update instanceof Update &&
            self::$update->hasChosenInlineResult()
        ) {
            $result = self::$update->getChosenInlineResult();
            $params = self::$configs;
            $result_id = $result->getResultId();
            if (RegEx::compare($pattern, $result_id, $params)) {
                Callback::apply($callback, $params);
                return true;
            }
        }

        return false;
    }

    /**
     * New incoming callback query.
     *
     * @param string $pattern
     * @param string|array|callable $callback
     * @return bool
     */
    public static function data($pattern, $callback)
    {
        if (
            self::$update instanceof Update &&
            self::$update->hasCallbackQuery()
        ) {
            $cQuery = self::$update->getCallbackQuery();
            if ($cQuery->hasData()) {
                $params = self::$configs;
                $data = $cQuery->getData();
                if (RegEx::compare($pattern, $data, $params)) {
                    Callback::apply($callback, $params);
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * New incoming shipping query. Only for
     * invoices with flexible price.
     *
     * @param string $pattern
     * @param string|array|callable $callback
     * @return bool
     */
    public static function shipping($pattern, $callback)
    {
        if (
            self::$update instanceof Update &&
            self::$update->hasShippingQuery()
        ) {
            $shipping = self::$update->getShippingQuery();
            $params = self::$configs;
            $id = $shipping->getId();
            if (RegEx::compare($pattern, $id, $params)) {
                Callback::apply($callback, $params);
                return true;
            }
        }

        return false;
    }

    /**
     * New incoming pre-checkout query.
     * Contains full information about checkout.
     *
     * @param string $pattern
     * @param string|array|callable $callback
     * @return bool
     */
    public static function preCheckout($pattern, $callback)
    {
        if (
            self::$update instanceof Update &&
            self::$update->hasPreCheckoutQuery()
        ) {
            $query = self::$update->getPreCheckoutQuery();
            $params = self::$configs;
            $id = $query->getId();
            if (RegEx::compare($pattern, $id, $params)) {
                Callback::apply($callback, $params);
                return true;
            }
        }

        return false;
    }

    /**
     * Initializes the object.
     * This method is invoked at the end of the constructor after
     * the object is initialized with the given configuration.
     */
    public function init()
    {
        self::$configs = [];
        $update = file_get_contents('php://input');

        if (
            self::$update == null &&
            !empty($update) && is_string($update)
        ) {
            $this->setUpdate($update);
        }

        parent::init();
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        if (is_int($id)) {
            self::$id = $id;

            if (self::$key !== null) {
                $token = self::$id . ':' . self::$key;
                $this->setToken($token);
            }
        }
    }

    /**
     * @param string $key
     */
    public function setKey($key)
    {
        if (is_string($key)) {
            self::$key = $key;

            if (self::$id !== null) {
                $token = self::$id . ':' . self::$key;
                $this->setToken($token);
            }
        }
    }

    /**
     * @param string $token
     */
    public function setToken($token)
    {
        $tObj = new Token($token);
        self::$id = $tObj->getId();
        self::$key = $tObj->getKey();
        self::$token = $tObj->getToken();
        self::$api = new API(self::$token);
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $string = strtolower($username);
        if (strpos($string, '@') === 0) {
            $string = substr($string, 1);
        }

        self::$username = $string;
        self::$link = 'https://t.me/' . $string;

        if (self::$path !== null) {
            Yii::setAlias('@' . $string, self::$path);
        }
    }

    /**
     * @param string $path
     */
    public function setPath($path)
    {
        if (file_exists($path . '/.token')) {
            $path = realpath($path);
            self::$path = $path;

            // cache
            self::$cache = new FileCache([
                'cachePath' => $path. '/cache'
            ]);

            // logs
            Yii::$app->log->targets[] = new FileTarget([
                'logFile' => $path . '/logs/bot.log',
                'categories' => ['bot']
            ]);

            Yii::setAlias('@bots/bot', $path);
            Yii::setAlias('@bot/src', $path . '/src');
            Yii::setAlias('@bot/files', $path . '/files');
            
            if (self::$username !== null) {
                Yii::setAlias('@' . self::$username, $path);
            }
        }
        else {
            $message = 'Not found ' . $path . '/.token';
            throw new InvalidParamException($message);
        }
    }

    /**
     * @param string|array $update
     */
    public function setUpdate($update)
    {
        if (is_string($update)) {
            $update = Json::decode($update, true);
        }

        self::$update = new Update($update);
        self::$user = self::$update->getFrom();
        self::$chat = self::$update->getChat();
    }
}