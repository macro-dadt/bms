<?php

/**
 * Place
 *
 * @package    app.Model
 * @property User          $User
 * @property PlaceCategory $PlaceCategory
 * @property Review        $Review
 * @property PlaceQuestion $PlaceQuestion
 * @property Notice        $Notice
 * @property PlaceHistory  $PlaceHistory
 * @property FavoritePlace $FavoritePlace
 */
class Place extends AppModel
{

    /**
     * Order
     *
     * @var array
     */
    public $order = array('Place.latlon');

    /**
     * Virtual Fields
     *
     * @var array
     */
    public $virtualFields = array(
        'lon' => 'X(latlon)',
        'lat' => 'Y(latlon)'
    );
    /**
     * アソシエーション参照範囲
     *
     * @var int
     */
    public $recursive = 0;

    /**
     * BelongsTo
     *
     * @var array
     */
    public $belongsTo = array(
        'User',
        'PlaceCategory',
    );

    /**
     * HasMany
     *
     * @var array
     */
    public $hasMany = array(
        'Review',
        'PlaceQuestion',
        'PlaceHistory',
        'PlaceAd',
        'FavoritePlace',
        'Notice',
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
            'name'              => array(
                'required'  => array(
                    'rule'     => 'notBlank',
                    'message'  => '未指定です',
                    'required' => true
                ),
                'maxLength' => array(
                    'rule'    => array('maxLength', 255),
                    'message' => '%s字以内で入力してください'
                ),
            ),
            'place_category_id' => array(
                'required' => array(
                    'rule'     => 'notBlank',
                    'message'  => '未指定です',
                    'required' => true
                ),
            ),
            'lat'               => array(
                'required' => array(
                    'rule'     => 'notBlank',
                    'message'  => '未指定です',
                    'required' => true
                ),
                'lat'      => array(
                    'rule'    => array('custom', '/^-?([0-8]?[0-9]|90)\.[0-9]{1,6}$/'),
                    'message' => '書式が正しくありません',
                ),
            ),
            'lon'               => array(
                'required' => array(
                    'rule'     => 'notBlank',
                    'message'  => '未指定です',
                    'required' => true
                ),
                'lon'      => array(
                    'rule'    => array('custom', '/^-?((1?[0-7]?|[0-9]?)[0-9]|180)\.[0-9]{1,6}$/'),
                    'message' => '書式が正しくありません',
                ),
            ),
            'nappy_seat'        => array(
                'int' => array(
                    'rule'       => array('custom', '/^\d+$/'),
                    'message'    => '数字のみで入力してください',
                    'allowEmpty' => true
                ),
            ),
            'milk_seat'         => array(
                'int' => array(
                    'rule'       => array('custom', '/^\d+$/'),
                    'message'    => '数字のみで入力してください',
                    'allowEmpty' => true
                ),
            ),
            'toilet_seat'       => array(
                'int' => array(
                    'rule'       => array('custom', '/^\d+$/'),
                    'message'    => '数字のみで入力してください',
                    'allowEmpty' => true
                ),
            ),
        ),
        'place_edit' => array(
            'name'              => array(
                'required'  => array(
                    'rule'     => 'notBlank',
                    'message'  => '未指定です',
                    'required' => true
                ),
                'maxLength' => array(
                    'rule'    => array('maxLength', 255),
                    'message' => '%s字以内で入力してください'
                ),
            ),
            'place_category_id' => array(
                'required' => array(
                    'rule'     => 'notBlank',
                    'message'  => '未指定です',
                    'required' => true
                ),
                'inList'   => array(
                    'rule'    => array('inList', array('1', '2', '3', '4', '5', '6', '7')),
                    'message' => '未指定です',
                )
            ),
            'nappy_seat'        => array(
                'int' => array(
                    'rule'       => array('custom', '/^\d+$/'),
                    'message'    => '数字のみで入力してください',
                    'allowEmpty' => true
                ),
            ),
            'milk_seat'         => array(
                'int' => array(
                    'rule'       => array('custom', '/^\d+$/'),
                    'message'    => '数字のみで入力してください',
                    'allowEmpty' => true
                ),
            ),
            'toilet_seat'       => array(
                'int' => array(
                    'rule'       => array('custom', '/^\d+$/'),
                    'message'    => '数字のみで入力してください',
                    'allowEmpty' => true
                ),
            ),
        )
    );

    /**
     * Before Validate
     *
     * @param array $options
     * @return bool
     */
    public function beforeValidate($options = array())
    {
        // 追加時
        if (isset($options['_validate']) && in_array($options['_validate'], array('place_add', 'place_edit'))) {
            // おむつ、ミルク、トイレの席数はいずれかが必須
            if (empty($this->data[$this->alias]['nappy_seat']) &&
                empty($this->data[$this->alias]['milk_seat']) &&
                empty($this->data[$this->alias]['toilet_seat'])
            ) {
                $this->invalidate('nappy_seat', '入力されていません');
                $this->invalidate('milk_seat', '入力されていません');
                $this->invalidate('toilet_seat', '入力されていません');
            }
        }

        return parent::beforeValidate($options);
    }

    /**
     * Before Save
     *
     * @param array $options
     * @return bool
     */
    public function beforeSave($options = array())
    {
        // 緯度経度をgeometry型で保存
        if (isset($this->data[$this->alias]['lat']) && isset($this->data[$this->alias]['lon'])) {
            $db = ConnectionManager::getDataSource($this->useDbConfig);
            $this->data[$this->alias]['latlon'] = $db->expression("GeomFromText('POINT(" . $this->data[$this->alias]['lon'] . " " . $this->data[$this->alias]['lat'] . ")')");
        }

        return true;
    }

    /**
     * 周辺施設の検索
     *
     * @param $lat
     * @param $lon
     * @return array|bool|null
     */
    public function search($lat, $lon)
    {
        // 緯度・経度のバリデート
        if (!preg_match('/^-?([0-8]?[0-9]|90)\.[0-9]{1,6}$/', $lat)) {
            return false;
        }
        if (!preg_match('/^-?((1?[0-7]?|[0-9]?)[0-9]|180)\.[0-9]{1,6}$/', $lon)) {
            return false;
        }
        // 検索範囲
        $range = 0.05;
        $result = $this->find('all', array(
            'fields'     => array(
                $this->alias . '.id',
                $this->alias . '.name',
                $this->alias . '.floor',
                $this->alias . '.address',
                $this->alias . '.tel',
                $this->alias . '.url',
                $this->alias . '.usable_week_day',
                $this->alias . '.usable_time',
                $this->alias . '.lat',
                $this->alias . '.lon',
                $this->alias . '.place_category_id',
                $this->alias . '.star',
                $this->alias . '.review_count',
                $this->alias . '.milk_seat',
                $this->alias . '.milk_baby_car',
                $this->alias . '.milk_hot_water',
                $this->alias . '.milk_papa',
                $this->alias . '.milk_private_room',
                $this->alias . '.nappy_seat',
                $this->alias . '.nappy_dust_box',
                $this->alias . '.nappy_dust_bag',
                $this->alias . '.nappy_papa',
                $this->alias . '.toilet_seat',
                $this->alias . '.toilet_boy',
                $this->alias . '.toilet_girl',
                $this->alias . '.cond_child_chair',
                $this->alias . '.cond_baby_chair',
                $this->alias . '.cond_baby_car',
                $this->alias . '.cond_no_smoke',
                $this->alias . '.cond_store',
                $this->alias . '.cond_parking',
                $this->alias . '.cond_tatami',
                $this->alias . '.cond_indoor',
                $this->alias . '.cond_outdoor',
                $this->alias . '.cond_one_year_old_over',
                $this->alias . '.cond_one_year_old_under',
                $this->alias . '.cond_day_care',
                $this->alias . '.cond_kids_space',
                $this->alias . '.is_official',
                $this->alias . '.is_closed',
                $this->alias . '.created',
                $this->alias . '.modified',
                $this->alias . '.is_busy',

            ),
            'conditions' => array(
                // 矩形検索
                "MBRContains(GeomFromText(Concat('LineString(',
                  {$lon} + {$range} , ' ',
                  {$lat} + {$range} , ',',
                  {$lon} - {$range} , ' ',
                  {$lat} - {$range} , ')'
                )),`Place`.latlon)",
                $this->alias . '.is_closed' => 0,
                'or' => array(
                    // 公開日時
                    array(
                        $this->alias . '.opened IS NULL',
                        $this->alias . '.closed IS NULL'
                    ),
                    array(
                        $this->alias . '.opened <=' => date('Y-m-d H:i:s'),
                        $this->alias . '.closed >=' => date('Y-m-d H:i:s'),
                    ),
                    // 公開開始日、終了日がなくてもヒットさせる
                    array(
                        $this->alias . '.opened = "0000-00-00 00:00:00"',
                        $this->alias . '.closed = "0000-00-00 00:00:00"'
                    )
                )
            ),
            'order'      => array(
                "Glength(GeomFromText(Concat('LineString(',
                    {$lon}  , ' ',
                    {$lat}  , ',',
                    X(`latlon`)  , ' ',
                    Y(`latlon`)  , ')'
                )))"
            ),
            'contain'    => array(
                'Review' => array('ReviewImage')
            )
        ));
        foreach ($result as &$t) {
            $image = Hash::extract($t, 'Review.{n}.ReviewImage.0.url');
            if ($image) {
                $t['Place']['image_url'] = $image[0];
            } else {
                $t['Place']['image_url'] = '';
            }
            unset($t['Review']);
        }

        return $result;
    }

    /**
     * 施設新規投稿
     *
     * @param $userId
     * @param $data
     * @return mixed
     * @throws \Exception
     */
    public function add($userId, $data)
    {
        $fieldList = array(
            $this->alias => array(
                'user_id',
                'place_category_id',
                'name',
                'milk_seat',
                'milk_baby_car',
                'milk_papa',
                'milk_hot_water',
                'milk_private_room',
                'nappy_seat',
                'nappy_dust_box',
                'nappy_dust_bag',
                'nappy_papa',
                'toilet_seat',
                'toilet_boy',
                'toilet_girl',
                'cond_child_chair',
                'cond_baby_chair',
                'cond_baby_car',
                'cond_no_smoke',
                'cond_store',
                'cond_parking',
                'cond_tatami',
                'cond_indoor',
                'cond_outdoor',
                'cond_one_year_old_over',
                'cond_one_year_old_under',
                'cond_day_care',
                'cond_kids_space',
                'floor',
                'latlon',
                'lat',
                'lon',
            ),
        );
        if (!isset($data['Review'])) {
            $data['Review'] = array();
        }
        if (!isset($data['ReviewImage'])) {
            $data['ReviewImage'] = array();
        }

        $data[$this->alias]['user_id'] = $userId;
        $data['Review']['user_id'] = $userId;
        $this->set($data[$this->alias]);
        // トランザクション不可なのでぞれぞれバリデートを行う
        if ($this->validates(array('_validate' => 'place_add', 'fieldList' => $fieldList))) {
            $review = array(
                'Review'      => $data['Review'],
                'ReviewImage' => $data['ReviewImage'],
            );
            if ($this->Review->saveAll($review, array('validate' => 'only', '_validate' => 'place_add'))) {
                // 全てのバリデーションが完了
                // まずは施設を保存
                $res = $this->save();
                // レビューと画像を保存
                $review['Review']['place_id'] = $res[$this->alias]['id'];
                $this->Review->saveAll($review, array('_validate' => 'place_add'));
                // 平均スター更新
                $this->starAverageCalc($res[$this->alias]['id']);
                // レビュー数更新
                $this->reviewCountCalc($res[$this->alias]['id']);

                #todo ポイント追加
                return true;
            }
        }

        return false;
    }

    //add nursing room just for admin
    public function add_nursing($data)
    {
        $fieldList = array(
            $this->alias => array(
                'user_id',
                'place_category_id',
                'name',
                'milk_seat',
                'milk_baby_car',
                'milk_papa',
                'milk_hot_water',
                'milk_private_room',
                'nappy_seat',
                'nappy_dust_box',
                'nappy_dust_bag',
                'nappy_papa',
                'toilet_seat',
                'toilet_boy',
                'toilet_girl',
                'cond_child_chair',
                'cond_baby_chair',
                'cond_baby_car',
                'cond_no_smoke',
                'cond_store',
                'cond_parking',
                'cond_tatami',
                'cond_indoor',
                'cond_outdoor',
                'cond_one_year_old_over',
                'cond_one_year_old_under',
                'cond_day_care',
                'cond_kids_space',
                'floor',
                'latlon',
                'lat',
                'lon',
            ),
        );
        if (!isset($data['Review'])) {
            $data['Review'] = array();
        }

        if (!isset($data['ReviewImage'])) {
            $data['ReviewImage'] = array();
        }
        $data[$this->alias]['place_category_id'] = 6;
        $data[$this->alias]['milk_seat'] = 1;
        $data[$this->alias]['floor'] = 0;
        $data[$this->alias]['user_id'] = 0;
        $data['Review']['user_id'] = 0;
        $this->set($data[$this->alias]);
        // トランザクション不可なのでぞれぞれバリデートを行う
        if ($this->validates(array('_validate' => 'place_add', 'fieldList' => $fieldList))) {
            $review = array(
                'Review'      => $data['Review'],
                'ReviewImage' => $data['ReviewImage'],
            );
            if ($this->Review->saveAll($review, array('validate' => 'only', '_validate' => 'place_add'))) {
                // 全てのバリデーションが完了
                // まずは施設を保存
                $res = $this->save();
                // レビューと画像を保存
                $review['Review']['place_id'] = $res[$this->alias]['id'];
                $this->Review->saveAll($review, array('_validate' => 'place_add'));
                // 平均スター更新
                $this->starAverageCalc($res[$this->alias]['id']);
                // レビュー数更新
                $this->reviewCountCalc($res[$this->alias]['id']);

                #todo ポイント追加
                return true;
            }
        }

        return false;
    }

    /**
     * @return array
     */
    public function getLastInsertedId()
    {
        return $this->getLastInsertId();
    }
    /**
     * 施設変更
     *
     * @param $userId
     * @param $id
     * @param $data
     * @return bool
     * @throws \Exception
     */
    public function edit($userId, $id, $data)
    {
        if (!$edit = $this->editData($userId, $id)) {
            $this->invalidate('id', '変更先が見つかりませんでした');

            return false;
        }

        $fieldList = array(
            $this->alias => array(
                'user_id',
                'place_category_id',
                'name',
                'milk_seat',
                'milk_baby_car',
                'milk_papa',
                'milk_hot_water',
                'milk_private_room',
                'nappy_seat',
                'nappy_dust_box',
                'nappy_dust_bag',
                'nappy_papa',
                'toilet_seat',
                'toilet_boy',
                'toilet_girl',
                'cond_child_chair',
                'cond_baby_chair',
                'cond_baby_car',
                'cond_no_smoke',
                'cond_store',
                'cond_parking',
                'cond_tatami',
                'cond_indoor',
                'cond_outdoor',
                'cond_one_year_old_over',
                'cond_one_year_old_under',
                'cond_day_care',
                'cond_kids_space',
                'floor',
            ),
        );
        if (!isset($data['Review'])) {
            $data['Review'] = array();
        }
        if (!isset($data['ReviewImage'])) {
            $data['ReviewImage'] = array();
        }

        $data[$this->alias]['id'] = $id;
        $data[$this->alias]['user_id'] = $userId;
        $data['Review']['id'] = $edit['Review']['id'];
        $data['Review']['place_id'] = $id;
        $data['Review']['user_id'] = $userId;

        // 画像削除リクエスト
        $deleteImages = array();
        if (isset($data[$this->alias]['delete_images']) && is_array($data[$this->alias]['delete_images'])) {
            $tmp = $data[$this->alias]['delete_images'];
            unset($data[$this->alias]['delete_images']);
            foreach ($tmp as $imageId) {
                // 削除すべき画像が無ければエラー
                if (!$imageId || !$this->Review->ReviewImage->hasAny(array('ReviewImage.id' => $imageId, 'ReviewImage.review_id' => $edit['Review']['id']))) {
                    $this->invalidate('delete_images', '削除画像が見つかりませんでした');

                    return false;
                } else {
                    $deleteImages[] = $imageId;
                }
            }
        }

        $this->set($data[$this->alias]);
        // トランザクション不可なのでぞれぞれバリデートを行う
        if ($this->validates(array('_validate' => 'place_edit', 'fieldList' => $fieldList))) {
            $review = array(
                'Review'      => $data['Review'],
                'ReviewImage' => $data['ReviewImage'],
            );
            if ($this->Review->saveAll($review, array('validate' => 'only', '_validate' => 'place_edit'))) {
                // 全てのバリデーションが完了
                // まずは施設を保存
                $res = $this->save();
                // レビューと画像を保存
                $this->Review->saveAll($review, array('_validate' => 'place_edit'));
                // 平均スター更新
                $this->starAverageCalc($res[$this->alias]['id']);
                // レビュー数更新
                $this->reviewCountCalc($res[$this->alias]['id']);
                if ($deleteImages) {
                    // 画像削除
                    $this->Review->ReviewImage->deleteAll(array('ReviewImage.id' => $deleteImages), true, true);
                }

                #todo ポイント追加
                return true;
            }
        }

        return false;
    }

    /**
     * スター更新
     */
    public function starAverageCalc($id)
    {
        if (!$data = $this->findById($id)) {
            return false;
        }
        // 平均を取得
        $avg = $this->Review->find('first', array(
            'fields'     => array('AVG(Review.star) as star_avg'),
            'conditions' => array(
                'Review.place_id' => $id
            )
        ));

        // スター更新
        $this->save(array(
            'id'   => $id,
            'star' => round($avg[0]['star_avg'], 1)
        ), array('validate' => false));

        return true;
    }

    /**
     * レビュー数更新
     */
    public function reviewCountCalc($id)
    {
        if (!$data = $this->findById($id)) {
            return false;
        }
        // 平均を取得
        $avg = $this->Review->find('first', array(
            'fields'     => array('COUNT(Review.id) as review_count'),
            'conditions' => array(
                'Review.place_id' => $id
            )
        ));

        // スター更新
        $this->save(array(
            'id'           => $id,
            'review_count' => $avg[0]['review_count'],
            1
        ), array('validate' => false));

        return true;
    }

    /**
     * 変更元データ
     *
     * @param $userId
     * @param $id
     * @return array|bool|null
     */
    public function editData($userId, $id)
    {
        $data = $this->find('first', array(
            'fields'     => array(
                $this->alias . '.id',
                $this->alias . '.name',
                $this->alias . '.floor',
                $this->alias . '.place_category_id',
                $this->alias . '.star',
                $this->alias . '.review_count',
                $this->alias . '.milk_seat',
                $this->alias . '.milk_baby_car',
                $this->alias . '.milk_papa',
                $this->alias . '.milk_hot_water',
                $this->alias . '.milk_private_room',
                $this->alias . '.nappy_seat',
                $this->alias . '.nappy_dust_box',
                $this->alias . '.nappy_dust_bag',
                $this->alias . '.nappy_papa',
                $this->alias . '.toilet_seat',
                $this->alias . '.toilet_boy',
                $this->alias . '.toilet_girl',
                $this->alias . '.cond_child_chair',
                $this->alias . '.cond_baby_chair',
                $this->alias . '.cond_baby_car',
                $this->alias . '.cond_no_smoke',
                $this->alias . '.cond_store',
                $this->alias . '.cond_parking',
                $this->alias . '.cond_tatami',
                $this->alias . '.cond_indoor',
                $this->alias . '.cond_outdoor',
                $this->alias . '.cond_one_year_old_over',
                $this->alias . '.cond_one_year_old_under',
                $this->alias . '.cond_day_care',
                $this->alias . '.cond_kids_space',
            ),
            'conditions' => array(
                $this->alias . '.id'      => $id,
                $this->alias . '.user_id' => $userId,
            ),
            'contain'    => array(
                'Review' => array(
                    'ReviewImage'
                )
            )
        ));
        if ($data) {
            $result = $data;

            $review = reset($data['Review']);
            $images = @$review['ReviewImage'];
            unset($review['ReviewImage']);

            $result['Review'] = array(
                'id'      => $review['id'],
                'star'    => $review['star'],
                'message' => $review['message'],
            );
            if ($images && is_array($images)) {
                foreach ($images as $image) {
                    $result['ReviewImage'][] = array(
                        'id'   => $image['id'],
                        'path' => $image['path'],
                        'url'  => $image['url'],
                        'del_flg'  => $image['del_flg'],
                    );
                }
            }

            return $result;
        }

        return false;
    }

    /**
     * 登録画像取得
     *
     * @param $id
     * @return array|bool
     */
    public function getImages($id)
    {
        $data = $this->find('first', array(
            'fields'     => array(
                $this->alias . '.id',
            ),
            'conditions' => array(
                $this->alias . '.id' => $id
            ),
            'contain'    => array(
                'Review' => array('ReviewImage')
            )
        ));
        if (!$data) {
            return false;
        }
        // 画像だけ抽出
        $images = Hash::extract($data, 'Review.{n}.ReviewImage.{n}');
        $result = array();
        foreach ($images as $image) {
            $result[] = array(
                'path' => $image['path'],
                'url'  => $image['url'],
                'del_flg'  => $image['del_flg']
            );
        }

        return $result;
    }

    /**
     * 施設詳細情報を取得
     *
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function view($id)
    {
        $limit = 3; // レビュー質問件数
        $result = $this->find('all', array(
            'fields' => array(
                $this->alias . '.id',
                $this->alias . '.name',
                $this->alias . '.floor',
                $this->alias . '.address',
                $this->alias . '.tel',
                $this->alias . '.url',
                $this->alias . '.usable_week_day',
                $this->alias . '.usable_time',
                $this->alias . '.lat',
                $this->alias . '.lon',
                $this->alias . '.place_category_id',
                $this->alias . '.star',
                $this->alias . '.review_count',
                $this->alias . '.milk_seat',
                $this->alias . '.milk_baby_car',
                $this->alias . '.milk_hot_water',
                $this->alias . '.milk_papa',
                $this->alias . '.milk_private_room',
                $this->alias . '.nappy_seat',
                $this->alias . '.nappy_dust_box',
                $this->alias . '.nappy_dust_bag',
                $this->alias . '.nappy_papa',
                $this->alias . '.toilet_seat',
                $this->alias . '.toilet_boy',
                $this->alias . '.toilet_girl',
                $this->alias . '.cond_child_chair',
                $this->alias . '.cond_baby_chair',
                $this->alias . '.cond_baby_car',
                $this->alias . '.cond_no_smoke',
                $this->alias . '.cond_store',
                $this->alias . '.cond_parking',
                $this->alias . '.cond_tatami',
                $this->alias . '.cond_indoor',
                $this->alias . '.cond_outdoor',
                $this->alias . '.cond_one_year_old_over',
                $this->alias . '.cond_one_year_old_under',
                $this->alias . '.cond_day_care',
                $this->alias . '.cond_kids_space',
                $this->alias . '.is_official',
                $this->alias . '.is_closed',
                $this->alias . '.created',
                $this->alias . '.modified',
                $this->alias . '.remarks',
                $this->alias . '.source_name'
            ),
            'conditions' => array(
                $this->alias . '.id' => $id,
                $this->alias . '.is_closed' => 0
            ),
            'contain' => array(
                'Review' => array(
                    'fields' => array(
                        'Review.id',
                        'Review.user_id',
                        'Review.star',
                        'Review.message',
                        'Review.created',
                        'Review.modified'
                    ),
                    'conditions' => array(
                        'Review.place_id' => $id
                    ),
                    'limit' => $limit,
                    'ReviewImage' => array(
                        'conditions' => array(
                            'ReviewImage.del_flg' => 0
                        )
                    )
                ),
                'PlaceQuestion' => array(
                    'fields' => array(
                        'PlaceQuestion.id',
                        'PlaceQuestion.user_id',
                        'PlaceQuestion.message',
                        'PlaceQuestion.created',
                        'PlaceQuestion.modified'
                    ),
                    'conditions' => array(
                        'PlaceQuestion.place_id' => $id
                    ),
                    'limit' => $limit
                ),
                'PlaceAd' => array(
                    'order' => array('PlaceAd.created DESC'),
                    'limit' => 1
                )
            )
        ));

        // 広告がなかったらデフォルトを設定
        if($result && empty($result[0]['PlaceAd'])) {

            // マスタを取得
            App::import('Model', 'Master');
            $this->Master = new Master;
            $master = $this->Master->get('ad');

            // 広告IDを取得
            App::import('Model', 'PlaceAd');
            $this->PlaceAd = new PlaceAd;
            $r = $this->PlaceAd->findById($master['adDefaultId']);

            $result[0]['PlaceAd'][0] = $r['PlaceAd'];
        }

        return $result;
    }


    /**
     * 施設詳細情報を取得
     *
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function viewFull($id)
    {
        $result = $this->find('all', array(
            'fields' => array(
                $this->alias . '.id',
                $this->alias . '.name',
                $this->alias . '.floor',
                $this->alias . '.address',
                $this->alias . '.tel',
                $this->alias . '.url',
                $this->alias . '.usable_week_day',
                $this->alias . '.usable_time',
                $this->alias . '.lat',
                $this->alias . '.lon',
                $this->alias . '.place_category_id',
                $this->alias . '.star',
                $this->alias . '.review_count',
                $this->alias . '.milk_seat',
                $this->alias . '.milk_baby_car',
                $this->alias . '.milk_hot_water',
                $this->alias . '.milk_papa',
                $this->alias . '.milk_private_room',
                $this->alias . '.nappy_seat',
                $this->alias . '.nappy_dust_box',
                $this->alias . '.nappy_dust_bag',
                $this->alias . '.nappy_papa',
                $this->alias . '.toilet_seat',
                $this->alias . '.toilet_boy',
                $this->alias . '.toilet_girl',
                $this->alias . '.cond_child_chair',
                $this->alias . '.cond_baby_chair',
                $this->alias . '.cond_baby_car',
                $this->alias . '.cond_no_smoke',
                $this->alias . '.cond_store',
                $this->alias . '.cond_parking',
                $this->alias . '.cond_tatami',
                $this->alias . '.cond_indoor',
                $this->alias . '.cond_outdoor',
                $this->alias . '.cond_one_year_old_over',
                $this->alias . '.cond_one_year_old_under',
                $this->alias . '.cond_day_care',
                $this->alias . '.cond_kids_space',
                $this->alias . '.is_official',
                $this->alias . '.is_closed',
                $this->alias . '.created',
                $this->alias . '.modified',
                $this->alias . '.remarks',
                $this->alias . '.source_name'
            ),
            'conditions' => array(
                $this->alias . '.id' => $id,
                $this->alias . '.is_closed' => 0
            ),
            'contain' => array(
                'Review' => array(
                    'fields' => array(
                        'Review.id',
                        'Review.user_id',
                        'Review.star',
                        'Review.message',
                        'Review.created',
                        'Review.modified'
                    ),
                    'conditions' => array(
                        'Review.place_id' => $id
                    ),
                    'ReviewImage'
                ),
                'PlaceQuestion' => array(
                    'fields' => array(
                        'PlaceQuestion.id',
                        'PlaceQuestion.user_id',
                        'PlaceQuestion.message',
                        'PlaceQuestion.created',
                        'PlaceQuestion.modified'
                    ),
                    'conditions' => array(
                        'PlaceQuestion.place_id' => $id
                    ),
                ),
                'PlaceAd' => array(
                    'order' => array('PlaceAd.created DESC'),
                    'limit' => 1
                )
            )
        ));

        // 広告がなかったらデフォルトを設定
        if($result && empty($result[0]['PlaceAd'])) {

            // マスタを取得
            App::import('Model', 'Master');
            $this->Master = new Master;
            $master = $this->Master->get('ad');

            // 広告IDを取得
            App::import('Model', 'PlaceAd');
            $this->PlaceAd = new PlaceAd;
            $r = $this->PlaceAd->findById($master['adDefaultId']);

            $result[0]['PlaceAd'][0] = $r['PlaceAd'];
        }

        return $result;
    }

    /**
     * 施設の削除フラグを変更する
     *
     * @param  [type] $placeId     [description]
     * @param  [type] $isClosed    [description]
     * @return [type]              [description]
     */
    public function setDelete($placeId, $isClosed) {

        $data = array(
            $this->alias => array(
                'id' => $placeId,
                'is_closed' => $isClosed
            )
        );
        return $this->save($data, array(
            'validate' => false,
            'callbacks' => false
        ));
    }

    public function setBusy($placeId, $isBusy) {

        $data = array(
            $this->alias => array(
                'id' => $placeId,
                'is_busy' => $isBusy
            )
        );
        return $this->save($data, array(
            'validate' => false,
            'callbacks' => false
        ));
    }

}
