<?php

/**
 * UserCustomizesController
 *
 * @package    app.Controller
 * @property UserCustomize $UserCustomize
 */
class UserCustomizesController extends AppController
{

    /**
     * Component
     *
     * @var array
     */
    public $components = array();

    /**
     * カスタマイズ情報の取得
     */
    public function api_view()
    {
        if ($customize = $this->UserCustomize->view($this->Auth->user('id'))) {
            $this->set(array(
                'customize'     => $customize,
                '_serialize' => array('customize')
            ));
        } else {
            $this->set(array(
                'error'     => '取得できませんでした',
                '_serialize' => array('error')
            ));
        }
    }

    /**
     * カスタマイズ情報更新
     */
    public function api_edit()
    {
        if ($this->UserCustomize->edit($this->Auth->user('id'), $this->request->data)) {
            $this->set(array(
                'result'     => 'success',
                '_serialize' => array('result')
            ));
        } else {
            $this->set(array(
                'error'     => '更新できませんでした',
                '_serialize' => array('error')
            ));
        }
    }
}
