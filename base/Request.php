<?php namespace bot\base;

use bot\InputFile;
use bot\helper\Curl;
use bot\object\File;
use yii\helpers\Json;
use bot\helper\Token;
use yii\helpers\ArrayHelper as AH;

/**
 * All queries to the Telegram Bot API must be served over
 * HTTPS and need to be presented in this form:
 * ```https://api.telegram.org/bot<token>/METHOD_NAME```
 *
 * Telegram support GET and POST HTTP methods. Telegram support
 * four ways of passing parameters in Bot API requests:
 *
 * 1. URL query string
 * 2. application/x-www-form-urlencoded
 * 3. application/json (except for uploading files)
 * 4. multipart/form-data (use to upload files)
 *
 * Note:
 * If the request was successful and the result of the query can be found
 * in the ‘result’ field. In case of an unsuccessful request, the error is
 * explained in the ‘description’.
 *
 * An Integer ‘error_code’ field is also returned,
 * but its contents are subject to change in the future.
 * Some errors may also have an optional field ‘parameters’ of
 * the type ResponseParameters, which can help to automatically
 * handle the error.
 *
 * 1. All methods in the Bot API are case-insensitive.
 * 2. All queries must be made using UTF-8.
 *
 * @author Mehdi Khodayari <mehdi.khodayari.khoram@gmail.com>
 * @since 3.0.1
 *
 * Class Request
 * @package bot\base
 * @link https://core.telegram.org/bots/api#making-requests
 */
class Request extends Object
{

    /**
     * @var string of api server.
     */
    const HOST = 'https://api.telegram.org';

    /**
     * @var Token
     */
    protected $token;

    /**
     * Request constructor.
     * @param string|Token $token the bot token
     * @param array $params properties of object request
     */
    public function __construct($token, $params = [])
    {
        if ($token instanceof Token) $this->token = $token;
        else $this->token = new Token($token, true);
        parent::__construct($params);
    }

    /**
     * Checks if a property is set, i.e. defined and not null.
     *
     * Note that if the property is not defined,
     * false will be returned.
     *
     * @param string $name the property name or the event name
     * @return bool whether the named property is set (not null).
     * @see http://php.net/manual/en/function.isset.php
     */
    public function has($name)
    {
        $has = $this->__isset($name);
        return $has;
    }

    /**
     * Sets an object property to null.
     *
     * Note that if the property is not defined,
     * this method will do nothing.
     *
     * If the property is read-only, it will throw an exception.
     * @param string $name the property name
     * @return mixed the property last value
     * @see http://php.net/manual/en/function.unset.php
     */
    public function rem($name)
    {
        $this->__unset($name);
        return $this;
    }

    /**
     * Sets value of an object property.
     *
     * @param string $name the property name or the event name
     * @param mixed $value the property value
     * @return $this
     */
    public function set($name, $value)
    {
        $this->__set($name, $value);
        return $this;
    }

    /**
     * Returns the value of an object property.
     *
     * @param string $name the property name
     * @param  mixed $default the default value to be returned if the
     * specified array key does not exist. Not used when getting value
     * from an object.
     *
     * @return mixed the property value
     */
    public function get($name, $default = null)
    {
        if ($this->has($name)) {
            return $this->__get($name);
        }

        return $default;
    }

    /**
     * Send this request to telegram api server.
     * @return array
     */
    public function send()
    {
        if ($this->__hasFile()) {
            return $this->__sendWithFile();
        }

        return $this->__send();
    }

    /**
     * Checking the file is in this request will help us
     * to save the files in the cache system so that later we
     * do not need to upload files again.
     *
     * @return bool
     */
    private function __hasFile()
    {
        $properties = $this->properties;
        foreach ($properties as $key => $value) {
            if (
                $value instanceof InputFile ||
                filter_var($value, FILTER_VALIDATE_URL)
            ) {
                return true;
            }
        }

        return false;
    }

    /**
     * Send request, this method take
     * a few second to get back result.
     *
     * @param callable $callback
     * @return array
     */
    private function __send($callback = null)
    {
        $curl = new Curl();
        $curl->setOption(CURLOPT_TIMEOUT, 30);
        $curl->setOption(CURLOPT_HEADER, false);
        $curl->setOption(CURLOPT_RETURNTRANSFER, true);
        $curl->setOption(CURLOPT_SSL_VERIFYPEER, false);

        $params = [];
        foreach ($this->_array() as $key => $value) {
            if (is_array($value)) {
                $params[$key] = Json::encode($value);
                continue;
            }

            $params[$key] = $value;
        }

        $curl->setOption(CURLOPT_POSTFIELDS, $params);
        @call_user_func($callback, $curl);

        $token = $this->token->id . ':' . $this->token->key;
        $url = self::HOST . '/bot' . $token . '/';
        return $curl->post($url, true);
    }

    /**
     * Send request with file, this method take
     * a few second to get back result.
     *
     * @return array
     */
    private function __sendWithFile()
    {
        $cache = \Yii::$app->cache;
        if (\Bot::$cache !== null) {
            $cache = \Bot::$cache;
        }

        $token = $this->token;
        $B_ID = 'BF:' . $token->id . ':';

        // file cache duration
        $duration = $this->get('cache_time', 0);
        $this->rem('cache_time');

        $properties = $this->properties;
        foreach ($properties as $key => $value) {
            if ($value instanceof InputFile) {
                $path = $value->getFilename();
                $BF_ID = $B_ID . md5_file($path);

                if ($file_id = $cache->get($BF_ID)) {
                    $this->set($key, $file_id);
                }
            }
        }

        $res = $this->__send(function (Curl $curl) {
            $curl->setOptions([
                CURLOPT_SAFE_UPLOAD     => true,
                CURLOPT_HTTPHEADER      => [
                    'Content-Type: multipart/form-data'
                ]
            ]);
        });

        if ($res['ok'] && isset($res['result'])) {
            foreach ($properties as $key => $value) {
                if (
                    $value instanceof InputFile &&
                    isset($res['result'][$key])
                ) {
                    $path = $value->getFilename();
                    $BF_ID = $B_ID . md5_file($path);

                    $file = $res['result'][$key];
                    if (AH::isIndexed($file)) $file = end($file);
                    $file = new File($file);

                    if (!$cache->get($BF_ID)) {
                        $file_id = $file->getFileId();
                        $cache->set($BF_ID, $file_id, $duration);
                    }
                }
            }
        }

        $this->set('cache_time', $duration);
        return $res;
    }
}
