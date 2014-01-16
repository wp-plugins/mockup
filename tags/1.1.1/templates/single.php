<?php if(!has_post_thumbnail()) {

	// @todo Add a 'How to' for users who are loged in. Or a link to the tutorial.

	wp_redirect(get_bloginfo('url'));

} else {

	// Define the page ID
	define('mockup_id', get_the_ID());

	// Get Featured Image info
	$image_data 	= wp_get_attachment_image_src(get_post_thumbnail_id(mockup_id), 'full' ); 
	$image_url 		= $image_data[0];
	$image_height 	= $image_data[2];

	// Get MockUp description
	$this_mockup 	= get_post(mockup_id);
	$content 		= $this_mockup->post_content;

	// Get related MockUps
	$terms = get_the_terms(mockup_id, 'relate_mockup');
	$numbers_terms = 1;

	if($terms) {

		foreach($terms as $term) {
			$numbers_terms = $numbers_terms + $term->count - 1;
		}
	}

	// Check if comments are enabbled
	if(get_post_meta(mockup_id, 'mockup_comments', true) == 'enable') {
		$mockup_comments = true;
	} else {
		$mockup_comments = false;
	}

	// Set the status of the MockUp
	if(get_post_meta(mockup_id, 'mockup_status', true) == 'approved') {
		$mockup_status = 'approved';
	} else {
		$mockup_status = false;
	}

	// Check the email settings
	$mockup_email_settings = get_post_meta(mockup_id, 'mockup_email_settings', true );

	if(empty($mockup_email_settings)) {
		$mockup_email_settings = get_option('mockup_email_settings');
	} else {
		$mockup_email_settings = get_post_meta(mockup_id, 'mockup_email_settings', true );
	}

	// Set Mockup to Approved
	// --------------------------------------
	// @link http://codex.wordpress.org/Function_Reference/update_post_meta
	// @link http://codex.wordpress.org/Function_Reference/wp_mail
	if(isset($_POST['InputNameApprove']) && $mockup_status == false) {

		$name = wp_strip_all_tags($_POST['InputNameApprove']);

		// Set post meta
		update_post_meta(mockup_id, 'mockup_status', 'approved');
		update_post_meta(mockup_id, 'mockup_approve_name', $name);
		update_post_meta(mockup_id, 'mockup_approved_time', time());

		// Email
		if($mockup_email_settings == 'email_always' || $mockup_email_settings == 'email_approved') {

			$to = get_post_meta(mockup_id, 'mockup_email', true );
			if(empty($to)) { $to = get_option('mockup_email'); }

			$subject	= sprintf(__('Mockup %s is approved', 'MockUp'), get_the_title());
			$message	= sprintf(__('Your mockup %s has been approved by %s', 'MockUp'), get_the_title(), $name);

			wp_mail( $to, $subject, $message);
		}

		// Change the MockUp status
		$mockup_status = 'approved';
	}

	// Add comments
	// ------------
	if($mockup_comments && isset($_POST['InputName']) && isset($_POST['InputText'])) {

		$name 		= wp_strip_all_tags($_POST['InputName']);
		$comment 	= wp_strip_all_tags($_POST['InputText']);
		$time 		= time();
		$version 	= '1';
		$count 		= get_post_meta(mockup_id, 'mockup_remark_count_'.$version, true);

		if(empty($count)) { $count = 0; } 

		$count++;

		update_post_meta(mockup_id, 'mockup_remark_count_'.$version, $count);

		add_post_meta(mockup_id, 'mockup_remark_name_'.$version, $name);
		add_post_meta(mockup_id, 'mockup_remark_date_'.$version, $time);
		add_post_meta(mockup_id, 'mockup_remark_text_'.$version, $comment);

		// Email
		if($mockup_email_settings == 'email_always' || $mockup_email_settings == 'email_comments') {

			$to = get_post_meta(mockup_id, 'mockup_email', true );
			if(empty($to)) { $to = get_option('mockup_email'); }

			$subject	= sprintf(__('%s made comments on Mockup %s', 'MockUp'), $name, get_the_title());
			$message	= $comment;

			wp_mail($to, $subject, $message);
		}

	}

	// Background color
	// ----------------
	function check_bg_color($bg_color) {

		// Check if background color is hex color string without hash
		if(preg_match('/^#[a-f0-9]{6}$/i', $bg_color)) {
			return $bg_color;
		} elseif(preg_match('/^[a-f0-9]{6}$/i', $bg_color)) {
			return $bg_color = '#'.$bg_color;
		} else {
			return $bg_color = '#ffffff';
		}
	}

	// Get background color
	if(get_post_meta(mockup_id, 'mockup_background_color', true) == '') {

		$bg_color = get_option('mockup_default_background_color');
		$bg_color = check_bg_color($bg_color);

	} else {

		$bg_color = get_post_meta(mockup_id, 'mockup_background_color', true);
		$bg_color = check_bg_color($bg_color);
	}

	// Get header color
	if(get_post_meta(mockup_id, 'mockup_header', true) != '') {

		$header_style = get_post_meta(mockup_id, 'mockup_header', true);

	} elseif(get_option('mockup_header') != '') {

		$header_style = get_option('mockup_header');

	} else {

		$header_style = 'navbar-default';

	} ?>

<!DOCTYPE html>

<html>

	<head>

		<meta name="robots" content="noindex,nofollow" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="cache-control" content="max-age=0" />
		<meta http-equiv="cache-control" content="no-cache" />
		<meta http-equiv="expires" content="0" />
		<meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
		<meta http-equiv="pragma" content="no-cache" />

		<title><?php the_title(); ?> | <?php bloginfo('name'); ?></title>

		<link href="<?php echo plugins_url('css/style.css', __FILE__); ?>" rel="stylesheet">

		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
		<script type="text/javascript">

				var slidebox_width 	= '450px';
				var slidebox_speed 	= 600;
				var mockup_id 		= '<?php echo mockup_id; ?>';
				var mockup_url 		= '<?php echo plugins_url( 'inc/' , __FILE__ ); ?>';

		</script>
		<script src="<?php echo plugins_url('js/custom.min.js', __FILE__); ?>"></script>

	</head>

	<body style="height: <?php echo $image_height.'px'; ?>; background-image: url('<?php echo $image_url; ?>'); background-color: <?php echo $bg_color ?>;">

		<nav class="navbar <?php echo $header_style; ?> navbar-fixed-top" role="navigation">

			<p><?php the_title(); ?></p>

			<?php if($mockup_status == 'approved') {

				echo '<a id="approve" href="#" class="btn btn-success btn-xs" disabled><strong>'.get_option('mockup_approved_text').'</strong></a>';

			} else {

				echo '<a id="approve" href="#" class="btn btn-success btn-xs btn-show-slidebox"><strong>'.get_option('mockup_approve_btn').'</strong></a>';

			}

			if($numbers_terms > 1) {

				echo '<a id="related" class="btn btn-default btn-xs btn-show-slidebox" href="#">'.get_option('mockup_related_btn').'</a>';

			}

			if($mockup_comments) {

				echo '<a id="comment" class="btn btn-default btn-xs btn-show-slidebox" href="#">'.get_option('mockup_comment_btn').'</a>';

			}

			if(!empty($content)) {

				echo '<a id="description" class="btn btn-default btn-xs btn-show-slidebox" href="#">'.get_option('mockup_description_btn').'</a>';

			} ?>

		</nav>

		<div class="slidebox">


			<?php if(!empty($content)) {

				echo '<div class="content description">';

					include_once 'inc/description.php';

				echo '</div>';

			} ?>


			<?php if($mockup_comments) {

				echo '<div class="content comment">';

					include_once 'inc/comment.php';

				echo '</div>';

			}

			if($numbers_terms > 1) {

				echo '<div class="content related">';

					include_once 'inc/related.php';

				echo '</div>';

			}


			if($mockup_status != 'approved') {

				echo '<div class="content approve">';

					include_once 'inc/approve.php';

				echo '</div>';

			} ?>


		</div>

	</body>

</html>

<?php } ?>