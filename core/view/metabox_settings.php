<?php wp_nonce_field('mockup_metabox_settings', 'mockup_metabox_settings_nonce');

	// Status
	$post_status = get_post_status($post->ID);
	$mockup_status = get_post_meta($post->ID, '_mockup_status_1', true);

	if(isset($mockup_status['approved']) && $mockup_status['approved'] == true) {

		$post_status = 'approved';

		if(!empty($mockup_status['approved_name'])) {

			$post_status_text = sprintf(__('Approved by %s', 'MockUp'), $mockup_status['approved_name']);

		} else {

			$post_status_text = __('Approved', 'MockUp');
		}

	} elseif($post_status == 'publish') {

		$post_status_text = __('Published', 'MockUp');

	} else {

		$post_status = NULL;
	}


	// Background Color
	$mockup_background_color = get_post_meta($post->ID, '_mockup_background_color_1', true);
	if(empty($mockup_background_color)) {
		$mockup_background_color = get_option('mockup_default_background_color');
	}


	// Comments status
	$mockup_comments = get_post_meta($post->ID, '_mockup_comment_settings_1', true );


	// Slidebox color
	$mockup_slidebox = get_post_meta($post->ID, '_mockup_slidebox_1', true );
	if(empty($mockup_slidebox)) {
		$mockup_slidebox = get_option('mockup_slidebox');
	}


	// Email settings
	$mockup_email_settings = get_post_meta($post->ID, '_mockup_email_settings_1', true );
	if(empty($mockup_email_settings)) {
		$mockup_email_settings = get_option('mockup_email_settings');
	}


	// Email address
	$mockup_email = get_post_meta($post->ID, '_mockup_email_1', true );
	if(empty($mockup_email)) {
		$mockup_email = get_option('mockup_email');
	} ?>


	<div class="table table_content">

		<table cellspacing="0" cellpadding="0" width="100%">

			<tbody>

				<?php if($post_status != NULL) { ?>
					<tr>
						<td width="20%"><span><strong>Status</strong></span></td>
						<td><?php echo $post_status_text; ?></td>
					</tr>

					<?php if($post_status == 'approved') { ?>

						<tr>
							<td><span><strong><?php _e('Change status', 'MockUp'); ?></strong></span></td>
							<td><label><input type="checkbox" id="mockup_unapprove" name="mockup_unapprove"><?php _e('Remove approval', 'MockUp'); ?></label></td>
						</tr>

					<?php } ?>

					<tr>
						<td><span><strong><?php _e('URL', 'MockUp'); ?></strong></span></td>
						<td><code><?php the_permalink(); ?></code></td>
					</tr>
				<?php } ?>

				<tr>	
					<td valign="top"><span><strong><?php _e('Background Color', 'MockUp'); ?></strong></span></td>
					<td><input type="text" id="mockup_background_color" name="mockup_background_color_1" value="<?php echo esc_attr($mockup_background_color); ?>" class="mockup-colorpicker" data-default-color="<?php echo get_option('mockup_default_background_color'); ?>" /></td>
				</tr>

				<tr>
					<td><span><strong><?php _e('Comments', 'MockUp'); ?></strong></span></td>
					<td>
						<select id="mockup_comment_settings" name="mockup_comment_settings_1" width="300" style="width: 300px">
							<option value="enable"<?php if($mockup_comments == 'enable') { echo ' selected="selected"'; } ?>><?php _e('Enable comments', 'MockUp'); ?></option>
							<option value="disable"<?php if($mockup_comments == 'disable') { echo ' selected="selected"'; } ?>><?php _e('Disable comments', 'MockUp'); ?></option>
						</select>
					</td>
				</tr>


				<tr>
					<td><span><strong><?php _e('Slidebox Color', 'MockUp'); ?></strong></span></td>
					<td>
						<select id="mockup_slidebox" name="mockup_slidebox_1" width="300" style="width: 300px">
							<option value="light"><?php _e('Light Gray', 'MockUp'); ?></option>
							<option value="dark"<?php if(!empty($mockup_slidebox) && $mockup_slidebox == 'dark') { echo ' selected="selected"'; } ?>><?php _e('Dark Gray', 'MockUp'); ?></option>
						</select>
					</td>
				</tr>

				<tr>
					<td><span><strong><?php _e('Email Settings', 'MockUp'); ?></strong></span></td>
					<td>
						<select id="mockup_email_settings" name="mockup_email_settings_1" width="300" style="width: 300px">
							<option value="email_always"<?php if($mockup_email_settings == 'email_always') { echo ' selected="selected"'; } ?>><?php _e('Email me on all changes.', 'MockUp'); ?></option>
							<option value="email_approved"<?php if($mockup_email_settings == 'email_approved') { echo ' selected="selected"'; } ?>><?php _e('Email me only when the MockUp is approved', 'MockUp'); ?></option>
							<option value="email_comments"<?php if($mockup_email_settings == 'email_comments') { echo ' selected="selected"'; } ?>><?php _e('Email me only when comments are made', 'MockUp'); ?></option>
							<option value="email_never"<?php if($mockup_email_settings == 'email_never') { echo ' selected="selected"'; } ?>><?php _e('Never email me', 'MockUp'); ?></option>
						</select>
					</td>
				</tr>

				<tr>
					<td><span><strong><?php _e('Email Address', 'MockUp'); ?></strong></span></td>
					<td>
						<input type="text" id="mockup_email" name="mockup_email_1" value="<?php echo $mockup_email; ?>" width="300" style="width: 300px" />
						<p class="description"><?php _e('Comma separate email addresses for multiple recipients', 'MockUp'); ?></p>
					</td>
				</tr>


				<tr>
					<td colspan="2"><p><a href="<?php echo get_admin_url(null, 'edit.php?post_type='.$this->posttype.'&page=mockup_options'); ?>"><?php _e('Change the general settings', 'MockUp'); ?></p></td>
				</tr>

			</tbody>

		</table>

	</div>