<?php

/**
 * RankingsController
 *
 * @package    app.Controller
 * @property Ranking ＄Ranking
 */
class RankingsController extends AppController
{

    /**
     * 協力会社一覧
     *
     * @param null
     */
    public function api_index()
    {
        $type = $this->request->query('type');
        $result = $this->Ranking->index($type);

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
