<?php

/**
 * FavoritePlace
 *
 * @package    app.Model
 * @property User  $User
 * @property Place $Place
 */
class FavoritePlace extends AppModel
{

    /**
     * Order
     *
     * @var array
     */
    public $order = array();

    /**
     * アソシエーション参照範囲
     *
     * @var int
     */
    public $recursive = -1;

    /**
     * BelongsTo
     *
     * @var array
     */
    public $belongsTo = array(
        'User',
        'Place'
    );

    /**
     * Behavior
     *
     * @var array
     */
    public $actsAs = array('Containable');

    /**
     * Validate
     *
     * @var array
     */
    public $validate = array();

    /**
     * お気に入り登録
     *
     * @param $userId
     * @param $placeId
     * @return bool
     * @throws \Exception
     */
    public function add($userId, $placeId)
    {
        // 施設無し
        if (!$this->Place->findById($placeId)) {
            return false;
        }
        // お気に入り登録状況
        $hasAny = $this->hasAny(array(
            $this->alias . '.user_id'  => $userId,
            $this->alias . '.place_id' => $placeId,
        ));
        if (!$hasAny) {
            // 登録
            $this->save(array(
                'user_id' => $userId,
                'place_id' => $placeId,
            ));
            return true;
        }

        return false;
    }

    /**
     * お気に入り解除
     *
     * @param $userId
     * @param $placeId
     * @return bool
     * @throws \Exception
     */
    public function myDelete($userId, $placeId)
    {
        // 登録確認
        $data = $this->find('first', array(
            'conditions' => array(
                $this->alias . '.user_id'  => $userId,
                $this->alias . '.place_id' => $placeId,
            )
        ));
        if ($data) {
            $this->delete($data[$this->alias]['id']);
            return true;
        }

        return false;
    }

    /**
     * お気に入り閲覧履歴
     *
     * @param  [type] $userId [description]
     * @return [type]         [description]
     */
    public function history($userId) {
        $data = $this->find('all', array(
            'fields' => array(
                $this->alias . '.id',
                $this->alias . '.user_id',
                $this->alias . '.place_id',
                $this->alias . '.created',
            ),
            'contain' => array(
                'User' => array(
                    'fields' => array(
                        'User.id',
                        'User.name'
                    )
                ),
                'Place' => array(
                    'fields' => array(
                        'Place.id',
                        'Place.name'
                    )
                )
            ),
            'order' => array($this->alias . '.created DESC'),
            'limit' => 20
        ));

        return $data;
    }

    /**
     * お気に入り履歴（ページング）
     *
     * @param  [type] $userId [description]
     * @return [type]         [description]
     */
    public function index($userId, $page = 1) {

        $data = $this->find('all', array(
            'fields' => array(
                $this->alias . '.id',
                $this->alias . '.user_id',
                $this->alias . '.place_id',
                $this->alias . '.created',
            ),
            'contain' => array(
                'User' => array(
                    'fields' => array(
                        'User.id',
                        'User.name'
                    )
                ),
                'Place' => array(
                    'fields' => array(
                        'Place.id',
                        'Place.name'
                    )
                )
            ),
            'order' => array($this->alias . '.created DESC'),
            'limit' => 20,
            'page' => $page
        ));

        return $data;
    }

}
