<?php

/**
 * UserCustomize
 *
 * @package    app.Model
 * @property User $User
 */
class UserCustomize extends AppModel
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
     * カスタマイズ情報の取得
     *
     * @param $userId
     * @return bool
     */
    public function view($userId)
    {
        if ($data = $this->findByUserId($userId)) {
            return $data;
        }

        return false;
    }

    /**
     * カスタマイズ情報の更新
     *
     * @param $userId
     * @param $data
     * @return mixed
     * @throws \Exception
     */
    public function edit($userId, $data)
    {
        $id = '';
        if ($tmp = $this->findByUserId($userId)) {
            $id = $tmp[$this->alias]['id'];
        }
        $data[$this->alias]['id'] = $id;
        $data[$this->alias]['user_id'] = $userId;

        return $this->save($data);
    }
}
