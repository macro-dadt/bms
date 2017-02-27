<?php
/**
 * Message
 *
 * @package    app.Model
 * @property User $User
 */
class Message extends AppModel {

	/**
	 * Order
	 *
	 * @var array
	 */
	public $order = array('Message.created' => 'desc');

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
	 * メッセージ取得
	 * 
	 * @param  [type] $userId [description]
	 * @return [type]         [description]
	 */
	public function index($userId)
	{
		$data = $this->find('all', array(
			'fields' => array(
				$this->alias . '.id',
				$this->alias . '.user_id',
				$this->alias . '.message',
				$this->alias . '.type',
				$this->alias . '.value',
				$this->alias . '.created'
			),
			'conditions' => array(
				$this->alias . '.user_id' => $userId
			)
		));

		return $data;
	}

}
