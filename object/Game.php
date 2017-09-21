<?php namespace bot\object;

/**
 * This object represents a game. Use BotFather to create and edit games,
 * their short names will act as unique identifiers.
 *
 * @method bool hasText()
 * @method bool hasTextEntities()
 * @method bool hasAnimation()
 * @method string getTitle()
 * @method string getDescription()
 * @method PhotoSize[] getPhoto()
 * @method string getText($default = null)
 * @method MessageEntity[] getTextEntities($default = null)
 * @method Animation getAnimation($default = null)
 *
 * @author Mehdi Khodayari <mehdi.khodayari.khoram@gmail.com>
 * @since 3.0.1
 *
 * Class Game
 * @package bot\object
 * @link https://core.telegram.org/bots/api#game
 */
class Game extends Object
{

    /**
     * Every object have relations with other object,
     * in this method we introduce all object we have relations.
     *
     * @return array of objects
     */
    protected function relations()
    {
        return [
            'photo' => PhotoSize::className(),
            'text_entities' => MessageEntity::className(),
            'animation' => Animation::className()
        ];
    }
}