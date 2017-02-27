<?php

/**
 * PlaceQuestionsController
 *
 * @package    app.Controller
 * @property PlaceQuestion PlaceQuestion
 */
class PlaceQuestionsController extends AppController
{

    /**
     * Component
     *
     * @var array
     */
    public $components = array();

    /**
     * 施設質問投稿
     */
    public function api_add()
    {
        if ($this->PlaceQuestion->add($this->Auth->user('id'), $this->request->data)) {
            $this->set(array(
                'result'     => 'success',
                '_serialize' => array('result')
            ));
        } else {
            $this->set(array(
                'errors'     => array_merge($this->PlaceQuestion->validationErrors),
                '_serialize' => array('errors')
            ));
        }
    }

    /**
     * 施設質問詳細
     *
     */
    public function api_index()
    {
        $place_id = $this->request->query('place_id');
        $result = $this->PlaceQuestion->index($place_id);

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
