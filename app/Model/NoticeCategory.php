<?php
/**
 * NoticeCategory
 *
 * @package    app.Model
 * @property Notice $Notice
 */
class NoticeCategory extends AppModel {

/**
 * Order
 *
 * @var array
 */
	public $order = array('NoticeCategory.view_no');

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
	public $hasMany = array('Notice');

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
