<?php

/**
 * PlaceAnswersController
 *
 * @package    app.Controller
 * @property PlaceAnswer PlaceAnswer
 */
class PlaceAnswersController extends AppController
{

    /**
     * Component
     *
     * @var array
     */
    public $components = array();

    /**
     * 施設回答投稿
     */
    public function api_add()
    {
        if ($this->PlaceAnswer->add($this->Auth->user('id'), $this->request->data)) {
            $this->set(array(
                'result'     => 'success',
                '_serialize' => array('result')
            ));
        } else {
            $this->set(array(
                'errors'     => array_merge($this->PlaceAnswer->validationErrors),
                '_serialize' => array('errors')
            ));
        }
    }

    /**
     * ありがとうを送る
     *
     * @return [type] [description]
     */
    public function api_best()
    {
        $place_answer_id = $this->request->query('place_answer_id');

        if ($this->PlaceAnswer->best($this->Auth->user('id'), $place_answer_id)) {
            $this->set(array(
                'result'     => 'success',
                '_serialize' => array('result')
            ));
        } else {
            $this->set(array(
                'errors'     => '更新できませんでした',
                '_serialize' => array('errors')
            ));
        }
    }

    /**
     * ありがとうを消す
     *
     * @return [type] [description]
     */
    public function api_best_delete()
    {
        $id = $this->request->query('id');

        if ($this->PlaceAnswer->best_delete($this->Auth->user('id'), $id)) {
            $this->set(array(
                'result'     => 'success',
                '_serialize' => array('result')
            ));
        } else {
            $this->set(array(
                'errors'     => '更新できませんでした',
                '_serialize' => array('errors')
            ));
        }
    }

    /**
     * 質問IDから回答を取得する
     *
     * @return [type] [description]
     */
    public function api_view()
    {
        $place_question_id = $this->request->query('place_question_id');
        $result = $this->PlaceAnswer->view($place_question_id);

        if ($result) {
            $this->set(array(
                'result'     => $result,
                '_serialize' => array('result')
            ));
        } else {
            $this->set(array(
                'errors'     => '取得できませんでした',
                '_serialize' => array('errors')
            ));
        }
    }

}
