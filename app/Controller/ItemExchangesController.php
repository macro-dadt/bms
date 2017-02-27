<?php

/**
 * ItemExchangesController
 *
 * @package    app.Controller
 * @property Item ＄Item
 */
class ItemExchangesController extends AppController
{

    /**
     * Component
     *
     * @var array
     */
    public $components = array();


    public function api_request()
    {

        // ポイントチェックなど
        $result = $this->ItemExchange->add($this->Auth->user('id'), $this->request->data);
        if($result)
        {
            $Email = new CakeEmail();
            // ユーザ宛
            $Email->viewVars($result)
                ->template('item/user', 'default')
                ->emailFormat('text')
                ->to($result['User']['email'])
                ->from('support@trim-inc.com')
                ->subject('【Baby map】商品の注文について')
                ->send();
            // 企業宛
            $Email->viewVars($result)
                ->template('item/client', 'default')
                ->emailFormat('text')
                ->to(array($result['Thank']['email']))
                ->from('support@trim-inc.com')
                ->subject('【Baby map】「' . $result['Item']['name'] . '」の発送をお願い致します')
                ->send();
            // Trim宛
            $Email->viewVars($result)
                ->template('item/trim', 'default')
                ->emailFormat('text')
                ->to(array('trimtrim1102@gmail.com'))
                ->from('support@trim-inc.com')
                ->subject('【Baby map】ユーザーが商品を注文しました')
                ->send();

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

    }
}
