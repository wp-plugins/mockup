<table width="100%" cellspacing="0" cellpadding="0">

	<tbody>

		<?php $comments = get_post_meta($post->ID, '_mockup_comments_1', false);

			if(!empty($comments)) {

				wp_nonce_field('mockup_delete_comment', 'mockupnonce');

				// Init
				krsort($comments);
				$total = count($comments);

				foreach($comments as $comment) {

					if(empty($comment)) continue;

					// Init
					$total--;

					echo '<tr valign="top">';

						echo '<td width="20%" >';

							echo '<span><strong>'.$comment['name'].'</strong></span>';
							echo '<br />';
							echo '<span><i>'.human_time_diff($comment['time']).' '.__('ago','MockUp').'</i></span>';
							echo '<br />';
							echo '<a href="#" id="'.$total.'" postid="'.$post->ID.'" class="delete_comment">'.__('Delete').'</a>';

						echo '</td>';

						echo '<td>';

							echo '<span>'.nl2br($comment['text']).'</span>';

						echo '</td>';

					echo '</tr>';

				}

			} else {

				echo '<tr valign="top">';

					echo '<td>';

						echo __('No comments have been made.', 'MockUp');

					echo '</td>';

				echo '</tr>';
			} ?>

	</tbody>

</table>