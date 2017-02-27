<?php
/**
 * PlaceAnswer
 *
 * @package    app.Model
 * @property User $User
 * @property PlaceQuestion $PlaceQuestion
 * @property PlaceAnswerImage $PlaceAnswerImage
 */
class PlaceAnswer extends AppModel {

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
		'PlaceQuestion'
	);

	/**
	 * HasMany
	 *
	 * @var array
	 */
	public $hasOne = array('PlaceAnswerImage');

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
		'place_answer_add' => array(
			'place_question_id' => array(
				'required' => array(
                    'rule'     => 'notBlank',
                    'message'  => '未指定です',
                    'required' => true
                ),
			),
			'user_id' => array(
				'required' => array(
                    'rule'     => 'notBlank',
                    'message'  => '未指定です',
                    'required' => true
                ),
			),
			'message' => array(
				'required' => array(
                    'rule'     => 'notBlank',
                    'message'  => '未指定です',
                    'required' => true
                ),
			),
		)
	);

	/**
	 * 設備回答新規投稿
	 *
	 * @param [type] $userId [description]
	 * @param [type] $data   [description]
	 */
	public function add($userId, $data)
	{

		// 質問したユーザを取得
		$pQuestion = $this->PlaceQuestion->findById($data['PlaceAnswer']['place_question_id']);

		// 通知に追加
		App::import('Model', 'Notify');
		$this->Notify = new Notify;
		$this->Notify->save(array(
			'user_id' => $pQuestion['PlaceQuestion']['user_id'],
			'type' => 'addAnswer',
			'target' => 'place_question_id=' . $data[$this->alias]['place_question_id']
		), array(
			'validate' => false
		));

		// ユーザIDを設定する
		$data[$this->alias]['user_id'] = $userId;

		// バリデーションをセット
		$this->set($data[$this->alias]);
		// バリデーション実行
		return $this->save($data, array('_validate' => 'place_answer_add'));
	}


	/**
	 * ありがとうを送る
	 *
	 * @param  [type] $userId [description]
	 * @param  [type] $id     [description]
	 * @return [type]         [description]
	 */
	public function best($userId, $placeAnswerId)
	{
		// 回答データを取得する
		$place_answer = $this->findById($placeAnswerId);

		// すでにされてたらエラー
		if($place_answer['PlaceAnswer']['is_best']) {
			return false;
		}

		// 質問データを得る
		$place_question = $this->PlaceQuestion->findById($place_answer['PlaceAnswer']['place_question_id']);

		// 質問者ではなかったらエラー
		if($place_question['PlaceQuestion']['user_id'] !== $userId) {
			return false;
		}

		// 質問を受付終了にする
		$this->PlaceQuestion->save(array(
			'id' => $place_question['PlaceQuestion']['id'],
			'is_closed' => 1
		));

		// フラグを立てる
		return $this->save(array('id' => $placeAnswerId, 'is_best' => 1));

	}

	/**
	 * ありがとうを消す
	 *
	 * @param  [type] $userId [description]
	 * @param  [type] $id     [description]
	 * @return [type]         [description]
	 */
	public function best_delete($userId, $id)
	{
		// 回答データを取得する
		$place_answer = $this->findById($id);

		// 質問データを得る
		$place_question = $this->PlaceQuestion->findById($place_answer['PlaceAnswer']['place_question_id']);
		// 質問者ではなかったらエラー
		if($place_question['PlaceQuestion']['user_id'] !== $userId) {
			return false;
		}

		// 質問を受付可能にする
		$this->PlaceQuestion->save(array(
			'id' => $place_question['PlaceQuestion']['id'],
			'is_closed' => 0
		));

		// フラグを取る
		return $this->save(array('id' => $id, 'is_best' => 0));

	}


	/**
	 * 質問IDから回答を取得する
	 *
	 * @param  [type] $place_question_id [description]
	 * @return [type]                    [description]
	 */
	public function view($place_question_id)
	{
		$data = $this->find('all', array(
            'fields' => array(
                $this->alias . '.id',
                $this->alias . '.user_id',
                $this->alias . '.message',
				$this->alias . '.is_best',
				$this->alias . '.modified'
            ),
            'conditions' => array(
                $this->alias . '.place_question_id' => $place_question_id,
            ),
            'contain' => array(
                'PlaceAnswerImage' => array(
                    'fields' => array(
                        'PlaceAnswerImage.url'
                    )
                )
            )
        ));

        return $data;

	}

}
