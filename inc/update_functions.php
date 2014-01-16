<?php 

function mockup_update_107($id) {

	// Clean the titles
	$title = str_replace('&#10004;','', get_the_title());
	$title = str_replace('✔','', $title);
	trim($title);

	// Get the content
	$content = nl2br(get_post_meta($id, 'mockup_description', true));
	delete_post_meta($id, 'mockup_description');

	// Update the post
	$args = array(
		'ID' 			=> $id,
		'post_title' 	=> $title,
		'post_content' 	=> $content,
	);

	wp_update_post($args);


	// Change the post meta
	// --------------------
	// Approved
	if(get_post_meta($id, 'mockup_approved', true ) == 'approved') {
		update_post_meta($id, 'mockup_status','approved');
		delete_post_meta($id, 'mockup_approved');
	}

	// Header color
	update_post_meta($id, 'mockup_header', 'navbar-default', 'light');
	update_post_meta($id, 'mockup_header', 'navbar-inverse', 'dark');

	// Loging the update
	update_post_meta($id, 'mockup_update', '111');
}