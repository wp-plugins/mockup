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
							
							$the_query = new WP_Query( $args );
			
								$i = 0;
								
								echo '<table class="table">';
			
								while ( $the_query->have_posts() ) : $the_query->the_post();
								
									$i++;

									$temp_id = get_the_ID();

									if($the_id == $temp_id) { $disable = ' disabled'; } else { $disable = ''; }
													
										echo '<tr>';
			
										echo '<th class="span8">';

										echo substr(get_the_title(), 0, 40); if(strlen(get_the_title()) > 41) { echo '&#133;'; }
			
										echo '</th>';

										echo '<th class="span4">';

										echo '<a class="btn btn-mini btn-info pull-right" href="'.get_permalink().'"'.$disable.'>'.get_option('mockup_related_popup_btn').'</a>';

										echo '</th>';
										
										echo '</tr>';
		
								endwhile;
							
							echo '</table>';

							$hide_related_btn = false;

							} else {
	
								$hide_related_btn = true;

							}
	
						wp_reset_postdata(); ?>
				
			</div>
	
			<div class="modal-footer">
				<button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo get_option('mockup_close_btn'); ?></button>
			</div>
	
	    </div>


	<!-- Description popup -->
	<?php $content = nl2br(get_post_meta(get_the_ID(), 'mockup_description', true));
		// Untill version 1.0.7 the content was in the_content now it is in the customfield 'mockup_description'.
		// Here it will be copied to the customfield.
		// This will be removed in version 2.0. 
		if($content == '') {
			$content = get_the_content();
		}

		if(!$content == '') { ?>
	
		<div id="description" class="modal hide fade">
	
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h3 id="myModalLabel"><?php echo get_option('mockup_description_title'); ?></h3>
			</div>
	
			<div class="modal-body">
				<p><?php echo $content; ?></p>
			</div>

			<div class="modal-footer">
				<button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo get_option('mockup_close_btn'); ?></button>
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

				<form class="mockup_remark form-horizontal" action="<?php the_permalink(); ?>" method="post">

					<div class="control-group">

						<input type="text" class="span3" name="name" id="name" placeholder="<?php echo get_option('mockup_comment_name_label'); ?>" />

					</div>

					<div class="control-group">

						<textarea rows="8" class="span3" name="comment" id="comment" placeholder="<?php echo get_option('mockup_comment_message_label'); ?>"></textarea>

					</div>

					<input type="submit" value="<?php echo get_option('mockup_send_btn'); ?>" class="btn btn-primary">
					<input type="hidden" name="mockup_remark" value="true" />

				</form>

				<div class="comments">

<?php 				
						$mockup_remark_count 	= get_post_meta(get_the_ID(), 'mockup_remark_count_1', true);
						$i 						= $mockup_remark_count;

						if($mockup_remark_count == 0) {

							echo '<div class="well span3 pull-right">';

								echo '<p>'.get_option('mockup_comment_no_comments').'<p>';

							echo '</div>';

						} else {

							$name = get_post_meta(get_the_ID(), 'mockup_remark_name_1', false);
							$date = get_post_meta(get_the_ID(), 'mockup_remark_date_1', false);
							$text = get_post_meta(get_the_ID(), 'mockup_remark_text_1', false);

							while ($i > 0) {

								$i--;

								echo '<div class="well span3 pull-right">';

									echo '<p><strong><small>'.$name[$i].'</small></strong> <small class="pull-right">'.$date[$i].'</small><br />';
									echo nl2br($text[$i]);
									echo '</p>';

								echo '</div>';

							}

						} ?>

				</div>

			</div>


			<div class="modal-footer">
				
				<button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo get_option('mockup_close_btn'); ?></button>

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

			</div>
	
			<div class="modal-footer">

				<form action="<?php the_permalink(); ?>" method="post">

					<input type="submit" value="<?php echo get_option('mockup_approve_btn'); ?>" class="btn btn-success">
					<input type="hidden" name="approved" value="true" />
					<button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo get_option('mockup_close_btn'); ?></button>

				</form>

			</div>
	
	    </div>