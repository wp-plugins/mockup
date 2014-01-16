<?php

echo '<h1>'.get_option('mockup_comment_title').'</h1>';

echo '<hr>'; ?>

<form method="post" action="<?php the_permalink(); ?>" role="form">

	<div class="form-group not-empty">
		<label for="InputName"><?php echo get_option('mockup_comment_name_label'); ?></label>
		<input type="text" id="InputName" name="InputName" class="form-control">
	</div>

	<div class="form-group not-empty">
		<label for="InputText"><?php echo get_option('mockup_comment_message_label'); ?></label>
		<textarea id="InputText" name="InputText" class="form-control" rows="3"></textarea>
	</div>

	<button type="submit" id="submit_comment" class="btn btn-info"><?php echo get_option('mockup_send_btn'); ?></button>

</form>

<?php

$date_format 	= get_option('date_format');
$time_format 	= get_option('time_format');
$format 		= $date_format.' '.$time_format;
$remark_count 	= get_post_meta(mockup_id, 'mockup_remark_count_1', true);

echo '<hr>';

echo '<h1>'.get_option('mockup_comment_message_label').'</h1>';


if(!empty($remark_count)) {

	$i 				= $remark_count;
	$name 			= get_post_meta(mockup_id, 'mockup_remark_name_1', false);
	$dates 			= get_post_meta(mockup_id, 'mockup_remark_date_1', false);
	$text 			= get_post_meta(mockup_id, 'mockup_remark_text_1', false);
	$date_format 	= get_option('date_format');
	$time_format 	= get_option('time_format');
	$format 		= $date_format.' '.$time_format;

	while ($i > 0) {

		$i--;

		$date = date($format, $dates[$i]);

		echo '<p><strong>',$name[$i],'</strong> '.__('on', 'MockUp').' '.$date.'</p>';
		echo '<p>',nl2br($text[$i]),'</p>';
		echo '<br />';
	}

} else {

	echo nl2br(get_option('mockup_comment_no_comments'));

}