<?php
/**
 * ActionCount
 *
 * @package    app.Model
 * @property User $User
 */
class ActionCount extends AppModel {

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

	/**
	 * ユーザアクション
	 *
	 * @param [type] $userId [description]
	 * @param [type] $name   [description]
	 */
	public function add($userId, $action)
	{
		$data = $this->find('first', array(
			'conditions' => array(
				$this->alias . '.user_id' => $userId,
				$this->alias . '.action' => $action
			)
		));

		if($data) {
			// 存在すればインクリメントして保存
			$data['ActionCount']['count']++;
			return $this->save(array('id' => $data['ActionCount']['id'], 'count' => $data['ActionCount']['count']));
		} else {
			// なければ追加
			return $this->save(array(
				'user_id' => $userId,
				'action' => $action,
				'adte' => date('Y-m-d'),
				'count' => 1
			));
		}
	}

}
