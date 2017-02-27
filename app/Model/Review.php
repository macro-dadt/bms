<?php

/**
 * Review
 *
 * @package    app.Model
 * @property User        $User
 * @property Place       $Place
 * @property ReviewImage $ReviewImage
 * @property ReviewGood  $ReviewGood
 */
class Review extends AppModel
{

    /**
     * Order
     *
     * @var array
     */
    public $order = array('Review.created' => 'desc');
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
        'User',
        'Place'
    );

    /**
     * HasMany
     *
     * @var array
     */
    public $hasMany = array(
        'ReviewImage',
        'ReviewGood'
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
        'place_add'  => array(
            'user_id' => array(
                'required' => array(
                    'rule'     => 'notBlank',
                    'message'  => '未指定です',
                    'required' => true
                ),
            ),
            'star'    => array(
                'required' => array(
                    'rule'     => 'notBlank',
                    'message'  => '未指定です',
                    'required' => true
                ),
                'star'     => array(
                    'rule'    => array('inList', array('1', '2', '3', '4', '5')),
                    'message' => '1～5から選択してください'
                ),
            ),
            'message' => array(
                'required' => array(
                    'rule'     => 'notBlank',
                    'message'  => '未指定です',
                    'required' => true
                ),
            ),
        ),
        'place_edit' => array(
            'user_id' => array(
                'required' => array(
                    'rule'     => 'notBlank',
                    'message'  => '未指定です',
                    'required' => true
                ),
            ),
            'star'    => array(
                'required' => array(
                    'rule'     => 'notBlank',
                    'message'  => '未指定です',
                    'required' => true
                ),
                'star'     => array(
                    'rule'    => array('inList', array('1', '2', '3', '4', '5')),
                    'message' => '1～5から選択してください'
                ),
            ),
            'message' => array(
                'required' => array(
                    'rule'     => 'notBlank',
                    'message'  => '未指定です',
                    'required' => true
                ),
            ),
        ),
        'add'        => array(
            'place_id' => array(
                'required' => array(
                    'rule'     => 'notBlank',
                    'message'  => '未指定です',
                    'required' => true
                ),
            ),
            'user_id'  => array(
                'required' => array(
                    'rule'     => 'notBlank',
                    'message'  => '未指定です',
                    'required' => true
                ),
            ),
            'star'     => array(
                'required' => array(
                    'rule'     => 'notBlank',
                    'message'  => '未指定です',
                    'required' => true
                ),
                'star'     => array(
                    'rule'    => array('inList', array('1', '2', '3', '4', '5')),
                    'message' => '1～5から選択してください'
                ),
            ),
            'message'  => array(
                'required' => array(
                    'rule'     => 'notBlank',
                    'message'  => '未指定です',
                    'required' => true
                ),
            ),
        ),
        'edit'       => array(
            'star'    => array(
                'required' => array(
                    'rule'     => 'notBlank',
                    'message'  => '未指定です',
                    'required' => true
                ),
                'star'     => array(
                    'rule'    => array('inList', array('1', '2', '3', '4', '5')),
                    'message' => '1～5から選択してください'
                ),
            ),
            'message' => array(
                'required' => array(
                    'rule'     => 'notBlank',
                    'message'  => '未指定です',
                    'required' => true
                ),
            ),
        )
    );

    /**
     * レビュー投稿
     *
     * @param $userId
     * @param $data
     * @return bool
     */
    public function add($userId, $data)
    {
        if (!isset($data[$this->alias]['place_id']) || $this->Place->findById($data[$this->alias]['place_id'])) {
            $this->invalidate('place_id', '施設が指定されていません');
        }
        $data[$this->alias]['user_id'] = $userId;
        if ($this->saveAll($data, array('_validate' => 'add'))) {
            // 平均スター更新
            $this->Place->starAverageCalc($data[$this->alias]['place_id']);
            // レビュー数更新
            $this->Place->reviewCountCalc($data[$this->alias]['place_id']);

            #todo ポイント追加
            return true;
        }

        return false;
    }

    /**
     * レビュー変更
     *
     * @param $userId
     * @param $id
     * @param $data
     * @return bool
     */
    public function edit($userId, $id, $data)
    {
        if (!$edit = $this->editData($userId, $id)) {
            $this->invalidate('id', '変更先が見つかりませんでした');

            return false;
        }

        // 画像削除リクエスト
        $deleteImages = array();
        if (isset($data[$this->alias]['delete_images']) && is_array($data[$this->alias]['delete_images'])) {
            $tmp = $data[$this->alias]['delete_images'];
            unset($data[$this->alias]['delete_images']);
            foreach ($tmp as $imageId) {
                // 削除すべき画像が無ければエラー
                if (!$imageId || !$this->ReviewImage->hasAny(array('ReviewImage.id' => $imageId, 'ReviewImage.review_id' => $id))) {
                    $this->invalidate('delete_images', '削除画像が見つかりませんでした');

                    return false;
                } else {
                    $deleteImages[] = $imageId;
                }
            }
        }
        $data[$this->alias]['id'] = $id;
        $data[$this->alias]['user_id'] = $userId;
        // バリデート
        if ($this->saveAll($data, array('validate' => 'only', '_validate' => 'edit'))) {
            // 更新
            $this->saveAll($data, array('_validate' => 'edit'));
            if ($deleteImages) {
                // 画像削除
                $this->ReviewImage->deleteAll(array('ReviewImage.id' => $deleteImages), true, true);
            }
            // 平均スター更新
            $this->Place->starAverageCalc($edit[$this->alias]['place_id']);

            return true;
        }

        return false;
    }

    /**
     * レビューの変更元データ取得
     *
     * @param $userId
     * @param $id
     * @return array|null
     */
    public function editData($userId, $id)
    {
        $data = $this->find('first', array(
            'fields'     => array(
                $this->alias . '.id',
                $this->alias . '.place_id',
                $this->alias . '.star',
                $this->alias . '.message',
                $this->alias . '.is_parent',
                $this->alias . '.del_flg'
            ),
            'conditions' => array(
                $this->alias . '.id'      => $id,
                $this->alias . '.user_id' => $userId,
            ),
            'contain'    => array(
                'ReviewImage' => array(
                    'fields' => array(
                        'ReviewImage.id',
                        'ReviewImage.path',
                        'ReviewImage.url',
                        'ReviewImage.del_flg',
                    )
                )
            )
        ));

        return $data;
    }

    /**
     * レビューの一覧を取得
     *
     * @param $userId
     * @param $id
     * @return array|null
     */
    public function place($placeId)
    {
//        pr($placeId);exit(0);
        $data = $this->find('all', array(
            'fields' => array(
                $this->alias . '.id',
                $this->alias . '.place_id',
                $this->alias . '.user_id',
                $this->alias . '.del_flg'
            ),
            'conditions' => array(
                $this->alias . '.place_id ' => $placeId,
                $this->alias . '.del_flg' => 0
            ),
            'contain' => array(
                'Place' => array(
                    'fields' => array(
                        'Place.name'
                    )
                )
            )
        ));

        return $data;
    }


    /**
     * レビュー詳細
     *
     * @param  [type] $userId [description]
     * @param  [type] $id     [description]
     * @return [type]         [description]
     */
    public function view($id)
    {
        $data = $this->find('first', array(
            'fields' => array(
                $this->alias . '.id',
                $this->alias . '.place_id',
                $this->alias . '.user_id',
                $this->alias . '.star',
                $this->alias . '.message',
                $this->alias . '.is_parent',
                $this->alias . '.del_flg'
            ),
            'conditions' => array(
                $this->alias . '.id' => $id,
                $this->alias . '.del_flg' => 0
            ),
            'contain' => array(
                'Place' => array(
                    'fields' => array(
                        'Place.name'
                    )
                )
            )
        ));

        return $data;
    }

    /**
     * 自分の投稿したレビューの一覧を取得
     *
     * @param $userId
     * @param $id
     * @return array|null
     */
    public function places($userId)
    {
        $data = $this->find('all', array(
            'fields' => array(
                $this->alias . '.id',
                $this->alias . '.place_id',
                $this->alias . '.user_id',
                $this->alias . '.del_flg'
            ),
            'conditions' => array(
                $this->alias . '.user_id ' => $userId
            ),
            'contain' => array(
                'Place' => array(
                    'fields' => array(
                        'Place.name'
                    )
                )
            ),
            'limit' => 20
        ));

        return $data;
    }

    /**
     * 自分の投稿した施設＋みんなのレビュー一覧を取得
     *
     * @param $userId
     * @param $id
     * @return array|null
     */
    public function myPlace($userId, $page = 1)
    {
        $data = $this->find('all', array(
            'fields' => array(
                $this->alias . '.id',
                $this->alias . '.place_id',
                $this->alias . '.user_id',
                $this->alias . '.del_flg'
            ),
            'contain' => array(
                'Place' => array(
                    'fields' => array(
                        'Place.name'
                    ),
                    'conditions' => array(
                        'Place.user_id' => $userId
                    )
                )
            ),
            'limit' => 20,
            'page' => $page
        ));

        return $data;
    }

    /**
     * 自分の投稿したレビュー＋みんなの施設一覧を取得
     *
     * @param $userId
     * @param $id
     * @return array|null
     */
    public function myReview($userId, $page = 1)
    {
        $data = $this->find('all', array(
            'fields' => array(
                $this->alias . '.id',
                $this->alias . '.place_id',
                $this->alias . '.user_id',
                $this->alias . '.del_flg'
            ),
            'conditions' => array(
                $this->alias . '.user_id' => $userId
            ),
            'contain' => array(
                'Place' => array(
                    'fields' => array(
                        'Place.name'
                    )
                )
            ),
            'limit' => 20,
            'page' => $page
        ));

        return $data;
    }

    /**
     * レビューの削除フラグを変更する
     *
     * @param  [type] $reviewId [description]
     * @param  [type] $flag    [description]
     * @return [type]          [description]
     */
    public function setDelete($reviewId, $delFlg) {

        $data = array(
            $this->alias => array(
                'id' => $reviewId,
                'del_flg' => $delFlg
            )
        );
        return $this->save($data, array(
            'validate' => false
        ));
    }
}
