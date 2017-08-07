<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
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
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Controller', 'Controller');
App::uses('CakeEmail', 'Network/Email');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{

    /**
     * Component
     *
     * @var array
     */
    public $components = array(
        'Auth'
    );
    public $uses = array(
        'Master'
    );


    /**
     * Before Filter
     */
    public function beforeFilter()
    {
        //exit(0);
        //$this->Cookie->time = '5 Days';

        if ($this->request->prefix == 'api') {
            if (!empty($this->data['User']['social_id'])) {
                $this->Auth->authenticate = array(
                    'Form' => array(
                        'fields' => array(
                            'username' => 'social_id',
                            'password' => 'new_password'
                        ),
                        'userModel' => 'User',
                    ),
                );

            }
            else if ((!empty($this->data['User']['email']))&&(!empty($this->data['User']['new_password'])))
            {
                $this->Auth->authenticate = array(
                    'Form' => array(
                        'fields' => array(
                            'username' => 'email',
                            'password' => 'new_password'
                        ),
                        'userModel' => 'User',
                    ),
                );
            }
            else
            {
                $this->Auth->authenticate = array(
                    'Form' => array(
                        'fields' => array(
                            'username' => 'uuid',
                            'password' => 'password'
                        ),
                        'userModel' => 'User',
                    ),
                );
            }

            // 最終利用日時更新
            if ($this->Auth->user()) {
                $User = ClassRegistry::init('User');
                $User->updateLastUsed($this->Auth->user('id'));
            }
        }
    }
    /**
     * Before Render
     */
    public function beforeRender()
    {
        parent::beforeRender();
        if ($this->request->prefix == 'api') {
            $this->viewClass = 'Json';
        }
    }
}
