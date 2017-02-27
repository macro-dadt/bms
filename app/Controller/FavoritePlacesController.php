<?php

/**
 * FavoritePlacesController
 *
 * @package    app.Controller
 * @property FavoritePlace $FavoritePlace
 */
class FavoritePlacesController extends AppController
{

    /**
     * Component
     *
     * @var array
     */
    public $components = array();

    /**
     * お気に入り登録
     *
     * @param null $placeId
     */
    public function api_add($placeId = null)
    {
        if ($this->FavoritePlace->add($this->Auth->user('id'), $placeId)) {
            $this->set(array(
                'result'     => 'success',
                '_serialize' => array('result')
            ));
        } else {
            $this->set(array(
                'error'      => '登録できませんでした',
                '_serialize' => array('error')
            ));
        }
    }

    /**
     * お気に入り解除
     *
     * @param null $placeId
     */
    public function api_delete($placeId = null)
    {
        if ($this->FavoritePlace->myDelete($this->Auth->user('id'), $placeId)) {
            $this->set(array(
                'result'     => 'success',
                '_serialize' => array('result')
            ));
        } else {
            $this->set(array(
                'error'      => '解除できませんでした',
                '_serialize' => array('error')
            ));
        }
    }

    /**
     * お気に入り施設閲覧履歴取得
     *
     * @return [type] [description]
     */
    public function api_history()
    {
        $result = $this->FavoritePlace->history($this->Auth->user('id'));

        if($result) {
            $this->set(array(
                'result'     => $result,
                '_serialize' => array('result')
            ));
        } else {
            $this->set(array(
                'error'      => '解除できませんでした',
                '_serialize' => array('error')
            ));
        }

    }


        /**
         * お気に入り施設取得（ページング）
         *
         * @return [type] [description]
         */
        public function api_index()
        {
            $page = $this->request->query('page');
            $result = $this->FavoritePlace->index($this->Auth->user('id'), $page);

            if($result) {
                $this->set(array(
                    'result'     => $result,
                    '_serialize' => array('result')
                ));
            } else {
                $this->set(array(
                    'error'      => '解除できませんでした',
                    '_serialize' => array('error')
                ));
            }

        }

}
