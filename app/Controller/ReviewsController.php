<?php

/**
 * ReviewsController
 *
 * @package    app.Controller
 * @property Review $Review
 */
class ReviewsController extends AppController
{

    /**
     * Component
     *
     * @var array
     */
    public $components = array();

    /**
     * レビュー投稿
     */
    public function api_add()
    {
        if ($this->Review->add($this->Auth->user('id'), $this->request->data)) {
            $this->set(array(
                'result'     => 'success',
                '_serialize' => array('result')
            ));
        } else {
            $this->set(array(
                'errors'     => array_merge($this->Review->validationErrors),
                '_serialize' => array('errors')
            ));
        }
    }

    /**
     * レビューの変更元データ
     *
     * @param null $id
     */
    public function api_edit_data($id = null)
    {
        if ($edit = $this->Review->editData($this->Auth->user('id'), $id)) {
            $this->set(array(
                'edit'       => $edit,
                '_serialize' => array('edit')
            ));
        } else {
            $this->set(array(
                'error'      => '取得できませんでした',
                '_serialize' => array('error')
            ));
        }

    }

    /**
     * レビュー変更
     *
     * @param null $id
     */
    public function api_edit($id = null)
    {
        if ($this->Review->edit($this->Auth->user('id'), $id, $this->request->data)) {
            $this->set(array(
                'result'     => 'success',
                '_serialize' => array('result')
            ));
        } else {
            $this->set(array(
                'errors'     => array_merge($this->Review->validationErrors),
                '_serialize' => array('errors')
            ));
        }
    }

    /**
     * レビュー一覧
     *
     * @param null
     */
    public function api_place()
    {
        $place_id = $this->request->query('place_id');
        $reviews = $this->Review->place($place_id);
        if($reviews === false) {
            $this->set(array(
                'error'      => '取得できませんでした',
                '_serialize' => array('error')
            ));
        } else {
            $this->set(array(
                'reviews' => $reviews,
                '_serialize' => array('reviews')
            ));
        }
    }

    /**
     * レビュー詳細
     *
     * @param  [type] $user_id [description]
     * @param  [type] $id      [description]
     * @return [type]          [description]
     */
    public function api_view($id)
    {
        $review = $this->Review->view($id);
        if($review === false) {
            $this->set(array(
                'error' => '取得できませんでした',
                '_serialize' => array('error')

            ));
        } else {
            $this->set(array(
                'review' => $review,
                '_serialize' => array('review')
            ));
        }
    }

    /**
     * 自分のレビュー一覧
     *
     * @param null
     */
    public function api_places()
    {
        $reviews = $this->Review->places($this->Auth->user('id'));
        if($reviews === false) {
            $this->set(array(
                'error'      => '取得できませんでした',
                '_serialize' => array('error')
            ));
        } else {
            $this->set(array(
                'reviews' => $reviews,
                '_serialize' => array('reviews')
            ));
        }
    }

    /**
     * 自分の施設＋レビュー一覧（ページング）
     *
     * @param null
     */
    public function api_my_place()
    {
        $page = $this->request->query('page');
        $reviews = $this->Review->myPlace($this->Auth->user('id'), $page);

        if($reviews === false) {
            $this->set(array(
                'error'      => '取得できませんでした',
                '_serialize' => array('error')
            ));
        } else {
            $this->set(array(
                'reviews' => $reviews,
                '_serialize' => array('reviews')
            ));
        }
    }

    /**
     * 自分のレビュー一覧（ページング）
     *
     * @param null
     */
    public function api_my_review()
    {
        $page = $this->request->query('page');
        $reviews = $this->Review->myReview($this->Auth->user('id'), $page);

        if($reviews === false) {
            $this->set(array(
                'error'      => '取得できませんでした',
                '_serialize' => array('error')
            ));
        } else {
            $this->set(array(
                'reviews' => $reviews,
                '_serialize' => array('reviews')
            ));
        }
    }

    /**
     * レビュー削除フラグ操作
     *
     * @return [type] [description]
     */
    public function api_delete()
    {
        // 対象の施設ID
        $review_id = $this->request->data('review_id');
        // フラグの状態
        $del_flg =   $this->request->data('del_flg');

        // フラグ変更
        $result = $this->Review->setDelete($review_id, $del_flg);

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
