<?php

/**
 * ThanksController
 *
 * @package    app.Controller
 * @property Thank ＄Thank
 */
class ThanksController extends AppController
{

    /**
     * 協力会社一覧
     *
     * @param null
     */
    public function api_index()
    {
        $result = $this->Thank->index();

        if($result) {
            $this->set(array(
                'result' => $result,
                '_serialize' => array('result')
            ));
        } else {
            $this->set(array(
                'error'      => '取得できませんでした',
                '_serialize' => array('error')
            ));
        }
    }

}
