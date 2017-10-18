<div class="wrap">
	<h2>Posts Date Reschedule</h2>
	<form method="POST">
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row" class="titledesc">
						<label for="pdr_function">Function</label>				
					</th>
					<td class="forminp forminp-select">
						<select id="pdr_function" name="pdt_function">
							<option value="1">Same date for all</option>
							<option value="2">Current date plus selected days</option>
							<option value="3">Current date minus selected days</option>
							<option value="4">Random dates</option>
						</select>
					</td>
				</tr>
				<tr valign="top" class="pdr_date_selector pdr_function_selector">
					<th scope="row" class="titledesc">
						<label for="psr_date_selector">Date & time for selected posts</label>				
					</th>
					<td class="forminp">
						<input type="date" id="psr_date_selector" name="pdt_function_date">
						<input type="time" id="psr_time_selector" name="pdt_function_time">
					</td>
				</tr>
				<tr valign="top" class="pdr_plus_selector pdr_function_selector">
					<th scope="row" class="titledesc">
						<label for="pdr_plus_selector">Plus days for selected posts</label>				
					</th>
					<td class="forminp">
						Post date + 
						<input type="number" id="pdr_plus_selector" name="pdt_function_days_plus">
						days
					</td>
				</tr>
				<tr valign="top" class="pdr_minus_selector pdr_function_selector">
					<th scope="row" class="titledesc">
						<label for="pdr_minus_selector">Minus days for selected posts</label>				
					</th>
					<td class="forminp">
						Post date - 
						<input type="number" id="pdr_minus_selector" name="pdt_function_days_minus">
						days
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" class="titledesc">
						<label for="pdr_minus_selector">Select posts</label>				
					</th>
					<td class="forminp">
						<select multiple="true" style="height: 350px;" name="pdr_posts[]">
							<?php
								$cpts = array();
								foreach ( get_post_types( array( 'public' => true ) ) as $cpt ) {
									array_push( $cpts, $cpt );
								}
								unset($cpts[2]);	
							
								$args = array(
									'post_type' => $cpts,
									'orderby' => 'date',
									'order' => 'DESC',
									'numberposts' => -1,
								);

								$get_posts = get_posts($args);
								foreach ($get_posts as $post) {
									echo '<option value="'.$post->ID.'">'.$post->post_title.'</option>';
								}
							?>
						</select>
					</td>
				</tr>
			</tbody>
		</table>
		<p class="submit">
			<input name="pdr_save" class="button-primary" type="submit" value="Go!">
		</p>
		<p class="developer">
			Developed by: Stoyan Kostadinov
		</p>
	</form>
</div>