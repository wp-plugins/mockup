		<!-- Related popup -->
		<div id="related" class="modal hide fade">
	
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
							'orderby' => 'menu_order',
							'order' => 'ASC',
							'tax_query' => array(
								array(
									'taxonomy' => 'relate_mockup',
									'terms' => $term->term_id
								)
							)
						);
						
						$the_query = new WP_Query( $args );
		
							$i = 0;
							
							echo '<table class="table">';
		
							while ( $the_query->have_posts() ) : $the_query->the_post();
							
								$i++;

								$temp_id = get_the_ID();

								if($the_id == $temp_id) { $disable = ' disabled'; } else { $disable = ''; }
												
									echo '<tr>';
		
									//echo '<img src="'.wp_get_attachment_url(get_post_thumbnail_id($temp_id) ).'">' ;
		
									echo '<th class="span8">';

									echo substr(get_the_title(), 0, 40); if(strlen(get_the_title()) > 41) { echo '&#133;'; }
		
									echo '</th>';

									echo '<th class="span4">';

									echo '<a class="btn btn-mini btn-info pull-right" href="'.get_permalink().'"'.$disable.'>'.get_option('mockup_related_popup_btn').'</a>';

									echo '</th>';
									
									echo '</tr>';
	
							endwhile;
							
							echo '</table>';
	
							if($i < 2) { $hide_related_btn = true; } else { $hide_related_btn = false; }
	
						wp_reset_postdata(); ?>
				
			</div>
	
			<div class="modal-footer">
				<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
			</div>
	
	    </div>


	<!-- Description popup -->
	<?php $content = get_the_content(); if(!$content == '') { ?>
	
		<div id="description" class="modal hide fade">
	
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
		<div id="comment" class="modal hide fade">

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
		<div id="approve" class="modal hide fade">
	
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