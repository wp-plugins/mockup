<?php

	$id = filter_input(INPUT_POST, 'post_id', FILTER_SANITIZE_NUMBER_INT);

	if(!empty($id)) {

		echo '<h1>'.get_option('mockup_description_title').'</h1>';
		echo '<p>'.nl2br(get_post_meta($id, '_mockup_description_1', true)).'</p>';
	}