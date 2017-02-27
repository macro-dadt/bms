<?php

/**
 * UserImagesController
 *
 * @package    app.Controller
 * @property UserImage $UserImage
 */
class UserImagesController extends AppController
{

    /**
     * 自分の画像アップロード
     */
    public function api_upload()
    {
        if ($data = $this->UserImage->upload($this->Auth->user('id'), $this->request->data)) {
            $this->set(array(
                'image'      => $data,
                '_serialize' => array('image')
            ));
        } else {
            $this->set(array(
                'errors'     => $this->UserImage->validationErrors,
                '_serialize' => array('errors')
            ));
        }
    }

    /**
     * 自分の画像削除
     */
    public function api_delete()
    {
        if ($this->UserImage->myDelete($this->Auth->user('id'))) {
            $this->set(array(
                'result'     => 'success',
                '_serialize' => array('result')
            ));
        } else {
            $this->set(array(
                'error'      => '画像が登録されていません',
                '_serialize' => array('error')
            ));
        }
    }
}
