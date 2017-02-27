<?php
/**
 * ItemExchange
 *
 * @package    app.Model
 * @property User $User
 * @property Item $Item
 */
class ItemExchange extends AppModel {

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
		'Item'
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
     * アクション毎のバリデート
     *
     * @var array
     */
    protected $_validate = array(
		'item_exchange' => array(
			'user_id' => array(
				'required' => array(
                    'rule'     => 'notBlank',
                    'message'  => '未指定です',
                    'required' => true
                ),
			),
			'item_id' => array(
				'required' => array(
                    'rule'     => 'notBlank',
                    'message'  => '未指定です',
                    'required' => true
                ),
			),
		)
	);

	/**
	 * アイテム購入履歴
	 *
	 * @param  [type] $userId [description]
	 * @param  [type] $itemId [description]
	 * @return [type]         [description]
	 */
	public function add($userId, $post)
	{
		// アイテム情報取得
		$item = $this->Item->findById($post['Item']['id']);
		// ユーザ情報
		$user = $this->User->findById($userId);

		// ポイントチェック
		$diff = $user['User']['point'] - ($item['Item']['point'] * $post['Item']['num']);
		if($diff < 0) {
			return false;
		}

		// ポイント処理
		if(!$this->User->save(array(
			'id' => $userId,
			'point' => $diff
		), array('validate' => false))) {
			return false;
		}

		// 履歴に保存
		App::import('Model', 'PointHistory');
		$this->PointHistory = new PointHistory;
        $history = array(
            'PointHistory' => array(
                'user_id' => $userId,
                'action' => 'itemExchange',
                'point' => $item['Item']['point'] * -1,
				'message' => 'item_id:' . $item['Item']['id']
            )
        );
        $this->PointHistory->save($history, array('validate' => false));

		// 保存するデータを生成
		$data = array(
			'ItemExchange' => array(
				'user_id' => $userId,
				'item_id' => $post['Item']['id'],
				'count' => $post['Item']['num'],
				'point' => $item['Item']['point']
			)
		);
		// 保存
		if($this->save($data, array('validate' => false))) {
			// アイテムと会社情報を返す
			App::import('Model', 'Thank');
			$this->Thank = new Thank;
			$seller = $this->Thank->findById($item['Item']['thank_id']);

			// 追加情報
			$item['Item']['num'] = $post['Item']['num'];
			$user['User']['realname'] = $post['User']['name'];
			$user['User']['email'] = $post['User']['email'];
			$user['User']['phone'] = $post['User']['phone'];
			$user['User']['address'] = $post['User']['address'];
			$user['User']['birthday'] = $post['User']['birthday'];
			$user['User']['child_birthday'] = $post['User']['child_birthday'];

			// メールのテンプレート用に全データを返す
			return Set::merge(
				$user, $item, $seller
			);
		} else {
			return false;
		}

	}

}
