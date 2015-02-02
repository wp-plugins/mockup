<table width="100%" cellspacing="0" cellpadding="0">

	<tbody valign="top">

		<?php $comments = get_post_meta($post->ID, '_mockup_comments_1', false);

			if(!empty($comments)) {

				// Init
				krsort($comments);
				$total = count($comments);

				foreach($comments as $comment) {

					if(empty($comment)) continue;

					// Init
					$total--;

					echo '<tr>';

						echo '<td width="20%">';

							echo '<span><strong>'.$comment['name'].'</strong></span>';
							echo '<br />';
							echo '<span><i>'.human_time_diff($comment['time']).' '.__('ago','MockUp').'</i></span>';
							echo '<br />';
							echo '<a href="#" id="'.$total.'" postid="'.$post->ID.'" class="delete_comment mockup_delete">'.__('Delete').'</a>';

						echo '</td>';

						echo '<td>';

							echo '<span>'.nl2br($comment['text']).'</span>';

						echo '</td>';

					echo '</tr>';

				}

			} else {

				echo '<tr>';

					echo '<td>';

						echo __('No comments have been made.', 'MockUp');

					echo '</td>';

				echo '</tr>';
			} ?>

	</tbody>

</table>