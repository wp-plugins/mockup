<?php
/*
Plugin Name: MockUp
Plugin URI: http://www.mockupplugin.com
Description: MockUp helps you to present your designs professionally.
Version: 1.3.0
Author: Eelco Tjallema
Author URI: http://www.estjallema.nl/en
License: GPL2
*/
if(!class_exists('MockUp')) {

	class MockUp {

		// Vars
		public $posttype = 'pt_mockup_plugin';

		public $taxonomy = 'relate_mockup';

		public function __construct() {

			// Vars
			$this->settings = array(
				'dir' 				=> __DIR__,
				'version'			=> '1.3.0',
				'upgrade_version'	=> '0.0.7',
				'wp_version'		=> str_replace('.', '', get_bloginfo('version')),
				'option_group' 		=> 'mockup_options'
			);

			$this->options = array(
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

				'mockup_default_background_color' 	=> '#ffffff',
				'mockup_slidebox' 					=> 'light',

				'mockup_email' 						=> get_option('admin_email'),
				'mockup_email_settings' 			=> 'email_always',
				'mockup_menu_position' 				=> 160
			);

			// Set the text domain
			load_textdomain('MockUp', __DIR__.'/languages/MockUp-'.get_locale().'.mo');

			// Actions
			add_action('init', array($this, 'mockup_posttype'), 1);
			add_action('init', array($this, 'mockup_taxonomy'), 1);
			add_action('admin_menu', array($this, 'mockup_optionspage'));
			add_action('template_redirect', array($this, 'mockup_templates'), 1);
			add_action('admin_init', array($this, 'mockup_register_settings'));
			add_action('manage_'.$this->posttype.'_posts_custom_column', array($this, 'mockup_admincolumn_comments'), 1, 2);
			add_action('manage_'.$this->posttype.'_posts_custom_column', array($this, 'mockup_admincolumn_status'), 1, 2);

			if(is_admin()) {
				add_action('restrict_manage_posts', array($this, 'mockup_taxonomy_filter'));
				add_action('add_meta_boxes', array($this, 'mockup_metabox'));
				add_action('save_post', array($this, 'mockup_save'));
				add_action('admin_enqueue_scripts', array($this, 'mockup_enqueue'));
				add_action('wp_ajax_mockup_delete_comment', array($this, 'mockup_delete_comment'));
				add_action('wp_ajax_mockup_delete_image', array($this, 'mockup_delete_image'));
				add_action('wp_ajax_mockup_set_image', array($this, 'mockup_set_image'));
				
			}


			// Filters
			add_filter('plugin_action_links_'.plugin_basename(__FILE__), array($this, 'mockup_pluginlink'));
			add_filter('manage_'.$this->posttype.'_posts_columns', array($this, 'mockup_admincolumn_comments_title'));
			add_filter('manage_'.$this->posttype.'_posts_columns', array($this, 'mockup_admincolumn_status_title'));
		}

		public function mockup_posttype() {

			if($this->settings['wp_version'] >= 38) $icon = 'dashicons-art';
			else $icon = plugins_url('include/img/ico.png', __FILE__);

			$pos = intval(get_option('mockup_menu_position'));
			if(empty($pos)) $pos = 160;

			$labels = array(
				'name'	 				=> __('MockUp', 'MockUp'),
				'all_items'	 			=> __('All MockUps', 'MockUp'),
				'singular_name'	 		=> __('MockUp', 'MockUp'),
				'add_new'	 			=> __('Add new MockUp', 'MockUp'),
				'add_new_item'	 		=> __('Add New MockUp', 'MockUp'),
				'edit'	 				=> __('Edit', 'MockUp'),
				'edit_item'	 			=> __('Edit MockUp', 'MockUp'),
				'new_item'	 			=> __('New MockUp', 'MockUp'),
				'view'	 				=> __('View MockUp', 'MockUp'),
				'view_item'				=> __('View MockUp', 'MockUp'),
				'search_items'	 		=> __('Search MockUps', 'MockUp'),
				'not_found'	 			=> __('No MockUps found', 'MockUp'),
				'not_found_in_trash'	=> __('No MockUps found in Trash', 'MockUp'),
				'parent'	 			=> __('Parent MockUp', 'MockUp')
			);

			$args = array(
				'labels' 				=> $labels,
				'public' 				=> true,
				'exclude_from_search' 	=> true,
				'show_ui' 				=> true,
				'show_in_nav_menus' 	=> false,
				'rewrite' 				=> array("slug" => __('mockup', 'MockUp')),
				'supports' 				=> array('title', 'page-attributes'),//,'custom-fields'
				'menu_position' 		=> $pos,
				'menu_icon' 			=> $icon
			);

			register_post_type($this->posttype, $args);
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
				'labels' 			=> $labels,
				'rewrite'			=> array( 'slug' => 'related', 'with_front' => false),
				'show_admin_column' => true,
				'hierarchical'      => true
			);

			register_taxonomy($this->taxonomy, $this->posttype, $args);
		}


		public function mockup_optionspage() {

			$parentslug 	= 'edit.php?post_type='.$this->posttype;
			$pagetitle 		= __('MockUp Options & Settings', 'MockUp');
			$menutitle 		= __('Settings', 'MockUp');
			$capability 	= 'manage_options';
			$menuslug 		= 'mockup_options';
			$function 		= array($this, 'mockup_optionspage_include');

			add_submenu_page($parentslug, $pagetitle, $menutitle, $capability, $menuslug, $function);
		}


		public function mockup_pluginlink($links) {
			$links[] = '<a href="'.get_admin_url(null, 'edit.php?post_type='.$this->posttype.'&page=mockup_options').'">'.__('Settings', 'MockUp').'</a>';
			return $links;
		}


		public function mockup_optionspage_include() {

			include_once 'core/view/options.php';
		}


		public function mockup_templates() {

			if(is_singular($this->posttype)) {

				include('core/view/single.php');
				exit();
			}
		}


		public function mockup_register_settings() {

			foreach($this->options as $name => $value) {

				$option_group = $this->settings['option_group'];
				$option_name = $name;

				register_setting($option_group, $option_name);
			}
		}

		// Taxonomy filter
		public function mockup_taxonomy_filter() {

			global $typenow, $post, $post_id;

			if($typenow == $this->posttype) {

				$taxonomies = get_object_taxonomies($this->posttype);

				if($taxonomies) {

					foreach ($taxonomies as $tax_slug) {

						$tax_obj = get_taxonomy($tax_slug);
						$tax_name = $tax_obj->labels->name;
						$terms = get_terms($tax_slug);

						echo '<select name='.$tax_slug.' id='.$tax_slug.' class="postform">';

							echo '<option value="">'.sprintf(__('Show all %s', 'MockUp'), $tax_name).'</option>';

							foreach($terms as $term) { 

								if(isset($_GET[$tax_slug]) && $_GET[$tax_slug] == $term->slug) {
									$select = ' selected="selected"';
								} else { 
									$select = '';
								}

								echo '<option value="'.$term->slug.'"'.$select.'>'.$term->name.' ('.$term->count.')</option>';
							}

						echo '</select>';
					}
				}
			}
		}


		// Admin colums
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

				if($key == 'taxonomy-'.$this->taxonomy)
					$status['mockup_status'] = 'Status';
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


		// Metaboxes
		public function mockup_metabox($post_type) {

			if($post_type == $this->posttype) {

				$id 			= 'mockup_metabox_images';
				$title 			=  __( 'Image', 'MockUp' );
				$callback 		= array($this, 'mockup_metabox_images');
				$post_type 		= $this->posttype;
				$context 		= 'normal';
				$priority 		= 'high';

				add_meta_box($id, $title, $callback, $post_type, $context, $priority);


				$id 			= 'mockup_metabox_content';
				$title 			=  __( 'Description', 'MockUp' );
				$callback 		= array($this, 'mockup_metabox_content');
				$post_type 		= $this->posttype;
				$context 		= 'normal';
				$priority 		= 'high';

				add_meta_box($id, $title, $callback, $post_type, $context, $priority);


				$id 			= 'mockup_metabox_comments';
				$title 			=  __( 'Comments', 'MockUp' );
				$callback 		= array($this, 'mockup_metabox_comments');
				$post_type 		= $this->posttype;
				$context 		= 'normal';
				$priority 		= 'core';

				add_meta_box($id, $title, $callback, $post_type, $context, $priority);


				$id 			= 'mockup_metabox_settings';
				$title 			=  __( 'Information and Settings', 'MockUp' );
				$callback 		= array($this, 'mockup_metabox_settings');
				$post_type 		= $this->posttype;
				$context 		= 'normal';
				$priority 		= 'low';

				add_meta_box($id, $title, $callback, $post_type, $context, $priority);


				$id 			= 'mockup_metabox_position';
				$title 			=  __( 'MockUp Position', 'MockUp' );
				$callback 		= array($this, 'mockup_metabox_position');
				$post_type 		= $this->posttype;
				$context 		= 'side';
				$priority 		= 'low';

				add_meta_box($id, $title, $callback, $post_type, $context, $priority);
			}
		}


		public function mockup_metabox_images($post) {

			include_once 'core/view/metabox_images.php';
		}


		public function mockup_metabox_content($post) {

			include_once 'core/view/metabox_content.php';
		}


		public function mockup_metabox_comments($post) {

			include_once 'core/view/metabox_comments.php';
		}


		public function mockup_metabox_settings($post) {

			include_once 'core/view/metabox_settings.php';
		}


		public function mockup_metabox_position($post) {

			include_once 'core/view/metabox_positions.php';
		}


		public function mockup_save($post_id) {


			if(!isset($_POST['mockup_metabox_settings_nonce']))
				return $post_id;

			$nonce = $_POST['mockup_metabox_settings_nonce'];


			if(!wp_verify_nonce($nonce, 'mockup_metabox_settings'))
				return $post_id;


			if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) 
				return $post_id;


			if('page' == $_POST['post_type']) {


			if(!current_user_can('edit_page', $post_id))
				return $post_id;

			} else {

			if(!current_user_can('edit_post', $post_id))
				return $post_id;
			}


			// Sanitize user input.
			$mockup_id 					= sanitize_text_field($_POST['mockup_id']);
			$mockup_description 		= $_POST['mockup_description'];
			$mockup_background_color 	= sanitize_text_field($_POST['mockup_background_color_1']);
			$mockup_comment_settings 	= sanitize_text_field($_POST['mockup_comment_settings_1']);
			$mockup_slidebox 			= sanitize_text_field($_POST['mockup_slidebox_1']);
			$mockup_email_settings 		= sanitize_text_field($_POST['mockup_email_settings_1']);
			$mockup_email 		 		= sanitize_email($_POST['mockup_email_1']);
			$mockup_position 			= sanitize_text_field($_POST['mockup_position']);
			

			// Update the meta field in the database.
			update_post_meta($post_id, '_mockup_id_1', $mockup_id);
			update_post_meta($post_id, '_mockup_description_1', $mockup_description);
			update_post_meta($post_id, '_mockup_background_color_1', $mockup_background_color);
			update_post_meta($post_id, '_mockup_comment_settings_1', $mockup_comment_settings);
			update_post_meta($post_id, '_mockup_slidebox_1', $mockup_slidebox);
			update_post_meta($post_id, '_mockup_email_settings_1', $mockup_email_settings);
			update_post_meta($post_id, '_mockup_email_1', $mockup_email);
			update_post_meta($post_id, '_mockup_position_1', $mockup_position);

			if(isset($_POST['mockup_unapprove'])) {

				delete_post_meta($post_id, '_mockup_status_1');
			}
		}


		// Add the CSS and JS files
		public function mockup_enqueue() {

			wp_register_style( 'mockup_css', plugin_dir_url(__FILE__).'include/css/admin.css', array('wp-color-picker'), $this->settings['version']);
			wp_register_script('mockup_js', plugin_dir_url(__FILE__).'include/js/admin.min.js', array('wp-color-picker') , $this->settings['version']);

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




		// View
		public function mockup_single_description() {

			include_once 'core/view/slidebox_description.php';
			exit();
		}


		public function mockup_single_comment() {

			include_once 'core/view/slidebox_comment.php';
			exit();
		}


		public function mockup_single_related() {

			include_once 'core/view/slidebox_related.php';
			exit();
		}


		public function mockup_single_approve() {

			include_once 'core/view/slidebox_approve.php';
			exit();
		}



		// Controllers
		public function mockup_process_comment() {

			include_once 'core/controllers/comment.php';
			exit();
		}


		public function mockup_show_comment($comments) {

			if(!empty($comments)) {

				krsort($comments);

				foreach($comments as $comment) {

					if(empty($comment)) continue;

					echo '<div class="comment">';

						echo '<p><span class="time">'.human_time_diff($comment['time']).' '.__('ago','MockUp').'</span><br />';
						echo '<strong>'.$comment['name'].' '.__('wrote', 'MockUp').': </strong>';
						echo nl2br($comment['text']).'</p>';

					echo '</div>';

				}
			} else {

				echo '<div class="comment">';

					echo '<p>'.get_option('mockup_comment_no_comments').'</p>';

				echo '</div>';
			}
		}

		public function mockup_set_image() {

			include_once 'core/controllers/image_set.php';
			exit();
		}

		public function mockup_delete_image() {

			include_once 'core/controllers/image_delete.php';
			exit();
		}

		public function mockup_delete_comment() {

			include_once 'core/controllers/comment_delete.php';
			exit();
		}


		public function mockup_process_approve() {

			include_once 'core/controllers/approve.php';
			exit();
		}


		// Save the option names for use in the uninstall.php
		public function mockup_save_options() {

			foreach($this->options as $name => $value) {
				$array[] = $name;
			}

			add_option('mockup_uninstall', $array);
		}


		public function mockup_add_options() {

			foreach($this->options as $name => $value) {
				add_option($name, $value);
			}
		}


		public function mockup_flushrules() {

			$this->mockup_posttype();
			$this->mockup_taxonomy();

			flush_rewrite_rules();
		}


		public function mockup_upgrade() {

			include_once 'core/controllers/upgrades.php';

			$upgrade = get_option('mockup_upgrade');

			if($upgrade == false || $upgrade < $this->settings['version']) {

				$args = array(
					'post_type' => 'pt_mockup_plugin',
					'posts_per_page' => -1
				);

				$the_query = new WP_Query($args);

				if($the_query->have_posts()) {

					while($the_query->have_posts()) {

						$the_query->the_post();
						$id = get_the_ID();

						// Start update
						if(!get_post_meta($id, 'mockup_update')) {
							mockup_update_107($id);
						}

						mockup_update_111($id);
					}
				}

				wp_reset_postdata();
			}

			update_option('mockup_upgrade', $this->settings['version']);
		}
	}


	$mockup = new MockUp;


	if(is_admin()) {

		add_action('wp_ajax_mockup_single_description', array($mockup, 'mockup_single_description'));
		add_action('wp_ajax_nopriv_mockup_single_description', array($mockup, 'mockup_single_description'));


		add_action('wp_ajax_mockup_single_comment', array($mockup, 'mockup_single_comment'));
		add_action('wp_ajax_nopriv_mockup_single_comment', array($mockup, 'mockup_single_comment'));


		add_action('wp_ajax_mockup_single_related', array($mockup, 'mockup_single_related'));
		add_action('wp_ajax_nopriv_mockup_single_related', array($mockup, 'mockup_single_related'));


		add_action('wp_ajax_mockup_single_approve', array($mockup, 'mockup_single_approve'));
		add_action('wp_ajax_nopriv_mockup_single_approve', array($mockup, 'mockup_single_approve'));


		add_action('wp_ajax_mockup_process_comment', array($mockup, 'mockup_process_comment'));
		add_action('wp_ajax_nopriv_mockup_process_comment', array($mockup, 'mockup_process_comment'));


		add_action('wp_ajax_mockup_process_approve', array($mockup, 'mockup_process_approve'));
		add_action('wp_ajax_nopriv_mockup_process_approve', array($mockup, 'mockup_process_approve'));
	}


	register_activation_hook(__FILE__, array($mockup, 'mockup_save_options'));
	register_activation_hook(__FILE__, array($mockup, 'mockup_add_options'));
	register_activation_hook(__FILE__, array($mockup, 'mockup_upgrade'));
	register_activation_hook(__FILE__, array($mockup, 'mockup_flushrules'));

} // class_exists