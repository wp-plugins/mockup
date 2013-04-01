<?php
/*
Plugin Name: Mockup
Plugin URI: http://www.mockupplugin.com
Description: Mockup helps you to <strong>present your designs professionally</strong> to your clients. Visit the Mockup website for support, demo’s and video tutorials.
Version: 1.0.7
Author: Eelco Tjallema
Author URI: http://www.estjallema.nl
License: GPL2

Copyright 2012  Eelco Tjallema  (email : mockup@estjallema.nl)

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

// Add custom post type
add_action( 'init', 'create_mockup_post_types' );

// Flush custom post type
register_activation_hook(__FILE__, 'custom_flush_rules');

// Add metabox to custom post type
add_action( 'load-post.php', 'mockup_meta_boxes_setup' );
add_action( 'load-post-new.php', 'mockup_meta_boxes_setup' );

// Add custom taxonomie to the custom post type
add_action( 'init', 'add_custom_taxonomies', 0 );

// Add custom taxonomie filter
add_action('restrict_manage_posts', 'filter_by_relate_mockup');
add_filter('parse_query', 'convert_id_to_term_in_query');

// Add Mockup Template
add_action('template_redirect', 'add_mockup_template');

// Adding admin menu
if(is_admin())	{
					add_action('admin_init', array('mockup_options', 'register'));
					add_action('admin_menu', array('mockup_options', 'menu'));
				}

// Add custom values on activation
register_activation_hook(__FILE__,'mockup_activation');

// Add stylesheet to backend WordPress
add_action('admin_head', 'admin_register_head');

// Making sure support for post thumbnails is enabled
add_theme_support( 'post-thumbnails' ); 


/*
 * 		Start functions
*/

function create_mockup_post_types() {
	register_post_type( 'pt_mockup_plugin',

		array(
			'labels' => array(
				'name' 					=> __( 'Mockup' ),
				'singular_name' 		=> __( 'Mockup' ),
				'add_new' 				=> __( 'Add new Mockup' ),
				'add_new_item' 			=> __( 'Add New Mockup' ),
				'edit' 					=> __( 'Edit' ),
				'edit_item' 			=> __( 'Edit Mockup' ),
				'new_item' 				=> __( 'New Mockup' ),
				'view' 					=> __( 'View Mockup' ),
				'view_item' 			=> __( 'View Mockup' ),
				'search_items' 			=> __( 'Search Mockups' ),
				'not_found' 			=> __( 'No Mockups found' ),
				'not_found_in_trash' 	=> __( 'No Mockups found in Trash' ),
				'parent' 				=> __( 'Parent Mockup' ),
			),
			'public'			=> true,
			'show_ui' 			=> true,
			'capability_type' 	=> 'post',
			'hierarchical' 		=> false,
			'show_in_nav_menus' => true,
   			'rewrite' 			=> array("slug" => "mockup"),
   			'supports' 			=> array('title', 'thumbnail','page-attributes'),//,'custom-fields'
   			'menu_position' 	=> 58,
   			'menu_icon' 		=> plugins_url( 'img/ico.png' , __FILE__ )
		)

	);

}


function custom_flush_rules(){

	create_mockup_post_types();
	flush_rewrite_rules();

}


function mockup_meta_boxes_setup() {

	add_action( 'add_meta_boxes', 'mockup_add_post_meta_box_info' );

	add_action( 'add_meta_boxes', 'mockup_add_post_meta_box_text' );

	add_action( 'add_meta_boxes', 'mockup_add_post_meta_box_settings' );

	add_action( 'save_post', 'mockup_save_post_class_meta', 10, 2 );

}


function mockup_add_post_meta_box_info() {

	add_meta_box(
		'mockup_meta_box_id_info',
		'Mockup Information',
		'mockup_meta_box_callback_info',
		'pt_mockup_plugin',
		'normal',	
		'default'
	);
}


function mockup_meta_box_callback_info( $object, $box ) { ?>

	<table>

		<?php if(!has_post_thumbnail()) { ?>

			<tr>

				<td colspan="2">
					<p>Select your Mockup via the featured images on the right.</p>
				</td>

			</tr>

<?php } 


		if(get_post_meta(get_the_ID(), 'mockup_approved', true) == 'approved') { ?>

			<tr>

				<td colspan="2">

					<p><strong>&#10004; This mockup is approved</strong></p>

				</td>

			</tr>

<?php } else { ?>

			<tr>

				<td colspan="2">

					<p>This mockup has not yet been approved</p>

				</td>

			</tr>

<?php }

			$remark_count 	= get_post_meta(get_the_ID(), 'mockup_remark_count_1', true);

			if($remark_count != '') { ?>

			<tr>

				<td  width="180px" valign="top"><p><strong>Remarks:</strong></p>
					<p>This are the remarks your client made.</p>
				</td>

				<td width="80%">

<?php				$i 		= $remark_count;
					$name 	= get_post_meta(get_the_ID(), 'mockup_remark_name_1', false);
					$date 	= get_post_meta(get_the_ID(), 'mockup_remark_date_1', false);
					$text 	= get_post_meta(get_the_ID(), 'mockup_remark_text_1', false);

					while ($i > 0) {

						$i--;

						echo '<div class="well span3 pull-right">';

							echo '<p><strong>'.$name[$i].'</strong> <span class="pull-right">'.$date[$i].'</span></p>';
							echo '<p>'.nl2br($text[$i]);
							echo '</p>';

						echo '</div>';

					} 

					// Get the comments made untill version 1.0.7. 
					// This will be removed in version 2.0. 
					$remarks = get_post_meta(get_the_ID(), 'mockup_remark', false);

					foreach($remarks as $remark) {

						echo '<div class="well span3 pull-right">';

							echo '<p>'.nl2br($remark);

						echo '</div>';
				
					} 

				echo '</td>';

			echo '</tr>';

		} else { ?>

			<tr>

				<td colspan="2">

					<p>No remarks have been made.</p>

				</td>

			</tr>

		<?php } ?>

	</table>

<?php }


function mockup_add_post_meta_box_text() {

	add_meta_box(
		'mockup_meta_box_id_text',
		'Mockup Text',
		'mockup_meta_box_callback_text',
		'pt_mockup_plugin',
		'normal',	
		'default'
	);
}


function mockup_meta_box_callback_text( $object, $box ) {

	wp_nonce_field( basename( __FILE__ ), 'mockup_class_nonce' );

	$page_object = get_page( $object );

	// Untill version 1.0.7 the content was in the_content now it is in the customfield 'mockup_description'.
	// Here it will be copied to the customfield.
	// This will be removed in version 2.0. 
	if(get_post_meta( $object->ID, 'mockup_description', true ) == '' ) {
		$content = $page_object->post_content;
	} else {
		$content = get_post_meta( $object->ID, 'mockup_description', true );
	} ?>

	<table>

		<tr>

			<td width="180px" valign="top"><p><strong>Description:</strong></p>
				<p>If left empty the button '<?php echo get_option('mockup_description_btn'); ?>' will not appear in the header.</p>
				<p>HTML is allowed.</p>
			</td>
			<td width="80%"><textarea class="mockup_description" name="mockup_description"><?php echo $content; ?></textarea></td>

		</tr>

	</table>

<?php }


function mockup_add_post_meta_box_settings() {

	add_meta_box(
		'mockup_meta_box_id_settings',
		'Mockup Settings',
		'mockup_meta_box_callback_settings',
		'pt_mockup_plugin',
		'normal',	
		'default'
	);
}


function mockup_meta_box_callback_settings( $object, $box ) {

	wp_nonce_field( basename( __FILE__ ), 'mockup_class_nonce' ); 
	$url = admin_url( 'options-general.php?page=mockup_options' ); 

	$background_color_value = esc_attr( get_post_meta( $object->ID, 'mockup_background_color', true ) );

	$header_value = esc_attr( get_post_meta( $object->ID, 'mockup_header', true ) );
	if($header_value == '') {
		$header_value = get_option('mockup_header');
	} ?>

	<table>

		<tr>
			<td colspan="2">
				<p>Here you can overide the <a href="<?php echo $url; ?>">general settings</a> for this mockup only.</p>
			</td>
		</tr>

		<tr>
			<td width="180"><p>Background Color</p></td>
			<td><input class="widefat" type="text" name="mockup_background_color" value="<?php echo $background_color_value; ?>" size="40" /></td>
		</tr>

		<tr>
			<td><p>Header color</p></td>
			<td>
				<select name="mockup_header">
					<option value="light"<?php if($header_value == 'light') { echo ' selected="selected"'; } ?>>Light</option>
					<option value="dark"<?php if($header_value == 'dark') { echo ' selected="selected"'; } ?>>Dark</option>
				</select>
			</td>
		</tr>


	</table>

<?php }



function mockup_save_post_class_meta($post_id, $post) {


	if(!isset( $_POST['mockup_class_nonce']) || !wp_verify_nonce($_POST['mockup_class_nonce'], basename( __FILE__ ) ) )
		return $post_id;

	$post_type = get_post_type_object( $post->post_type );

	if(!current_user_can( $post_type->cap->edit_post, $post_id))
		return $post_id;



	// Background color
	$new_meta_value = (isset($_POST['mockup_background_color']) ? $_POST['mockup_background_color']: '' );

	$meta_key_background = 'mockup_background_color';

	$meta_value = get_post_meta( $post_id, $meta_key_background, true );

	update_post_meta( $post_id, $meta_key_background, $new_meta_value );



	// Header
	$new_meta_value = (isset($_POST['mockup_header']) ? $_POST['mockup_header']: '' );

	$meta_key_header = 'mockup_header';

	$meta_value = get_post_meta( $post_id, $meta_key_header, true );

	if($new_meta_value && '' == $meta_value )
		add_post_meta( $post_id, $meta_key_header, $new_meta_value, true );

	elseif($new_meta_value && $new_meta_value != $meta_value )
		update_post_meta( $post_id, $meta_key_header, $new_meta_value );



	// Text
	$new_meta_value = (isset($_POST['mockup_description']) ? $_POST['mockup_description']: '' );

	$meta_key = 'mockup_description';

	$meta_value = get_post_meta( $post_id, $meta_key, true );

	if($new_meta_value && '' == $meta_value )
		add_post_meta( $post_id, $meta_key, $new_meta_value, true );

	elseif($new_meta_value && $new_meta_value != $meta_value )
		update_post_meta( $post_id, $meta_key, $new_meta_value );

}



function add_custom_taxonomies() {
	register_taxonomy('relate_mockup', 'pt_mockup_plugin', array(
		'hierarchical'	=> true,
		'labels' 		=> array(
			'name' 					=> __( 'Customers'),
			'singular_name' 		=> __( 'Customer'),
			'search_items' 			=> __( 'Search Customers'),
			'all_items' 			=> __( 'All Customers'),
			'parent_item' 			=> __( 'Parent Customer'),
			'parent_item_colon' 	=> __( 'Parent Customer:'),
			'edit_item' 			=> __( 'Edit Customer'),
			'update_item' 			=> __( 'Update Customer'),
			'add_new_item' 			=> __( 'Add New Customer'),
			'new_item_name' 		=> __( 'New Customer Name'),
			'menu_name' 			=> __( 'Customers'),
		),

		'rewrite' 	=> array(
			'slug' 			=> 'related', 
			'with_front' 	=> false, 
			'hierarchical' 	=> true
		),
	));
}




function filter_by_relate_mockup() {
	global $typenow;
	$post_type = 	'pt_mockup_plugin';
	$taxonomy = 	'relate_mockup';
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

function convert_id_to_term_in_query($query) {
	global $pagenow;
	$post_type 		= 'pt_mockup_plugin';
	$taxonomy 		= 'relate_mockup';
	$q_vars = &$query->query_vars;
	if ($pagenow == 'edit.php' && isset($q_vars['post_type']) && $q_vars['post_type'] == $post_type && isset($q_vars[$taxonomy]) && is_numeric($q_vars[$taxonomy]) && $q_vars[$taxonomy] != 0) {
		$term = get_term_by('id', $q_vars[$taxonomy], $taxonomy);
		$q_vars[$taxonomy] = $term->slug;
	}
}



function add_mockup_template() {
	if(is_singular('pt_mockup_plugin')) {
		include('templates/single-pt_mockup_plugin.php');
		exit;
	}
}


function admin_register_head() {
    $siteurl = get_option('siteurl');
    $url = $siteurl . '/wp-content/plugins/' . basename(dirname(__FILE__)) . '/css/mockup_style.css';
    echo "<link rel='stylesheet' type='text/css' href='$url' />\n";
}


if(!class_exists('mockup_options')) :

define('mockup_options_id', 'mockup');
define('mockup_options_nick', 'Mockup');

    class mockup_options {

		public static function file_path($file) {
			return ABSPATH.'wp-content/plugins/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)).$file;
		}

		public static function register() {
			register_setting(mockup_options_id.'_options', 'mockup_related_btn');
			register_setting(mockup_options_id.'_options', 'mockup_related_popup_btn');
			register_setting(mockup_options_id.'_options', 'mockup_description_btn');
			register_setting(mockup_options_id.'_options', 'mockup_comment_btn');
			register_setting(mockup_options_id.'_options', 'mockup_send_btn');
			register_setting(mockup_options_id.'_options', 'mockup_close_btn');
			register_setting(mockup_options_id.'_options', 'mockup_approve_btn');

			register_setting(mockup_options_id.'_options', 'mockup_related_title');
			register_setting(mockup_options_id.'_options', 'mockup_description_title');
			register_setting(mockup_options_id.'_options', 'mockup_comment_title');
			register_setting(mockup_options_id.'_options', 'mockup_approve_title');
			register_setting(mockup_options_id.'_options', 'mockup_approve_text');

			register_setting(mockup_options_id.'_options', 'mockup_comment_name_label');
			register_setting(mockup_options_id.'_options', 'mockup_comment_message_label');
			register_setting(mockup_options_id.'_options', 'mockup_comment_no_comments');
			register_setting(mockup_options_id.'_options', 'mockup_approved_text');

			register_setting(mockup_options_id.'_options', 'mockup_default_background_color');
			register_setting(mockup_options_id.'_options', 'mockup_header');
			//register_setting(mockup_options_id.'_options', 'mockup_border_color');

			register_setting(mockup_options_id.'_options', 'mockup_email');

		}

		public static function menu() {
			add_options_page(mockup_options_nick.' Plugin Options', mockup_options_nick, 'manage_options', mockup_options_id.'_options', array('mockup_options', 'options_page'));
		}


		public static function options_page() {
			if (!current_user_can('manage_options')) {
				wp_die( __('You do not have sufficient permissions to access this page.') );
			}

			$plugin_id = mockup_options_id;
			include(self::file_path('options.php'));
		}

    }

endif;




function mockup_activation() {

	add_option('mockup_related_btn', __('Show related Mockups') );
	add_option('mockup_related_popup_btn', __('View Mockup') );
	add_option('mockup_description_btn', __('Show description') );
	add_option('mockup_comment_btn', __('Write a comment') );
	add_option('mockup_send_btn', __('Send') );
	add_option('mockup_close_btn', __('Close') );
	add_option('mockup_approve_btn', __('Approve') );


	add_option('mockup_related_title', __('Related Mockups') );
	add_option('mockup_description_title', __('Mockup description') );
	add_option('mockup_comment_title', __('Write a comment') );
	add_option('mockup_approve_title', __('Approve this Mockup') );
	add_option('mockup_approve_text', __('Are you sure you want to approve this Mockup?') );

	add_option('mockup_comment_name_label', __('Name') );
	add_option('mockup_comment_message_label', __('Your comment') );
	add_option('mockup_comment_no_comments', __('Here you can add your comments on this Mockup.') );
	add_option('mockup_approved_text', __('You approved this Mockup') );


	add_option('mockup_default_background_color', __('FFFFFF') );
	add_option('mockup_header', 'light' );

	$mockup_email_default_value = get_option('admin_email');
	add_option('mockup_email', $mockup_email_default_value );

}

?>