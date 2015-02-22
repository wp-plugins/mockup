<?php
/*
 * Plugin Name:       MockUp
 * Plugin URI:        http://www.mockupplugin.com
 * Description:       MockUp helps you to present your designs professionally.
 * Version:           1.5.3
 * Author:            Eelco Tjallema
 * Author URI:        http://estjallema.nl?utm_medium=mockup
 * License:           GPL2
 */
if(!defined('WPINC')) exit();


if(!class_exists('MockUp')) {

	define('MOCKUP_POSTTYPE',           'pt_mockup_plugin');
	define('MOCKUP_TAXONOMY',           'relate_mockup');
	define('MOCKUP_OPTIONSPAGE_SLUG',   'mockup_options');
	define('MOCKUP_VERSION',            '1.5.3');
	define('MOCKUP_UPGRADE_VERSION',    '1.3.0');
	define('MOCKUP_WP_VERSION',         get_bloginfo('version'));

	class MockUp {

		public function __construct() {
			load_textdomain('MockUp', __DIR__.'/languages/MockUp-'.get_locale().'.mo');
		}

		public function mockup_options_settings_array() {

			$options = array(
				'mockup_default_background_color'   => '#ffffff',
				'mockup_sidebar'                   => 'light',

				'mockup_email'                      => get_option('admin_email'),
				'mockup_email_settings'             => 'email_always',
				'mockup_menu_position'              => 160
			);

			return $options;
		}

		public function mockup_options_text_array() {

			$options = array(
				'mockup_related_btn'               =>  __('Show related MockUps', 'MockUp'),
				'mockup_description_btn'           =>  __('Show description', 'MockUp'),
				'mockup_comment_btn'               =>  __('Write a comment', 'MockUp'),
				'mockup_send_btn'                  =>  __('Send', 'MockUp'),
				'mockup_approve_btn'               =>  __('Approve', 'MockUp'),

				'mockup_related_title'             =>  __('Related MockUps', 'MockUp'),
				'mockup_description_title'         =>  __('MockUp description', 'MockUp'),
				'mockup_comment_title'             =>  __('Write a comment', 'MockUp'),
				'mockup_approve_title'             =>  __('Approve this MockUp', 'MockUp'),
				'mockup_approve_text'              =>  __('Are you sure you want to approve this MockUp?', 'MockUp'),

				'mockup_comment_name_label'        =>  __('Name', 'MockUp'),
				'mockup_comment_message_label'     =>  __('Comments', 'MockUp'),
				'mockup_comment_no_comments'       =>  __('Here you can add your comments on this MockUp.', 'MockUp'),
				'mockup_approved_text'             =>  __('You approved this MockUp', 'MockUp'),

				'mockup_locked_title'             =>  __('To view this protected MockUp, enter the password below:', 'MockUp'),
			);

			return $options;
		}


		public function mockup_options_style_array() {

			$options = array(
				'mockup_color_active'	 => '#21759b',
				'mockup_importfont'		 => '',
				'mockup_fontfamily'		 => "'Helvetica Neue', Helvetica, Arial, sans-serif",
				'mockup_style_general'   => ''
			);

			return $options;
		}


		public function mockup_options_advanced_array() {

			$options = array(
				'mockup_password_settings'   => 'default'
			);

			return $options;
		}
	}


	class MockUpCore extends MockUp {

		public function mockup_posttype() {

			$pos = intval(get_option('mockup_menu_position'));
			if(empty($pos)) $pos = 160;

			$labels = array(
				'name'                  => __('MockUp\'s', 'MockUp'),
				'menu_name'             => __('MockUp', 'MockUp'),
				'singular_name'         => __('MockUp', 'MockUp'),
				'all_items'             => __('All MockUps', 'MockUp'),
				'add_new'               => __('Add new MockUp', 'MockUp'),
				'add_new_item'          => __('Add new MockUp', 'MockUp'),
				'edit'                  => __('Edit', 'MockUp'),
				'edit_item'             => __('Edit MockUp', 'MockUp'),
				'new_item'              => __('New MockUp', 'MockUp'),
				'view'                  => __('View MockUp', 'MockUp'),
				'view_item'             => __('View MockUp', 'MockUp'),
				'search_items'          => __('Search MockUps', 'MockUp'),
				'not_found'             => __('No MockUps found', 'MockUp'),
				'not_found_in_trash'    => __('No MockUps found in Trash', 'MockUp'),
				'parent'                => __('Parent MockUp', 'MockUp')
			);

			$args = array(
				'labels'                => $labels,
				'public'                => true,
				'exclude_from_search'   => true,
				'show_ui'               => true,
				'show_in_nav_menus'     => false,
				'rewrite'               => array('slug' => 'mockup'),
				'supports'              => array('title', 'page-attributes'),//,'custom-fields'
				'menu_position'         => $pos,
				'menu_icon'             => 'dashicons-art'
			);

			register_post_type(MOCKUP_POSTTYPE, $args);
		}


		public function mockup_taxonomy() {

			$labels = array(
				'name'              => __('Customers' ,'MockUp'),
				'singular_name'     => __('Customer' ,'MockUp'),
				'search_items'      => __('Search customers' ,'MockUp'),
				'all_items'         => __('All customers' ,'MockUp'),
				'parent_item'       => __('Parent customer' ,'MockUp'),
				'parent_item_colon' => __('Parent customer' ,'MockUp'),
				'edit_item'         => __('Edit customer' ,'MockUp'),
				'update_item'       => __('Update customer' ,'MockUp'),
				'add_new_item'      => __('Add new customer' ,'MockUp'),
				'new_item_name'     => __('New customer name' ,'MockUp'),
				'menu_name'         => __('Customers' ,'MockUp')
			);

			$args = array(
				'labels'            => $labels,
				'rewrite'           => array( 'slug' => 'related', 'with_front' => false),
				'show_admin_column' => true,
				'hierarchical'      => true
			);

			register_taxonomy(MOCKUP_TAXONOMY, MOCKUP_POSTTYPE, $args);
		}


		public function mockup_templates() {

			if(is_singular(MOCKUP_POSTTYPE)) {

				include('view/single.php');
				exit();
			}
		}

		public function mockup_flushrules() { // Activation

			$this->mockup_posttype();
			$this->mockup_taxonomy();

			flush_rewrite_rules();
		}
	}


	class MockUpOptions extends MockUp {

		public $group = 'mockup_option_group';

		public function mockup_optionspage() {

			$parentslug     = 'edit.php?post_type='.MOCKUP_POSTTYPE;
			$pagetitle      = __('MockUp Options & Settings', 'MockUp');
			$menutitle      = __('Settings', 'MockUp');
			$capability     = 'manage_options';
			$function       = array($this, 'mockup_optionspage_include');

			add_submenu_page($parentslug, $pagetitle, $menutitle, $capability, MOCKUP_OPTIONSPAGE_SLUG, $function);
		}


		public function mockup_optionspage_include() {

			include_once 'view/options.php';
		}


		public function mockup_pluginlink($links) {
			$links[] = '<a href="'.get_admin_url(NULL, 'edit.php?post_type='.MOCKUP_POSTTYPE.'&page='.MOCKUP_OPTIONSPAGE_SLUG).'">'.__('Settings', 'MockUp').'</a>';
			return $links;
		}


		public function mockup_register_settings() {

			foreach ($this->mockup_options_settings_array() as $key => $value) {

				$option_group = $this->group.'_settings';

				register_setting($option_group, $key);
			}

			foreach ($this->mockup_options_text_array() as $key => $value) {

				$option_group = $this->group.'_text';

				register_setting($option_group, $key);
			}

			foreach ($this->mockup_options_style_array() as $key => $value) {

				$option_group = $this->group.'_style';

				register_setting($option_group, $key);
			}

			foreach ($this->mockup_options_advanced_array() as $key => $value) {

				$option_group = $this->group.'_advanced';

				register_setting($option_group, $key);
			}

		}


		public function mockup_add_options() { // Activation

			foreach ($this->mockup_options_settings_array() as $key => $value) {

				add_option($key, $value);
				$uninstall[] = $key;
			}

			foreach ($this->mockup_options_text_array() as $key => $value) {

				add_option($key, $value);
				$uninstall[] = $key;
			}

			foreach ($this->mockup_options_style_array() as $key => $value) {

				add_option($key, $value);
				$uninstall[] = $key;
			}

			foreach ($this->mockup_options_advanced_array() as $key => $value) {

				add_option($key, $value);
				$uninstall[] = $key;
			}

			update_option('mockup_uninstall', $uninstall);
		}
	}


	class MockUpBackend {
		public function mockup_enqueue() {

			wp_register_style( 'mockup_css', plugin_dir_url(__FILE__).'include/css/admin.min.css', array('wp-color-picker'), MOCKUP_VERSION);
			wp_register_script('mockup_js', plugin_dir_url(__FILE__).'include/js/admin.min.js', array('wp-color-picker'), MOCKUP_VERSION);

			$translation_array = array(
				'popup_title' => __('Select or upload the MockUp', 'MockUp'),
				'popup_button' => __('Add MockUp', 'MockUp'),
				'confirm' => __('Are you sure?', 'MockUp'),
			);

			wp_localize_script('mockup_js', 'mockupL10n', $translation_array);

			wp_enqueue_media();
			wp_enqueue_style('mockup_css');
			wp_enqueue_script('mockup_js');
		}
		public function mockup_taxonomy_filter() {

			global $typenow, $post, $post_id;

			if(isset($_GET[MOCKUP_TAXONOMY])) $selected = $_GET[MOCKUP_TAXONOMY];
			else $selected = false;

			$args = array(
				'orderby'           => 'name', 
				'order'             => 'ASC',
				'hide_empty'        => false,
				'parent'            => 0,
			); 

			$terms = get_terms(MOCKUP_TAXONOMY, $args);

			echo '<select name='.MOCKUP_TAXONOMY.' id='.MOCKUP_TAXONOMY.' class="postform">';

				echo '<option value="">'.__('Show all customers', 'MockUp').'</option>';

				foreach($terms as $term) { 
					if($selected && $selected == $term->slug) $select = ' selected="selected"';
					else $select = '';
					echo '<option value="'.$term->slug.'"'.$select.'>'.$term->name.'</option>';
					$args = array(
						'orderby'           => 'name', 
						'order'             => 'ASC',
						'hide_empty'        => true,
						'child_of'          => $term->term_id
					);

					$childTerms = get_terms(MOCKUP_TAXONOMY, $args);

					foreach($childTerms as $childTerm) {
						if($selected && $selected == $childTerm->slug) $select = ' selected="selected"';
						else $select = '';

						echo '<option value="'.$childTerm->slug.'"'.$select.'> - '.$childTerm->name.' ('.$childTerm->count.')</option>';
					}
				}

			echo '</select>';
		}
		public function mockup_admincolumn_comments_title($defaults) {

			$comments = array();

			foreach($defaults as $key => $title) {

				if($key == 'title')
					$comments['mockup_comments'] = '<div class="comment-grey-bubble"></div>';
					$comments[$key] = $title;
			}

			return $comments;
		}


		public function mockup_admincolumn_comments($column_name, $post_ID) {

			if($column_name == 'mockup_comments') {

				$number = count(get_post_meta(get_the_ID(), '_mockup_comments_1'));

				if(empty($number)) $number = 0;

				echo '<span><strong>'.$number.'<strong></span>';
			}
		}


		public function mockup_admincolumn_status_title($defaults) {

			$status = array();

			foreach($defaults as $key => $title) {

				if($key == 'taxonomy-'.MOCKUP_TAXONOMY)
					$status['mockup_status'] = __('Status', 'MockUp');
					$status[$key] = $title;
			}

			return $status;
		}


		public function mockup_admincolumn_status($column_name, $post_ID) {

			if($column_name == 'mockup_status') {

				$status = get_post_meta($post_ID, '_mockup_status_1', true);

				if(isset($status['approved']) && $status['approved'] == true) {

					echo '<span>'.__('Approved', 'MockUp').'</span>';
				}
			}
		}
		public function mockup_content($post) {

			if($post->post_type == MOCKUP_POSTTYPE) {

				$mockup_description = get_post_meta($post->ID, '_mockup_description_1', true);
				$content = $mockup_description;
				$editor_id = 'mockup_description_1';

				$settings = array(

					'media_buttons' => false,
					'textarea_rows' => 10,
					'teeny' => true,
				);

				wp_editor($content, $editor_id, $settings);
			}
		}
		function mockup_glance_items($items = array()) {

				$num_posts = wp_count_posts(MOCKUP_POSTTYPE);
				
				if($num_posts) {
					
					$number = intval( $num_posts->publish );
					$post_type = get_post_type_object(MOCKUP_POSTTYPE);


					$text = _n('%s ' . $post_type->labels->singular_name, '%s ' . $post_type->labels->name, $number, 'MockUp');
					$text = sprintf($text, number_format_i18n($number));
					
					if(current_user_can( $post_type->cap->edit_posts)) {
						$items[] = sprintf( '<a class="mockup-count" href="edit.php?post_type=%1$s">%2$s</a>', MOCKUP_POSTTYPE, $text) . "\n";
					} else {
						$items[] = sprintf( '<span class="mockup-count">%1$s</span>', $text ) . "\n";
					}
				}

			return $items;
		}
	}


	class MockUpMetaboxes {

		public function mockup_metabox($post_type) {

			if($post_type == MOCKUP_POSTTYPE) {

				$id             = 'mockup_metabox_images';
				$title          =  __( 'Image\'s', 'MockUp' );
				$callback       = array($this, 'mockup_metabox_images');
				$post_type      = MOCKUP_POSTTYPE;
				$context        = 'normal';
				$priority       = 'high';

				add_meta_box($id, $title, $callback, $post_type, $context, $priority);



				$id             = 'mockup_metabox_comments';
				$title          =  __( 'Comments', 'MockUp' );
				$callback       = array($this, 'mockup_metabox_comments');
				$post_type      = MOCKUP_POSTTYPE;
				$context        = 'normal';
				$priority       = 'core';

				add_meta_box($id, $title, $callback, $post_type, $context, $priority);


				$id             = 'mockup_metabox_settings';
				$title          =  __( 'Settings', 'MockUp' );
				$callback       = array($this, 'mockup_metabox_settings');
				$post_type      = MOCKUP_POSTTYPE;
				$context        = 'normal';
				$priority       = 'low';

				add_meta_box($id, $title, $callback, $post_type, $context, $priority);
			}
		}


		public function mockup_metabox_images($post) {

			include_once 'view/metabox_images.php';
		}


		public function mockup_metabox_comments($post) {

			include_once 'view/metabox_comments.php';
		}


		public function mockup_metabox_settings($post) {

			include_once 'view/metabox_settings.php';
		}


		public function mockup_save($post_id) {

			if(!isset($_POST['mockupnonce_backend']))
				return $post_id;

			if(!wp_verify_nonce($_POST['mockupnonce_backend']))
				return $post_id;


			if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) 
				return $post_id;


			if($_POST['post_type'] == MOCKUP_POSTTYPE && !current_user_can('edit_page', $post_id))
				return $post_id;
			$allowed_html = array(
				'a' => array(
					'href' => array(),
					'title' => array(),
					'target' => array()
				),
				'span' => array(
					'style' => array()
				),
				'br' => array(),
				'em' => array(),
				'strong' => array(),
			);
			$mockup_id                  = sanitize_text_field($_POST['mockup_id']);
			$mockup_description         = wp_kses($_POST['mockup_description_1'], $allowed_html);
			if(isset($_POST['mockup_position_1']))
				$mockup_position = sanitize_text_field($_POST['mockup_position_1']);
			$mockup_background_color    = sanitize_text_field($_POST['mockup_background_color_1']);
			$mockup_background_position = sanitize_text_field($_POST['mockup_background_position_1']);
			$mockup_comment_settings    = sanitize_text_field($_POST['mockup_comment_settings_1']);
			$mockup_email_settings      = sanitize_text_field($_POST['mockup_email_settings_1']);
			$mockup_email               = sanitize_text_field($_POST['mockup_email_1']);
			update_post_meta($post_id, '_mockup_id_1', $mockup_id);
			update_post_meta($post_id, '_mockup_description_1', $mockup_description);
			if(isset($_POST['mockup_position_1']))
				update_post_meta($post_id, '_mockup_position_1', $mockup_position);
			update_post_meta($post_id, '_mockup_background_color_1', $mockup_background_color);
			update_post_meta($post_id, '_mockup_background_position_1', $mockup_background_position);
			update_post_meta($post_id, '_mockup_comment_settings_1', $mockup_comment_settings);
			update_post_meta($post_id, '_mockup_email_settings_1', $mockup_email_settings);
			update_post_meta($post_id, '_mockup_email_1', $mockup_email);
		}
	}


	class MockUpAjaxBackend {

		public function mockup_set_image() {

			$nonce = sanitize_text_field($_POST['mockupnonce_backend']);
			$postid = sanitize_text_field($_POST['postid']);
			$img_action = sanitize_text_field($_POST['img_action']);
			$mockup_id = sanitize_text_field($_POST['mockup_id']);

			if(!empty($postid) && !empty($mockup_id) && !empty($img_action) && !empty($nonce) && wp_verify_nonce($nonce)) {

				if($img_action == 'mockup') {
					update_post_meta($postid, '_mockup_id_1', $mockup_id);
				} elseif($img_action == 'background') {
					update_post_meta($postid, '_mockup_background_id_1', $mockup_id);
				}

				$image_data = wp_get_attachment_image_src($mockup_id, 'full');
				echo $image_data[0].'{split}'.$image_data[2];
			}

			exit();
		}

		public function mockup_delete_image() {

			$nonce = sanitize_text_field($_POST['mockupnonce_backend']);
			$postid = sanitize_text_field($_POST['postid']);
			$img_action = sanitize_text_field($_POST['img_action']);
			$mockup_id = sanitize_text_field($_POST['mockup_id']);

			if(!empty($postid) && !empty($mockup_id) && !empty($img_action) && !empty($nonce) && wp_verify_nonce($nonce)) {

				if($img_action == 'mockup') {
					delete_post_meta($postid, '_mockup_id_1', $mockup_id);
				} elseif($img_action == 'background') {
					delete_post_meta($postid, '_mockup_background_id_1', $mockup_id);
				}
			}

			exit();
		}


		public function mockup_unapprove() {

			$nonce = sanitize_text_field($_POST['mockupnonce_backend']);
			$postid = sanitize_text_field($_POST['postid']);

			if(!empty($postid) && !empty($nonce) && wp_verify_nonce($nonce)) {

				delete_post_meta($postid, '_mockup_status_1');
			}

			exit();
		}


		public function mockup_delete_comment() {

			$nonce = sanitize_text_field($_POST['mockupnonce_backend']);
			$postid = sanitize_text_field($_POST['postid']);
			$commentid = sanitize_text_field($_POST['commentid']);

			$comments = get_post_meta($postid, '_mockup_comments_1', false);

			if(!empty($postid) && !empty($nonce) && wp_verify_nonce($nonce)) {

				delete_post_meta($postid, '_mockup_comments_1', $comments[$commentid]);
			}

			exit();
		}
	}


	class MockUpSingle {

		public function __construct() {
			$this->postID = get_the_ID();
			$this->mockupID = get_post_meta($this->postID, '_mockup_id_1', true);
			$this->mockup_backgroundID = get_post_meta($this->postID, '_mockup_background_id_1', true);
			$this->description = get_post_meta($this->postID, '_mockup_description_1', true);
			$this->comments = get_post_meta($this->postID, '_mockup_comment_settings_1', true);
			$this->terms = get_the_terms($this->postID, MOCKUP_TAXONOMY);
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
			$image_data = wp_get_attachment_image_src($this->mockupID, 'full');

			$this->url = $image_data[0];
			$this->width = $image_data[1];
			$this->height = $image_data[2];
			if(empty($this->width) && !empty($this->url) && function_exists('getimagesize')) {

				$image_data = getimagesize($this->url);
				$this->width = $image_data[0];
			}

			if(empty($this->height) && !empty($this->url) && function_exists('getimagesize')) {

				$image_data = getimagesize($this->url);
				$this->height = $image_data[1];
			}
			$image_data = wp_get_attachment_image_src($this->mockup_backgroundID, 'full');
			$this->url_background = $image_data[0];
			$this->position = get_post_meta($this->postID, '_mockup_position_1', true);
			$this->position_background = get_post_meta($this->postID, '_mockup_background_position_1', true);
			$this->bgcolor = get_post_meta($this->postID, '_mockup_background_color_1', true);
			$this->activecolor = get_option('mockup_color_active');
			$this->sidebarsize = '300';
			$this->fontfamily = get_option('mockup_fontfamily');
		}


		public function mockup_terms($password) {

			if($this->terms && !is_wp_error($this->terms)) {

				$term_id_array = array();

				foreach($this->terms as $term) {

					$term_id_array[] = $term->term_id;
				}

				$args = array(
					'post_type'         => MOCKUP_POSTTYPE,
					'orderby'           => 'menu_order',
					'order'             => 'ASC',
					'has_password'      => $password, // true, false or null
					'posts_per_page'    => -1,
					'tax_query'         => array(
						array(
							'taxonomy'  => MOCKUP_TAXONOMY,
							'terms'     => $term_id_array
						)
					)
				);

				return $query = new WP_Query($args);
			}

			return false;
		}


		public function mockup_single_menu() {
			
			$menu = null;
			$terms = $this->mockup_terms(null);
			if(!empty($this->description))
				$menu .= '<a href="#" title="'.get_option('mockup_description_btn').'" id="description" class="toggle show-title"><span class="dashicons dashicons-welcome-write-blog"></span></a>';
			if($this->comments == 'enable')
				$menu .= '<a href="#" title="'.get_option('mockup_comment_btn').'" id="comment" class="toggle show-title"><span class="dashicons dashicons-format-chat"></span></a>';
			if(isset($terms->found_posts) && $terms->found_posts > 1)
				$menu .= '<a href="#" title="'.get_option('mockup_related_btn').'" id="related" class="toggle show-title"><span class="dashicons dashicons-editor-ul"></span></a>';
			$menu .= '<a href="#" title="'.get_option('mockup_approve_btn').'" id="approve" class="toggle show-title"><span class="dashicons dashicons-yes"></span></a>';

			return $menu; 
		}


		public function mockup_check_password() {
			if(post_password_required()) return true;
			$password_settings = get_option('mockup_password_settings');

			if($password_settings == 'inherit') {

				$query = $this->mockup_terms(true);

				if(empty($query)) return false;

				while($query->have_posts()): $query->the_post();

					if(post_password_required()) {
						wp_reset_postdata();
						return true;
					} 

				endwhile;

				wp_reset_postdata();
			}

			return false;
		}


		public function mockup_password_form() {

			global $post;

			$label = 'pwbox-'.(empty($post->ID ) ? rand() : $post->ID);

			$form = '<form class="password" action="'.esc_url(site_url('wp-login.php?action=postpass', 'login_post')).'" method="post">';
			$form .= '<span class="dashicons dashicons-lock"></span>';
			$form .= '<p>'.get_option('mockup_locked_title').'</p>';
			$form .= '<input class="field" name="post_password" id="'.$label.'" type="password" size="20" maxlength="20" /><br />';
			$form .= '<input type="submit" class="btn" name="Submit" value="'.get_option('mockup_send_btn').'" />';
			$form .= '</form>';

			return $form;
		}
	}


	class MockUpAjaxSingle {


		public function mockup_single_description() {

			$id = filter_input(INPUT_POST, 'post_id', FILTER_SANITIZE_NUMBER_INT);

			if(!empty($id)) {

				echo '<h1>'.get_option('mockup_description_title').'</h1>';
				echo '<p>'.nl2br(get_post_meta($id, '_mockup_description_1', true)).'</p>';
			}

			exit();
		}


		public function mockup_single_comment() {

			$id = filter_input(INPUT_POST, 'post_id', FILTER_SANITIZE_NUMBER_INT);
			echo '<h1>'.get_option('mockup_comment_title').'</h1>';

			echo '<form role="form">';

				wp_nonce_field(-1, 'mockupnonce_frontend');

				echo '<input type="text" id="comment_name" class="field not-empty" placeholder="'.get_option('mockup_comment_name_label').'">';
				echo '<textarea id="comment_text" class="field not-empty" placeholder="'.get_option('mockup_comment_message_label').'"></textarea>';

				echo '<button type="submit" id="comment_submit" class="submit btn">'.get_option('mockup_send_btn').'</button>';

			echo '</form>';
			$comments = get_post_meta($id, '_mockup_comments_1', false);
			$num = count($comments);
			$i = 0;

			if(!empty($comments)) {

				krsort($comments);

				foreach($comments as $comment) {

					$i++;
					if($i == '1') $class = ' first-comment';
					elseif($i == $num) $class = ' last-comment';
					else $class = '';

					echo '<div class="comment'.$class.'">';

						echo '<p><strong>'.$comment['name'].'</strong> ('.human_time_diff($comment['time']).' '.__('ago', 'MockUp').'):<br />';
						echo nl2br($comment['text']).'</p>';

					echo '</div>';

				}

			} else {

				echo '<div class="comment">';

					echo '<p>'.get_option('mockup_comment_no_comments').'</p>';

				echo '</div>';
			}

			exit();
		}


		public function mockup_process_comment() {
			$allowed_html = array(
				'br' => array(),
				'em' => array(),
				'strong' => array(),
			);

			$id = filter_input(INPUT_POST, 'post_id', FILTER_SANITIZE_NUMBER_INT);
			$nonce = filter_input(INPUT_POST, 'mockupnonce_frontend', FILTER_UNSAFE_RAW);
			$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
			$text = wp_kses($_POST['text'], $allowed_html);

			if(!empty($id) && !empty($nonce) && !empty($name) && !empty($text) && wp_verify_nonce($nonce)) {
				$comment = array('name' => $name, 'text' => $text, 'time' => time());

				add_post_meta($id, '_mockup_comments_1', $comment);
				$email_settings = get_post_meta($id, '_mockup_email_settings_1', true );

				if($email_settings == 'email_always' || $email_settings == 'email_comments') {

					$to = get_post_meta($id, '_mockup_email_1', true);
					if(empty($to)) $to = get_option('mockup_email');

					$subject = sprintf(__('%s made comments on MockUp %s', 'MockUp'), $name, get_the_title($id));
					$message = $text;
					$message .= "\n\n";
					$message .= sprintf(__('Link: %s', 'MockUp'), get_permalink($id));

					wp_mail($to, $subject, $message);
				}
				$this->mockup_single_comment();
			}

			exit();
		}


		public function mockup_single_approve() {

			$id = filter_input(INPUT_POST, 'post_id', FILTER_SANITIZE_NUMBER_INT);
			$status = get_post_meta($id, '_mockup_status_1', true);

			echo '<h1>'.get_option('mockup_approve_title').'</h1>';

			if(isset($status['approved']) && $status['approved'] == true) {

				echo '<p>'.get_option('mockup_approved_text').'</p>';
				exit();
			}

			echo '<p>'.nl2br(get_option('mockup_approve_text')).'</p>';

			echo '<form role="form">';

				wp_nonce_field(-1, 'mockupnonce_frontend');

				echo '<input type="text" id="approve_name" class="field not-empty" placeholder="'.get_option('mockup_comment_name_label').'">';

				echo '<button type="submit" id="approve_submit" class="submit btn">'.get_option('mockup_approve_btn').'</button>';

			echo '</form>';

			exit();
		}


		public function mockup_process_approve() {

			$id = filter_input(INPUT_POST, 'post_id', FILTER_SANITIZE_NUMBER_INT);
			$nonce = filter_input(INPUT_POST, 'mockupnonce_frontend', FILTER_UNSAFE_RAW);
			$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);

			if(!empty($id) && !empty($nonce) && wp_verify_nonce($nonce)) {

				$status = array('approved' => true, 'approved_name' => $name, 'approved_time' => time());

				update_post_meta($id, '_mockup_status_1', $status);
				$email = get_post_meta($id, '_mockup_email_settings_1', true);

				if($email == 'email_always' || $email == 'email_approved') {

					$to = get_post_meta($id, '_mockup_email_1', true);
					if(empty($to)) $to = get_option('mockup_email');

					$subject = sprintf(__('Mockup %s is approved', 'MockUp'), get_the_title($id));
					$message = sprintf(__('Your MockUp %s has been approved by %s', 'MockUp'), get_the_title($id), $name);
					$message .= "\n\n";
					$message .= sprintf(__('Link: %s', 'MockUp'), get_permalink($id));

					wp_mail($to, $subject, $message);
				}

				echo '<h1>'.get_option('mockup_approve_title').'</h1>';
				echo '<p>'.get_option('mockup_approved_text').'</p>';
			}

			exit();
		}


		public function mockup_single_related() {

			$id = filter_input(INPUT_POST, 'post_id', FILTER_SANITIZE_NUMBER_INT);
			$terms = get_the_terms($id, MOCKUP_TAXONOMY);


			if($terms && !is_wp_error($terms)) {

				echo '<h1>'.get_option('mockup_related_title').'</h1>';

				$term_id_array = array();

				foreach($terms as $term) {

					$term_id_array[] = $term->term_id;
				}

				$args = array(
					'post_type'         => MOCKUP_POSTTYPE,
					'orderby'           => 'menu_order',
					'order'             => 'ASC',
					'posts_per_page'    => -1,
					'tax_query'         => array(
						array(
							'taxonomy'  => MOCKUP_TAXONOMY,
							'terms'     => $term_id_array
						)
					)
				);

				$query = new WP_Query($args);

				echo '<ul>';

				while($query->have_posts()) {
					$query->the_post();

					$tmp_id = get_the_ID();
					$mockup = get_post_meta($tmp_id, '_mockup_id_1', true);
					$status = get_post_meta($tmp_id, '_mockup_status_1', true);

					if(empty($mockup)) continue;

					if($tmp_id == $id && !empty($status['approved']) && $status['approved'] == true) {
						echo '<li>'.get_the_title().'</li>';

					} elseif($tmp_id == $id && empty($status['approved'])) {
						echo '<li>'.get_the_title().'</li>';

					} elseif($tmp_id != $id && !empty($status['approved']) && $status['approved'] == true) {
						echo '<li><a href="'.get_permalink().'">'.get_the_title().'</a></li>';

					} else {

						echo '<li><a href="'.get_permalink().'">'.get_the_title().'</a></li>';
					}
				}

				echo '</ul>';
			}

			exit();
		}
	}
	function MockUpActivation() {

		$options = new MockUpOptions();
		$options->mockup_add_options();

		$core = new MockUpCore();
		$core->mockup_flushrules();
		update_option('mockup_upgrade', MOCKUP_VERSION);
	}
	function runMockUp() {

		$MockUpCore = new MockUpCore();
		add_action('init', array($MockUpCore, 'mockup_posttype'), 1);
		add_action('init', array($MockUpCore, 'mockup_taxonomy'), 1);
		add_action('template_redirect', array($MockUpCore, 'mockup_templates'), 1);
		$ajaxSingle = new MockUpAjaxSingle();
		add_action('wp_ajax_mockup_single_description', array($ajaxSingle, 'mockup_single_description'));
		add_action('wp_ajax_nopriv_mockup_single_description', array($ajaxSingle, 'mockup_single_description'));

		add_action('wp_ajax_mockup_single_comment', array($ajaxSingle, 'mockup_single_comment'));
		add_action('wp_ajax_nopriv_mockup_single_comment', array($ajaxSingle, 'mockup_single_comment'));


		add_action('wp_ajax_mockup_single_related', array($ajaxSingle, 'mockup_single_related'));
		add_action('wp_ajax_nopriv_mockup_single_related', array($ajaxSingle, 'mockup_single_related'));


		add_action('wp_ajax_mockup_single_approve', array($ajaxSingle, 'mockup_single_approve'));
		add_action('wp_ajax_nopriv_mockup_single_approve', array($ajaxSingle, 'mockup_single_approve'));


		add_action('wp_ajax_mockup_process_comment', array($ajaxSingle, 'mockup_process_comment'));
		add_action('wp_ajax_nopriv_mockup_process_comment', array($ajaxSingle, 'mockup_process_comment'));


		add_action('wp_ajax_mockup_process_approve', array($ajaxSingle, 'mockup_process_approve'));
		add_action('wp_ajax_nopriv_mockup_process_approve', array($ajaxSingle, 'mockup_process_approve'));
		if(is_admin()) {
			$optionsPage = new MockUpOptions();
			add_action('admin_menu', array($optionsPage, 'mockup_optionspage'));
			add_action('admin_init', array($optionsPage, 'mockup_register_settings'));

			add_filter('plugin_action_links_'.plugin_basename(__FILE__), array($optionsPage, 'mockup_pluginlink'));
			$backend = new MockUpBackend();
			add_action('admin_enqueue_scripts', array($backend, 'mockup_enqueue'));
			add_action('restrict_manage_posts', array($backend, 'mockup_taxonomy_filter'));
			add_action('manage_'.MOCKUP_POSTTYPE.'_posts_custom_column', array($backend, 'mockup_admincolumn_comments'), 1, 2);
			add_action('manage_'.MOCKUP_POSTTYPE.'_posts_custom_column', array($backend, 'mockup_admincolumn_status'), 1, 2);
			add_action('edit_form_after_title', array($backend, 'mockup_content'));

			add_filter('manage_'.MOCKUP_POSTTYPE.'_posts_columns', array($backend, 'mockup_admincolumn_comments_title'));
			add_filter('manage_'.MOCKUP_POSTTYPE.'_posts_columns', array($backend, 'mockup_admincolumn_status_title'));
			add_filter('dashboard_glance_items', array($backend, 'mockup_glance_items'), 10, 1);
			$metaboxes = new MockUpMetaboxes();
			add_action('add_meta_boxes', array($metaboxes, 'mockup_metabox'));
			add_action('save_post', array($metaboxes, 'mockup_save'));
			$ajaxBackend = new MockUpAjaxBackend();
			add_action('wp_ajax_mockup_delete_image', array($ajaxBackend, 'mockup_delete_image'));
			add_action('wp_ajax_mockup_set_image', array($ajaxBackend, 'mockup_set_image'));
			add_action('wp_ajax_mockup_unapprove', array($ajaxBackend, 'mockup_unapprove'));
			add_action('wp_ajax_mockup_delete_comment', array($ajaxBackend, 'mockup_delete_comment'));
		}
	}

	register_activation_hook(__FILE__, 'MockUpActivation');
	runMockUp();
}