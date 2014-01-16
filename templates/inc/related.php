<?php 

echo '<h1>'.get_option('mockup_related_title').'</h1>';

echo '<hr>';

if($terms) {

	foreach($terms as $term) {
		$term->term_id;
	}

	$args = array(
		'post_type' 		=> 'pt_mockup_plugin',
		'orderby' 			=> 'menu_order',
		'order' 			=> 'ASC',
		'posts_per_page' 	=> 999,
		'tax_query' 		=> array(
									array(
										'taxonomy' 	=> 'relate_mockup',
										'terms' 	=> $term->term_id
									)
		)
	);
	
	$the_query = new WP_Query($args);

		$i = 0;

		while($the_query->have_posts()) : $the_query->the_post();
		
			$i++;

			$temp_id = get_the_ID();

			if(mockup_id == $temp_id) { $disable = ' disabled'; } else { $disable = NULL; }

				echo '<p>'.$i.'. ';

				echo substr(get_the_title(), 0, 40); if(strlen(get_the_title()) > 41) { echo '&#133;'; }

				echo '<a class="btn btn-info btn-xs pull-right" href="'.get_permalink().'"'.$disable.'>'.get_option('mockup_related_popup_btn').'</a>';

				echo '</p>';

		endwhile;

	wp_reset_postdata();

}