<?php

	$id = filter_input(INPUT_POST, 'post_id', FILTER_SANITIZE_NUMBER_INT);
	$terms = get_the_terms($id, $this->taxonomy);

	if(!empty($id) && $terms && !is_wp_error($terms)) {

		$term_id_array = array();

		foreach($terms as $term) {

			$term_id_array[] = $term->term_id;
		}
	
		$args = array(
			'post_type' 		=> $this->posttype,
			'orderby' 			=> 'menu_order',
			'order' 			=> 'ASC',
			'posts_per_page' 	=> 999,
			'tax_query' 		=> array(
										array(
											'taxonomy' 	=> $this->taxonomy,
											'terms' 	=> $term_id_array
										)
			)
		);

		$the_query = new WP_Query($args);

		echo '<h1>'.get_option('mockup_related_title').'</h1>';

		echo '<ul>';

		while($the_query->have_posts()) : $the_query->the_post();

			$tmp_id = get_the_ID();
			$mockup = get_post_meta($tmp_id, '_mockup_id_1', true);
			$status = get_post_meta($tmp_id, '_mockup_status_1', true);

			if(empty($mockup)) continue;

			if($tmp_id == $id) {

				echo '<li><p><i class="fa fa-circle"></i> <strong>'.get_the_title().'</strong></p></li>';

			} elseif(isset($status['approved']) && $status['approved'] == true) {

				echo '<li><p><i class="fa fa-check-circle-o"></i> <strong><a href="'.get_permalink().'">'.get_the_title().'</a></strong></p></li>';

			} else {

				echo '<li><p><i class="fa fa-circle-o"></i> <strong><a href="'.get_permalink().'">'.get_the_title().'</a></strong></p></li>';
			}


		endwhile;

		echo '</ul>';

		wp_reset_postdata();
	}