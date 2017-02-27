<?php

/**
 * PlaceHistoriesController
 *
 * @package    app.Controller
 * @property PlaceHistory $PlaceHistory
 */
class PlaceHistoriesController extends AppController
{

    /**
     * Component
     *
     * @var array
     */
    public $components = array();

    /**
     * 施設閲覧履歴取得（ページング）
     *
     * @return [type] [description]
     */
    public function api_index()
    {
        $page = $this->request->query('page');
        $result = $this->PlaceHistory->index($this->Auth->user('id'), $page);

        if($result) {
            $this->set(array(
                'result'     => $result,
                '_serialize' => array('result')
            ));
        } else {
            $this->set(array(
                'error'      => '解除できませんでした',
                '_serialize' => array('error')
            ));
        }

    }


    /**
     * 施設閲覧履歴を保存する
     *
     */
    public function add($user_id, $place_id)
    {
        $result = $this->PlaceHistory->add($user_id, $place_id);

        if($result) {
            $this->set(array(
                'result'     => 'success',
                '_serialize' => array('result')
            ));
        } else {
            $this->set(array(
                'error'      => '保存できませんでした',
                '_serialize' => array('error')
            ));
        }
    }
}
