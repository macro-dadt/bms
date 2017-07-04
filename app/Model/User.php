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
        ),
        'send_token' => array(
            'id'    => array(
                    'required' => array(
                        'rule'     => 'notBlank',
                        'message'  => '未指定です',
                        'required' => true
                    ),
                    'isUnique' => array(
                        'rule'    => 'isUnique',
                        'message' => '登録されています',
                    ),
            ),
            'token'    => array(
                'required' => array(
                    'rule'     => 'notBlank',
                    'message'  => '未指定です',
                ),
                'isUnique' => array(
                    'rule'    => 'isUnique',
                    'message' => '登録されています',
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
    public function getPremium($day)
    {
        $date = date('Y-m-d H:i:s');
        $day = "+"." ".$day." days";

        return $this->save(array(
            'expiration' =>  date('Y-m-d H:i:s', strtotime($date. $day))
        ));
    }
    public function checkIsExpired($userId)
    {
        $data = $this->findById($userId);
        if ($data['User']['expiration'] < date('Y-m-d H:i:s')){
            return true;
        }

        // データ加工
        return false;
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
    public function getPointBack($userId, $point)
    {
        // データ取得
        $data = $this->findById($userId);

        // データ加工
        $data['User']['point'] -= $point;

        // データ保存
        $result = $this->save(array(
            'id' => $userId,
            'point' => $data['User']['point']
        ));
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
        $user = $this->findByEmail($email);
        $user['User']['recovery_code'] =  $this->generateRandomString();
        if($this->save($user)) {
            return $user;
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
    function getAllToken(){
        $resultArr = $this->find('all', array(
            'fields' => array(
                $this->alias . '.token',
            ),
            'conditions' =>array('not' => array(
                $this->alias . '.token'  => null
            )),
        )
        );
        $tokenArr = array();
        foreach ($resultArr as $result) {
            $tokenArr[] = $result['User']['token'];
        }
        return $tokenArr;
    }
    function getToken($userId){
        $user = $this->findById($userId);
        return $user['User']['token'];
    }
    function change_password($email,$new_password){
        $user = $this->findByEmail($email);
        $user['User']['new_password'] =  $new_password;

        if($this->save($user, array(
            '_validate' => 'change_password'
        ))) {
            return true;
        } else {
            return false;
        }
    }
    function send_token($userId,$token){
        $user = $this->findById($userId);
        $user['User']['token'] =  $token;
        if($this->save($user, array(
            '_validate' => 'send_token'
        ))) {
            return true;
        } else {
            return false;
        }
    }
    public function sendFCMMessage($data,$target,$message){
        //$data in json form
        /*
Parameter Example
	$data = array('post_id'=>'12345','post_title'=>'A Blog post');
	$target = 'single tocken id or topic name';
	or
	$target = array('token1','token2','...'); // up to 1000 in one request
*/
        //http://sab99r.com/blog/firebase-cloud-messaging-fcm-php-backend/
//FCM api URL
        $url = 'https://fcm.googleapis.com/fcm/send';
//api_key available in Firebase Console -> Project Settings -> CLOUD MESSAGING -> Server key
        $server_key = 'AAAAtjvaTKk:APA91bGlBvkxM1ZKnPfEk643kTtsPuUuMAlZ0_bThbqvfpr7FpjSgIQ_QaiwayskmimSdhuin1vaYSCKTWDfLmgzgPzTy1xFt_CGazffOKGkVTb8rfqDmWzldopEQ3ImwD0YxkYrayat';

        $fields = array();
        $fields['data'] = $data;
        $fields['notification'] = array(
            'body'       => $message,
            'title'    => 'Babymap',
            'sound' => 'default',
            'click_action' => $data['action']);
        if(is_array($target)){
            $fields['registration_ids'] = $target;
        }else{
            $fields['to'] = $target;
        }
//header with content_type api key
        $headers = array(
            'Content-Type:application/json',
            'Authorization:key='.$server_key
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('FCM Send Error: ' . curl_error($ch));
        }
        curl_close($ch);
        return $result;
    }
    function sendPushMessage($tToken,$tAlert,$tPayload) {
        // Provide the Host Information.

        $tHost = 'gateway.sandbox.push.apple.com';

        $tPort = 2195;

        // Provide the Certificate and Key Data.


        $tCert = '/var/www/html/babymap-api/app/Model/Babymap_Push.pem';
        $tCa = '/var/www/html/babymap-api/app/Model/entrust_2048_ca.cer';

        // Provide the Private Key Passphrase (alternatively you can keep this secrete

        // and enter the key manually on the terminal -> remove relevant line from code).

        // Replace XXXXX with your Passphrase

        $tPassphrase = '163182776';

        // Provide the Device Identifier (Ensure that the Identifier does not have spaces in it).

        // Replace this token with the token of the iOS device that is to receive the notification.

        // The message that is to appear on the dialog.

        // The Badge Number for the Application Icon (integer >=0).

        $tBadge = 8;

        // Audible Notification Option.

        $tSound = 'default';

        // The content that is returned by BabyMapApp "pushNotificationReceived" message.

        //$tPayload = 'APNS Message Handled by BabyMapApp';

        // Create the message content that is to be sent to the device.

        $tBody['aps'] = array (

            'alert' => $tAlert,

            'badge' => $tBadge,

            'sound' => $tSound,
        );
        $tBody ['payload'] = $tPayload;
        // Encode the body to JSON.
        $tBody = json_encode ($tBody);
        // Create the Socket Stream.
        $tContext = stream_context_create ();
//        $tContext = stream_context_create([ 'ssl' => [
//            'verify_peer'       => false,
//            'verify_peer_name'  => false,]]);
        stream_context_set_option ($tContext, 'ssl', 'local_cert', $tCert);

        // Remove this line if you would like to enter the Private Key Passphrase manually.

        stream_context_set_option ($tContext, 'ssl', 'passphrase', $tPassphrase);
        stream_context_set_option($tContext, 'ssl', 'cafile',$tCa);

        // Open the Connection to the APNS Server.

        $tSocket = stream_socket_client ('ssl://'.$tHost.':'.$tPort, $error, $errstr,30, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $tContext);

        // Check if we were able to open a socket.

        if (!$tSocket)

            exit ("APNS Connection Failed: $error $errstr" . PHP_EOL);

        // Build the Binary Notification.

        $tMsg = chr (0) . chr (0) . chr (32) . pack ('H*', $tToken) . pack ('n', strlen ($tBody)) . $tBody;

        // Send the Notification to the Server.

        $tResult = fwrite ($tSocket, $tMsg, strlen ($tMsg));
        fclose ($tSocket);
        if ($tResult)
        {

            echo 'Delivered Message to APNS' . PHP_EOL;
            return true;
        }


        else

            echo 'Could not Deliver Message to APNS' . PHP_EOL;

        // Close the Connection to the Server.


        return false;

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
