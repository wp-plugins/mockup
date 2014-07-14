<?php 

class Single extends MockUp {

	public function __construct() {

		$this->postID = get_the_ID();
		$this->mockupID = get_post_meta($this->postID, '_mockup_id_1', true);
		$this->mockupURL = get_permalink($this->mockupID);

		$this->description = get_post_meta($this->postID, '_mockup_description_1', true);
		$this->comments = get_post_meta($this->postID, '_mockup_comment_settings_1', true);
		$this->terms = get_the_terms($this->postID, $this->taxonomy);

		$this->mockup_single_check();
		$this->mockup_single_layout();
	}


	public function mockup_single_check() {

		if(empty($this->mockupID) && is_user_logged_in()) {

			$title = __('No Mockup found' ,'MockUp');
			$message = __('You did not add a MockUp.' ,'MockUp');

			wp_die($message, $title);

		} elseif(empty($this->mockupID) && !is_user_logged_in()) {

			wp_redirect(get_bloginfo('url'));
		}
	}


	public function mockup_single_layout() {

		// Get image data.
		$image_data = wp_get_attachment_image_src($this->mockupID, 'full');

		$this->url = $image_data[0];
		$this->height = $image_data[2];

		// Get image position.
		$this->position = get_post_meta($this->postID, '_mockup_position_1', true);

		// Get colors.
		$this->bgcolor = get_post_meta($this->postID, '_mockup_background_color_1', true);
		$this->menucolor = get_post_meta($this->postID, '_mockup_slidebox_1', true);
	}


	public function mockup_password_form() {

		global $post;

		$label = 'pwbox-'.(empty($post->ID ) ? rand() : $post->ID);

		$form = '<form class="mockup_password" action="'.esc_url(site_url('wp-login.php?action=postpass', 'login_post')).'" method="post">';
		$form .= '<i class="fa fa-lock"></i>';
		$form .= '<p>'.get_option('mockup_locked_title').'</p>';
		$form .= '<input class="field" name="post_password" id="'.$label.'" type="password" size="20" maxlength="20" /><br />';
		$form .= '<input type="submit" class="submit_password" name="Submit" value="'.get_option('mockup_send_btn').'" />';
		$form .= '</form>';

		return $form;
	}
}


$single = new Single; ?>

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

		<title><?php wp_title('|', true, 'right'); ?></title>

		<link href="<?php echo plugins_url('mockup'); ?>/include/css/single.css" rel="stylesheet" />

		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
		<script type="text/javascript">
			post_id = '<?php echo $single->postID; ?>';
			ajax_url = '<?php echo admin_url('admin-ajax.php'); ?>'
		</script>
		<script src="<?php echo plugins_url('mockup'); ?>/include/js/single.min.js"></script>

	</head>

	<?php if(post_password_required()) { ?>

		<body class="<?php if(!empty($single->menucolor)) echo 'form_',$single->menucolor; else echo 'form_light'; ?>">

			<?php echo $single->mockup_password_form(); ?>

		</body>

	<?php } else { ?>

		<body style="height: <?php echo $single->height.'px'; ?>; background-image: url('<?php echo $single->url; ?>'); background-position:<?php echo $single->position; ?> ; background-color: <?php echo $single->bgcolor; ?>;">

			<div class="slidebox <?php if(!empty($single->menucolor)) echo 'slidebox_',$single->menucolor; else echo 'slidebox_light'; ?>">

				<div class="content">

					<a href="#" title="<?php _e('Close', 'MockUp'); ?>" id="close" class="show-title"><i class="fa fa-arrow-circle-left"></i></a>

					<div id="load">

						<div class="loading"></div>

					</div>

					<div class="navbar">

						<?php

						if(!empty($single->description)) {

							echo '<a href="#" title="'.get_option('mockup_description_btn').'" id="description" class="toggle show-title"><i class="fa fa-pencil-square-o"></i></a>';
						} 

						if($single->comments == 'enable') {

							echo '<a href="#" title="'.get_option('mockup_comment_btn').'" id="comment" class="toggle show-title"><i class="fa fa-comments-o"></i></a>';
						} 

						if($single->terms && !is_wp_error($single->terms)) {

							echo '<a href="#" title="'.get_option('mockup_related_btn').'" id="related" class="toggle show-title"><i class="fa fa-list"></i></a>';

						} ?>

						<a href="#" title="<?php echo get_option('mockup_approve_btn'); ?>" id="approve" class="toggle show-title"><i class="fa fa-check-square-o"></i></a>

					</div>
					
				</div>

			</div>

		</body>

	<?php } // End of password protection ?>

</html>