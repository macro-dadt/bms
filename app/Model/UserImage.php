<?php

/**
 * UserImage
 *
 * @package    app.Model
 * @property User $User
 */
class UserImage extends AppModel
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
    public $validate = array(
        'file' => array(
            'upload-file' => array(
                'rule'    => 'uploadError',
                'message' => 'アップロードに失敗しました'
            ),
            'mimeType'    => array(
                'rule'    => array('mimeType', array('image/jpeg', 'image/pjpeg')),
                'message' => 'jpeg以外がアップロードされています'
            ),
            'extension'   => array(
                'rule'    => array('extension', array('jpeg', 'jpg')),
                'message' => 'jpeg以外がアップロードされています'
            ),
            'fileSize'    => array(
                'rule'    => array('fileSize', '<=', '1000KB'),
                'message' => '%2$s以下のファイルを選択してください'
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
        if (
            isset($this->data[$this->alias]['file']) &&
            $this->data[$this->alias]['file']['error'] === UPLOAD_ERR_NO_FILE
        ) {
            unset($this->data[$this->alias]['file']);
        }
        // ファイルが送信されていない時のエラー
        if (empty($this->data[$this->alias]['file'])) {
            $this->invalidate('file', 'アップロードに失敗しました');
        }

        return parent::beforeValidate($options);
    }

    /**
     * レコード保存前に画像をアップロード
     *
     * @param array $options
     * @return bool
     */
    public function beforeSave($options = array())
    {
        try {
            $data = $this->data;

            // ランダムにファイル名を作る
            $fileInfo = new SplFileInfo($data[$this->alias]['file']['name']);
            $name = md5(uniqid('', true)) . '.' . strtolower($fileInfo->getExtension());

            App::uses('WideImage', 'Lib/WideImage');
            // 画像読み込み
            $image = WideImage::load($data[$this->alias]['file']['tmp_name']);
            // リサイズして保存
            $image->resize(300, 300, 'outside')->crop('center', 'center', 300, 300)->saveToFile(TMP . $name);

            // 画像をS3にアップロード
            $AmazonS3 = ClassRegistry::init('AmazonS3');
            $url = $AmazonS3->putFile(TMP . $name, '/uploads/user/' . $name);
            // 一時保存画像削除
            unlink(TMP . $name);

            // DBにパス・URL保存
            $data[$this->alias]['path'] = '/uploads/user/' . $name;
            $data[$this->alias]['url'] = $url;
            $data[$this->alias]['del_flg'] = 0;


            unset($data[$this->alias]['file']);

            // 前の画像があれば削除する
            if ($pre = $this->findByUserId($data[$this->alias]['user_id'])) {
                $this->delete($pre[$this->alias]['id']);
            }

            $this->data = $data;

            return true;

        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * レコード削除時に画像を削除
     *
     * @param bool|true $cascade
     * @return bool
     */
    public function beforeDelete($cascade = true)
    {
        $data = $this->read('path', $this->id);
        $AmazonS3 = ClassRegistry::init('AmazonS3');
        $AmazonS3->deleteFile($data[$this->alias]['path']);

        return true;
    }

    /**
     * 自分の画像アップロード
     *
     * @param $userId
     * @param $data
     * @return array|bool
     * @throws \Exception
     */
    public function upload($userId, $data)
    {
        $data[$this->alias]['user_id'] = $userId;
        if ($saved = $this->save($data)) {
            return array(
                'path' => $saved[$this->alias]['path'],
                'url'  => $saved[$this->alias]['url'],
                'del_flg'  => $saved[$this->alias]['del_flg']
            );
        }

        return false;
    }

    /**
     * 自分の画像削除
     *
     * @param $userId
     * @return bool
     */
    public function myDelete($userId)
    {
        $data = $this->findByUserId($userId);
        if (!$data) {
            return false;
        }

        return $this->delete($data[$this->alias]['id']);
    }
}
