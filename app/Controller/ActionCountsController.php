<?php

/**
 * ActionCountsController
 *
 * @package    app.Controller
 * @property ActionCount ＄ActionCount
 */
class ActionCountsController extends AppController
{

    /**
     * ユーザアクション登録
     *
     * @param null
     */
    public function api_add()
    {
        $action = $this->request->data('action');

        if($this->ActionCount->add($this->Auth->user('id'), $action)) {
            $this->set(array(
                'result' => 'success',
                '_serialize' => array('result')
            ));
        } else {
            $this->set(array(
                'error'      => '更新できませんでした',
                '_serialize' => array('error')
            ));
        }
    }

}
