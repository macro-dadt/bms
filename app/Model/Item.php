<?php
/**
 * Item
 *
 * @package    app.Model
 * @property ItemCategory $ItemCategory
 * @property ItemImage $ItemImage
 * @property ItemExchange $ItemExchange
 */
class Item extends AppModel {

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
	public $belongsTo = array('ItemCategory');

	/**
	 * HasMany
	 *
	 * @var array
	 */
	public $hasMany = array(
		'ItemImage',
		'ItemExchange'
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
	 * アイテム取得
	 *
	 * @param  [type] $userId [description]
	 * @return [type]         [description]
	 */
	public function index($userId)
	{
		$data = $this->find('all', array(
			'fields' => array(
				$this->alias . '.id',
				$this->alias . '.item_category_id',
				$this->alias . '.name',
				$this->alias . '.description',
				$this->alias . '.point',
				$this->alias . '.modified'
			),
			'contain' => array(
				'ItemCategory' => array(
					'fields' => array(
						'ItemCategory.name',
						'ItemCategory.view_no',
						'ItemCategory.is_view',
						'ItemCategory.modified'
					)
				) ,
				'ItemImage' => array(
					'fields' => array(
						'ItemImage.item_id',
						'ItemImage.url',
						'ItemImage.modified'
					)
				)
			)
		));

		return $data;
	}

	/**
	 * アイテム詳細
	 *
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function view($id)
	{
		return $this->findById($id);
	}

	/**
	 * アイテム交換申請
	 *
	 * @param  [type] $userId [description]
	 * @param  [type] $itemId [description]
	 * @return [type]         [description]
	 */
	public function exchange($userId, $data)
	{
		// ユーザIDを設定する
		$data['ItemExchange']['user_id'] = $userId;
		$data['ItemExchange']['datetime'] = date('Y-m-d H:i:s');

		// バリデーションをセット
		$this->set($data['ItemExchange']);
		// バリデーション実行
		return $this->ItemExchange->save($data, array('_validate' => 'item_exchange'));

	}

}
