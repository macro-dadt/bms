<?php

/**
 * Notice
 *
 * @package    app.Model
 * @property NoticeCategory $NoticeCategory
 * @property User           $User
 * @property Place          $Place
 */
class Notice extends AppModel
{

    /**
     * Order
     *
     * @var array
     */
    public $order = array('Notice.created' => 'desc');

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
        'NoticeCategory',
        'User',
        'Place'
    );

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
     * アクション毎のバリデート
     *
     * @var array
     */
    protected $_validate = array(
        'add' => array(
            'notice_category_id' => array(
                'required'  => array(
                    'rule'     => 'notBlank',
                    'message'  => '未指定です',
                    'required' => true
                ),
                'inList' => array(
                    'rule'     => array('inList', array('1', '2', '3', '4', '5')),
                    'message'  => '未指定です',
                )
            ),
            'user_id' => array(
                'required'  => array(
                    'rule'     => 'notBlank',
                    'message'  => '未指定です',
                    'required' => true
                ),
            ),
            'place_id' => array(
                'required'  => array(
                    'rule'     => 'notBlank',
                    'message'  => '未指定です',
                    'required' => true
                ),
            ),
            'message' => array(
                'required'  => array(
                    'rule'     => 'notBlank',
                    'message'  => '未指定です',
                    'required' => true
                ),
                'maxLength' => array(
                    'rule'    => array('maxLength', 500),
                    'message' => '%s字以内で入力してください'
                ),
            ),
        )
    );

    /**
     * 誤り報告
     *
     * @param $userId
     * @param $data
     * @return mixed
     * @throws \Exception
     */
    public function add($userId, $data)
    {
        // 対象施設チェック
        if (isset($data[$this->alias]['place_id'])) {
            if (!$this->Place->findById($data[$this->alias]['place_id'])) {
                $this->invalidate('place_id', '施設が見つかりませんでした');
                return false;
            }
        }
        $data[$this->alias]['user_id'] = $userId;

        $fieldList = array(
            'notice_category_id',
            'user_id',
            'place_id',
            'message',
        );

        return $this->save($data, array('_validate' => 'add', 'fieldList' => $fieldList));
    }

}
