<?php $single = new MockUpSingle(); ?>
<!DOCTYPE html>

<html>

	<head>

		<meta name="robots" content="noindex, nofollow" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="cache-control" content="max-age=0" />
		<meta http-equiv="cache-control" content="no-cache" />
		<meta http-equiv="expires" content="0" />
		<meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
		<meta http-equiv="pragma" content="no-cache" />

		<title><?php wp_title('|', true, 'right'); ?></title>

		<link rel='stylesheet' id='dashicons-css'  href='<?php echo includes_url('css'); ?>/dashicons.min.css?ver=<?php echo MOCKUP_WP_VERSION; ?>' type='text/css' media='all' />
		<link href="<?php echo plugins_url('mockup'); ?>/include/css/single.min.css?ver=<?php echo MOCKUP_VERSION; ?>" rel="stylesheet" />
		<style type="text/css">

			<?php // Get import font.
			echo get_option('mockup_importfont'); ?>

			body {
				background-image: url('<?php echo $single->url_background; ?>'); 
				background-repeat: <?php echo $single->position_background; ?>;
			}

			div.sidebar {
				width: <?php echo $single->sidebarsize; ?>px;
				margin-left: -<?php echo $single->sidebarsize; ?>px;
			}

			.tooltip,
			h1, p, a, li {
				font-family: <?php echo $single->fontfamily; ?>;
			}

			a.active span.dashicons,
			a.toggle:hover span.dashicons {
				color: <?php echo $single->activecolor; ?> !important;
			}

			.btn,
			input,
			textarea {
				font-family: <?php echo $single->fontfamily; ?>;
			}

			<?php // Get custom style.
			echo get_option('mockup_style_general'); ?>

		</style>

		<script type='text/javascript' src='<?php echo includes_url('js/jquery'); ?>/jquery.js?ver=<?php echo MOCKUP_VERSION; ?>'></script>
		<script type="text/javascript">
			post_id = "<?php echo $single->postID; ?>";
			images_url = "<?php echo admin_url('images'); ?>";
			ajax_url = "<?php echo admin_url('admin-ajax.php'); ?>";
			silidebox_size = "<?php echo $single->sidebarsize; ?>";
		</script>
		<script src="<?php echo plugins_url('mockup'); ?>/include/js/single.min.js"></script>

	</head>

	<?php if($single->mockup_check_password()) {
		// Password protected area.

		echo '<body>';

			echo '<div class="overlay-password">';

				echo $single->mockup_password_form();

			echo '</div>';

		echo '</body>';

	} else {
		// Show mockup.

		$menu = $single->mockup_single_menu(); ?>

		<body style="background-color: <?php echo $single->bgcolor; ?>;">


			<div class="overlay"></div>


			<div class="mockup" style="height: <?php echo $single->height.'px'; ?>; min-width: <?php echo $single->width.'px'; ?>; background-image: url('<?php echo $single->url; ?>'); background-position:<?php echo $single->position; ?>;"></div>


			<div class="navbar"><?php echo $menu; ?></div>

			<div class="sidebar">

				<a href="#" id="close"><span class="dashicons dashicons-dismiss"></span></a>

				<div id="load_content_here" class="content"></div>

				<div class="scroll">

					<a href="#" class="scroll-up"><span class="dashicons dashicons-arrow-up-alt2"></span></a>
					<a href="#" class="scroll-down"><span class="dashicons dashicons-arrow-down-alt2"></span></a>

				</div>

			</div>

		</body>
		
	<?php } ?>

</html>