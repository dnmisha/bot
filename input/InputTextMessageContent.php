<?php namespace bot\input;

/**
 * Represents the content of a text message to be sent as the result
 * of an inline query.
 *
 * @method bool hasMessageText()
 * @method bool hasParseMode()
 * @method bool hasDisableWebPagePreview()
 * @method $this setMessageText($string)
 * @method $this setParseMode($string)
 * @method $this setDisableWebPagePreview($boolean)
 * @method $this remMessageText()
 * @method $this remParseMode()
 * @method $this remDisableWebPagePreview()
 * @method string getMessageText($default = null)
 * @method string getParseMode($default = null)
 * @method bool getDisableWebPagePreview($default = null)
 *
 * @author Mehdi Khodayari <mehdi.khodayari.khoram@gmail.com>
 * @since 3.0.1
 *
 * Class InputTextMessageContent
 * @package bot\input
 * @link https://core.telegram.org/bots/api#inputtextmessagecontent
 */
class InputTextMessageContent extends InputMessageContent
{
}