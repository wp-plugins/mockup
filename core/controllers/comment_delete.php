<?php

	$nonce = filter_input(INPUT_POST, 'mockupnonce', FILTER_UNSAFE_RAW);
	$postid = filter_input(INPUT_POST, 'postid', FILTER_SANITIZE_NUMBER_INT);
	$commentid = filter_input(INPUT_POST, 'commentid', FILTER_SANITIZE_NUMBER_INT);
	$comments = get_post_meta($postid, '_mockup_comments_1', false);

	if(empty($postid) || empty($nonce) || !wp_verify_nonce($nonce, 'mockup_delete_comment')) {

		exit();

	} else {

		delete_post_meta($postid, '_mockup_comments_1', $comments[$commentid]);
	}