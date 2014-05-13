<?php 

	$id = filter_input(INPUT_POST, 'post_id', FILTER_SANITIZE_NUMBER_INT);
	$comments = get_post_meta($id, '_mockup_comments_1', false);

	echo '<h1>'.get_option('mockup_comment_title').'</h1>'; ?>

	<form role="form">

	 	<?php wp_nonce_field('mockup_process_comment', 'mockupnonce'); ?> 

		<input type="text" id="comment_name" class="field not-empty" placeholder="<?php echo get_option('mockup_comment_name_label'); ?>">
		<textarea id="comment_text" class="field not-empty" rows="3" placeholder="<?php echo get_option('mockup_comment_message_label'); ?>"></textarea>

		<button type="submit" id="comment_submit" class="submit"><?php echo get_option('mockup_send_btn'); ?></button>

	</form>
		
	<?php $this->mockup_show_comment($comments);