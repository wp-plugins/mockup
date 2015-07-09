<?php
// Comments status
$mockup_comments = get_post_meta($post->ID, '_mockup_comment_settings_1', true );


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

		<tbody valign="top">

			<tr>
				<td width="25%"><span><strong><?php _e('Comments', 'MockUp'); ?></strong></span></td>
				<td width="75%">
					<select id="mockup_comment_settings" name="mockup_comment_settings_1" width="300" style="width: 300px">
						<option value="enable"<?php if($mockup_comments == 'enable') { echo ' selected="selected"'; } ?>><?php _e('Enable comments', 'MockUp'); ?></option>
						<option value="disable"<?php if($mockup_comments == 'disable') { echo ' selected="selected"'; } ?>><?php _e('Disable comments', 'MockUp'); ?></option>
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
				<td colspan="2"><p><a href="<?php echo get_admin_url(null, 'edit.php?post_type='.MOCKUP_POSTTYPE.'&page='.MOCKUP_OPTIONSPAGE_SLUG.''); ?>"><?php _e('Change the general settings', 'MockUp'); ?></p></td>
			</tr>

		</tbody>

	</table>

</div>