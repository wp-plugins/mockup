		<!-- Related popup -->
		<div id="related" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h3 id="myModalLabel"><?php echo get_option('mockup_related_title'); ?></h3>
			</div>
	
			<div class="modal-body">
				
				
					<?php
						
						$the_id = get_the_ID();
						
						$terms = get_the_terms( $the_id , 'relate_mockup' );
						if($terms) {
							foreach( $terms as $term ) {
								$term->term_id;
							}
						}
			
						$args = array(
							'post_type' => 'pt_mockup_plugin',
							'tax_query' => array(
								array(
									'taxonomy' => 'relate_mockup',
									'terms' => $term->term_id
								)
							)
						);
						
						$the_query = new WP_Query( $args );
		
							$i = 0;
							
							echo '<ul class="thumbnails">';
		
							while ( $the_query->have_posts() ) : $the_query->the_post();
							
								$i++;
							
								$temp_id = get_the_ID();
							
								if($the_id != $temp_id) {
										
									echo '<li class="span3">';
							
									echo '<div class="thumbnail">';
		
									echo '<img src="'.wp_get_attachment_url(get_post_thumbnail_id($temp_id) ).'">' ;
		
									echo '<h4>';

									the_title();
		
									echo '</h4>';

									echo '<p>';

									if(get_post_meta(get_the_ID(), 'mockup_approved', true) == 'approved') {

										echo '<span class="label label-success">';

										echo get_option('mockup_approved_text');

										echo '</span>';


									} else {

										echo substr(get_the_content(), 0, 55); if ( strlen(get_the_content()) > 56) { echo '…'; }
									
									}
									
									echo '</p>';
		
									echo '<a class="btn btn-primary" href="'.get_permalink().'">'.get_option('mockup_related_popup_btn').'</a>';

									echo '</div>';
									
									echo '</li>';
	
								}
	
							endwhile;
							
							echo '</ul>';
	
							if($i < 2) { $hide_related_btn = true; } else { $hide_related_btn = false; }
	
						wp_reset_postdata(); ?>
				
			</div>
	
			<div class="modal-footer">
				<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
			</div>
	
	    </div>


	<!-- Description popup -->
	<?php $content = get_the_content(); if(!$content == '') { ?>
	
		<div id="description" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h3 id="myModalLabel"><?php echo get_option('mockup_description_title'); ?></h3>
			</div>
	
			<div class="modal-body">
				<p><?php the_content(); ?></p>
			</div>

			<div class="modal-footer">
				<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
			</div>

	    </div>

	<?php } ?>


		<!-- Comment popup -->
		<div id="comment" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h3 id="myModalLabel"><?php echo get_option('mockup_comment_title'); ?></h3>
			</div>
	
			<div class="modal-body">

				<form class="mockup_remark" action="<?php the_permalink(); ?>" method="post">

					<label for="name"><?php echo get_option('mockup_comment_name_label'); ?></label>
					<input type="text" class="span3" name="name" id="name" />

					<label for="comment"><?php echo get_option('mockup_comment_message_label'); ?></label>
					<textarea class="span6" rows="7" name="comment" id="comment"></textarea>

			</div>

			<div class="modal-footer">

					<input type="submit" value="<?php echo get_option('mockup_send_btn'); ?>" class="btn btn-primary">
					<input type="hidden" name="mockup_remark" value="true" />

				</form>

				<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>

			</div>

	    </div>

		<!-- Approve popup -->
		<div id="approve" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h3 id="myModalLabel"><?php echo get_option('mockup_approve_title'); ?></h3>
			</div>
	
			<div class="modal-body">
				<p><?php echo get_option('mockup_approve_text'); ?></p>

				<form action="<?php the_permalink(); ?>" method="post">

			</div>
	
			<div class="modal-footer">

					<input type="submit" value="<?php echo get_option('mockup_approve_btn'); ?>" class="btn btn-success">
					<input type="hidden" name="approved" value="true" />

				</form>

				<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>

			</div>
	
	    </div>