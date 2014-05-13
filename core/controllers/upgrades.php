<?php

	function mockup_update_107($id) {

		// Clean the titles
		$remove = array('&#10004;', '✔', '? ');
		$title = str_replace($remove, '', get_the_title());
		$title = trim($title);

		// Update the post
		$args = array(
			'ID' => $id,
			'post_title' => $title
		);

		wp_update_post($args);
	}

	function mockup_update_111($id) {

		// The content
		$value = get_the_content();
		if(empty($value)) $value = get_post_meta($id, 'mockup_description', true);
		if(!empty($value)) update_post_meta($id, '_mockup_description_1', $value);

		delete_post_meta($id, 'mockup_description');


		// The MockUp Image
		$value = get_post_thumbnail_id($id);
		if(!empty($value)) {
			update_post_meta($id, '_mockup_id_1', $value);
		}


		// The comments
		$remark_count = get_post_meta($id, 'mockup_remark_count_1', true);

		if(!empty($remark_count)) {

			$i = $remark_count;
			$name = get_post_meta($id, 'mockup_remark_name_1', false);
			$dates = get_post_meta($id, 'mockup_remark_date_1', false);
			$text = get_post_meta($id, 'mockup_remark_text_1', false);

			while ($i > 0) {

				$i--;

				// Update the date of comments made in older versions.
				if(strtotime($dates[$i])) $dates[$i] = strtotime($dates[$i]);

				// Add to the new metaboxe
				$comment = array('name' => $name[$i], 'text' => $text[$i], 'time' => $dates[$i]);
				add_post_meta($id, '_mockup_comments_1', $comment);
			}

			// Delete the old metaboxes
			// I had some unknow problems with the update of the comments. So to be sure I will not delete the old post meta. Next update it will be clean. :)
			#delete_post_meta($id, 'mockup_remark_count_1');
			#delete_post_meta($id, 'mockup_remark_name_1');
			#delete_post_meta($id, 'mockup_remark_date_1');
			#delete_post_meta($id, 'mockup_remark_text_1');
		}


		// The background position
		$value = 'center top';
		update_post_meta($id, '_mockup_position_1', $value);


		// The status
		$status = get_post_meta($id, 'mockup_status', true);
		$name = get_post_meta($id, 'mockup_approve_name', true);
		$time = get_post_meta($id, 'mockup_approved_time', true);

		if(!empty($status)) {

			$value = array('approved' => true, 'approved_name' => $name, 'approved_time' => $time);
			update_post_meta($id, '_mockup_status_1', $value);

			delete_post_meta($id, 'mockup_status');
			delete_post_meta($id, 'mockup_approve_name');
			delete_post_meta($id, 'mockup_approved_time');

		} elseif(get_post_meta($id, 'mockup_approved', true ) == 'approved') {

			$value = array('approved' => true, 'approved_name' => '', 'approved_time' => '');
			update_post_meta($id, '_mockup_status_1', $value);

			delete_post_meta($id, 'mockup_approved');
		}


		// The Background Color
		$value = get_post_meta($id, 'mockup_background_color', true);
		$count = strlen($value);

		if($count == 6 || $count == 3) $value = '#'.$value;
		if(strpos($value, '#') === false) $value = '#ffffff';

		update_post_meta($id, '_mockup_background_color_1', $value);
		delete_post_meta($id, 'mockup_background_color');


		// The Comments Settings
		$value = get_post_meta($id, 'mockup_comment', true);
		if(empty($value)) $value = 'enable';

		update_post_meta($id, '_mockup_comment_settings_1', $value);
		delete_post_meta($id, 'mockup_comment');


		// The Header Color
		$value = get_post_meta($id, 'mockup_header', true);
		if(empty($value)) $value = 'light';
		if($value == 'navbar-default') $value = 'light';
		if($value == 'navbar-inverse') $value = 'dark';

		update_post_meta($id, '_mockup_slidebox_1', $value);
		delete_post_meta($id, 'mockup_header');


		// The Email Settings
		$value = get_post_meta($id, 'mockup_email_settings', true);
		if(empty($value)) $value = 'email_always';

		update_post_meta($id, '_mockup_email_settings_1', $value);
		delete_post_meta($id, 'mockup_email_settings');


		// The Email address
		$value = get_post_meta($id, 'mockup_email', true);
		if(empty($value)) $value = get_option('mockup_email');
		if(empty($value)) $value = get_option('admin_email');

		update_post_meta($id, '_mockup_email_1', $value);
		delete_post_meta($id, 'mockup_email');


		// Delete old stuff
		delete_post_meta($id, 'mockup_update');
		delete_post_meta($id, 'mockup_approved');
	}