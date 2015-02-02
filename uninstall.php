<?php

if(defined('WP_UNINSTALL_PLUGIN')) {

	$options = get_option('mockup_uninstall');
	if(!is_multisite()) {
		foreach($options as $name) {
			delete_option($name);
		}

		delete_option('mockup_related_popup_btn'); // last use: 1.1
		delete_option('mockup_sidebar'); // last use: 1.4.1
		delete_option('mockup_uninstall');
	} else {

		global $wpdb;
		$blog_ids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
		$original_blog_id = get_current_blog_id();

		foreach($blog_ids as $blog_id) {

			switch_to_blog($blog_id);
			foreach($options as $name) {
				delete_option($name);
			}

			delete_option('mockup_related_popup_btn'); // last use: 1.1
			delete_option('mockup_sidebar'); // last use: 1.4.1
			delete_option('mockup_uninstall');
		}

		switch_to_blog($original_blog_id);
	}
}