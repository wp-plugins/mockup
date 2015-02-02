<?php 

$mockup_id = get_post_meta($post->ID, '_mockup_id_1', true); 
$mockup_background_id = get_post_meta($post->ID, '_mockup_background_id_1', true); 

echo '<input type="hidden" id="mockup_id" name="mockup_id" value="'.$mockup_id.'">';
echo '<input type="hidden" id="mockup_background_id" name="mockup_background_id" value="'.$mockup_background_id.'">';
wp_nonce_field(-1, 'mockupnonce_backend'); ?>


<table width="100%" cellspacing="0" cellpadding="0">

	<tr valign="top">

		<td class="img_mockup_info">

			<p><strong><?php _e('MockUp', 'MockUp'); ?></strong></p>


			<div class="holder">

				<?php $mockup_status = get_post_meta($post->ID, '_mockup_status_1', true);

				$holder_class = '';

				if(isset($mockup_status['approved']) && $mockup_status['approved'] == true) {

					$holder_class = ' holder-hide';

					if(!empty($mockup_status['approved_name'])) $post_status_text = sprintf(__('Approved by %s', 'MockUp'), $mockup_status['approved_name']);
					else $post_status_text = __('Approved', 'MockUp');

					echo '<div class="mockup_approved_text holder">';

						echo '<p>'.$post_status_text.'</p>';
						echo '<a href="#" id="mockup_unapprove" class="mockup_delete" postid="'.$post->ID.'">'.__('Remove approval', 'MockUp').'</a>';

					echo '</div>';
				} ?>

			</div>



			<div class="holder<?php echo $holder_class; ?>">

				<?php
				$noimage_class = '';
				$hasimage_class = '';

				if(empty($mockup_id)) $hasimage_class = ' hide';
				else $noimage_class = ' hide';

					echo '<a href="#" class="control_mockup delete_image mockup_delete'.$hasimage_class.'" action="mockup">'.__('Delete MockUp', 'MockUp').'</a>';
					echo '<a href="#" class="control_mockup set_image button button-primary'.$noimage_class.'" action="mockup">'.__('Set MockUp', 'MockUp').'</a>'; ?>

			</div>




			<div class="holder">

				<p><?php _e('MockUp position:', 'MockUp'); ?></p>

				<?php 
				$position = get_post_meta($post->ID, '_mockup_position_1', true); 

				if(isset($mockup_status['approved']) && $mockup_status['approved'] == true) $disabled = ' disabled';
				else $disabled = ''; ?>

				<select id="mockup_position" name="mockup_position_1"<?php echo $disabled; ?>>

					<option value="center top"<?php if($position == 'center top' || empty($position)) echo ' selected' ; ?>>Center / Top</option>
					<option value="center center"<?php if($position == 'center center') echo ' selected' ; ?>>Center / Center</option>
					<option value="left top"<?php if($position == 'left top') echo ' selected' ; ?>>Left / Top</option>
					<option value="left center"<?php if($position == 'left center') echo ' selected' ; ?>>Left / Center</option>
					<option value="right top"<?php if($position == 'right top') echo ' selected' ; ?>>Right / Top</option>
					<option value="right center"<?php if($position == 'right center') echo ' selected' ; ?>>Right / Center</option>

				</select>

			</div>

		 </td>


		<?php 
		$image_data = wp_get_attachment_image_src($mockup_id, 'full');
		$style = 'background-image: url('.$image_data[0].'); ';

		if($image_data[2] > 180) {
			$style .= 'height: 260px; ';
			$style .= 'background-position: center top;';
		} else {
			$style .= 'height: 180px; ';
			$style .= 'background-position: center center;';
		}

		echo '<td class="img_mockup" style="'.$style.'">
		<p class="description description_mockup'.$noimage_class.'">'.__('No MockUp is set.', 'MockUp').'</p>
		<p class="disclaimer disclaimer_mockup'.$hasimage_class.'">'.__('This is a preview of your MockUp image.', 'MockUp').'</p>
		</td>'; ?>




		<td class="img_background_info">


			<p><strong>Background</strong></p>


			<div class="holder<?php echo $holder_class; ?>">

				<p><?php _e('Background Color:', 'MockUp'); ?></p>

				<?php $mockup_background_color = get_post_meta($post->ID, '_mockup_background_color_1', true);
				if(empty($mockup_background_color)) $mockup_background_color = get_option('mockup_default_background_color'); ?>

				<input type="text" id="mockup_background_color" name="mockup_background_color_1" value="<?php echo esc_attr($mockup_background_color); ?>" class="mockup-colorpicker" data-default-color="<?php echo get_option('mockup_default_background_color'); ?>" />

			</div>



			<div class="holder<?php echo $holder_class; ?>">

				<?php
				$noimage_class = '';
				$hasimage_class = '';

				if(empty($mockup_background_id)) $hasimage_class = ' hide';
				else $noimage_class = ' hide';

				echo '<a href="#" class="control_mockup_bg delete_image mockup_delete'.$hasimage_class.'" action="background">'.__('Delete background image', 'MockUp').'</a>';
				echo '<a href="#" class="control_mockup_bg set_image'.$noimage_class.'" action="background">'.__('Set background image', 'MockUp').'</a>'; ?>

			</div>



			<div class="holder<?php echo $holder_class; ?>">

				<p><?php _e('Background image position:', 'MockUp'); ?></p>

				<?php $position_bg = get_post_meta($post->ID, '_mockup_background_position_1', true); ?>

				<select name="mockup_background_position_1">

					<option value="repeat"<?php if($position_bg == 'repeat' || empty($position_bg)) echo ' selected' ; ?>>Repeat</option>
					<option value="repeat-y"<?php if($position_bg == 'repeat-y') echo ' selected' ; ?>>Repeat Y</option>
					<option value="repeat-x"<?php if($position_bg == 'repeat-x') echo ' selected' ; ?>>Repeat X</option>

				</select>

			</div>


		</td>

		<?php $image_data = wp_get_attachment_image_src($mockup_background_id, 'full');
		$style = 'background-image: url('.$image_data[0].'); ';
		$style .= 'background-position: '.$position_bg.';';

		echo '<td class="img_background" style="'.$style.'">
			<p class="description description_background'.$noimage_class.'">'.__('No background image is set, the background color will be used.', 'MockUp').'</p>
			<p class="disclaimer disclaimer_background'.$hasimage_class.'">'.__('This is a preview of your background image.', 'MockUp').'</p>
		</td>'; ?>

	</tr>

</table>