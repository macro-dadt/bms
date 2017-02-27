<?php
/**
 * @var      View $this
 */
?>
<?php
echo $this->Form->create('Review', array('url' => '/api/reviews/add', 'type' => 'file'));
echo $this->Form->text('Review.place_id');
echo $this->Form->input('Review.star');
echo $this->Form->input('Review.message');
echo $this->Form->file('ReviewImage.0.file');
echo $this->Form->file('ReviewImage.1.file');
echo $this->Form->end('submit');