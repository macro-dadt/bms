<?php
/**
 * PlaceQuestion
 *
 * @package    app.Model
 * @property PlaceQuestionImage $PlaceQuestionImage
 * @property PlaceAnswer $PlaceAnswer
 */
class PlaceQuestion extends AppModel {

	/**
	 * Order
	 *
	 * @var array
	 */
	public $order = array('PlaceQuestion.created' => 'desc');

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
	public $belongsTo = array('Place');

	/**
	 * HasOne
	 *
	 * @var array
	 */
	public $hasOne = array('PlaceQuestionImage');

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
		'place_question_add' => array(
			'place_id' => array(
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
	 * 設備質問新規投稿
	 *
	 * @param [type] $userId [description]
	 * @param [type] $data   [description]
	 */
	public function add($userId, $data)
	{
		// ユーザIDを設定する
		$data[$this->alias]['user_id'] = $userId;

		// バリデーションをセット
		$this->set($data[$this->alias]);
		// バリデーション実行
		return $this->save($data, array('_validate' => 'place_question_add'));
	}

	/**
	 * 質問一覧取得
	 *
	 * @param  [type] $placeId [description]
	 * @return [type]          [description]
	 */
	public function index($placeId)
	{
		$data = $this->find('all', array(
            'fields' => array(
                $this->alias . '.id',
                $this->alias . '.place_id',
                $this->alias . '.user_id',
                $this->alias . '.message',
				$this->alias . '.is_closed',
				$this->alias . '.modified'
            ),
            'conditions' => array(
                $this->alias . '.place_id' => $placeId,
            ),
            'contain' => array(
                'PlaceQuestionImage' => array(
                    'fields' => array(
                        'PlaceQuestionImage.url'
                    )
                )
            )
        ));

        return $data;
	}

}
