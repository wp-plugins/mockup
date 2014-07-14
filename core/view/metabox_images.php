<?php 

$mockup_id = get_post_meta($post->ID, '_mockup_id_1', true); 


echo '<input type="hidden" id="mockup_id" name="mockup_id" value="'.$mockup_id.'">';
wp_nonce_field('mockup_delete_image', 'mockupnonce_images');

if(!empty($mockup_id)) {

	echo '<p class="mockup_hide"><a href="#" class="set_mockup"  postid="'.$post->ID.'">'.__('Add MockUp', 'MockUp').'</a></p>';
	echo '<p class="mockup_show"><a href="#" class="delete_mockup" postid="'.$post->ID.'">'.__('Delete MockUp', 'MockUp').'</a></p>';

	echo '<div class="mockup_img">'.wp_get_attachment_image($mockup_id, array(200, 400), true).'</div>';

} else {

	echo '<p class="mockup_show"><a href="#" class="set_mockup"  postid="'.$post->ID.'">'.__('Add MockUp', 'MockUp').'</a></p>';
	echo '<p class="mockup_hide"><a href="#" class="delete_mockup" postid="'.$post->ID.'">'.__('Delete MockUp', 'MockUp').'</a></p>';

	echo '<div class="mockup_img"></div>';
}