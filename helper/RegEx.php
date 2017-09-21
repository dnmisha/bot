<?php namespace bot\helper;

use yii\base\Object;
use yii\base\InvalidParamException;

/**
 * @property string $pattern
 * @property string $subject
 * @property array $params
 *
 * @author Mehdi Khodayari <mehdi.khodayari.khoram@gmail.com>
 * @since 3.0.1
 *
 * Class RegEx
 * @package bot\helper
 */
class RegEx extends Object
{

    /**
     * Variables to perform a regular expression search and replace.
     * Searches subject for matches to pattern and replaces them with replacement.
     */
    const SENSITIVE_WORDS = '\/!@#$%^&*()-=_+[]{}.,|';
    const PATTERN = '/(.)?\$\{([\w._-]+)(\?)?\,?\s?([^\}]+)?\}/';

    /**
     * @var string
     */
    private $_pattern;

    /**
     * @var string
     */
    private $_subject;

    /**
     * @var array
     */
    private $_params = [];

    /**
     * Compare two strings and get information and
     * params from strings.
     *
     * @param string $pattern
     * @param string $subject
     * @param array $params
     * @return bool
     */
    public static function compare($pattern, $subject, &$params = [])
    {
        $params = (array) $params;
        $regEx = new static($pattern);
        if ($regEx->checkout($subject)) {
            $params = $regEx->params + $params;
            return true;
        }

        return false;
    }

    /**
     * RegEx constructor.
     * @param string $pattern
     */
    public function __construct($pattern)
    {
        if (!is_string($pattern)) {
            $message = 'Invalid Param: $pattern must be string.';
            throw new InvalidParamException($message);
        }

        $before = str_split(self::SENSITIVE_WORDS);
        $after = preg_filter('/^/', '\\', $before);
        $pattern = str_replace($before, $after, $pattern);
        $this->_pattern = $this->__createRegExPattern($pattern);
        parent::__construct();
    }

    /**
     * checkout and get params from subject. if strings matches,
     * return true, otherwise return false.
     *
     * @param string $subject
     * @return bool
     */
    public function checkout($subject)
    {
        if (!is_string($subject)) {
            $message = 'Invalid Param: $subject must be string.';
            throw new InvalidParamException($message);
        }

        $this->_params = [];
        $this->_subject = $subject;
        $pattern = $this->_pattern;

        if (preg_match($pattern, $subject, $matches)) {
            if (sizeof($matches) > 0) {
                foreach ($matches as $key => $value) {
                    if (is_string($key) && !empty($value)) {
                        $this->_params[$key] = $value;
                    }
                }

                return true;
            }
        }
        
        return false;
    }

    /**
     * @return string
     */
    public function getPattern()
    {
        return $this->_pattern;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->_subject;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->_params;
    }

    /**
     * @param string $pattern
     * @return string
     */
    private function __createRegExPattern($pattern)
    {
        return '/^' . preg_replace_callback(

            // preg replace pattern
            self::PATTERN,

            // preg replace callback
            function ($match) {
                $after = '';
                $name = $match[2];
                $pattern = isset($match[4]) ? $match[4] : '[^\/\s]+';
                $optional = isset($match[3]) && $match[3] == '?' ? true : false;

                if ($optional && isset($match[1]))
                    $before = $match[1] == ' ' ? '\s?' : $match[1];
                else
                    $before = isset($match[1]) ? $match[1] : '';

                if ($optional) {
                    $after = ')?';
                    $before = '(?:' . $before;
                }

                $var = '(?P<' . $name . '>' . $pattern . ')';
                return $before . $var . $after;
            },

            // preg replace subject
            $pattern

        ) . '$/';
    }
}