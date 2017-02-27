<?php
/**
 * Application model for CakePHP.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class AppModel extends Model
{

    /**
     * アクション別バリデート
     *
     * @var array
     */
    protected $_validate = array();

    /**
     * sql デバグ（ログファイル）
     * @return [type] [description]
     */
    public function sql(){
      $sql = $this->getDataSource()->getLog();
      $this->log($sql);
      return $sql;
    }

    /**
     * バリデートの切り替え
     *
     * @param array $options
     * @return bool
     */
    public function beforeValidate($options = array())
    {
        if (isset($options['_validate']) && isset($this->_validate[$options['_validate']])) {
            $this->validate = $this->_validate[$options['_validate']];
        }

        return true;
    }

}
