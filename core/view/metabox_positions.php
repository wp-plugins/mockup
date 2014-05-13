<?php $position = get_post_meta($post->ID, '_mockup_position_1', true); ?>

<div class="table table_content">

	<ul>

		<li><input type="radio" name="mockup_position" value="center top" id="ct"<?php if($position == 'center top' || empty($position)) echo ' checked="checked"' ; ?>><label for="ct">Center / Top</label></li>
		<li><input type="radio" name="mockup_position" value="center center" id="cc"<?php if($position == 'center center') echo ' checked="checked"' ; ?>><label for="cc">Center / Center</label></li>
		<li><input type="radio" name="mockup_position" value="left top" id="lt"<?php if($position == 'left top') echo ' checked="checked"' ; ?>><label for="lt">Left / Top</label></li>
		<li><input type="radio" name="mockup_position" value="left center" id="lc"<?php if($position == 'left center') echo ' checked="checked"' ; ?>><label for="lc">Left / Center</label></li>
		<li><input type="radio" name="mockup_position" value="right top" id="rt"<?php if($position == 'right top') echo ' checked="checked"' ; ?>><label for="rt">Right / Top</label></li>
		<li><input type="radio" name="mockup_position" value="right center" id="rc"<?php if($position == 'right center') echo ' checked="checked"' ; ?>><label for="rc">Right / Center</label></li>

	</ul>

</div>