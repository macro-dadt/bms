<?php

/**
 * ReviewIMagesController
 *
 * @package    app.Controller
 * @property Review $Review
 */
class ReviewImagesController extends AppController
{

    /**
     * Component
     *
     * @var array
     */
    public $components = array();

    /**
     * レビュー画像削除フラグ操作
     *
     * @return [type] [description]
     */
    public function api_delete()
    {
        // 対象の施設ID
        $review_image_id = $this->request->data('review_image_id');
        // フラグの状態
        $del_flg =   $this->request->data('del_flg');

        // フラグ変更
        $result = $this->ReviewImage->setDelete($review_image_id, $del_flg);

        if ($result) {
            $this->set(array(
                'result'     => 'success',
                '_serialize' => array('result')
            ));
        } else {
            $this->set(array(
                'error'      => '変更出来ませんでした',
                '_serialize' => array('error')
            ));
        }

    }
}
