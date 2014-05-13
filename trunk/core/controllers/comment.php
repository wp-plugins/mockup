<?php

	$id = filter_input(INPUT_POST, 'post_id', FILTER_SANITIZE_NUMBER_INT);
	$nonce = filter_input(INPUT_POST, 'mockupnonce', FILTER_UNSAFE_RAW);
	$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
	$text = filter_input(INPUT_POST, 'text', FILTER_SANITIZE_MAGIC_QUOTES);

	if(empty($id) || empty($nonce) || !wp_verify_nonce($nonce, 'mockup_process_comment')) {

		exit();

	} else {

		// Add comment
		$comment = array('name' => $name, 'text' => $text, 'time' => time());

		add_post_meta($id, '_mockup_comments_1', $comment);


		// Email
		$email_settings = get_post_meta($id, '_mockup_email_settings_1', true );

		if($email_settings == 'email_always' || $email_settings == 'email_comments') {

			$to = get_post_meta($id, '_mockup_email_1', true);
			if(empty($to)) $to = get_option('mockup_email');

			$subject = sprintf(__('%s made comments on MockUp %s', 'MockUp'), $name, get_the_title($id));
			$message = $text;

			wp_mail($to, $subject, $message);
		}


		// The output
		include $this->settings['dir'].'/core/view/slidebox_comment.php';
	}