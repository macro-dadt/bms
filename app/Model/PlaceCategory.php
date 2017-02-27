<?php
/**
 * PlaceCategory
 *
 * @package    app.Model
 * @property Place $Place
 */
class PlaceCategory extends AppModel {

/**
 * Order
 *
 * @var array
 */
	public $order = array('PlaceCategory.view_no');

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
