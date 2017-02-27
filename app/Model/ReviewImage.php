<?php

/**
 * ReviewImage
 *
 * @package    app.Model
 * @property Review $Review
 */
class ReviewImage extends AppModel
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
    public $belongsTo = array('Review');

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
                'rule'    => array('fileSize', '<=', '5000KB'),
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

            // ファイルのexif取得
            $exif = exif_read_data($data[$this->alias]['file']['tmp_name']);
            // オリエンテーション6対策
            if(isset($exif['Orientation']) && $exif['Orientation'] === 6){
                $tmp_file = imagecreatefromjpeg($data[$this->alias]['file']['tmp_name']);
                $rotate = imagerotate($tmp_file, -90, 0);
                imagejpeg($rotate, $data[$this->alias]['file']['tmp_name'], 100);
            }

            App::uses('WideImage', 'Lib/WideImage');
            // 画像読み込み
            $image = WideImage::load($data[$this->alias]['file']['tmp_name']);
            // リサイズして保存(長辺500px以内に抑える)
            $image->resize(500, 500, 'inside', 'down')->saveToFile(TMP . $name);

            // 画像をS3にアップロード
            $AmazonS3 = ClassRegistry::init('AmazonS3');
            $url = $AmazonS3->putFile(TMP . $name, '/uploads/review/' . $name);
            // 一時保存画像削除
            unlink(TMP . $name);

            // DBにパス・URL保存
            $data[$this->alias]['path'] = '/uploads/review/' . $name;
            $data[$this->alias]['url'] = $url;

            unset($data[$this->alias]['file']);

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
     * レビュー画像の削除フラグを変更する
     *
     * @param  [type] $reviewImageId [description]
     * @param  [type] $flag    [description]
     * @return [type]          [description]
     */
    public function setDelete($reviewImageId, $delFlg) {

        $data = array(
            $this->alias => array(
                'id' => $reviewImageId,
                'del_flg' => $delFlg
            )
        );
        return $this->save($data, array(
            'validate' => false,
            'callbacks' => false
        ));
    }

}
