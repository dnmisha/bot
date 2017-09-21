<?php namespace bot\object;

/**
 * This object describes the position on faces
 * where a mask should be placed by default.
 *
 * @method string getPoint()
 * @method float getXShift()
 * @method float getYShift()
 * @method float getScale()
 *
 * @author Mehdi Khodayari <mehdi.khodayari.khoram@gmail.com>
 * @since 3.0.1
 *
 * Class MaskPosition
 * @package bot\object
 * @link https://core.telegram.org/bots/api#maskposition
 */
class MaskPosition extends Object
{

    /**
     * Every object have relations with other object,
     * in this method we introduce all object we have relations.
     *
     * @return array of objects
     */
    protected function relations()
    {
        return [];
    }
}