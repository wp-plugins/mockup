<?php
/*

	Plugin Name:    MockUp
	Plugin URI:     http://www.mockupplugin.com
	Description:    MockUp helps you to present your designs professionally.
	Version:        1.1.0
	Author:         Eelco Tjallema
	Author URI:     http://www.estjallema.nl
	License:        GPL2

	Copyright 2014  Eelco Tjallema  (email : mail@estjallema.nl)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as 
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/

// Making sure the Thumbnails are active
add_theme_support('post-thumbnails'); 


// Add the post type 'pt_mockup_plugin'
add_action('init', 'create_mockup_post_type', 1);

function create_mockup_post_type() {

	// Get the WordPress version.
	$wp_version = get_bloginfo('version');

	if($wp_version >= 3.8) {
		$icon = '';
		add_action('admin_head', 'add_icon_to_mockup');
	} else {
		$icon = plugins_url('img/ico.png', __FILE__);
	}

	// Get the menu position for the MockUp post type
	$pos = intval(get_option('mockup_menu_position'));

	// If not found use the default.
	if(!isset($pos)) {
		$pos = 160;
	}

	register_post_type( 'pt_mockup_plugin',

		array(

			'labels' => array(

				'name'                  => __( 'MockUp'                      ,'MockUp' ),
				'singular_name'         => __( 'MockUp'                      ,'MockUp' ),
				'add_new'               => __( 'Add new MockUp'              ,'MockUp' ),
				'add_new_item'          => __( 'Add New MockUp'              ,'MockUp' ),
				'edit'                  => __( 'Edit'                        ,'MockUp' ),
				'edit_item'             => __( 'Edit MockUp'                 ,'MockUp' ),
				'new_item'              => __( 'New MockUp'                  ,'MockUp' ),
				'view'                  => __( 'View MockUp'                 ,'MockUp' ),
				'view_item'             => __( 'View MockUp'                 ,'MockUp' ),
				'search_items'          => __( 'Search MockUps'              ,'MockUp' ),
				'not_found'             => __( 'No MockUps found'            ,'MockUp' ),
				'not_found_in_trash'    => __( 'No MockUps found in Trash'   ,'MockUp' ),
				'parent'                => __( 'Parent MockUp'               ,'MockUp' )
			),

			'public'                => true,
			'exclude_from_search'   => true,
			'show_ui'               => true,
			'hierarchical'          => false,
			'has_archive'           => false,
			'show_in_nav_menus'     => false,
			'rewrite'               => array("slug" => "mockup"), // @todo Make adjustable 
			'supports'              => array('title', 'editor', 'page-attributes', 'thumbnail'),//,'custom-fields'
			'menu_position'         => $pos,
			'menu_icon'             => $icon
		)
	);
}

// Add a icon to the Post type in Wordpress 3.8 +
function add_icon_to_mockup() {

	echo '<style>';
	echo '#adminmenu .menu-icon-pt_mockup_plugin div.wp-menu-image:before { content: "\f309"; }';
	echo '</style>';
}


add_action( 'init', 'create_mockup_taxonomies_relate', 1);

function create_mockup_taxonomies_relate() {

	register_taxonomy('relate_mockup', 'pt_mockup_plugin', 

		array(

			'labels' => array( // @todo Make adjustable 

				'name'              => __( 'Customers'          ,'MockUp' ),
				'singular_name'     => __( 'Customer'           ,'MockUp' ),
				'search_items'      => __( 'Search Customers'   ,'MockUp' ),
				'all_items'         => __( 'All Customers'      ,'MockUp' ),
				'parent_item'       => __( 'Parent Customer'    ,'MockUp' ),
				'parent_item_colon' => __( 'Parent Customer:'   ,'MockUp' ),
				'edit_item'         => __( 'Edit Customer'      ,'MockUp' ),
				'update_item'       => __( 'Update Customer'    ,'MockUp' ),
				'add_new_item'      => __( 'Add New Customer'   ,'MockUp' ),
				'new_item_name'     => __( 'New Customer Name'  ,'MockUp' ),
				'menu_name'         => __( 'Customers'          ,'MockUp' )
			),

			'rewrite'   => array(
				'slug'          => 'related',
				'with_front'    => false
			),

			'show_admin_column' => true,
			'hierarchical'      => true
		)
	);
}


// Templates
// ---------
add_action('template_redirect', 'add_mockup_template');

function add_mockup_template() {

	if(is_singular('pt_mockup_plugin')) {

		include('templates/single.php');
		exit();
	}
}

// Backend
// -------

// Changing the single post
// ------------------------

// Update the thumbnail text
function rewrite_text_to_mockup($content) {
	return str_replace( __( 'Set featured image' ), __( 'Set MockUp image', 'MockUp' ), $content );
}

function adjust_for_mockup() {
	ob_start('rewrite_text_to_mockup');
}

// Making sure the changes only occur in the single MockUp post type
if(isset($_GET['post'])) {

	$post_id 	= $_GET['post'];
	$post_type 	= get_post_type($post_id);

	if($post_type == 'pt_mockup_plugin') {
		adjust_for_mockup();
	}

} elseif(isset($_GET['post_type'])) {

	if($_GET['post_type'] == 'pt_mockup_plugin') {
		adjust_for_mockup();
	}
}


// Load the admin stylesheet and Javascript file.
add_action('admin_enqueue_scripts', 'mockup_admin_style');

function mockup_admin_style() {

	wp_register_style('mockup_admin_stylesheet', plugins_url('inc/stylesheet.css', __FILE__), false, '1.1.0');
	wp_enqueue_style('mockup_admin_stylesheet');

	#wp_enqueue_script( 'mockup_admin_script', plugins_url('inc/script.js', __FILE__), false, '1.1.0');
}


// Admin Colums
// ------------

// Comments
add_filter('manage_pt_mockup_plugin_posts_columns', 'mockup_column_comments_title');  
add_action('manage_pt_mockup_plugin_posts_custom_column', 'mockup_column_comments', 10, 2);


function mockup_column_comments_title($defaults) {

	$comments = array();

	foreach($defaults as $key => $title) {

		if($key == 'title')
			$comments['mockup_comments'] = '<div class="comment-grey-bubble"></div>';
			$comments[$key] = $title;
	}

	return $comments;
}


function mockup_column_comments($column_name, $post_ID) {  

	if($column_name == 'mockup_comments') {

		$number = get_post_meta(get_the_ID(), 'mockup_remark_count_1', true);

		if(empty($number))
			$number = 0;

		echo '<span><strong>'.$number.'<strong></span>';
	}
}


// Status
add_filter('manage_pt_mockup_plugin_posts_columns', 'mockup_column_status_title');  
add_action('manage_pt_mockup_plugin_posts_custom_column', 'mockup_column_status', 10, 2);

function mockup_column_status_title($defaults) {

	$comments = array();

	foreach($defaults as $key => $title) {

		if($key == 'taxonomy-relate_mockup')
			$comments['mockup_status'] = 'Status';
			$comments[$key] = $title;
	}

	return $comments;
}

function mockup_column_status($column_name, $post_ID) {  

	if($column_name == 'mockup_status') {

		if(get_post_meta(get_the_ID(), 'mockup_status', true ) == 'approved') {
			echo '<span>'.__('Approved').'</span>';
		}
	}
}



// Taxonomy Filter
// ---------------
add_filter('parse_query', 'convert_id_to_mockup_term');

add_action('restrict_manage_posts', 'filter_by_relate_mockup');


function convert_id_to_mockup_term($query) {

	global $pagenow;
	$post_type 		= 'pt_mockup_plugin';
	$taxonomy 		= 'relate_mockup';
	$q_vars = &$query->query_vars;

	if ($pagenow == 'edit.php' && isset($q_vars['post_type']) && $q_vars['post_type'] == $post_type && isset($q_vars[$taxonomy]) && is_numeric($q_vars[$taxonomy]) && $q_vars[$taxonomy] != 0) {

		$term = get_term_by('id', $q_vars[$taxonomy], $taxonomy);
		$q_vars[$taxonomy] = $term->slug;

	}
}

function filter_by_relate_mockup() {

	global $typenow;
	$post_type 		= 'pt_mockup_plugin';
	$taxonomy 		= 'relate_mockup';

	if ($typenow == $post_type) {
		$selected = isset($_GET[$taxonomy]) ? $_GET[$taxonomy] : '';
		$info_taxonomy = get_taxonomy($taxonomy);
		wp_dropdown_categories(array(
			'show_option_all' 	=> __("Show All {$info_taxonomy->label}"),
			'taxonomy' 			=> $taxonomy,
			'name' 				=> $taxonomy,
			'orderby' 			=> 'name',
			'selected' 			=> $selected,
			'show_count' 		=> true,
			'hide_empty' 		=> true,
		));
	};
}



// Add the Metabox
// ---------------
add_action('add_meta_boxes', 'mockup_add_metabox');

function mockup_add_metabox() {

	add_meta_box('mockup_comments_metabox', __( 'Comments', 'MockUp' ), 'mockup_comments_metabox_callback', 'pt_mockup_plugin', 'normal');
	add_meta_box('mockup_info_metabox', __( 'Information and Settings', 'MockUp' ), 'mockup_info_metabox_callback', 'pt_mockup_plugin', 'normal');
}


function mockup_comments_metabox_callback($post) { ?>

	<table width="100%" cellspacing="0" cellpadding="0">

		<tbody>

			<?php $remark_count = get_post_meta($post->ID, 'mockup_remark_count_1', true);

				if(!empty($remark_count)) {

					$i 				= $remark_count;
					$name 			= get_post_meta($post->ID, 'mockup_remark_name_1', false);
					$dates 			= get_post_meta($post->ID, 'mockup_remark_date_1', false);
					$text 			= get_post_meta($post->ID, 'mockup_remark_text_1', false);
					$date_format 	= get_option('date_format');
					$time_format 	= get_option('time_format');
					$format 		= $date_format.' '.$time_format;

					while ($i > 0) {

						$i--;

						// Update the date of comments made in older versions.
						if(strtotime($dates[$i])) {
							$date = $dates[$i];
							$date = strtotime($date);
							update_post_meta($post->ID, 'mockup_remark_date_1', $date, $dates[$i]);
							$date = date($format, $date);
						} else {
							$date = date($format, $dates[$i]);
						}

						if($i % 2 == 0) {
							$class = 'odd';
						} else {
							$class = 'even';
						}

						echo '<tr valign="top" class="'.$class.'">';

							echo '<td width="20%" >';

								echo '<span><strong>',$name[$i],'</strong></span>';
								echo '<br />';
								echo '<span><i>',$date,'</i></span>';

							echo '</td>';

							echo '<td>';

								echo '<span>',nl2br($text[$i]),'</span>';

							echo '</td>';

						echo '</tr>';

					}

				} else {

					echo '<tr valign="top">';

						echo '<td>';

							echo __('No comments have been made.', 'MockUp');

						echo '</td>';

					echo '</tr>';
				} ?>

		</tbody>

	</table>

<?php }


function mockup_info_metabox_callback($post) {

	wp_nonce_field( 'mockup_info_metabox_callback', 'mockup_info_metabox_callback_nonce' );

	// Status
	$post_status = get_post_status($post->ID);
	if(get_post_meta($post->ID, 'mockup_status', true ) == 'approved') {

		$name = get_post_meta($post->ID, 'mockup_approve_name', true );

		if(!empty($name)) {
			$post_status = sprintf(__('Approved by %s', 'MockUp'), $name);
		} else {
			$post_status = __('Approved', 'MockUp');
		}
		
	} elseif($post_status == 'publish') {
		$post_status = __('Published', 'MockUp');
	} else {
		$post_status = NULL;
	}

	// Background Color
	$mockup_background_color = get_post_meta($post->ID, 'mockup_background_color', true );
	if(empty($mockup_background_color)) {
		$mockup_background_color = get_option('mockup_default_background_color');
	} else {
		$mockup_background_color = get_post_meta($post->ID, 'mockup_background_color', true );
	}

	// Comments status
	$mockup_comments = get_post_meta($post->ID, 'mockup_comments', true );
	if(empty($mockup_comments)) {
		$mockup_comments = '';
	} else {
		$mockup_comments = get_post_meta($post->ID, 'mockup_comments', true );
	}

	// Header color
	$mockup_header = get_post_meta($post->ID, 'mockup_header', true );
	if(empty($mockup_header)) {
		$mockup_header = get_option('mockup_header');
	} else {
		$mockup_header = get_post_meta($post->ID, 'mockup_header', true );
	}

	// Email settings
	$mockup_email_settings = get_post_meta($post->ID, 'mockup_email_settings', true );
	if(empty($mockup_email_settings)) {
		$mockup_email_settings = get_option('mockup_email_settings');
	} else {
		$mockup_email_settings = get_post_meta($post->ID, 'mockup_email_settings', true );
	}

	// Link to the MockUp options page
	$mockup_settings_url = '<a href="'.admin_url("options-general.php?page=mockup_options").'">general settings</a>';

?>

	<div class="table table_content">

		<table cellspacing="0" cellpadding="0" width="100%">

			<tbody>

				<?php if($post_status != NULL) { ?>
					<tr>
						<td width="20%"><span><strong>Status</strong></span></td>
						<td><?php echo $post_status ?></td>
					</tr>
				<?php } ?>

				<?php if($post_status == __('Published', 'MockUp')) { ?>
					<tr>
						<td><span><strong>URL</strong></span></td>
						<td><?php the_permalink(); ?></td>
					</tr>
				<?php } ?>

				<tr>	
					<td><span><strong>Background Color</strong></span></td>
					<td><input type="text" id="mockup_background_color" name="mockup_background_color" value="<?php echo esc_attr($mockup_background_color); ?>" maxlength="6"  width="300" style="width: 300px"></td>
				</tr>

				<tr>
					<td><span><strong>Comments</strong></span></td>
					<td>
						<select id="mockup_comments" name="mockup_comments" width="300" style="width: 300px">
							<option value="enable"<?php if($mockup_comments == 'enable') { echo ' selected="selected"'; } ?>><?php _e('Enable comments', 'MockUp'); ?></option>
							<option value="disable"<?php if($mockup_comments == 'disable') { echo ' selected="selected"'; } ?>><?php _e('Disable comments', 'MockUp'); ?></option>
						</select>
					</td>
				</tr>


				<tr>
					<td><span><strong>Header Color</strong></span></td>
					<td>
						<select id="mockup_header" name="mockup_header" width="300" style="width: 300px">
							<option value="navbar-default"<?php if($mockup_header == 'navbar-default') { echo ' selected="selected"'; } ?>><?php _e('Light Gray', 'MockUp'); ?></option>
							<option value="navbar-inverse"<?php if($mockup_header == 'navbar-inverse') { echo ' selected="selected"'; } ?>><?php _e('Dark Gray', 'MockUp'); ?></option>
						</select>
					</td>
				</tr>

				<tr>
					<td><span><strong>Email Settings</strong></span></td>
					<td>
						<select id="mockup_email_settings" name="mockup_email_settings" width="300" style="width: 300px">
							<option value="email_always"<?php if($mockup_email_settings == 'email_always') { echo ' selected="email_always"'; } ?>><?php _e('Email me on all changes.', 'MockUp'); ?></option>
							<option value="email_approved"<?php if($mockup_email_settings == 'email_approved') { echo ' selected="email_approved"'; } ?>><?php _e('Email me only when the MockUp is approved', 'MockUp'); ?></option>
							<option value="email_comments"<?php if($mockup_email_settings == 'email_comments') { echo ' selected="email_comments"'; } ?>><?php _e('Email me only when comments are made', 'MockUp'); ?></option>
							<option value="email_never"<?php if($mockup_email_settings == 'email_never') { echo ' selected="email_never"'; } ?>><?php _e('Never email me', 'MockUp'); ?></option>
						</select>
					</td>
				</tr>


				<tr>
					<td colspan="2"><p><a href="<?php echo admin_url('options-general.php?page=mockup_options'); ?>"><?php _e('Change the general settings.', 'MockUp'); ?></p></td>
				</tr>

			</tbody>

		</table>

	</div>

<?php }


add_action('save_post', 'mockup_save_postdata');

function mockup_save_postdata($post_id) {

	// Check if our nonce is set.
	if(!isset($_POST['mockup_info_metabox_callback_nonce']))
		return $post_id;

	$nonce = $_POST['mockup_info_metabox_callback_nonce'];

	// Verify that the nonce is valid.
	if(!wp_verify_nonce($nonce, 'mockup_info_metabox_callback'))
		return $post_id;

	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) 
		return $post_id;

	// Check the user's permissions.
	if('page' == $_POST['post_type']) {

	if(!current_user_can('edit_page', $post_id))
		return $post_id;

	} else {

	if(!current_user_can('edit_post', $post_id))
		return $post_id;
	}

	// Sanitize user input.
	$mockup_background_color 	= sanitize_text_field($_POST['mockup_background_color']);
	$mockup_comments 			= sanitize_text_field($_POST['mockup_comments']);
	$mockup_header 				= sanitize_text_field($_POST['mockup_header']);
	$mockup_email_settings 		= sanitize_text_field($_POST['mockup_email_settings']);


	// Update the meta field in the database.
	update_post_meta( $post_id, 'mockup_background_color', $mockup_background_color );
	update_post_meta( $post_id, 'mockup_comments', $mockup_comments );
	update_post_meta( $post_id, 'mockup_header', $mockup_header );
	update_post_meta( $post_id, 'mockup_email_settings', $mockup_email_settings );
}

// Update most meta
// ----------------
// Add current version to post meta
add_action('save_post', 'mockup_add_version');

function mockup_add_version($post_id) {
	add_post_meta($post_id, 'mockup_update', '110', true);
}


// Options page
// ------------
add_action('admin_menu', 'mockup_options');

function mockup_options() {

	$page_title 	= __('MockUp Options & Settings', 'MockUp');
	$menu_title 	= __('MockUp', 'MockUp');
	$capability 	= 'manage_options';
	$menu_slug 		= 'mockup_options_new';
	$function 		= 'mockup_options_page';

	add_options_page( $page_title, $menu_title, $capability, $menu_slug, $function);

}

function mockup_options_page() {  

	$plugin_id = 'mockup';
	include_once 'inc/options.php';
}  

// Register the settings
// @todo: In an array?
// @edit: functions: mockup_add_options, mockup_delete_options, mockup_unregister_settings
add_action('admin_init', 'mockup_register_settings');

function mockup_register_settings() {
	register_setting('mockup_options', 'mockup_related_btn');
	register_setting('mockup_options', 'mockup_related_btn');
	register_setting('mockup_options', 'mockup_related_popup_btn');
	register_setting('mockup_options', 'mockup_description_btn');
	register_setting('mockup_options', 'mockup_comment_btn');
	register_setting('mockup_options', 'mockup_send_btn');
	register_setting('mockup_options', 'mockup_approve_btn');

	register_setting('mockup_options', 'mockup_related_title');
	register_setting('mockup_options', 'mockup_description_title');
	register_setting('mockup_options', 'mockup_comment_title');
	register_setting('mockup_options', 'mockup_approve_title');
	register_setting('mockup_options', 'mockup_approve_text');

	register_setting('mockup_options', 'mockup_comment_name_label');
	register_setting('mockup_options', 'mockup_comment_message_label');
	register_setting('mockup_options', 'mockup_comment_no_comments');
	register_setting('mockup_options', 'mockup_approved_text');

	register_setting('mockup_options', 'mockup_default_background_color');
	register_setting('mockup_options', 'mockup_header');

	register_setting('mockup_options', 'mockup_email');
	register_setting('mockup_options', 'mockup_email_settings');
	register_setting('mockup_options', 'mockup_menu_position');
}


// Activation hooks
// ----------------
register_activation_hook(__FILE__, 'mockup_update_check');
register_activation_hook(__FILE__, 'mockup_add_options' );
register_activation_hook(__FILE__, 'mockup_flush_rules');

// Flush rewrite rules
function mockup_flush_rules(){

	create_mockup_post_type();
	create_mockup_taxonomies_relate();

	flush_rewrite_rules();
}

// Add mockup options
// @edit functions: mockup_register_settings, mockup_delete_options, mockup_unregister_settings
function mockup_add_options() {

	add_option('mockup_related_btn',                __( 'Show related MockUps',                             'MockUp' ) );
	add_option('mockup_related_popup_btn',          __( 'View MockUp',                                      'MockUp' ) );
	add_option('mockup_description_btn',            __( 'Show description',                                 'MockUp' ) );
	add_option('mockup_comment_btn',                __( 'Write a comment',                                  'MockUp' ) );
	add_option('mockup_send_btn',                   __( 'Send',                                             'MockUp' ) );
	add_option('mockup_approve_btn',                __( 'Approve',                                          'MockUp' ) );

	add_option('mockup_related_title',              __( 'Related MockUps',                                  'MockUp' ) );
	add_option('mockup_description_title',          __( 'MockUp description',                               'MockUp' ) );
	add_option('mockup_comment_title',              __( 'Write a comment',                                  'MockUp' ) );
	add_option('mockup_approve_title',              __( 'Approve this MockUp',                              'MockUp' ) );
	add_option('mockup_approve_text',               __( 'Are you sure you want to approve this MockUp?',    'MockUp' ) );

	add_option('mockup_comment_name_label',         __( 'Name',                                             'MockUp' ) );
	add_option('mockup_comment_message_label',      __( 'Comments',                                         'MockUp' ) );
	add_option('mockup_comment_no_comments',        __( 'Here you can add your comments on this MockUp.',   'MockUp' ) );
	add_option('mockup_approved_text',              __( 'You approved this MockUp',                         'MockUp' ) );

	add_option('mockup_default_background_color', 'ffffff');
	add_option('mockup_header', 'navbar-default' );

	$mockup_email_default_value = get_option('admin_email');

	add_option('mockup_email', $mockup_email_default_value);
	add_option('mockup_email_settings', 'email_always');
	add_option('mockup_menu_position', 160);
}

// Updates
function mockup_update_check() {

	include_once 'inc/update_functions.php';

	$args = array(
		'post_type' 		=> 'pt_mockup_plugin',
		'posts_per_page' 	=> 999,
	);


	$mockup_query = new WP_Query($args);

	if($mockup_query->have_posts()) {

		while($mockup_query->have_posts()): $mockup_query->the_post();

			$id = get_the_ID();

			if(get_post_meta($id, 'mockup_update', true ) < '107') {
				mockup_update_107($id);
			}			

		endwhile;
	}
}


// Uninstall MockUp
// ----------------
register_uninstall_hook(__FILE__, 'mockup_delete_options');
register_uninstall_hook(__FILE__, 'mockup_unregister_settings');


// Delete mockup options
// @edit functions: mockup_add_options, mockup_register_settings, mockup_unregister_settings
function mockup_delete_options() {

	delete_option('mockup_related_btn');
	delete_option('mockup_related_popup_btn');
	delete_option('mockup_description_btn');
	delete_option('mockup_comment_btn');
	delete_option('mockup_send_btn');
	delete_option('mockup_approve_btn');

	delete_option('mockup_related_title');
	delete_option('mockup_description_title');
	delete_option('mockup_comment_title');
	delete_option('mockup_approve_title');
	delete_option('mockup_approve_text');

	delete_option('mockup_comment_name_label');
	delete_option('mockup_comment_message_label');
	delete_option('mockup_comment_no_comments');
	delete_option('mockup_approved_text');

	delete_option('mockup_default_background_color');
	delete_option('mockup_header');

	delete_option('mockup_email');
	delete_option('mockup_email_settings');
	delete_option('mockup_menu_position');
	
}

// Unregister the settings
// @edit functions: mockup_add_options, mockup_register_settings, mockup_delete_options
function mockup_unregister_settings() {

	unregister_setting('mockup_options', 'mockup_related_btn');
	unregister_setting('mockup_options', 'mockup_related_popup_btn');
	unregister_setting('mockup_options', 'mockup_description_btn');
	unregister_setting('mockup_options', 'mockup_comment_btn');
	unregister_setting('mockup_options', 'mockup_send_btn');
	unregister_setting('mockup_options', 'mockup_approve_btn');

	unregister_setting('mockup_options', 'mockup_related_title');
	unregister_setting('mockup_options', 'mockup_description_title');
	unregister_setting('mockup_options', 'mockup_comment_title');
	unregister_setting('mockup_options', 'mockup_approve_title');
	unregister_setting('mockup_options', 'mockup_approve_text');

	unregister_setting('mockup_options', 'mockup_comment_name_label');
	unregister_setting('mockup_options', 'mockup_comment_message_label');
	unregister_setting('mockup_options', 'mockup_comment_no_comments');
	unregister_setting('mockup_options', 'mockup_approved_text');

	unregister_setting('mockup_options', 'mockup_default_background_color');
	unregister_setting('mockup_options', 'mockup_header');

	unregister_setting('mockup_options', 'mockup_email');
	unregister_setting('mockup_options', 'mockup_email_settings');
	unregister_setting('mockup_options', 'mockup_menu_position');
}