<?php
/**
 * ThanksCategory
 *
 * @package    app.Model
 * @property Thank $Thank
 */
class ThanksCategory extends AppModel {

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
 * HasMany
 *
 * @var array
 */
	public $hasMany = array('Thank');

/**
 * HasAndBelongsToMany
 *
 * @var array
 */
	public $hasAndBelongsToMany = array();

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
