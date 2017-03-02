<?php

/**
 * UsersController
 *
 * @package    app.Controller
 * @property User $User
 */
class UsersController extends AppController
{

    /**
     * Before Filter
     */
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow('api_generate');


        parent::new_beforeFilter();
        $this->Auth->allow('api_new_generate');
    }

    /**
     * 新規ユーザーの生成
     *generate new user
     * @return mixed
     */
    public function api_new_generate()
    {
        if ($this->User->new_generate($this->request->data('User.email'), $this->request->data('User.social_id'), $this->request->data('User.password'))) {
            // 登録が完了したらログインする
            $this->setAction('api_login');
        } else {
            $this->set(array(
                'errors'     => $this->User->validationErrors,
                '_serialize' => array('errors')
            ));
        }
    }
    public function api_generate()
    {
        if ($this->User->generate($this->request->data('User.uuid'), $this->request->data('User.password'))) {
            // 登録が完了したらログインする
            $this->setAction('api_login');
        } else {
            $this->set(array(
                'errors'     => $this->User->validationErrors,
                '_serialize' => array('errors')
            ));
        }
    }

    /**
     * ログイン
     */
    public function api_login()
    {
        if ($this->Auth->login()) {
            $this->set('result', 'success');
            $this->set('_serialize', array('result'));
        } else {
            throw new ForbiddenException;
        }
    }
    public function api_logout()
    {
        if($this->Auth->logout()) {
            $this->set('result', 'success');
            $this->set('_serialize', array('result'));
        } else {
            throw new ForbiddenException;
        }
    }

    /**
     * ニックネーム登録
     */
    public function api_save_nickname()
    {
        if ($this->User->saveNickname($this->request->data('User.name'))) {
            $this->set(array(
                'result'     => 'success',
                '_serialize' => array('result')
            ));
        } else {
            $this->set(array(
                'errors'     => $this->User->validationErrors,
                '_serialize' => array('errors')
            ));
        }
    }

    /**
     * ユーザー情報変更バリデードのみ
     */
    public function api_validate()
    {
        if ($this->User->edit($this->Auth->user('id'), $this->request->data, true)) {
            $this->set(array(
                'result'     => 'success',
                '_serialize' => array('result')
            ));
        } else {
            $this->set(array(
                'errors'     => array_merge($this->User->validationErrors),
                '_serialize' => array('errors')
            ));
        }
    }

    /**
     * ユーザー情報変更
     */
    public function api_edit()
    {
        if ($this->User->edit($this->Auth->user('id'), $this->request->data)) {
            $this->set(array(
                'result'     => 'success',
                '_serialize' => array('result')
            ));
        } else {
            $this->set(array(
                'errors'     => array_merge($this->User->validationErrors),
                '_serialize' => array('errors')
            ));
        }
    }

    /**
     * ユーザー情報
     */
    public function api_view()
    {
        if($this->request->query('id')) {
            $user_id = $this->request->query('id');
        } else {
            $user_id = $this->Auth->user('id');
        }

        $user = $this->User->view($user_id);

        $this->set(array(
            'user'       => $user,
            '_serialize' => array('user')
        ));
    }

    public function api_new_view()
    {
        if($this->request->query('email')) {
            $user_email = $this->request->query('email');
        } else {
            $user_email = $this->Auth->user('email');
        }

        $user = $this->User->new_view($user_email);

        $this->set(array(
            'user'       => $user,
            '_serialize' => array('user')
        ));
    }
    /**
     * お気に入りを非公開にする
     *
     * @return [type] [description]
     */
    public function api_close_favorite()
    {
        if($this->User->closeFavorite($this->Auth->user('id'))) {
            $this->set(array(
                'result'     => 'success',
                '_serialize' => array('result')
            ));
        } else {
            $this->set(array(
                'errors'     => '変更できませんでした',
                '_serialize' => array('errors')
            ));
        }
    }

    /**
     * お気に入り非公開を解除
     *
     * @return [type] [description]
     */
    public function api_open_favorite()
    {
        if($this->User->openFavorite($this->Auth->user('id'))) {
            $this->set(array(
                'result'     => 'success',
                '_serialize' => array('result')
            ));
        } else {
            $this->set(array(
                'errors'     => '変更できませんでした',
                '_serialize' => array('errors')
            ));
        }
    }

    public function api_point()
    {
        // マスターデータのpointを取得
        $master = $this->Master->get('point');
        $type = $this->request->query('type');

        if($this->User->point($this->Auth->user('id'), $type, $master[$type]))
        {
            $this->set(array(
                'result'     => 'success',
                '_serialize' => array('result')
            ));
        } else {
            $this->set(array(
                'errors'     => '変更できませんでした',
                '_serialize' => array('errors')
            ));
        }

    }

    /**
     * 機種変申請
     *
     * @return [type] [description]
     */
    public function api_dc_request()
    {
        $email = $this->request->data('User.email');
        $user = $this->User->deviceChangeRequest($this->Auth->user('id'), $email);

        if($user) {

            // メール送信
            $Email = new CakeEmail();
            $result = $Email->viewVars($user)
                ->template('dc/user', 'default')
                ->emailFormat('text')
                ->to($email)
                ->from('support@trim-inc.com')
                ->subject('【Baby map】機種変更手続き完了のお知らせ')
                ->send();

            if($result) {
                $this->set(array(
                    'result'     => 'success',
                    '_serialize' => array('result')
                ));
            } else {
                $this->set(array(
                    'errors'     => '送信できませんでした',
                    '_serialize' => array('errors')
                ));
            }
        } else {
            $this->set(array(
                'errors'     => '変更できませんでした',
                '_serialize' => array('errors')
            ));
        }
    }

    /**
     * 機種変完了
     *
     */
    public function api_dc_restore()
    {
        $email = $this->request->data('User.email');
        $old = $this->User->deviceChangeRestore($email);

        if($old)
        {
            if(!$this->Auth->logout()) {
                $this->set(array(
                    'errors'     => 'ログアウトできませんでした',
                    '_serialize' => array('errors')
                ));
            }

            // ログインし直す
            $this->request->data = array(
                'User' => array(
                    'uuid' => $old['User']['uuid'],
                    'password' => 'trim1234babymap'
                )
            );
            if($this->Auth->login()) {
                $this->set(array(
                    'result'     => 'success',
                    '_serialize' => array('result')
                ));
            } else {
                throw new ForbiddenException;
            }

        } else {
            $this->set(array(
                'errors'     => '変更できませんでした',
                '_serialize' => array('errors')
            ));
        }
    }

    /**
     * ユーザ情報取得
     *
     * @return [type] [description]
     */
    public function api_info()
    {
        $user = $this->Auth->user();
        if($user) {
            $this->set(array(
                'result'     => $user,
                '_serialize' => array('result')
            ));
        } else {
            $this->set(array(
                'errors'     => 'ログインしていません',
                '_serialize' => array('errors')
            ));
        }
    }

    /**
     * 特定のユーザがポストした施設を取得
     * @return [type] [description]
     */
    public function api_place()
    {
        if($this->request->query('user_id')) {
            $user_id = $this->request->query('user_id');
        } else {
            $user_id = $this->Auth->user('id');
        }

        $result = $this->User->place($user_id);
        if($result) {
            $this->set(array(
                'result'     => $result,
                '_serialize' => array('result')
            ));
        } else {
            $this->set(array(
                'errors'     => '取得できませんでした',
                '_serialize' => array('errors')
            ));
        }
    }

    /**
     * 特定のユーザがポストしたレビューを取得
     * @return [type] [description]
     */
    public function api_review()
    {
        if($this->request->query('user_id')) {
            $user_id = $this->request->query('user_id');
        } else {
            $user_id = $this->Auth->user('id');
        }

        $result = $this->User->review($user_id);
        if($result) {
            $this->set(array(
                'result'     => $result,
                '_serialize' => array('result')
            ));
        } else {
            $this->set(array(
                'errors'     => '取得できませんでした',
                '_serialize' => array('errors')
            ));
        }
    }

    /**
     * ユーザへの通知を取得
     *
     * @return [type] [description]
     */
    public function api_notify()
    {
        $type = $this->request->query('type');
        $result = $this->User->notify($this->Auth->user('id'), $type);
        if($result) {
            $this->set(array(
                'result'     => $result,
                '_serialize' => array('result')
            ));
        } else {
            $this->set(array(
                'errors'     => '通知はありませんでした',
                '_serialize' => array('errors')
            ));
        }

    }

    /**
     * 通知をID指定で削除
     *
     * @return [type] [description]
     */
    public function api_delete_notify()
    {
        $notify_id = $this->request->data('notify_id');
        $result = $this->User->deleteNotify($notify_id);
        if($result) {
            $this->set(array(
                'result'     => 'success',
                '_serialize' => array('result')
            ));
        } else {
            $this->set(array(
                'errors'     => '削除出来ませんでした',
                '_serialize' => array('errors')
            ));
        }

    }

    /**
     * ユーザが登録しているかどうか
     *
     * @return [type] [description]
     */
    public function api_registered()
    {
        $result = $this->User->isRegistered($this->Auth->user('id'));
        if($result) {
            $this->set(array(
                'result'     => 1,
                '_serialize' => array('result')
            ));
        } else {
            $this->set(array(
                'result'     => 0,
                '_serialize' => array('result')
            ));
        }
    }
}
