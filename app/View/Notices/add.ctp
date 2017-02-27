<?php
/**
 * @var      View $this
 */
?>
<?php
echo $this->Form->create('Notice', array('url' => '/api/notices/add', 'type' => 'post'));
echo $this->Form->input('Notice.notice_category_id', array('options' => array('1' => 'その他')));
echo $this->Form->text('Notice.place_id');
echo $this->Form->input('Notice.message');
echo $this->Form->end('submit');