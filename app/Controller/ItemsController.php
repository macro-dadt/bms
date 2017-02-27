<?php

/**
 * ItemsController
 *
 * @package    app.Controller
 * @property Item ＄Item
 */
class ItemsController extends AppController
{

    /**
     * Component
     *
     * @var array
     */
    public $components = array();


    /**
     * ポイント履歴取得
     *
     * @return [type] [description]
     */
    public function api_index()
    {
        $result = $this->Item->index($this->Auth->user('id'));
        if($result) {
            $this->set(array(
                'result'     => $result,
                '_serialize' => array('result')
            ));
        } else {
            $this->set(array(
                'error' => '取得できませんでした',
                '_serialize' => array('error')
            ));
        }
    }

    /**
     * アイテム取得
     *
     * @return [type] [description]
     */
    public function api_view()
    {
        $id = $this->request->query('id');
        $result = $this->Item->view($id);
        if($result) {
            $this->set(array(
                'result'     => $result,
                '_serialize' => array('result')
            ));
        } else {
            $this->set(array(
                'error' => '取得できませんでした',
                '_serialize' => array('error')
            ));
        }
    }

    /**
     * アイテム交換申請
     *
     * @return [type] [description]
     */
    public function api_exchange()
    {
        if($this->Item->exchange($this->Auth->user('id'), $this->request->data)) {
            $this->set(array(
                'result'     => 'success',
                '_serialize' => array('result')
            ));
        } else {
            $this->set(array(
                'error' => '申請できませんでした',
                '_serialize' => array('error')
            ));
        }
    }

}
