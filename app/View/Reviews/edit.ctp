<?php
/**
 * @var      View $this
 */
?>
<?php
echo $this->Form->create('Review', array('url' => '/api/reviews/edit/12', 'type' => 'file'));
echo $this->Form->input('Review.star');
echo $this->Form->input('Review.message');
echo $this->Form->end('submit');