<?php

/**
 * NoticesController
 *
 * @package    app.Controller
 * @property Notice $Notice
 */
class NoticesController extends AppController
{

    /**
     * Component
     *
     * @var array
     */
    public $components = array();

    /**
     * 誤り報告
     */
    public function api_add()
    {
        if ($this->Notice->add($this->Auth->user('id'), $this->request->data)) {
            $this->set(array(
                'result'     => 'success',
                '_serialize' => array('result')
            ));
        } else {
            $this->set(array(
                'errors'     => array_merge($this->Notice->validationErrors),
                '_serialize' => array('errors')
            ));
        }
    }

    public function add() {}

}
