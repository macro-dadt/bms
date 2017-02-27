<?php

/**
 * PlaceAd
 *
 * @package    app.Model
 */
class PlaceAd extends AppModel
{

    /**
     * アソシエーション参照範囲
     *
     * @var int
     */
    public $recursive = -1;

    /**
     * BelongsTo
     *
     * @var array
     */
    public $belongsTo = array(
        'Place',
    );

}
