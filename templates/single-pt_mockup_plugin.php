<?php if (!has_post_thumbnail() ) { 

	wp_redirect(get_bloginfo('url'));

} else { 

	if(isset($_POST['approved'])) {

		update_post_meta(get_the_ID(), 'mockup_approved','approved');

		//E-mail
		$from = get_bloginfo('name');
		$the_title = get_the_title();

		$emailTo = get_option('mockup_email');

		$subject = 'Mockup: '.$the_title.' Approved';
		$body = "Your mockup $the_title is Apporved!";
		$headers = 'From: '.$from.' <'.$emailTo.'>' . "\r\n" . 'Reply-To: ' . $emailTo;
	
		mail($emailTo, $subject, $body, $headers);

	}



	if(isset($_POST['mockup_remark'])) {
		
		$mockup_name = trim($_POST['name']);
		$mockup_comment = trim($_POST['comment']);
		
		update_post_meta(get_the_ID(), 'mockup_remark_name',$mockup_name);
		update_post_meta(get_the_ID(), 'mockup_remark_comment',$mockup_comment);

		//E-mail
		$from = trim($_POST['name']);
		$the_title = get_the_title();

		$emailTo = get_option('mockup_email');

		$subject = 'Remarks on your Mockup: '.$the_title;
		$body = "$mockup_name made the following remark on your Mockup ($the_title)
		\n\n $mockup_comment";
		$headers = 'From: '.$from.' <'.$emailTo.'>' . "\r\n" . 'Reply-To: ' . $emailTo;
	
		mail($emailTo, $subject, $body, $headers);


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
		
		<link href="<?php echo plugins_url( 'css/reset.css' , __FILE__ ); ?>" rel="stylesheet">
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
				background-position: center 30px;
				background-repeat:no-repeat;
				background-color: <?php echo '#'.$bg_color ?>;
				height: <?php echo $height.'px'; ?>;
				margin: 30px 0 -30px 0;
			}

			.header {
				position:fixed;
				z-index:600;
				top:0;
				left:0;
				
				height:30px;
				width:100%;
				
				background-color:<?php echo '#'.get_option('mockup_header_color'); ?>;
				border-bottom:2px solid <?php echo '#'.get_option('mockup_border_color'); ?>;

				-webkit-box-shadow: 0 5px 5px rgba(0, 0, 0, 0.3);
				   -moz-box-shadow: 0 5px 5px rgba(0, 0, 0, 0.3);
				        box-shadow: 0 5px 5px rgba(0, 0, 0, 0.3);
			}

			.header .btn {
				margin:3px 0 0 10px;
			}

			.header .btn-success {
				margin-right:10px;
			}

			.header span.label-success {
				float:right;
				margin:6px 10px 0 0;
			}
			
			ul.thumbnails li div.thumbnail {
				position:relative;
				height:290px;
			}

			ul.thumbnails li div.thumbnail img {
				max-height:120px;
				max-width:160px;
			}

			ul.thumbnails li div.thumbnail .btn {
				position:absolute;
				bottom:4px;
			}


		</style>

		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
		<script src="<?php echo plugins_url( 'js/bootstrap.min.js' , __FILE__ ); ?>"></script>

	</head>

	<body>

<?php include_once 'popups.php'; ?>

		<div class="header">

			<?php if(!$hide_related_btn == true) { ?>
				<a href="#related" role="button" class="btn btn-small" data-toggle="modal"><?php echo get_option('mockup_related_btn'); ?></a>
			<?php } ?>

			<?php $content = get_the_content(); if(!$content == '') { ?>
				<a href="#description" role="button" class="btn btn-small" data-toggle="modal"><?php echo get_option('mockup_description_btn'); ?></a>
			<?php } ?>

			<a href="#comment" role="button" class="btn btn-small" data-toggle="modal"><?php echo get_option('mockup_comment_btn'); ?></a>

			<?php if(get_post_meta(get_the_ID(), 'mockup_approved', true) == 'approved') { ?>

					<span class="label label-success">
						<?php echo get_option('mockup_approved_text'); ?>
					</span>

			<?php } else { ?>

					<a href="#approve" role="button" class="btn btn-small btn-success pull-right" data-toggle="modal"><?php echo get_option('mockup_approve_btn'); ?></a>

			<?php } ?>

		</div>

	</body>

</html>

<?php  } ?>