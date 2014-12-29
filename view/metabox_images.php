<?php 

$mockup_id = get_post_meta($post->ID, '_mockup_id_1', true); 

echo '<input type="hidden" id="mockup_id" name="mockup_id" value="'.$mockup_id.'">';
wp_nonce_field('mockup_delete_image', 'mockupnonce_images');

echo '<table>';

	echo '<tr valign="top">';

		echo '<td class="image">';

			echo '<div class="mockup_img">';

				if(!empty($mockup_id)) echo wp_get_attachment_image($mockup_id, array(150, 150), true);

			echo '</div>';

		echo '<td>';


		echo '<td>';

			if(!empty($mockup_id)) {
				$class_set_mockup = 'mockup_hide';
				$class_delete_mockup = 'mockup_show';
			} else {
				$class_set_mockup = 'mockup_show';
				$class_delete_mockup = 'mockup_hide';
			}

			echo '<p class="'.$class_set_mockup.'"><a href="#" class="set_mockup button button-primary button-large"  postid="'.$post->ID.'">'.__('Add MockUp', 'MockUp').'</a></p>';
			echo '<p class="'.$class_delete_mockup.'"><a href="#" class="delete_mockup button button-large" postid="'.$post->ID.'">'.__('Delete MockUp', 'MockUp').'</a></p>';

		echo '</td>';

	echo '</tr>';

echo '</table>';