<?php
/**
 * PlaceQuestionImage
 *
 * @package    app.Model
 * @property PlaceQuestion $PlaceQuestion
 */
class PlaceQuestionImage extends AppModel {

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
	public $belongsTo = array('PlaceQuestion');

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

}
