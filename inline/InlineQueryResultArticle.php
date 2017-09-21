<?php namespace bot\inline;

use bot\input\InputMessageContent;
use bot\keyboard\InlineKeyboardMarkup;

/**
 * Represents a link to an article or web page.
 *
 * @method bool hasTitle()
 * @method bool hasInputMessageContent()
 * @method bool hasReplyMarkup()
 * @method bool hasUrl()
 * @method bool hasHideUrl()
 * @method bool hasDescription()
 * @method bool hasThumbUrl()
 * @method bool hasThumbWidth()
 * @method bool hasThumbHeight()
 * @method $this setTitle($string)
 * @method $this setInputMessageContent(InputMessageContent $input)
 * @method $this setReplyMarkup(InlineKeyboardMarkup $markup)
 * @method $this setUrl($string)
 * @method $this setHideUrl($boolean)
 * @method $this setDescription($string)
 * @method $this setThumbUrl($string)
 * @method $this setThumbWidth($integer)
 * @method $this setThumbHeight($integer)
 * @method $this remTitle()
 * @method $this remInputMessageContent()
 * @method $this remReplyMarkup()
 * @method $this remUrl()
 * @method $this remHideUrl()
 * @method $this remDescription()
 * @method $this remThumbUrl()
 * @method $this remThumbWidth()
 * @method $this remThumbHeight()
 * @method string getTitle($default = null)
 * @method InputMessageContent getInputMessageContent($default = null)
 * @method InlineKeyboardMarkup getReplyMarkup($default = null)
 * @method string getUrl($default = null)
 * @method bool getHideUrl($default = null)
 * @method string getDescription($default = null)
 * @method string getThumbUrl($default = null)
 * @method int getThumbWidth($default = null)
 * @method int getThumbHeight($default = null)
 *
 * @author Mehdi Khodayari <mehdi.khodayari.khoram@gmail.com>
 * @since 3.0.1
 *
 * Class InlineQueryResultArticle
 * @package bot\inline
 * @link https://core.telegram.org/bots/api#inlinequeryresultarticle
 */
class InlineQueryResultArticle extends InlineQueryResult
{
}