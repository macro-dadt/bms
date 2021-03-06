<?php
//App::uses('APNSComponent', 'Controller/Component');
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
        $this->Auth->allow('api_view','api_send_notification_to_all_FCM','api_send_notification_to_one_FCM','api_send_notification_to_all','api_send_notification_to_one','api_generate','api_new_generate','api_change_password','api_recovery_password','api_recovery_code_true','api_registered','api_new_password_true','api_change_email');
    }


    public function api_new_generate()
    {
        if ($this->User->new_generate($this->request->data('User.email'), $this->request->data('User.social_id'), $this->request->data('User.name'), $this->request->data('User.new_password'))) {
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
     * 新規ユーザーの生成
     *generate new user
     * @return mixed
     */
    public function api_generate()
    {

        if ($this->User->new_generate($this->request->data('User.email'), $this->request->data('User.social_id'), $this->request->data('User.name'), $this->request->data('User.new_password'))) {
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
        $this->setAction('api_login');
        if($this->Auth->logout()) {
            //session_destroy ();
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
    public function api_getPremium(){
        if ($this->User->getPremium($this->request->query('day'))) {
            $this->set(array(
                'result'     => "success",
                '_serialize' => array('result')
            ));
        } else {
            $this->set(array(
                'errors'     => $this->User->validationErrors,
                '_serialize' => array('errors')
            ));
        }
    }
    public function api_checkIsExpired(){
        if ($this->User->checkIsExpired($this->request->query('id'))) {
            $this->set(array(
                'result'     => "success",
                '_serialize' => array('result')
            ));
        } else {
            $this->set(array(
                'errors'     => $this->User->validationErrors,
                '_serialize' => array('errors')
            ));
        }
    }
    public function api_getPointBack(){
        if ($this->User->getPointBack($this->request->query('id'),$this->request->query('point') )) {
            $this->set(array(
                'result'     => "success",
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
     * not use
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
        }
        else {
            $user_email = $this->Auth->user('email');
        }
        if($this->request->query('social_id')) {
            $user_social_id = $this->request->query('social_id');
        }
        else {
            $user_social_id = $this->Auth->user('social_id');
        }
        $user = $this->User->new_view($user_email,$user_social_id);
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
        return true;
    }

    public function api_recovery_password()
    {

             $email = $this->request->data('User.email');
             $user = $this->User->recoverPasswordRequest($email);
             if($user) {

                 // メール送信
                 $Email = new CakeEmail();
                 $result = $Email->viewVars($user)
                     ->template('recovery/user', 'default')
                     ->emailFormat('text')
                     ->to($email)
                     ->from('support@trim-inc.com')
                     ->subject('【Baby map】パスワードリカバリのお知らせ')
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
    public function api_change_password(){
        $new_password = $this->request->data('User.new_password');
        $email = $this->request->data('User.email');
        if ($this->User->change_password($email,$new_password)){
            $this->set(array(
                'result'     => 'success',
                '_serialize' => array('result')
            ));
        }
        else {
            $this->set(array(
                'errors'     => 'パスワード変更できません',
                '_serialize' => array('errors')
            ));
        }
    }
    public function api_change_email(){
        $email = $this->request->data('User.email');
        if ($this->User->change_email($email)){
            $this->set(array(
                'result'     => 'success',
                '_serialize' => array('result')
            ));
        }
        else {
            $this->set(array(
                'errors'     => 'パスワード変更できません',
                '_serialize' => array('errors')
            ));
        }
    }
    public function api_send_token(){
        $id = $this->request->data('User.id');
        $token = $this->request->data('User.token');
        if ($this->User->send_token($id,$token)){
            $this->set(array(
                'result'     => 'success',
                '_serialize' => array('result')
            ));
        }
        else {
            $this->set(array(
                'errors'     => 'cannot send a token',
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
        if($this->request->query('email')) {
            $user_email = $this->request->query('email');
        }
        else {
            $user_email = $this->Auth->user('email');
        }
        if($this->request->query('social_id')) {
            $user_social_id = $this->request->query('social_id');
        }
        else {
            $user_social_id = $this->Auth->user('social_id');
        }
        $result = $this->User->isRegistered($user_email, $user_social_id );
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

    public function api_recovery_code_true()

    {
        $user_recovery_code = $this->request->query('recovery_code');
        $user_email = $this->request->query('email');
        $result = $this->User->recoveryCodeTrue($user_email, $user_recovery_code);
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

    public function api_new_password_true()

    {
        $user_new_password = $this->request->query('new_password');
        $user_email = $this->request->query('email');
        $result = $this->User->passwordTrue($user_email, $user_new_password);
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
    public function api_send_notification_to_one()
    {

        $userId = $this->request->data('User.id');
        $message = $this->request->data('User.message');

        $data = $this->request->data('User.data');
        $token = $this->User->getToken($userId);
        if ($this->User->sendPushMessage($token,$message,$data)){
            $this->set(array(
                'result'     => 'success',
                '_serialize' => array('result')
            ));
        }
        else {
            $this->set(array(
                'result'     => 'failed',
                '_serialize' => array('result')
            ));
        }
    }
    public function api_send_notification_to_all()
    {
        $message = $this->request->query('message');
        $tokenArr = $this->User->getAllToken();
        $data = $this->request->query('data');

        foreach ($tokenArr as $token) {
            $this->User->sendPushMessage($token,$message,$data);
        }
    }
    public function api_send_notification_to_one_FCM()
    {
        $data = $this->request->data;
        echo $data;
        $message = $this->request->query('message');
        $userId = $this->request->query('id');
        $token = $this->User->getToken($userId);
        $this->set(array(
            'result'     => 'success',
            '_serialize' => array('result')
        ));
        if ($this->User->sendFCMMessage($data,$token,$message)){
            $this->set(array(
                'result'     => 'success',
                '_serialize' => array('result')
            ));
        }
    }
    public function api_send_notification_to_all_FCM()
    {
        $message = $this->request->query('message');
        $tokenArr = $this->User->getAllToken();
        $data = $this->request->data;
        if ($this->User->sendFCMMessage($data,$tokenArr,$message)){
            $this->set(array(
                'result'     => 'success',
                '_serialize' => array('result')
            ));
        }
    }

}
