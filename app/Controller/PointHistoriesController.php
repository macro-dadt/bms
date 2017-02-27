<?php

/**
 * PointHistoriesController
 *
 * @package    app.Controller
 * @property PointHistory $PointHistory
 */
class PointHistoriesController extends AppController
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
        $result = $this->PointHistory->index($this->Auth->user('id'));
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
