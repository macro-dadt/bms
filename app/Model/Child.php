<?php

/**
 * Child
 *
 * @package    app.Model
 * @property User $User
 */
class Child extends AppModel
{

    /**
     * Order
     *
     * @var array
     */
    public $order = array();

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
    public $belongsTo = array('User');

    /**
     * Behavior
     *
     * @var array
     */
    public $actsAs = array('Containable');

    /**
     * Validate
     *
     * @var array
     */
    public $validate = array();

    /**
     * アクション毎のバリデーション
     *
     * @var array
     */
    protected $_validate = array(
        'edit' => array(
            'birthday' => array(
                'required' => array(
                    'rule'     => 'notBlank',
                    'message'  => '未指定です',
                    'required' => true
                ),
                'date'     => array(
                    'rule'    => 'date',
                    'message' => '日付が正しくありません',
                ),
            ),
            'gendar'   => array(
                'required' => array(
                    'rule'     => 'notBlank',
                    'message'  => '未指定です',
                    'required' => true
                ),
            ),
        )
    );
}
