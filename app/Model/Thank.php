<?php
/**
 * Thank
 *
 * @package    app.Model
 * @property ThanksCategory $ThanksCategory
 * @property ThanksImage $ThanksImage
 */
class Thank extends AppModel {

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
	public $belongsTo = array('ThanksCategory');

	/**
	 * HasOne
	 *
	 * @var array
	 */
	public $hasOne = array('ThanksImage');

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
	public function index()
	{
		$data = $this->find('all', array(
			'fields' => array(
				$this->alias . '.id',
				$this->alias . '.thanks_category_id',
				$this->alias . '.name',
				$this->alias . '.url',
				$this->alias . '.modified',
			),
			'contain' => array(
				'ThanksCategory' => array(
					'fields' => array(
						'ThanksCategory.name'						
					)
				)
			)
		));

		return $data;
	}

}
