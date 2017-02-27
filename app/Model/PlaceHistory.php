<?php
/**
 * PlaceHistory
 *
 * @package    app.Model
 * @property User $User
 * @property Place $Place
 */
class PlaceHistory extends AppModel {

	/**
	 * Order
	 *
	 * @var array
	 */
	public $order = array('PlaceHistory.created' => 'desc');

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

	/**
	 * 施設閲覧履歴を保存する
	 * 
	 * @param [type] $userId  [description]
	 * @param [type] $placeId [description]
	 */
	public function add($userId, $placeId)
	{
		$data[$this->alias]['user_id'] = $userId;
		$data[$this->alias]['place_id'] = $placeId;

		return $this->save($data, array('validate' => false));
	}
}
