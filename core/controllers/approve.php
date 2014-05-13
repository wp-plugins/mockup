<?php

	$id = filter_input(INPUT_POST, 'post_id', FILTER_SANITIZE_NUMBER_INT);
	$nonce = filter_input(INPUT_POST, 'mockupnonce', FILTER_UNSAFE_RAW);
	$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);

	if(empty($id) || empty($nonce) || !wp_verify_nonce($nonce, 'mockup_process_approve')) {

		exit();

	} else {

		$status = array('approved' => true, 'approved_name' => $name, 'approved_time' => time());

		update_post_meta($id, '_mockup_status_1', $status);

		// Email
		$email = get_post_meta($id, '_mockup_email_settings_1', true);

		if($email == 'email_always' || $email == 'email_approved') {

			$to = get_post_meta($id, '_mockup_email_1', true);
			if(empty($to)) $to = get_option('mockup_email');

			$subject = sprintf(__('Mockup %s is approved', 'MockUp'), get_the_title($id));
			$message = sprintf(__('Your MockUp %s has been approved by %s', 'MockUp'), get_the_title($id), $name);

			wp_mail($to, $subject, $message);
		}

		echo '<h1>'.get_option('mockup_approve_title').'</h1>';
		echo '<p>'.get_option('mockup_approved_text').'</p>';
	}