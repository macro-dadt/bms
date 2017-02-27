<?php
class Notify extends AppModel
{

    /**
     * Order
     *
     * @var array
     */
    public $order = array('Notify.created DESC');

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
    public $belongsTo = array();

    /**
     * HasOne
     *
     * @var array
     */
    public $hasOne = array(
        'User'
    );

    /**
     * HasMany
     *
     * @var array
     */
    public $hasMany = array();

}
