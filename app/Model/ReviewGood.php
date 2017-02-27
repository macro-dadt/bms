<?php
/**
 * ReviewGood
 *
 * @package    app.Model
 * @property User $User
 * @property Review $Review
 */
class ReviewGood extends AppModel {

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
	public $belongsTo = array();

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
	 * いいねが存在するか確認
	 * @param [type] $userId   [description]
	 * @param [type] $reviewId [description]
	 */
	public function isExists($userId, $reviewId)
	{
		$data = $this->find('first', array(
			'fields' => array(
				$this->alias . '.review_id',
				$this->alias . '.user_id'
			),
			'conditions' => array(
				$this->alias . '.review_id' => $reviewId,
				$this->alias . '.user_id' => $userId
			)
		));
		return $data;
	}

}
