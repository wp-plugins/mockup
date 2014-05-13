<div class="table table_content">

	<table cellspacing="0" cellpadding="0" width="100%">

		<tbody>

			<?php 
				$mockup_description = get_post_meta($post->ID, '_mockup_description_1', true);
				$mockup_id = get_post_meta($post->ID, '_mockup_id_1', true); 
				$content = $mockup_description;
				$editor_id = 'mockup_description';
				$settings = array(

					 'media_buttons' => false,
					  'textarea_rows' => 5,
					  'teeny' => true
					);

				echo '<input type="hidden" id="mockup_id" name="mockup_id" value="'.$mockup_id.'">';

				if(!empty($mockup_id)) {

					echo '<tr>';

						echo '<td width="20%"><img class="mockup_show" src="'.wp_get_attachment_url($mockup_id).'" /></td>';
						echo '<td>';
							wp_editor($content, $editor_id, $settings);
						echo '</td>';

					echo '</tr>';


					echo '<tr>';

						echo '<td colspan="2"><p><a href="#" class="set_mockup button button-primary">'.__('Replace MockUp', 'MockUp').'</a></p></td>';

					echo '</tr>';


				} else {

					echo '<tr>';

						echo '<td width="20%"><img class="mockup_show mockup_none" src="" /></td>';
						echo '<td>';
							wp_editor($content, $editor_id, $settings);
						echo '</td>';

					echo '</tr>';

					echo '<tr>';

						echo '<td colspan="2"><p><a href="#" class="set_mockup button button-primary">'.__('Add MockUp', 'MockUp').'</a></p></td>';

					echo '</tr>';

				} ?>

			<tr>
				
			</tr>

		</tbody>

	</table>

</div>