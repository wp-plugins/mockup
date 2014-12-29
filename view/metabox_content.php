<?php 

	$mockup_description = get_post_meta($post->ID, '_mockup_description_1', true);
	$content = $mockup_description;
	$editor_id = 'mockup_description';
	$settings = array(

		'media_buttons' => false,
		'textarea_rows' => 5,
		'teeny' => true
	);

	wp_editor($content, $editor_id, $settings);