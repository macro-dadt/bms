<?php
/**
 * PlacesController
 *
 * @package    app.Controller
 * @property Place $Place
 */
class PlacesController extends AppController
{
    public function beforeFilter()
    {
        $this->Auth->allow('api_admin_edit_data','api_admin_add','api_images','api_view_full','api_view','api_search','api_add_nursing_room','api_set_busy','api_edit_data','api_update_nursing_room','api_add');
    }

    /**
     * Component
     *
     * @var array
     */
    public $components = array();

    /**
     * 周辺施設の検索
     */
    public function api_search()
    {
        $lat = $this->request->query('lat'); // 緯度
        $lon = $this->request->query('lon'); // 経度
        $places = $this->Place->search($lat, $lon);
        if ($places === false) {
            $this->set(array(
                'error'      => '緯度・経度の指定が正しくありません',
                '_serialize' => array('error')
            ));
        } else {
            $this->set(array(
                'places'     => $places,
                '_serialize' => array('places')
            ));
        }
    }
    public function api_search_nursing_room()
    {
        $lat = $this->request->query('lat'); // 緯度
        $lon = $this->request->query('lon'); // 経度
        $places = $this->Place->search_nursing_room($lat, $lon);
        if ($places === false) {
            $this->set(array(
                'error'      => '緯度・経度の指定が正しくありません',
                '_serialize' => array('error')
            ));
        } else {
            $this->set(array(
                'places'     => $places,
                '_serialize' => array('places')
            ));
        }
    }
    public function api_get_all_spot()
    {
        $places = $this->Place->getAllSpot();
        if ($places === false) {
            $this->set(array(
                'error'      => '緯度・経度の指定が正しくありません',
                '_serialize' => array('error')
            ));
        } else {
            $this->set(array(
                'places'     => $places,
                '_serialize' => array('places')
            ));
        }
    }
    /**
     * 施設新規投稿
     */
    public function api_admin_add()
    {
        if ($this->Place->admin_add($this->request->data)) {
            $this->set(array(
                'result'     => $this->Place->getLastInsertId(),
                '_serialize' => array('result')
            ));
        } else {
            $this->set(array(
                'errors'     => array_merge($this->Place->validationErrors),
                '_serialize' => array('errors')
            ));
        }
    }
    public function api_add()
    {
        if ($this->Place->add($this->Auth->user('id'), $this->request->data)) {
            $this->set(array(
                'result'     => 'success',
                '_serialize' => array('result')
            ));
        } else {
            $this->set(array(
                'errors'     => array_merge($this->Place->validationErrors),
                '_serialize' => array('errors')
            ));
        }
    }

    // add nursing room, just for admin, return babymap_place_id
    public function api_add_nursing_room()
    {
        if ($this->Place->add_nursing($this->request->data)) {
            $this->set(array(
                'result'   => $this->Place->getLastInsertId(),
                '_serialize' => array('result')
            ));
        } else {
            $this->set(array(
                'errors'     => array_merge($this->Place->validationErrors),
                '_serialize' => array('errors')
            ));
        }
    }





    /**
     * 施設変更元データ取得
     *
     * @param null $id
     */
    public function api_edit_data($id = null)
    {
        if ($edit = $this->Place->editData($this->Auth->user('id'), $id)) {
            $this->set(array(
                'edit'       => 'success',
                '_serialize' => array('edit')
            ));
        } else {
            $this->set(array(
                'error'      => '取得できませんでした',
                '_serialize' => array('error')
            ));
        }
    }
    public function api_admin_edit_data()
    {
        $id = $this->request->query('id');
        $data = $this->request->data;
        if ($edit = $this->Place->adminEditData($id,$data)) {
            $this->set(array(
                'edit'       => 'ahihi',
                '_serialize' => array('edit')
            ));
        } else {
            $this->set(array(
                'error'      => '取得できませんでした',
                '_serialize' => array('error')
            ));
        }
    }




    /**
     * 施設の画像一覧
     *
     * @param null $id
     */
    public function api_images($id = null)
    {
        $images = $this->Place->getImages($id);
        if ($images !== false) {
            $this->set(array(
                'images'     => $images,
                '_serialize' => array('images')
            ));
        } else {
            $this->set(array(
                'error'      => '取得できませんでした',
                '_serialize' => array('error')
            ));
        }
    }

    /**
     * 施設情報詳細
     *
     * @return [type] [description]
     */
    public function api_view()
    {
        // 施設ID
        $id = $this->request->query('id');

//        // 履歴保存
//        App::import("Controller", "PlaceHistories");
//        $PlaceHistories = new PlaceHistoriesController;
//        if (!empty($this->Auth->user('id'))){
//            $PlaceHistories->add($this->Auth->user('id'), $id);
//        }


        $place = $this->Place->view($id);

        if ($place === false) {
            $this->set(array(
                'error'      => 'エラー',
                '_serialize' => array('error')
            ));
        } else {
            $this->set(array(
                'place'     => $place,
                '_serialize' => array('place')
            ));
        }

    }

    /**
     * 施設情報詳細
     *
     * @return [type] [description]
     */
    public function api_view_full()
    {
        // 施設ID
        $id = $this->request->query('id');

        // 履歴保存
        App::import("Controller", "PlaceHistories");
        $PlaceHistories = new PlaceHistoriesController;
        $PlaceHistories->add($this->Auth->user('id'), $id);

        $place = $this->Place->viewFull($id);

        if ($place === false) {
            $this->set(array(
                'error'      => 'エラー',
                '_serialize' => array('error')
            ));
        } else {
            $this->set(array(
                'place'     => $place,
                '_serialize' => array('place')
            ));
        }

    }


    /**
     * 施設削除フラグ操作
     *
     * @return [type] [description]
     */
    public function api_delete()
    {
        // 対象の施設ID
        $place_id = $this->request->data('place_id');
        // フラグの状態
        $is_closed =   $this->request->data('is_closed');

        // フラグ変更
        $result = $this->Place->setDelete($place_id, $is_closed);

        if ($result) {
            $this->set(array(
                'result'     => 'success',
                '_serialize' => array('result')
            ));
        } else {
            $this->set(array(
                'error'      => '変更出来ませんでした',
                '_serialize' => array('error')
            ));
        }

    }

    ///new api

    public function api_set_busy()
    {
        // 対象の施設ID
        $place_id = $this->request->query('id');
        // フラグの状態
        $is_busy =   $this->request->query('is_busy');

        // フラグ変更
        $result = $this->Place->setBusy($place_id, $is_busy);

        if ($result) {
            $this->set(array(
                'result'     => 'success',
                '_serialize' => array('result')
            ));
        } else {
            $this->set(array(
                'error'      => '変更出来ませんでした',
                '_serialize' => array('error')
            ));
        }

    }

    /**
     * 施設変更
     *
     * @param null $id
     */
    public function api_edit($id = null)
    {
        if ($this->Place->edit($this->Auth->user('id'), $id, $this->request->data)) {
            $this->set(array(
                'result'     => 'success',
                '_serialize' => array('result')
            ));
        } else {
            $this->set(array(
                'errors'     => array_merge($this->Place->validationErrors),
                '_serialize' => array('errors')
            ));
        }
    }
    public function api_update_nursing_room($id = null)
    {
        if ($this->Place->updateNursingRoom($id,$this->request->data)) {
            $this->set(array(
                'result'     => 'success',
                '_serialize' => array('result')
            ));
        } else {
            $this->set(array(
                'errors'     => array_merge($this->Place->validationErrors),
                '_serialize' => array('errors')
            ));
        }
    }
}
