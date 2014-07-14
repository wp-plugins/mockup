<?php

	$nonce = filter_input(INPUT_POST, 'mockupnonce', FILTER_UNSAFE_RAW);
	$postid = filter_input(INPUT_POST, 'postid', FILTER_SANITIZE_NUMBER_INT);
	$mockup_id = filter_input(INPUT_POST, 'mockup_id', FILTER_SANITIZE_NUMBER_INT);


	if(empty($postid) || empty($mockup_id) || empty($nonce) || !wp_verify_nonce($nonce, 'mockup_delete_image')) {

		exit();

	} else {

		delete_post_meta($postid, '_mockup_id_1', $mockup_id);
	}