<?php

if(defined('WP_UNINSTALL_PLUGIN')) {

	$options = get_option('mockup_uninstall');

	// For Single site
	if(!is_multisite()) {

		// Loop through the options
		foreach($options as $name) {
			delete_option($name);
		}

		delete_option('mockup_related_popup_btn');
		delete_option('mockup_uninstall');

	// For Multisite
	} else {

		global $wpdb;
		$blog_ids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
		$original_blog_id = get_current_blog_id();

		foreach($blog_ids as $blog_id) {

			switch_to_blog($blog_id);

			// Loop through the options
			foreach($options as $name) {
				delete_option($name);
			}

			delete_option('mockup_related_popup_btn');
			delete_option('mockup_uninstall');
		}

		switch_to_blog($original_blog_id);
	}
}