<?php
/*
Plugin Name: MockUp
Plugin URI: http://www.estjallema.nl/mockup
Description: Mockup helps you to <strong>present your designs professionally</strong> to your clients. Visit the Mockup website for support, demo’s and video tutorials.
Version: 1.0.5
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

// Add metabox to custom post type
add_action( 'add_meta_boxes', 'mockup_meta_box_add' );

// Save the information from the metabox 
add_action( 'save_post', 'mockup_meta_box_save' );

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

// Add stylesheet
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
				'name' => __( 'Mockup' ),
				'singular_name' => __( 'Mockup' ),
				'add_new' => __( 'Add new Mockup' ),
				'add_new_item' => __( 'Add New Mockup' ),
				'edit' => __( 'Edit' ),
				'edit_item' => __( 'Edit Mockup' ),
				'new_item' => __( 'New Mockup' ),
				'view' => __( 'View Mockup' ),
				'view_item' => __( 'View Mockup' ),
				'search_items' => __( 'Search Mockups' ),
				'not_found' => __( 'No Mockups found' ),
				'not_found_in_trash' => __( 'No Mockups found in Trash' ),
				'parent' => __( 'Parent Mockup' ),
			),
			'public' => true,
			'show_ui' => true,
			'capability_type' => 'post',
			'hierarchical' => false,
			'show_in_nav_menus' => true,
   			'rewrite' => array("slug" => "mockup"),
   			'supports' => array('title', 'editor', 'thumbnail','page-attributes'),//,'custom-fields'
   			'menu_icon' => plugins_url( 'img/ico.png' , __FILE__ )
		)
	);
}


function mockup_meta_box_add()
{
	add_meta_box( 'mockup_meta_box_add_id', 'Mockup settings', 'mockup_meta_box_fields', 'pt_mockup_plugin', 'normal', 'high' );
}


function mockup_meta_box_fields( $post )
{
	$values = get_post_custom( $post->ID );
	
	$background_color = isset( $values['mockup_background_color'] ) ? esc_attr( $values['mockup_background_color'][0] ) : '';

	wp_nonce_field( 'mockup_meta_box_nonce', 'meta_box_nonce' );
	?>

	<table class="mockup">

	<?php if(get_post_meta(get_the_ID(), 'mockup_approved', true) == 'approved') { ?>
		<tr>

			<td class="mockup_left"><strong>Mockup</strong></td>
			<td class="mockup_right">This mockup is approved!</td>

		</tr>
	<?php } ?>



		<tr>

			<td class="mockup_left"><strong>Override background color</strong></td>
			<td class="mockup_right">#<input type="tekst" name="mockup_background_color" value="<?php echo $background_color; ?>" size="6" maxlength="6"></td>

		</tr>

		<?php 	$remarks = get_post_meta(get_the_ID(), 'mockup_remark', false);
				$remarks_display = get_post_meta(get_the_ID(), 'mockup_remark', true);
				if($remarks_display != '') {
		?>

		<tr>

			<td class="mockup_left" valign="top">
				<h4>Remarks</h4>
			</td>

			<td class="mockup_right">
				<?php foreach($remarks as $remark) {
					echo $remark;
					echo '<br><br>';
				} ?>
			</td>

		</tr>

		<?php } ?>

	</table>

<?php //echo get_option('mockup_default_background_color'); ?>

<?php } 



function mockup_meta_box_save( $post_id )
{

	if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

	if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'mockup_meta_box_nonce' ) ) return;

	if( !current_user_can( 'edit_post' ) ) return;
	

	$allowed = array( 
		'a' => array(
			'href' => array()
		)
	);
	
	if( isset( $_POST['mockup_background_color'] ) )
		update_post_meta( $post_id, 'mockup_background_color', wp_kses( $_POST['mockup_background_color'], $allowed ) );
}




function add_custom_taxonomies() {
	register_taxonomy('relate_mockup', 'pt_mockup_plugin', array(
		'hierarchical' => true,
		'labels' => array(
			'name' => _x( 'Customers', 'taxonomy general name' ),
			'singular_name' => _x( 'Customer', 'taxonomy singular name' ),
			'search_items' =>  __( 'Search Customers' ),
			'all_items' => __( 'All Customers' ),
			'parent_item' => __( 'Parent Customer' ),
			'parent_item_colon' => __( 'Parent Customer:' ),
			'edit_item' => __( 'Edit Customer' ),
			'update_item' => __( 'Update Customer' ),
			'add_new_item' => __( 'Add New Customer' ),
			'new_item_name' => __( 'New Customer Name' ),
			'menu_name' => __( 'Customers' ),
		),

		'rewrite' => array(
			'slug' => 'related', 
			'with_front' => false, 
			'hierarchical' => true
		),
	));
}




function filter_by_relate_mockup() {
	global $typenow;
	$post_type = 'pt_mockup_plugin';
	$taxonomy = 'relate_mockup';
	if ($typenow == $post_type) {
		$selected = isset($_GET[$taxonomy]) ? $_GET[$taxonomy] : '';
		$info_taxonomy = get_taxonomy($taxonomy);
		wp_dropdown_categories(array(
			'show_option_all' => __("Show All {$info_taxonomy->label}"),
			'taxonomy' => $taxonomy,
			'name' => $taxonomy,
			'orderby' => 'name',
			'selected' => $selected,
			'show_count' => true,
			'hide_empty' => true,
		));
	};
}

function convert_id_to_term_in_query($query) {
	global $pagenow;
	$post_type = 'pt_mockup_plugin';
	$taxonomy = 'relate_mockup';
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

define('mockup_options_id', 'mockup-options');
define('mockup_options_nick', 'MockUp');

    class mockup_options
    {

		public static function file_path($file)
		{
			return ABSPATH.'wp-content/plugins/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)).$file;
		}

		public static function register()
		{
			register_setting(mockup_options_id.'_options', 'mockup_related_btn');
			register_setting(mockup_options_id.'_options', 'mockup_related_popup_btn');
			register_setting(mockup_options_id.'_options', 'mockup_description_btn');
			register_setting(mockup_options_id.'_options', 'mockup_comment_btn');
			register_setting(mockup_options_id.'_options', 'mockup_send_btn');
			register_setting(mockup_options_id.'_options', 'mockup_approve_btn');

			register_setting(mockup_options_id.'_options', 'mockup_related_title');
			register_setting(mockup_options_id.'_options', 'mockup_description_title');
			register_setting(mockup_options_id.'_options', 'mockup_comment_title');
			register_setting(mockup_options_id.'_options', 'mockup_approve_title');
			register_setting(mockup_options_id.'_options', 'mockup_approve_text');

			register_setting(mockup_options_id.'_options', 'mockup_comment_name_label');
			register_setting(mockup_options_id.'_options', 'mockup_comment_message_label');
			register_setting(mockup_options_id.'_options', 'mockup_approved_text');

			register_setting(mockup_options_id.'_options', 'mockup_default_background_color');
			register_setting(mockup_options_id.'_options', 'mockup_header');
			//register_setting(mockup_options_id.'_options', 'mockup_border_color');

			register_setting(mockup_options_id.'_options', 'mockup_email');

		}

		public static function menu()
		{

			add_options_page(mockup_options_nick.' Plugin Options', mockup_options_nick, 'manage_options', mockup_options_id.'_options', array('mockup_options', 'options_page'));
		}


		public static function options_page()
		{
			if (!current_user_can('manage_options'))
			{
				wp_die( __('You do not have sufficient permissions to access this page.') );
			}

			$plugin_id = mockup_options_id;
			// display options page
			include(self::file_path('options.php'));
		}

    }


endif;




function mockup_activation() {

	$mockup_related_btn_name = 'mockup_related_btn' ;
	$mockup_related_btn_default_value = 'Show related Mockups' ;
	
	if (get_option( $mockup_related_btn_name ) == $mockup_related_btn_default_value ) {
	    update_option( $mockup_related_btn_name, $mockup_related_btn_default_value );
	} else {
		add_option( $mockup_related_btn_name, $mockup_related_btn_default_value );
	}


	$mockup_related_popup_btn_name = 'mockup_related_popup_btn' ;
	$mockup_related_popup_btn_default_value = 'View Mockup' ;
	
	if (get_option( $mockup_related_popup_btn_name ) == $mockup_related_popup_btn_default_value ) {
	    update_option( $mockup_related_popup_btn_name, $mockup_related_popup_btn_default_value );
	} else {
		add_option( $mockup_related_popup_btn_name, $mockup_related_popup_btn_default_value );
	}

	$mockup_description_btn_name = 'mockup_description_btn' ;
	$mockup_description_btn_default_value = 'Show description' ;
	
	if (get_option( $mockup_description_btn_name ) == $mockup_description_btn_default_value ) {
	    update_option( $mockup_description_btn_name, $mockup_description_btn_default_value );
	} else {
		add_option( $mockup_description_btn_name, $mockup_description_btn_default_value );
	}


	$mockup_comment_btn_name = 'mockup_comment_btn' ;
	$mockup_comment_btn_default_value = 'Write a comment' ;
	
	if (get_option( $mockup_comment_btn_name ) == $mockup_comment_btn_default_value ) {
	    update_option( $mockup_comment_btn_name, $mockup_comment_btn_default_value );
	} else {
		add_option( $mockup_comment_btn_name, $mockup_comment_btn_default_value );
	}


	$mockup_send_btn_name = 'mockup_send_btn' ;
	$mockup_send_btn_default_value = 'Send' ;
	
	if (get_option( $mockup_send_btn_name ) == $mockup_send_btn_default_value ) {
	    update_option( $mockup_send_btn_name, $mockup_send_btn_default_value );
	} else {
		add_option( $mockup_send_btn_name, $mockup_send_btn_default_value );
	}


	$mockup_approve_btn_name = 'mockup_approve_btn' ;
	$mockup_approve_btn_default_value = 'Approve' ;
	
	if (get_option( $mockup_approve_btn_name ) == $mockup_approve_btn_default_value ) {
	    update_option( $mockup_approve_btn_name, $mockup_approve_btn_default_value );
	} else {
		add_option( $mockup_approve_btn_name, $mockup_approve_btn_default_value );
	}


	$mockup_related_title_name = 'mockup_related_title' ;
	$mockup_related_title_default_value = 'Related Mockups' ;
	
	if (get_option( $mockup_related_title_name ) == $mockup_related_title_default_value ) {
	    update_option( $mockup_related_title_name, $mockup_related_title_default_value );
	} else {
		add_option( $mockup_related_title_name, $mockup_related_title_default_value );
	}


	$mockup_description_title_name = 'mockup_description_title' ;
	$mockup_description_title_default_value = 'Mockup description' ;
	
	if (get_option( $mockup_description_title_name ) == $mockup_description_title_default_value ) {
	    update_option( $mockup_description_title_name, $mockup_description_title_default_value );
	} else {
		add_option( $mockup_description_title_name, $mockup_description_title_default_value );
	}


	$mockup_comment_title_name = 'mockup_comment_title' ;
	$mockup_comment_title_default_value = 'Write a comment' ;
	
	if (get_option( $mockup_comment_title_name ) == $mockup_comment_title_default_value ) {
	    update_option( $mockup_comment_title_name, $mockup_comment_title_default_value );
	} else {
		add_option( $mockup_comment_title_name, $mockup_comment_title_default_value );
	}


	$mockup_approve_title_name = 'mockup_approve_title' ;
	$mockup_approve_title_default_value = 'Approve this Mockup' ;
	
	if (get_option( $mockup_approve_title_name ) == $mockup_approve_title_default_value ) {
	    update_option( $mockup_approve_title_name, $mockup_approve_title_default_value );
	} else {
		add_option( $mockup_approve_title_name, $mockup_approve_title_default_value );
	}


	$mockup_approve_text_name = 'mockup_approve_text' ;
	$mockup_approve_text_default_value = 'Are you sure you want to approve this Mockup?' ;
	
	if (get_option( $mockup_approve_text_name ) == $mockup_approve_text_default_value ) {
	    update_option( $mockup_approve_text_name, $mockup_approve_text_default_value );
	} else {
		add_option( $mockup_approve_text_name, $mockup_approve_text_default_value );
	}


	$mockup_comment_name_label_name = 'mockup_comment_name_label' ;
	$mockup_comment_name_label_default_value = 'Name' ;
	
	if (get_option( $mockup_comment_name_label_name ) == $mockup_comment_name_label_default_value ) {
	    update_option( $mockup_comment_name_label_name, $mockup_comment_name_label_default_value );
	} else {
		add_option( $mockup_comment_name_label_name, $mockup_comment_name_label_default_value );
	}


	$mockup_comment_message_label_name = 'mockup_comment_message_label' ;
	$mockup_comment_message_label_default_value = 'Your comment' ;
	
	if (get_option( $mockup_comment_message_label_name ) == $mockup_comment_message_label_default_value ) {
	    update_option( $mockup_comment_message_label_name, $mockup_comment_message_label_default_value );
	} else {
		add_option( $mockup_comment_message_label_name, $mockup_comment_message_label_default_value );
	}

	
	$mockup_approved_text_name = 'mockup_approved_text' ;
	$mockup_approved_text_default_value = 'You approved this Mockup' ;
	
	if (get_option( $mockup_approved_text_name ) == $mockup_approved_text_default_value ) {
	    update_option( $mockup_approved_text_name, $mockup_approved_text_default_value );
	} else {
		add_option( $mockup_approved_text_name, $mockup_approved_text_default_value );
	}


	$mockup_background_color_name = 'mockup_default_background_color' ;
	$mockup_background_color_default_value = 'FFFFFF' ;
	
	if (get_option( $mockup_background_color_name ) == $mockup_background_color_default_value ) {
	    update_option( $mockup_background_color_name, $mockup_background_color_default_value );
	} else {
		add_option( $mockup_background_color_name, $mockup_background_color_default_value );
	}


	$mockup_header_name = 'mockup_header' ;
	$mockup_header_default_value = 'light' ;
	
	if (get_option( $mockup_header_name ) == $mockup_header_default_value ) {
	    update_option( $mockup_header_name, $mockup_header_default_value );
	} else {
		add_option( $mockup_header_name, $mockup_header_default_value );
	}

/*
	$mockup_border_color_name = 'mockup_border_color' ;
	$mockup_border_color_default_value = '4D4D4D' ;
	
	if (get_option( $mockup_border_color_name ) == $mockup_border_color_default_value ) {
	    update_option( $mockup_border_color_name, $mockup_border_color_default_value );
	} else {
		add_option( $mockup_border_color_name, $mockup_border_color_default_value );
	}
*/


	$mockup_email_name = 'mockup_email' ;
	$mockup_email_default_value = get_option('admin_email');
	
	if (get_option( $mockup_email_name ) == $mockup_email_default_value ) {
	    update_option( $mockup_email_name, $mockup_email_default_value );
	} else {
		add_option( $mockup_email_name, $mockup_email_default_value );
	}

}

?>