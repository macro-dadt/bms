<?php
/**
 * Rankings
 *
 * @package    app.Model
 */
class Ranking extends AppModel {

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
	public $recursive = 0;

	/**
	 * BelongsTo
	 *
	 * @var array
	 */
	public $belongsTo = array(
		'User'
	);

	/**
	 * HasOne
	 *
	 * @var array
	 */
	public $hasOne = array();

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
	 * 協力会社取得
	 *
	 * @return [type] [description]
	 */
	public function index($type = null)
	{
		if($type) {
			$data = $this->find('all', array(
				'fields' => array(
					$this->alias . '.id',
					$this->alias . '.type',
					$this->alias . '.user_id',
					$this->alias . '.point',
				),
				'conditions' => array(
					$this->alias . '.type' => $type
				),
				'order' => array(
					$this->alias . '.point DESC'
				),
				'contain' => array(
					'User' => array(
						'fields' => array(
							'User.name'
						)
					)
				)
			));

			return $data;

		} else {

			return false;
		}
	}

}
