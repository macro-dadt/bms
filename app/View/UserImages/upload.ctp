<?php
/**
 * @var      View $this
 */
?>
<?php
echo $this->Form->create('UserImage', array('url' => '/api/user_images/upload', 'type' => 'file'));
echo $this->Form->file('file');
echo $this->Form->end('submit');