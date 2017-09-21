<?php namespace bot\base;

use Bot;
use yii\base\InvalidParamException;

/**
 * I18N provides features related with internationalization
 * (I18N) and localization (L10N).
 *
 * @author Mehdi Khodayari <mehdi.khodayari.khoram@gmail.com>
 * @since 3.0.1
 *
 * Class Translator
 * @package bot\base
 */
trait Translator
{

    /**
     * @var string
     */
    public static $language;

    /**
     * @param string $message
     * @param array $params
     * @return string
     */
    public static function t($message, $params = [])
    {
        $language = self::$language;
        $path = Bot::$path . '/messages/' . $language;

        // read message from txt file
        if (strpos($message, ' ') === false) {
            $message_path = $path . '/' . $message . '.txt';

            if (file_exists($message_path)) {
                $p = [];
                foreach ((array) $params as $name => $value) {
                    $p['{' . $name . '}'] = $value;
                }

                $new_message = file_get_contents($path);
                return ($p === []) ? $new_message : strtr($new_message, $p);
            }
        }

        return \Yii::t('bot', $message, $params, $language);
    }

    /**
     * @param string $language
     */
    public static function setLanguage($language)
    {
        $trans = \Yii::$app->i18n->translations;
        $path = Bot::$path . '/messages/' . $language;

        if (file_exists($path . '/bot.php')) {
            $trans['bot'] = [
                'basePath' => dirname($path),
                'sourceLanguage' => 'en_BOT',
                'class' => 'yii\i18n\PhpMessageSource'
            ];

            self::$language = $language;
            \Yii::$app->language = $language;
            $trans['bot']['fileMap']['bot'] = 'bot.php';
            \Yii::$app->i18n->translations = $trans;
        }
        else {
            $message = 'Not found language file:  ' . $path . '/bot.php';
            throw new InvalidParamException($message);
        }
    }
}