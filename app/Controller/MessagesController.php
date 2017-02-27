<?php

/**
 * MessagesController
 *
 * @package    app.Controller
 * @property Message ＄Message
 */
class MessagesController extends AppController
{

    /**
     * Component
     *
     * @var array
     */
    public $components = array();


    /**
     * メッセージ取得
     *
     * @return [type] [description]
     */
    public function api_index()
    {
        $result = $this->Message->index($this->Auth->user('id'));
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

}
