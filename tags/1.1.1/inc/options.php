<div class="wrap">

	<?php screen_icon(); ?>
	
	<form action="options.php" method="post" id="<?php echo $plugin_id; ?>_options_form" name="<?php echo $plugin_id; ?>_options_form">
	
		<?php settings_fields($plugin_id.'_options'); ?>
		
		<h2><?php _e('MockUp Options & Settings', 'MockUp'); ?></h2>

		<table cellpadding="5" width="100%">

			<tr>
				<td><h3 style="border-bottom: 1px solid #ccc;"><?php _e('Description', 'MockUp'); ?></h3></td>
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
					<td valign="top"><span><?php _e('No comments text', 'MockUp'); ?></span></td>
					<td>
						<textarea name="mockup_comment_no_comments" style="width: 300px; height: 100px;"><?php echo get_option('mockup_comment_no_comments'); ?></textarea>
						<!--<p class="description"></p>-->
					</td>
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
				<td><h3 style="border-bottom: 1px solid #ccc;"><?php _e('Related'); ?></h3></td>
			</tr>

				<tr>
					<td width="220"><span><?php _e('Related button text (Header)', 'MockUp'); ?></span></td>
					<td><input type="text" name="mockup_related_btn" value="<?php echo get_option('mockup_related_btn'); ?>" style="width: 300px;" /></td>
				</tr>

				<tr>
					<td><span><?php _e('Related button text', 'MockUp'); ?></span></td>
					<td><input type="text" name="mockup_related_popup_btn" value="<?php echo get_option('mockup_related_popup_btn'); ?>" style="width: 300px;" /></td>
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
				<td><h3 style="border-bottom: 1px solid #ccc;"><?php _e('Settings', 'MockUp'); ?></h3></td>
			</tr>			
				
				<tr>
					<td valign="top"><span><?php _e('Default background color', 'MockUp'); ?></span></td>
					<td><input type="text" name="mockup_default_background_color" class="mockup-colorpicker" data-default-color="#ffffff" value="<?php echo get_option('mockup_default_background_color'); ?>" /></td>
				</tr>

				<tr>
					<td><span><?php _e('Default header color', 'MockUp'); ?></span></td>
					<td>
						<?php $mockup_header = get_option('mockup_header'); ?>
						<select name="mockup_header"  width="300" style="width: 300px">
							<option value="navbar-default"<?php if($mockup_header == 'navbar-default') { echo ' selected="selected"'; } ?>><?php _e('Light Gray', 'MockUp'); ?></option>
							<option value="navbar-inverse"<?php if($mockup_header == 'navbar-inverse') { echo ' selected="selected"'; } ?>><?php _e('Dark Gray', 'MockUp'); ?></option>
						</select>
					</td>
				</tr>

				<tr>
					<td><span><?php _e('E-mail adres', 'MockUp'); ?></span></td>
					<td>
						<input type="text" name="mockup_email" value="<?php echo get_option('mockup_email'); ?>" style="width: 300px;" />
						<p class="description"><?php _e('Comma separate email addresses for multiple recipients'); ?></p>
					</td>
				</tr>

				<tr>
					<td><span><?php _e('E-mail settings', 'MockUp'); ?></span></td>
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
							<option value="5"<?php if($mockup_menu_position == '5') { echo ' selected="selected"'; } ?>><?php _e('5 - below Posts', 'MockUp'); ?></option>
							<option value="10"<?php if($mockup_menu_position == '10') { echo ' selected="selected"'; } ?>><?php _e('10 - below Media', 'MockUp'); ?></option>
							<option value="15"<?php if($mockup_menu_position == '15') { echo ' selected="selected"'; } ?>><?php _e('15 - below Links', 'MockUp'); ?></option>
							<option value="20"<?php if($mockup_menu_position == '20') { echo ' selected="selected"'; } ?>><?php _e('20 - below Pages', 'MockUp'); ?></option>
							<option value="25"<?php if($mockup_menu_position == '25') { echo ' selected="selected"'; } ?>><?php _e('25 - below comments', 'MockUp'); ?></option>
							<option value="60"<?php if($mockup_menu_position == '60') { echo ' selected="selected"'; } ?>><?php _e('60 - below first separator', 'MockUp'); ?></option>
							<option value="65"<?php if($mockup_menu_position == '65') { echo ' selected="selected"'; } ?>><?php _e('65 - below Plugins', 'MockUp'); ?></option>
							<option value="70"<?php if($mockup_menu_position == '70') { echo ' selected="selected"'; } ?>><?php _e('70 - below Users', 'MockUp'); ?></option>
							<option value="75"<?php if($mockup_menu_position == '75') { echo ' selected="selected"'; } ?>><?php _e('75 - below Tools', 'MockUp'); ?></option>
							<option value="80"<?php if($mockup_menu_position == '80') { echo ' selected="selected"'; } ?>><?php _e('80 - below Settings', 'MockUp'); ?></option>
							<option value="100"<?php if($mockup_menu_position == '100') { echo ' selected="selected"'; } ?>><?php _e('100 - below second separator', 'MockUp'); ?></option>
							<option value="160"<?php if($mockup_menu_position == '160') { echo ' selected="selected"'; } ?>><?php _e('160 - Default position', 'MockUp'); ?></option>
						</select>
					</td>
				</tr>


			<tr>
				<td><input type="submit" name="submit" value="<?php _e('Save Settings', 'MockUp'); ?>" class="button-primary" /></td>
			</tr>

		</table>
	
	</form>

</div>