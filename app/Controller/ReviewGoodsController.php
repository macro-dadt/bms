<?php

/**
 * ReviewsController
 *
 * @package    app.Controller
 * @property ReviewGood $ReviewGood
 */
class ReviewGoodsController extends AppController
{

    /**
     * Component
     *
     * @var array
     */
    public $components = array();

    /**
     * レビュー参考になった投稿
     */
    public function api_add()
    {
        $review_id = $this->request->query('review_id');
        $user_id = $this->Auth->user('id');

        if(!$this->ReviewGood->isExists($user_id, $review_id)) {

            $data = array(
                'ReviewGood' => array(
                    'review_id' => $review_id,
                    'user_id' => $user_id
                )
            );
            if($this->ReviewGood->save($data)) {
                $this->set(array(
                    'result'     => 'success',
                    '_serialize' => array('result')
                ));
            } else {
                $this->set(array(
                    'error' => '更新きませんでした',
                    '_serialize' => array('error')
                ));
            }
        } else {
            $this->set(array(
                'error' => 'すでに投稿されています',
                '_serialize' => array('error')
            ));
        }
    }

    /**
     * レビュー参考になった削除
     *
     * @return [type] [description]
     */
    public function api_delete()
    {
        $id = $this->request->query('id');

        if($this->ReviewGood->delete($id)) {
            $this->set(array(
                'result'     => 'success',
                '_serialize' => array('result')
            ));
        } else {
            $this->set(array(
                'error' => '更新きませんでした',
                '_serialize' => array('error')
            ));
        }

    }

}
