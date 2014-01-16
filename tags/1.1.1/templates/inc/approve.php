<?php 

echo '<h1>'.get_option('mockup_approve_title').'</h1>';

echo '<hr>';

echo '<p>'.nl2br(get_option('mockup_approve_text')).'</p>'; ?>

<form method="post" action="<?php the_permalink(); ?>" role="form">

	<div class="form-group not-empty">
		<label for="InputName"><?php echo get_option('mockup_comment_name_label'); ?></label>
		<input type="text" id="InputNameApprove" name="InputNameApprove" class="form-control">
	</div>

	<button type="submit" id="submit_approve" class="btn btn-success"><?php echo get_option('mockup_approve_btn'); ?></button>

</form>