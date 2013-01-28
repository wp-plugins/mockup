<div class="wrap">

	<?php screen_icon(); ?>
	
	<form action="options.php" method="post" id="<?php echo $plugin_id; ?>_options_form" name="<?php echo $plugin_id; ?>_options_form">
	
		<?php settings_fields($plugin_id.'_options'); ?>
		
		<h2>Mockup Options &amp; Settings</h2>

		<table>

			<tr>
				<td colspan="2"><h3>Button text</h3></td>
			</tr>

			<tr>
				<td width="180"><p>Related button text (Header)</p></td>
				<td><input type="text" name="mockup_related_btn" value="<?php echo get_option('mockup_related_btn'); ?>" size="40" /></td>
			</tr>

			<tr>
				<td width="180"><p>Related button text (Popup)</p></td>
				<td><input type="text" name="mockup_related_popup_btn" value="<?php echo get_option('mockup_related_popup_btn'); ?>" size="40" /></td>
			</tr>

			<tr>
				<td><p>Description button text</p></td>
				<td><input type="text" name="mockup_description_btn" value="<?php echo get_option('mockup_description_btn'); ?>" size="40" /></td>
			</tr>

			<tr>
				<td><p>Comment button text</p></td>
				<td><input type="text" name="mockup_comment_btn" value="<?php echo get_option('mockup_comment_btn'); ?>" size="40" /></td>
			</tr>

			<tr>
				<td><p>Send button text</p></td>
				<td><input type="text" name="mockup_send_btn" value="<?php echo get_option('mockup_send_btn'); ?>" size="40" /></td>
			</tr>

			<tr>
				<td><p>Approve button text</p></td>
				<td><input type="text" name="mockup_approve_btn" value="<?php echo get_option('mockup_approve_btn'); ?>" size="40" /></td>
			</tr>


			<tr>
				<td colspan="2"><h3>Text</h3></td>
			</tr>

			<tr>
				<td><p>Comment name label</p></td>
				<td><input type="text" name="mockup_comment_name_label" value="<?php echo get_option('mockup_comment_name_label'); ?>" size="40" /></td>
			</tr>

			<tr>
				<td><p>Comment message label</p></td>
				<td><input type="text" name="mockup_comment_message_label" value="<?php echo get_option('mockup_comment_message_label'); ?>" size="40" /></td>
			</tr>	

			<tr>
				<td><p>Approved text</p></td>
				<td><input type="text" name="mockup_approved_text" value="<?php echo get_option('mockup_approved_text'); ?>" size="40" /></td>
			</tr>	

			<tr>
				<td><p>Related Mockups popup title</p></td>
				<td><input type="text" name="mockup_related_title" value="<?php echo get_option('mockup_related_title'); ?>" size="40" /></td>
			</tr>

			<tr>
				<td><p>Mockup description popup title</p></td>
				<td><input type="text" name="mockup_description_title" value="<?php echo get_option('mockup_description_title'); ?>" size="40" /></td>
			</tr>

			<tr>
				<td><p>Mockup comment popup title</p></td>
				<td><input type="text" name="mockup_comment_title" value="<?php echo get_option('mockup_comment_title'); ?>" size="40" /></td>
			</tr>

			<tr>
				<td><p>Confirm approval popup title</p></td>
				<td><input type="text" name="mockup_approve_title" value="<?php echo get_option('mockup_approve_title'); ?>" size="40" /></td>
			</tr>

			<tr>
				<td><p>Confirm approval popup text</p></td>
				<td><input type="text" name="mockup_approve_text" value="<?php echo get_option('mockup_approve_text'); ?>" size="40" /></td>
			</tr>

			<tr>
				<td colspan="2"><h3>Color</h3></td>
			</tr>				
				
			<tr>
				<td><p>Default background color</p></td>
				<td><input type="text" name="mockup_default_background_color" value="<?php echo get_option('mockup_default_background_color'); ?>" size="40" /></td>
			</tr>

			<tr>
				<td><p>Header color</p></td>
				<td>
					<select name="mockup_header">
						<option value="light"<?php $value = get_option('mockup_header'); if($value == 'light') { echo ' selected="selected"'; } ?>>Light</option>
						<option value="dark"<?php if($value == 'dark') { echo ' selected="selected"'; } ?>>Dark</option>
					</select>
				</td>
			</tr>

			<tr>
				<td colspan="2"><h3>Settings</h3></td>
			</tr>

			<tr>
				<td><p>E-mail adres</p></td>
				<td><input type="text" name="mockup_email" value="<?php echo get_option('mockup_email'); ?>" size="40" /></td>
			</tr>

			<tr>
				<td colspan="2"><input type="submit" name="submit" value="Save Settings" class="button-primary"  /></td>
			</tr>

		</table>
	
	</form>

</div>
