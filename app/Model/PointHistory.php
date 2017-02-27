<?php
/**
 * PointHistory
 *
 * @package    app.Model
 * @property User $User
 */
class PointHistory extends AppModel {

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
	public $belongsTo = array('User');

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


	public function index($userId)
	{
		$data = $this->find('all', array(
			'fields' => array(
				$this->alias . '.id',
				$this->alias . '.user_id',
				$this->alias . '.action',
				$this->alias . '.type',
				$this->alias . '.point',
				$this->alias . '.message',
				$this->alias . '.modified'
			),
			'conditions' => array(
				$this->alias . '.user_id' => $userId
			)
		));

		return $data;
	}
}
