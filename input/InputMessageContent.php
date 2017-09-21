<?php namespace bot\input;

use bot\base\Object;

/**
 * This object represents the content of a message to be sent as a result of an inline query.
 * Telegram clients currently support the following 4 types:
 * - InputTextMessageContent
 * - InputLocationMessageContent
 * - InputVenueMessageContent
 * - InputContactMessageContent
 *
 * @author Mehdi Khodayari <mehdi.khodayari.khoram@gmail.com>
 * @since 3.0.1
 *
 * Class InputMessageContent
 * @package bot\input
 * @link https://core.telegram.org/bots/api#inputmessagecontent
 */
abstract class InputMessageContent extends Object
{
}