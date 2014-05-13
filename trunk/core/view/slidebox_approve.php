<?php 

	$id = filter_input(INPUT_POST, 'post_id', FILTER_SANITIZE_NUMBER_INT);
	$status = get_post_meta($id, '_mockup_status_1', true);

	echo '<h1>'.get_option('mockup_approve_title').'</h1>';

	if(isset($status['approved']) && $status['approved'] == true) {

		echo '<p>'.get_option('mockup_approved_text').'</p>';
		exit();
	}

	echo '<p>'.nl2br(get_option('mockup_approve_text')).'</p>'; ?>

	<form role="form">

	 	<?php wp_nonce_field('mockup_process_approve', 'mockupnonce'); ?> 

		<input type="text" id="approve_name" class="field not-empty" placeholder="<?php echo get_option('mockup_comment_name_label'); ?>">

		<button type="submit" id="approve_submit" class="submit"><?php echo get_option('mockup_approve_btn'); ?></button>

	</form>