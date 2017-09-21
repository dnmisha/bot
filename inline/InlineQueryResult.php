<?php namespace bot\inline;

use bot\base\Object;
use yii\helpers\StringHelper;

/**
 * This object represents one result of an inline query.
 * Telegram clients currently support results of the following 20 types:
 * - InlineQueryResultCachedAudio
 * - InlineQueryResultCachedDocument
 * - InlineQueryResultCachedGif
 * - InlineQueryResultCachedMpeg4Gif
 * - InlineQueryResultCachedPhoto
 * - InlineQueryResultCachedSticker
 * - InlineQueryResultCachedVideo
 * - InlineQueryResultCachedVoice
 * - InlineQueryResultArticle
 * - InlineQueryResultAudio
 * - InlineQueryResultContact
 * - InlineQueryResultGame
 * - InlineQueryResultDocument
 * - InlineQueryResultGif
 * - InlineQueryResultLocation
 * - InlineQueryResultMpeg4Gif
 * - InlineQueryResultPhoto
 * - InlineQueryResultVenue
 * - InlineQueryResultVideo
 * - InlineQueryResultVoice
 *
 * @method bool hasId()
 * @method $this setId($string)
 * @method $this remId()
 * @method string getId($default = null)
 *
 * @author Mehdi Khodayari <mehdi.khodayari.khoram@gmail.com>
 * @since 3.0.1
 *
 * Class InlineQueryResult
 * @package bot\inline
 * @link https://core.telegram.org/bots/api#inlinequeryresult
 */
abstract class InlineQueryResult extends Object
{

    /**
     * There is type for every InlineQueryResult,
     * and the type's name exists in the end of
     * object's name.
     *
     * @return string
     */
    public static function typeName()
    {
        $className = self::className();
        $baseName = StringHelper::basename($className);

        $mainName = 'InlineQueryResult';
        $replaces = [$mainName, $mainName . 'Cached'];
        $correctType = str_replace($replaces, '', $baseName);
        return strtolower($correctType);
    }

    /**
     * InlineQueryResult constructor.
     * @param array|string $properties
     */
    public function __construct($properties = [])
    {
        if (is_string($properties)) {
            $this->__set('id', $properties);
            $properties = [];
        }

        $this->__set('type', $this->typeName());
        parent::__construct($properties);
    }

    /**
     * Can add this result to other results.
     * @param array $results
     */
    public function addTo(&$results = [])
    {
        $results[] = $this;
    }
}
