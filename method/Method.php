<?php namespace bot\method;

use bot\base\Request;
use bot\helper\Token;
use bot\object\Error;
use bot\object\Object;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use yii\helpers\ArrayHelper as AH;
use yii\base\UnknownClassException;

/**
 * Available methods
 * All methods in the Bot API are case-insensitive.
 * We support GET and POST HTTP methods. Use either URL query string
 * or application/json or application/x-www-form-urlencoded or
 * multipart/form-data for passing parameters in Bot API requests.
 * On successful call, a Bot-object containing
 * the result will be returned.
 *
 * @author Mehdi Khodayari <mehdi.khodayari.khoram@gmail.com>
 * @since 3.0.1
 *
 * Class Method
 * @package bot\method
 * @link https://core.telegram.org/bots/api#available-methods
 */
abstract class Method extends Request
{

    /**
     * Returns the fully qualified name of this method.
     * @return string
     */
    public static function methodName()
    {
        $className = self::className();
        $correctName = StringHelper::basename($className);
        return lcfirst($correctName);
    }

    /**
     * Request constructor.
     * @param string|Token $token the bot token
     * @param array $params properties of object request
     */
    public function __construct($token, $params = [])
    {
        $this->set('method', $this->methodName());
        parent::__construct($token, $params);
    }

    /**
     * Send this request by old token, you can use next token by
     * self::sendBy() method instead of this method.
     *
     * @return object of response
     */
    public function send()
    {
        $res = parent::send();

        // success
        if ($res['ok'] && isset($res['result'])) {
            $result = $res['result'];
            if (is_array($result)) {
                $className = $this->response();
                if (class_exists($className)) {
                    $value = $result;
                    return $this->__createResponse($className, $value);
                }
            }

            return $result;
        }

        // warning
        if (is_array($res)) {
            $id = $this->token->id;
            $error = new Error($res);
            $code = $error->getErrorCode();
            $description = $error->getDescription();

            $message = '[' . $id. '][' . $code . '] ' . $description;
            \Yii::warning(self::className() . ': ' . $message, 'bot');
            \Yii::warning($message, self::className());

            return $error;
        }

        // error
        return false;
    }

    /**
     * Finding responses, creating and
     * return them.
     *
     * @param string $className the relation class object
     * @param array $params all properties of object
     * @return array
     * @throws UnknownClassException
     */
    private function __createResponse($className, $params)
    {
        if (AH::isAssociative($params)) {
            $class = new $className($params);
            if ($class instanceof Object) {
                return $class;
            }

            $message = $className . ' isn\'t a response object.';
            throw new UnknownClassException($message);
        }

        if (AH::isIndexed($params)) {
            $output = [];
            foreach ($params as $name => $value) {
                $relation = $this->__createResponse($className, $value);
                $output[$name] = $relation;
            }

            return $output;
        }

        return $params;
    }

    /**
     * Every method have a response.
     * @return string the class's name.
     */
    abstract protected function response();
}