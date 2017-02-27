<?php
/**
 * ItemCategory
 *
 * @package    app.Model
 * @property Item $Item
 */
class ItemCategory extends AppModel {

/**
 * Order
 *
 * @var array
 */
	public $order = array('ItemCategory.view_no');

/**
 * アソシエーション参照範囲
 *
 * @var int
 */
	public $recursive = -1;

/**
 * HasMany
 *
 * @var array
 */
	public $hasMany = array('Item');

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
