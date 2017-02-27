<?php
/**
 * @var      View $this
 */
?>
<?php
echo $this->Form->create('Place', array('url' => '/api/places/add', 'type' => 'file'));
echo $this->Form->input('Place.place_category_id', array('options' => array('1' => '公園', '2' => 'レジャー')));
echo $this->Form->input('Place.name');
echo $this->Form->input('Place.floor');
echo $this->Form->input('Place.milk_seat');
echo $this->Form->input('Place.milk_baby_car');
echo $this->Form->input('Place.milk_papa');
echo $this->Form->input('Place.milk_hot_water');
echo $this->Form->input('Place.milk_private_room');
echo $this->Form->input('Place.nappy_seat');
echo $this->Form->input('Place.nappy_dust_box');
echo $this->Form->input('Place.nappy_dust_bag');
echo $this->Form->input('Place.nappy_papa');
echo $this->Form->input('Place.toilet_seat');
echo $this->Form->input('Place.toilet_boy');
echo $this->Form->input('Place.toilet_girl');
echo $this->Form->input('Place.cond_child_chair');
echo $this->Form->input('Place.cond_baby_chair');
echo $this->Form->input('Place.cond_baby_car');
echo $this->Form->input('Place.cond_no_smoke');
echo $this->Form->input('Place.cond_store');
echo $this->Form->input('Place.cond_parking');
echo $this->Form->input('Place.cond_tatami');
echo $this->Form->input('Place.cond_indoor');
echo $this->Form->input('Place.cond_outdoor');
echo $this->Form->input('Place.cond_one_year_old_over');
echo $this->Form->input('Place.cond_one_year_old_under');
echo $this->Form->input('Place.cond_day_care');
echo $this->Form->input('Place.cond_kids_space');
echo $this->Form->input('Place.lat');
echo $this->Form->input('Place.lon');
echo $this->Form->input('Review.star');
echo $this->Form->input('Review.message');
echo $this->Form->file('ReviewImage.0.file');
echo $this->Form->file('ReviewImage.1.file');
echo $this->Form->end('submit');