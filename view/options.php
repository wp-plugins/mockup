<div class="wrap">

	<?php
	echo '<h2>'.__('MockUp Options & Settings', 'MockUp').'</h2>';

	if(!empty($_GET['settings-updated']) && $_GET['settings-updated'] == true) {
		echo '<div class="updated below-h2"><p>'.__('Changes saved', 'MockUp').'</p></div>';
	}

	// Set tabs
	$tabs = array(
		'general' => __('General settings', 'MockUp'),
		'text' => __('Text', 'MockUp'),
		'style' => __('Styling', 'MockUp'),
		'advanced' => __('Advanced Settings', 'MockUp')
	);

	// Get tab
	$showTab = filter_input(INPUT_GET, 'tab', FILTER_SANITIZE_SPECIAL_CHARS);
	if(empty($showTab)) $showTab = 'general';

	// Footer text
	$url = 'http://www.mockupplugin.com?utm_medium=options';
	$link = sprintf( __('Do you have any questions? Visit the <a href="%s" target="_blank">website</a> for documentation and support.', 'MockUp'), esc_url($url));


	// Start page
	// Tabs
	echo '<h2 class="nav-tab-wrapper">';

		foreach($tabs as $tab => $name) {

			if($tab == $showTab) $class = ' nav-tab-active';
			else $class = '';

			echo '<a class="nav-tab'.$class.'" href="?post_type='.MOCKUP_POSTTYPE.'&page='.MOCKUP_OPTIONSPAGE_SLUG.'&tab='.$tab.'">'.$name.'</a>';
		}

	echo '</h2>';

	// Content per tab
	if($showTab == 'general') { ?>


		<form action="options.php" method="post">

			<?php settings_fields($this->group.'_settings');
			do_settings_sections($this->group.'_settings'); ?>

			<table cellpadding="5" width="100%" valign="top">

				<tr>
					<td width="220"><h3 style="border-bottom: 1px solid #ccc;"><?php echo $tabs['general']; ?></h3></td>
				</tr>			
				
					<tr>
						<td valign="top"><span><?php _e('Default background color', 'MockUp'); ?></span></td>
						<td><input type="text" name="mockup_default_background_color" class="mockup-colorpicker" data-default-color="#ffffff" value="<?php echo get_option('mockup_default_background_color'); ?>" /></td>
					</tr>

					<tr>
						<td><span><?php _e('Email address', 'MockUp'); ?></span></td>
						<td>
							<input type="text" name="mockup_email" value="<?php echo get_option('mockup_email'); ?>" style="width: 300px;" />
							<p class="description"><?php _e('Comma separate email addresses for multiple recipients', 'MockUp'); ?></p>
						</td>
					</tr>


					<tr>
						<td><span><?php _e('Email settings', 'MockUp'); ?></span></td>
						<td>
							<?php $mockup_email_settings = get_option('mockup_email_settings'); ?>
							<select name="mockup_email_settings" width="300" style="width: 300px">
								<option value="email_always"<?php if($mockup_email_settings == 'email_always') { echo ' selected="email_always"'; } ?>><?php _e('Email me on all changes.', 'MockUp'); ?></option>
								<option value="email_approved"<?php if($mockup_email_settings == 'email_approved') { echo ' selected="email_approved"'; } ?>><?php _e('Email me only when the MockUp is approved', 'MockUp'); ?></option>
								<option value="email_comments"<?php if($mockup_email_settings == 'email_comments') { echo ' selected="email_comments"'; } ?>><?php _e('Email me only when comments are made', 'MockUp'); ?></option>
								<option value="email_never"<?php if($mockup_email_settings == 'email_never') { echo ' selected="email_never"'; } ?>><?php _e('Never email me', 'MockUp'); ?></option>
							</select>
						</td>
					</tr>

					<tr>
						<td><span><?php _e('Menu position', 'MockUp'); ?></span></td>
						<td>
							<?php $mockup_menu_position = get_option('mockup_menu_position'); ?>
							<select name="mockup_menu_position"  width="300" style="width: 300px">
								<option value="5"<?php if($mockup_menu_position == '5') { echo ' selected="selected"'; } ?>><?php printf(__('%s below Posts', 'MockUp'), '5 -'); ?></option>
								<option value="10"<?php if($mockup_menu_position == '10') { echo ' selected="selected"'; } ?>><?php printf(__('%s below Media', 'MockUp'), '10 -'); ?></option>
								<option value="15"<?php if($mockup_menu_position == '15') { echo ' selected="selected"'; } ?>><?php printf(__('%s below Links', 'MockUp'), '15 -'); ?></option>
								<option value="20"<?php if($mockup_menu_position == '20') { echo ' selected="selected"'; } ?>><?php printf(__('%s below Pages', 'MockUp'), '20 -'); ?></option>
								<option value="25"<?php if($mockup_menu_position == '25') { echo ' selected="selected"'; } ?>><?php printf(__('%s below comments', 'MockUp'), '25 -'); ?></option>
								<option value="60"<?php if($mockup_menu_position == '60') { echo ' selected="selected"'; } ?>><?php printf(__('%s below first separator', 'MockUp'), '60 -'); ?></option>
								<option value="65"<?php if($mockup_menu_position == '65') { echo ' selected="selected"'; } ?>><?php printf(__('%s below Plugins', 'MockUp'), '65 -'); ?></option>
								<option value="70"<?php if($mockup_menu_position == '70') { echo ' selected="selected"'; } ?>><?php printf(__('%s below Users', 'MockUp'), '70 -'); ?></option>
								<option value="75"<?php if($mockup_menu_position == '75') { echo ' selected="selected"'; } ?>><?php printf(__('%s below Tools', 'MockUp'), '75 -'); ?></option>
								<option value="80"<?php if($mockup_menu_position == '80') { echo ' selected="selected"'; } ?>><?php printf(__('%s below Settings', 'MockUp'), '80 -'); ?></option>
								<option value="100"<?php if($mockup_menu_position == '100') { echo ' selected="selected"'; } ?>><?php printf(__('%s below second separator', 'MockUp'), '100 -'); ?></option>
								<option value="160"<?php if($mockup_menu_position == '160') { echo ' selected="selected"'; } ?>><?php printf(__('%s Default position', 'MockUp'), '160 -'); ?></option>
							</select>
						</td>
					</tr>

				<tr>
					<td colspan="2"><?php submit_button(__('Save Settings', 'MockUp')); ?></td>
				</tr>

				<tr>
					<td colspan="2"><p class="description"><?php echo $link; ?></p></td>
				</tr>

			</table>
		
		</form>


	<?php } elseif($showTab == 'text') { ?>


		<form action="options.php" method="post">

			<?php settings_fields($this->group.'_text');
			do_settings_sections($this->group.'_text'); ?>

			<table cellpadding="5" width="100%" valign="top">

				<tr>
					<td width="220"><h3 style="border-bottom: 1px solid #ccc;"><?php echo $tabs['text']; ?></h3></td>
				</tr>

					<tr>
						<td><span><?php _e('Description button text', 'MockUp'); ?></span></td>
						<td><input type="text" name="mockup_description_btn" value="<?php echo get_option('mockup_description_btn'); ?>" style="width: 300px;" /></td>
					</tr>

					<tr>
						<td><span><?php _e('MockUp description title', 'MockUp'); ?></span></td>
						<td><input type="text" name="mockup_description_title" value="<?php echo get_option('mockup_description_title'); ?>" style="width: 300px;" /></td>
					</tr>


				<tr>
					<td><h3 style="border-bottom: 1px solid #ccc;"><?php _e('Comment', 'MockUp'); ?></h3></td>
				</tr>

					<tr>
						<td><span><?php _e('Comment button text', 'MockUp'); ?></span></td>
						<td><input type="text" name="mockup_comment_btn" value="<?php echo get_option('mockup_comment_btn'); ?>" style="width: 300px;" /></td>
					</tr>

					<tr>
						<td><span><?php _e('Comment name label', 'MockUp'); ?></span></td>
						<td><input type="text" name="mockup_comment_name_label" value="<?php echo get_option('mockup_comment_name_label'); ?>" style="width: 300px;" /></td>
					</tr>

					<tr>
						<td><span><?php _e('Comment message label', 'MockUp'); ?></span></td>
						<td><input type="text" name="mockup_comment_message_label" value="<?php echo get_option('mockup_comment_message_label'); ?>" style="width: 300px;" /></td>
					</tr>

					<tr>
						<td><span><?php _e('MockUp comment title', 'MockUp'); ?></span></td>
						<td><input type="text" name="mockup_comment_title" value="<?php echo get_option('mockup_comment_title'); ?>" style="width: 300px;" /></td>
					</tr>

					<tr>
						<td><span><?php _e('Send button text', 'MockUp'); ?></span></td>
						<td><input type="text" name="mockup_send_btn" value="<?php echo get_option('mockup_send_btn'); ?>" style="width: 300px;" /></td>
					</tr>

					<tr>
						<td valign="top"><span><?php _e('No comments text', 'MockUp'); ?></span></td>
						<td>
							<textarea name="mockup_comment_no_comments" style="width: 300px; height: 100px;"><?php echo get_option('mockup_comment_no_comments'); ?></textarea>
							<!--<p class="description"></p>-->
						</td>
					</tr>


				<tr>
					<td><h3 style="border-bottom: 1px solid #ccc;"><?php _e('Related'); ?></h3></td>
				</tr>

					<tr>
						<td width="220"><span><?php _e('Related button text', 'MockUp'); ?></span></td>
						<td><input type="text" name="mockup_related_btn" value="<?php echo get_option('mockup_related_btn'); ?>" style="width: 300px;" /></td>
					</tr>

					<tr>
						<td><span><?php _e('Related MockUps title', 'MockUp'); ?></span></td>
						<td><input type="text" name="mockup_related_title" value="<?php echo get_option('mockup_related_title'); ?>" style="width: 300px;" /></td>
					</tr>


				<tr>
					<td><h3 style="border-bottom: 1px solid #ccc;"><?php _e('Approve', 'MockUp'); ?></h3></td>
				</tr>

					<tr>
						<td><span><?php _e('Approve button text', 'MockUp'); ?></span></td>
						<td><input type="text" name="mockup_approve_btn" value="<?php echo get_option('mockup_approve_btn'); ?>" style="width: 300px;" /></td>
					</tr>

					<tr>
						<td><span><?php _e('Approved text', 'MockUp'); ?></span></td>
						<td><input type="text" name="mockup_approved_text" value="<?php echo get_option('mockup_approved_text'); ?>" style="width: 300px;" /></td>
					</tr>

					<tr>
						<td><span><?php _e('Confirm approval title', 'MockUp'); ?></span></td>
						<td><input type="text" name="mockup_approve_title" value="<?php echo get_option('mockup_approve_title'); ?>" style="width: 300px;" /></td>
					</tr>

					<tr>
						<td valign="top"><span><?php _e('Confirm approval text', 'MockUp'); ?></span></td>
						<td><textarea name="mockup_approve_text" style="width: 300px; height: 100px;"><?php echo get_option('mockup_approve_text'); ?></textarea></td>
					</tr>


				<tr>
					<td><h3 style="border-bottom: 1px solid #ccc;"><?php _e('Password protected', 'MockUp'); ?></h3></td>
				</tr>

					<tr>
						<td valign="top"><span><?php _e('Password protected title', 'MockUp'); ?></span></td>
						<td><textarea name="mockup_locked_title" style="width: 300px; height: 100px;"><?php echo get_option('mockup_locked_title'); ?></textarea></td>
					</tr>


				<tr>
					<td colspan="2"><?php submit_button(__('Save text', 'MockUp')); ?></td>
				</tr>

				<tr>
					<td colspan="2"><p class="description"><?php echo $link; ?></p></td>
				</tr>

			</table>
		
		</form>


	<?php } elseif($showTab == 'style') { ?>


		<form action="options.php" method="post">

			<?php settings_fields($this->group.'_style');
			do_settings_sections($this->group.'_style'); ?>

			<table cellpadding="5" width="100%" valign="top">

				<tr>
					<td width="220"><h3 style="border-bottom: 1px solid #ccc;"><?php echo $tabs['style']; ?></h3></td>
				</tr>			


					<tr>
						<td valign="top"><span><?php _e('Active link color', 'MockUp'); ?></span></td>
						<td><input type="text" name="mockup_color_active" class="mockup-colorpicker" data-default-color="#21759b" value="<?php echo get_option('mockup_color_active'); ?>" /></td>
					</tr>

					<tr>
						<td><span><?php _e('Import Google font', 'MockUp'); ?></span></td>
						<td>
							<input type="text" name="mockup_importfont" value="<?php echo get_option('mockup_importfont'); ?>" style="width: 300px;" />
							<p class="description"><?php _e('Example: ', 'MockUp'); ?>@import url(http://fonts.googleapis.com/css?family=Open+Sans);</p>
						</td>
					</tr>

					<tr>
						<td><span><?php _e('Font family', 'MockUp'); ?></span></td>
						<td>
							<input type="text" name="mockup_fontfamily" value="<?php echo get_option('mockup_fontfamily'); ?>" style="width: 300px;" />
							<p class="description"><?php _e('Example: ', 'MockUp'); ?>'Open Sans', sans-serif</p>
						</td>
					</tr>

					<tr>
						<td valign="top"><span><?php _e('Add your own CSS', 'MockUp'); ?></span></td>
						<td><textarea name="mockup_style_general" style="width: 300px; height: 100px;"><?php echo get_option('mockup_style_general'); ?></textarea></td>
					</tr>

				<tr>
					<td colspan="2"><?php submit_button(__('Save Style', 'MockUp')); ?></td>
				</tr>

				<tr>
					<td colspan="2"><p class="description"><?php echo $link; ?></p></td>
				</tr>

			</table>
		
		</form>


	<?php } else { ?>


		<form action="options.php" method="post">

			<?php settings_fields($this->group.'_advanced');
			do_settings_sections($this->group.'_advanced'); ?>

			<table cellpadding="5" width="100%" valign="top">

				<tr>
					<td width="220"><h3 style="border-bottom: 1px solid #ccc;"><?php echo $tabs['advanced']; ?></h3></td>
				</tr>			
				

					<tr>
						<td><span><?php _e('Password settings', 'MockUp'); ?></span></td>
						<td>
							<?php $mockup_email_settings = get_option('mockup_password_settings'); ?>
							<select name="mockup_password_settings" width="300" style="width: 300px">
								<option value="default"<?php if($mockup_email_settings == 'default') { echo ' selected="default"'; } ?>><?php _e('Use default WordPress password settings.', 'MockUp'); ?></option>
								<option value="inherit"<?php if($mockup_email_settings == 'inherit') { echo ' selected="inherit"'; } ?>><?php _e('Use bulk password settings.', 'MockUp'); ?></option>
							</select>
						</td>
					</tr>


					<tr>
						<td><span><?php _e('Overlay settings', 'MockUp'); ?></span></td>
						<td>
							<?php $mockup_email_settings = get_option('mockup_overlay_settings'); ?>
							<select name="mockup_overlay_settings" width="300" style="width: 300px">
								<option value="show"<?php if($mockup_email_settings == 'show') { echo ' selected="show"'; } ?>><?php _e('Use black overlay.', 'MockUp'); ?></option>
								<option value="hide"<?php if($mockup_email_settings == 'hide') { echo ' selected="hide"'; } ?>><?php _e('Don\'t black use overlay.', 'MockUp'); ?></option>
							</select>
						</td>
					</tr>


					<tr>
						<td><span><?php _e('Overflow settings', 'MockUp'); ?></span></td>
						<td>
							<?php $mockup_email_settings = get_option('mockup_overflow_settings'); ?>
							<select name="mockup_overflow_settings" width="300" style="width: 300px">
								<option value="true"<?php if($mockup_email_settings == 'true') { echo ' selected="true"'; } ?>><?php _e('Center screen if has horizontal overflow.', 'MockUp'); ?></option>
								<option value="false"<?php if($mockup_email_settings == 'false') { echo ' selected="false"'; } ?>><?php _e('Do nothing on horizontal overflow.', 'MockUp'); ?></option>
							</select>
						</td>
					</tr>


				<tr>
					<td colspan="2"><?php submit_button(__('Save Settings', 'MockUp')); ?></td>
				</tr>

				<tr>
					<td colspan="2"><p class="description"><?php echo $link; ?></p></td>
				</tr>

			</table>
		
		</form>

	<?php } ?>

</div>