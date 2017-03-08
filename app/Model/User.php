<?php
App::uses('SimplePasswordHasher', 'Controller/Component/Auth');

/**
 * User
 *
 * @package    app.Model
 * @property UserImage     $UserImage
 * @property UserCustomize $UserCustomize
 * @property Child         $Child
 * @property Message       $Message
 * @property PointAlert    $PointAlert
 * @property PointHistory  $PointHistory
 * @property ActionCount   $ActionCount
 * @property FavoritePlace $FavoritePlace
 * @property Notice        $Notice
 * @property Place         $Place
 * @property PlaceQuestion $PlaceQuestion
 * @property PlaceAnswer   $PlaceAnswer
 * @property Review        $Review
 * @property ReviewGood    $ReviewGood
 * @property ItemExchange  $ItemExchange
 */
class User extends AppModel
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
    public $belongsTo = array();

    /**
     * HasOne
     *
     * @var array
     */
    public $hasOne = array(
        'UserImage',
        'UserCustomize'
    );

    /**
     * HasMany
     *
     * @var array
     */
    public $hasMany = array(
        'Child',
        'Message',
        'PointAlert',
        'PointHistory',
        'ActionCount',
        'FavoritePlace',
        'Notice',
        'Place',
        'PlaceQuestion',
        'PlaceAnswer',
        'Review',
        'ReviewGood',
        'ItemExchange',
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
     * アクション毎のバリデーション
     *
     * @var array
     */
    protected $_validate = array(
        'generate'     => array(
            'uuid'     => array(
                'required' => array(
                    'rule'     => 'notBlank',
                    'message'  => '未指定です',
                    'required' => true
                ),
                'isUnique' => array(
                    'rule'    => 'isUnique',
                    'message' => '登録されています',
                ),
                'uuid'     => array(
                    'rule'    => 'uuid',
                    'message' => '書式が正しくありません',
                ),
            ),
            'password' => array(
                'required'     => array(
                    'rule'     => 'notBlank',
                    'message'  => '未指定です',
                    'required' => true
                ),
                'alphaNumeric' => array(
                    'rule'    => 'alphaNumeric',
                    'message' => '半角英数のみ利用可能です',
                ),
                'maxLength'    => array(
                    'rule'    => array('maxLength', 40),
                    'message' => '%s字以内で入力してください'
                ),
            )
        ),
        'new_generate'     => array(
            'new_password' => array(
                'required'     => array(
                    'rule'     => 'notBlank',
                    'message'  => '未指定です',
                    'required' => true
                ),
                'alphaNumeric' => array(
                    'rule'    => 'alphaNumeric',
                    'message' => '半角英数のみ利用可能です',
                ),
                'maxLength'    => array(
                    'rule'    => array('maxLength', 40),
                    'message' => '%s字以内で入力してください'
                ),
            ),
            'social_id' => array(
                'isUnique' => array(
                    'rule'    => 'isUnique',
                    'message' => '登録されています',
                ),

            ),
            'name' => array(
                'required'     => array(
                    'rule'     => 'notBlank',
                    'message'  => '未指定です',
                    'required' => true
                )
            ),
            'email'    => array(
                'email' => array(
                    'rule'       => 'email',
                    'message'    => '書式が正しくありません',
                    'allowEmpty' => true
                ),
                'isUnique' => array(
                    'rule'    => 'isUnique',
                    'message' => '登録されています',
                ),
            ),
        ),
        'saveNickname' => array(
            'name' => array(
                'required'  => array(
                    'rule'     => 'notBlank',
                    'message'  => '未指定です',
                    'required' => true
                ),
                'maxLength' => array(
                    'rule'    => array('maxLength', 30),
                    'message' => '%s字以内で入力してください'
                ),
            )
        ),
        'edit'         => array(
            'name'     => array(
                'required'  => array(
                    'rule'     => 'notBlank',
                    'message'  => '未指定です',
                    'required' => true
                ),
                'maxLength' => array(
                    'rule'    => array('maxLength', 30),
                    'message' => '%s字以内で入力してください'
                ),
            ),
            'fullname' => array(
                'maxLength' => array(
                    'rule'       => array('maxLength', 30),
                    'message'    => '%s字以内で入力してください',
                    'allowEmpty' => true
                ),
            ),
            'zipcode'  => array(
                'custom'    => array(
                    'rule'       => array('custom', '/^\d+$/'),
                    'message'    => '数字のみで入力してください',
                    'allowEmpty' => true
                ),
                'maxLength' => array(
                    'rule'       => array('maxLength', 7),
                    'message'    => '%s字以内で入力してください',
                    'allowEmpty' => true
                ),
            ),
            'addresss' => array(
                'maxLength' => array(
                    'rule'       => array('maxLength', 150),
                    'message'    => '%s字以内で入力してください',
                    'allowEmpty' => true
                ),
            ),
            'birthday' => array(
                'date' => array(
                    'rule'       => 'date',
                    'message'    => '日付が正しくありません',
                    'allowEmpty' => true
                ),
            ),
            'tel'      => array(
                'date' => array(
                    'rule'       => array('custom', '/^\d+$/'),
                    'message'    => '数字のみで入力してください',
                    'allowEmpty' => true
                ),
            ),
            'email'    => array(
                'email' => array(
                    'rule'       => 'email',
                    'message'    => '書式が正しくありません',
                    'allowEmpty' => true
                ),
                'isUnique' => array(
                    'rule'    => 'isUnique',
                    'message' => '登録されています',
                ),
),
        ),
        'deviceChange' => array(
            'email'    => array(
                'email' => array(
                    'rule'       => 'email',
                    'message'    => '書式が正しくありません',
                    'allowEmpty' => true
                ),
            ),
        ),
     'recoverPassword' => array(
            'email'    => array(
                'email' => array(
                    'rule'       => 'email',
                    'message'    => '書式が正しくありません',
                    'allowEmpty' => true
                ),
            ),
        )
    );

    /**
     * パスワードのハッシュ化
     */
    public function beforeSave($options = array())
    {
        parent::beforeSave($options);
        if (!empty($this->data[$this->alias]['new_password'])) {
            $haser = new SimplePasswordHasher();
            $this->data[$this->alias]['new_password'] = $haser->hash($this->data[$this->alias]['new_password']);
        } else {
            unset($this->data[$this->alias]['new_password']);
        }
        if (!empty($this->data[$this->alias]['password'])) {
            $haser = new SimplePasswordHasher();
            $this->data[$this->alias]['password'] = $haser->hash($this->data[$this->alias]['password']);
        } else {
            unset($this->data[$this->alias]['password']);
        }
    }

    /**
     * ユーザー新規作成
     *
     * @param $uuid
     * @param $password
     * @return bool|mixed
     * @throws \Exception
     */
    public function generate($uuid, $password)
    {
        return $this->save(array(
            'uuid'      => $uuid,
            'password'  => $password,
            'generated' => date('Y-m-d H:i:s')
        ), array('_validate' => 'generate'));
    }

    public function new_generate($email, $social_id, $name, $new_password)
    {
        if ($email == "")
    {
        return $this->save(array(
            'social_id'      => $social_id,
            'new_password'  => $new_password,
            'name'  => $name,
            'generated' => date('Y-m-d H:i:s')
        ), array('_validate' => 'new_generate'));
    }
    else
    {
        if ($social_id == ""){
            return $this->save(array(
                'email'      => $email,
                'new_password'  => $new_password,
                'name'  => $name,
                'generated' => date('Y-m-d H:i:s')
            ), array('_validate' => 'new_generate'));
        }
        else{
            return $this->save(array(
                'social_id'      => $social_id,
                'email'      => $email,
                'new_password'  => $new_password,
                'name'  => $name,
                'generated' => date('Y-m-d H:i:s')
            ), array('_validate' => 'new_generate'));
        }

    }

    }
    /**
     * ニックネーム登録
     *
     * @param $name
     * @return mixed
     * @throws \Exception
     */
    public function saveNickname($name)
    {
        return $this->save(array(
            'name' => $name,
            'joined' => date('Y-m-d H:i:s')
        ), array('_validate' => 'saveNickname'));
    }

    /**
     * 変更
     *
     * @param            $id
     * @param            $data
     * @param bool|false $validateOnly
     * @return bool|mixed
     * @throws \Exception
     */
    public function edit($id, $data, $validateOnly = false)
    {
        $fieldList = array(
            $this->alias => array(
                'name',
                'fullname',
                'zipcode',
                'address',
                'birthday',
                'tel',
                'email',
                'is_children_open',
            ),
            'Child'      => array(
                'number',
                'birthday',
                'gendar',
            )
        );

        $data[$this->alias]['id'] = $id;
        if ($this->saveAll($data, array('validate' => 'only', '_validate' => 'edit', 'fieldList' => $fieldList))) {
            if ($validateOnly) {
                return true;
            }
            // 子供データは送信された分だけ登録する
            if (isset($data['Child'])) {
                $children = array();
                $number = 1;
                foreach ($data['Child'] as $child) {
                    $child['number'] = $number;
                    $children[] = $child;
                    $number++;
                }
                $data['Child'] = $children;
                $this->Child->deleteAll(array('Child.user_id' => $id));
            }

            return $this->saveAll($data, array('_validate' => 'edit', 'fieldList' => $fieldList));
        }

        return false;
    }

    /**
     * ユーザー情報
     *
     * @param $id
     * @return array|null
     */
    public function view($id)
    {
        return $this->find('first', array(
            'conditions' => array(
                $this->alias . '.id' => $id
            ),
            'fields'     => array(
                $this->alias . '.name',
                $this->alias . '.fullname',
                $this->alias . '.zipcode',
                $this->alias . '.address',
                $this->alias . '.birthday',
                $this->alias . '.point',
                $this->alias . '.tel',
                $this->alias . '.email',
            ),
            'contain'    => array(
                'Child'     => array(
                    'fields' => array(
                        'Child.number',
                        'Child.birthday',
                        'Child.gendar',
                    )
                ),
                'UserImage' => array(
                    'fields' => array(
                        'UserImage.path',
                        'UserImage.url',
                        'UserImage.del_flg',
                    )
                ),
            )
        ));
    }
    public function new_view($email,$social_id)
    {
        $data = $this->findByEmail($email);
        if(!empty($data['User']['email'])) {
            return $this->find('first', array(
                'conditions' => array(
                    $this->alias . '.email' => $email,
                ),
                'fields' => array(
                    $this->alias . '.name',
                    $this->alias . '.fullname',
                    $this->alias . '.zipcode',
                    $this->alias . '.address',
                    $this->alias . '.birthday',
                    $this->alias . '.point',
                    $this->alias . '.tel',
                    $this->alias . '.email',
                    $this->alias . '.new_password',
                    $this->alias . '.social_id'
                ),
                'contain' => array(
                    'Child' => array(
                        'fields' => array(
                            'Child.number',
                            'Child.birthday',
                            'Child.gendar',
                        )
                    ),
                    'UserImage' => array(
                        'fields' => array(
                            'UserImage.path',
                            'UserImage.url',
                            'UserImage.del_flg',
                        )
                    ),
                )
            ));
        } else {
            $data = $this->findBySocial_id($social_id);
            if(!empty($data['User']['social_id'])) {
                return $this->find('first', array(
                    'conditions' => array(
                        $this->alias . '.social_id' => $social_id,
                    ),
                    'fields' => array(
                        $this->alias . '.name',
                        $this->alias . '.fullname',
                        $this->alias . '.zipcode',
                        $this->alias . '.address',
                        $this->alias . '.birthday',
                        $this->alias . '.point',
                        $this->alias . '.tel',
                        $this->alias . '.email',
                        $this->alias . '.new_password',
                        $this->alias . '.social_id'
                    ),
                    'contain' => array(
                        'Child' => array(
                            'fields' => array(
                                'Child.number',
                                'Child.birthday',
                                'Child.gendar',
                            )
                        ),
                        'UserImage' => array(
                            'fields' => array(
                                'UserImage.path',
                                'UserImage.url',
                                'UserImage.del_flg',
                            )
                        ),
                    )
                ));
            }
        }
    }
    /**
     * 最終利用日時更新
     *
     * @param $id
     * @return bool
     * @throws \Exception
     */
    public function updateLastUsed($id)
    {
        if ($this->hasAny(array($this->alias . '.id' => $id))) {
            $this->save(array(
                'id' => $id,
                'last_used' => date('Y-m-s H:i:s')
            ));
            return true;
        }
        return false;
    }

    /**
     * お気に入りを非公開にする
     *
     * @param  [type] $userId [description]
     * @param  [type] $id     [description]
     * @return [type]         [description]
     */
    public function closeFavorite($userId)
    {
        $data = $this->find('first', array(
            'conditions' => array(
                $this->alias . '.id' => $userId,
                $this->alias . '.is_favorite_open' => 1
            )
        ));

        if($data) {
            return $this->save(array('id' => $userId, 'is_favorite_open' => 0));
        } else {
            return false;
        }

    }

    /**
     * お気に入りを非公開を解除
     *
     * @param  [type] $userId [description]
     * @param  [type] $id     [description]
     * @return [type]         [description]
     */
    public function openFavorite($userId)
    {
        $data = $this->find('first', array(
            'conditions' => array(
                $this->alias . '.id' => $userId,
                $this->alias . '.is_favorite_open' => 0
            )
        ));

        if($data) {
            return $this->save(array('id' => $userId, 'is_favorite_open' => 1));
        } else {
            return false;
        }

    }

    /**
     * ポイント操作
     *
     * @param  [type] $userId [description]
     * @param  [type] type  [description]
     * @param  [type] $point  [description]
     * @return [type]         [description]
     */
    public function point($userId, $type, $point)
    {
        // データ取得
        $data = $this->findById($userId);

        // データ加工
        $data['User']['point'] += $point;

        // データ保存
        $result = $this->save(array(
            'id' => $userId,
            'point' => $data['User']['point']
        ));

        // 履歴に保存
        $history = array(
            'PointHistory' => array(
                'user_id' => $userId,
                'action' => $type,
                'point' => $point
            )
        );
        $this->PointHistory->save($history, array('validate' => false));

        return $result;

    }


    /**
     * 機種変依頼
     *
     * @return [type] [description]
     */
    public function deviceChangeRequest($userId, $email)
    {

        $data = array(
            'User' => array(
                'id' => $userId,
                'email' => $email
            )
        );

        // メールアドレス追加に成功したらユーザ情報を返す
        if($this->save($data, array(
            '_validate' => 'deviceChange'
        ))) {
            return $this->findById($userId);
        } else {
            return false;
        }
    }

    public function recoverPasswordRequest($email)
    {
        $data = array(
            'User' => array(
                'recovery_code'=> $this->generateRandomString()
            )
        );

        if($this->save($data)) {
            return $this->findByEmail($email);
        } else {
            return false;
        }
    }
    function generateRandomString($length = 6) {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    function change_password($new_password){
        $data = array(
            'User' => array(
                'new_password'=> $new_password
            )
        );

        if($this->save($data, array(
            '_validate' => 'change_password'
        ))) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 機種変依頼
     *
     * @return [type] [description]
     */
    public function deviceChangeRestore($email)
    {
        // 元ユーザの情報
        return $this->findByEmail($email);

    }

    /**
     * 特定ユーザがポストした施設一覧を取得
     *
     * @param  [type] $userId [description]
     * @return [type]         [description]
     */
    public function place($userId)
    {
        $data = $this->Place->find('all', array(
            'fields' => array(
                'id',
                'user_id',
                'name',
                'floor',
                'address',
                'tel',
                'url',
                'usable_week_day',
                'usable_time',
                'lat',
                'lon',
                'place_category_id',
                'star',
                'review_count',
                'milk_seat',
                'milk_baby_car',
                'milk_hot_water',
                'milk_papa',
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
                'is_official',
                'created',
                'modified',
            ),
            'conditions' => array(
                'user_id' => $userId
            ),
            'order' => array(
                'modified DESC'
            )
        ));
        return $data;
    }

    /**
     * 特定ユーザがポストした施設一覧を取得
     *
     * @param  [type] $userId [description]
     * @return [type]         [description]
     */
    public function review($userId)
    {
        $data = $this->Review->find('all', array(
            'fields' => array(
                'id',
                'place_id',
                'user_id',
                'star',
                'message',
                'is_parent',
                'created',
                'modified',
            ),
            'conditions' => array(
                'user_id' => $userId
            ),
            'order' => array(
                'modified DESC'
            )
        ));
        return $data;
    }

    /**
     * ユーザへの通知内容取得
     *
     * @param  [type] $userId [description]
     * @param  [type] $type   [description]
     * @return [type]         [description]
     */
    public function notify($userId, $type)
    {
        App::import('Model', 'Notify');
        $this->Notify = new Notify;

        if(!$type) {
            $result = $this->Notify->find('all', array(
                'conditions' => array(
                    'Notify.user_id' => $userId
                )
            ));
        } else {
            $result = $this->Notify->find('all', array(
                'conditions' => array(
                    'Notify.user_id' => $userId,
                    'Notify.type' => $type
                )
            ));
        }

        return $result;

    }

    /**
     * 通知をID指定で削除
     *
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function deleteNotify($id)
    {
        App::import('Model', 'Notify');
        $this->Notify = new Notify;

        return $this->Notify->delete($id);

    }


    /**
     * ユーザデータを取得する
     *
     * @param  [type] $userId [description]
     * @return [type]         [description]
     */
    public function getUserData($userId)
    {
        return $this->find('first', array(
            $this->alias . '.id' => $userId
        ));
    }
    public function getUserDataWithEmail($email)
        {
        return $this->find('first', array(
            $this->alias . '.email' => $email
        ));
    }

    /**
     * ニックネームが登録されているか確認
     *
     * @param  [type]  $userId [description]
     * @return boolean         [description]
     */
    public function isRegistered($userEmail,$userSocial_id)
    {
        $data = $this->findByEmail($userEmail);
        if(!empty($data['User']['email'])) {
            return true;
        } else {
            $data = $this->findBySocial_id($userSocial_id);
            if(!empty($data['User']['social_id'])) {
                return true;
            } else {
                return false;
            }
        }
    }
    public function recoveryCodeTrue($user_email, $user_recovery_code)
    {
        $data = $this->findByEmail($user_email);
        if($data['User']['recovery_code'] == $user_recovery_code) {
            return true;
        } else {
            return false;

        }
    }
    public function passwordTrue($user_email, $user_new_password)
    {
        $data = $this->findByEmail($user_email);
        if($data['User']['new_password'] == $user_new_password) {
            return true;
        } else {
            return false;

        }
    }

}
