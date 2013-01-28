<?php if (!has_post_thumbnail() ) { 

	wp_redirect(get_bloginfo('url'));

} else { 

	if(isset($_POST['approved'])) {

		$title = get_the_title();

		if(strpos(get_the_title(),'&#10004;') === false) {
			$new_title = '&#10004; '.$title;
		} else {
			$new_title = $title;
		}

		$current_id = get_the_ID();

		$my_post = array();
		$my_post['ID'] = $current_id;
		$my_post['post_title'] = $new_title;

		// Update the post into the database
		wp_update_post($my_post);
		update_post_meta(get_the_ID(), 'mockup_approved','approved');

		// Email 
		$from = get_bloginfo('name');
		$the_title = get_the_title();

		$emailTo = get_option('mockup_email');

		$subject = 'Mockup: '.$the_title.' Approved';
		$body = "Your mockup $the_title is Apporved!";
		$headers = 'From: '.$from.' <'.$emailTo.'>' . "\r\n" . 'Reply-To: ' . $emailTo;
	
		mail($emailTo, $subject, $body, $headers);

	}



	if(isset($_POST['mockup_remark'])) {

		$time = date('d-m-Y');

		$has_error = false;
		
		if(wp_strip_all_tags($_POST['name']) == '') {
			$has_error = true;
		} else {
			$mockup_name = wp_strip_all_tags($_POST['name']);
		}

		$mockup_comment = wp_strip_all_tags($_POST['comment']);

		if($has_error != true){
		
			add_post_meta(get_the_ID(), 'mockup_remark',$mockup_name.' ('.$time.')<br />'.$mockup_comment);

			//E-mail
			$from = wp_strip_all_tags($_POST['name']);
			$the_title = get_the_title();

			$emailTo = get_option('mockup_email');

			$subject = 'Remarks on your Mockup: '.$the_title;
			$body = "$mockup_name made the following remark on your Mockup ($the_title)
			\n\n $mockup_comment";
			$headers = 'From: '.$from.' <'.$emailTo.'>' . "\r\n" . 'Reply-To: ' . $emailTo;
		
			mail($emailTo, $subject, $body, $headers);

		}
	}



	$mockup_img = wp_get_attachment_url(get_post_thumbnail_id(get_the_ID()) );
	
	list($width, $height, $type, $attr) = getimagesize($mockup_img);


?>

<!DOCTYPE html>

<html>

	<head>

		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta name='robots' content='noindex,nofollow'>
		<title><?php the_title(); ?> | <?php bloginfo('name'); ?></title>

		<link href="<?php echo plugins_url( 'css/bootstrap.min.css' , __FILE__ ); ?>" rel="stylesheet">

		<?php // Get background color

			if(get_post_meta(get_the_ID(), 'mockup_background_color', true) == '') {
				$bg_color = get_option('mockup_default_background_color');
			} else {
				$bg_color = get_post_meta(get_the_ID(), 'mockup_background_color', true);
			}

		?>

		<style>
		
			body { 
				background-image: url('<?php echo $mockup_img; ?>');
				background-position: center 40px;
				background-repeat:no-repeat;
				background-color: <?php echo '#'.$bg_color ?>;
				height: <?php echo $height.'px'; ?>;
				margin: 40px 0 -40px 0;
			}	

			.navbar .btn-success {
				margin-right:10px;
			}

			.navbar span.label-success {
				float:right;
				margin:6px 10px 0 0;
			}


		</style>

		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
		<script src="<?php echo plugins_url( 'js/bootstrap.min.js' , __FILE__ ); ?>"></script>

	</head>

	<body>

<?php include_once 'popups.php'; ?>

		<div class="header">



		</div>

			<div class="navbar navbar-fixed-top <?php if(get_option('mockup_header') == 'dark') { echo ' navbar-inverse"'; } ?>">

				<div class="navbar-inner">

					<ul class="nav">

						<?php if(!$hide_related_btn == true) { ?>
							<li>
								<a href="#related" data-toggle="modal"><?php echo get_option('mockup_related_btn'); ?></a>
							</li>
						<?php } ?>

						<?php $content = get_the_content(); if(!$content == '') { ?>
						<li>
							<a href="#description" data-toggle="modal"><?php echo get_option('mockup_description_btn'); ?></a>
						</li>
						<?php } ?>

						<li>
							<a href="#comment" data-toggle="modal"><?php echo get_option('mockup_comment_btn'); ?></a>
						</li>

					</ul>

					<?php if(get_post_meta(get_the_ID(), 'mockup_approved', true) == 'approved') { ?>

							<a href="#" class="btn btn-success pull-right" disabled><strong><?php echo get_option('mockup_approved_text'); ?></strong></a>

					<?php } else { ?>

							<a href="#approve" role="button" class="btn btn-success pull-right" data-toggle="modal"><strong><?php echo get_option('mockup_approve_btn'); ?></strong></a>

					<?php } ?>


				</div>

			</div>

	</body>

</html>

<?php  } ?>