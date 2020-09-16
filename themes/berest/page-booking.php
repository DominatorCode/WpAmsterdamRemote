<?php
/**
 * Template Name: Booking Page
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package berest
 */

get_header();

if (isset($_SESSION['booking_page_id'])) {
	$directory_id = $_SESSION['booking_page_id'];
} else {
	$directory_id = 0;
}

$meta = get_post_meta($directory_id, '_igmb_image_gallery_id', true);
$selected_image = wp_get_attachment_image_src($meta[1]);
$selected_image = $selected_image[0];

$title = get_the_title($directory_id);

if ($directory_id != 0)
	$model_name = $title;
else
	$model_name = "";

$args = array(
	'post_type' => array('directory'),
	'nopaging' => false,
	'order' => 'DESC',
	'orderby' => 'modified',
);

$query = new WP_Query($args);

$directory = [];

if ($query->have_posts()) {

	while ($query->have_posts()) {
		$query->the_post();

		$permalink = get_permalink();
		$post_id = get_the_ID();

		$meta = get_post_meta($post_id, '_igmb_image_gallery_id', true);
		$image = wp_get_attachment_image_src($meta[1]);
		$image = $image[0];

		$title = get_the_title();

		if (empty($image))
			$image = "/wp-content/themes/berest/images/booking-placeholder.png"; // Default;

		$directory[$post_id] = array('url' => $image, 'title' => $title);

	}

}

wp_reset_postdata(); // reset the query 

?>

	<div class="container main_content">
		<div class="row">
			<div class="entry-content">
				<article>
					<section class="booking-section paddingTB50" data-aos="fade-in">
						<div class="container">
							<h1 class="text-center">BOOK <span class="model-name"><?php echo $model_name; ?></span> NOW!
							</h1>
							<div class="custom-form-div">
								<h3>Your contact info</h3>
								<div class="row">
									<div class="col-md-6">
										<div class="left-div">
											<div class="form-group">
												<label>NAME</label>
												<input type="text" id="name" class="form-control">
											</div>
											<div class="form-group">
												<label>EMAIL</label>
												<input type="email" id="email" class="form-control">
											</div>
											<div class="form-group">
												<label>PHONE</label>
												<input type="tel" id="tel" class="form-control">
											</div>
											<div class="form-group">
												<label>LOCATION</label>
												<input type="tel" id="location" class="form-control">
											</div>
											<div class="form-group">
												<label>MESSAGE</label>
												<textarea class="form-control" id="message" rows="3"></textarea>
											</div>

										</div>
									</div>
									<div class="col-sm-6 col-md-3">
										<div class="left-div">
											<div class="form-group">
												<label>MODEL</label>
												<select class="form-control w150 madel-dropdown"
												        onchange="booking.modelChange(this)">
													<option value="0">Choose Model</option>
													<?php
													foreach ($directory as $key => $value) {
														echo '<option img="' . $value['url'] . '" value="' . $key . '" ' . isSelected($key, $directory_id) . '>' . $value['title'] . '</option>';
													}
													?>
												</select>
											</div>
											<div class="form-group">
												<label>DATE</label>
												<table cellpadding="0" cellspacing="0" width="100%" class="w200">
													<tr>
														<td width="112">
															<select class="form-control month-dropdown">
																<option>Month</option>
																<option value='01'>January</option>
																<option value='02'>Febuary</option>
																<option value='03'>March</option>
																<option value='04'>April</option>
																<option value='05'>May</option>
																<option value='06'>June</option>
																<option value='07'>July</option>
																<option value='08'>August</option>
																<option value='09'>September</option>
																<option value='10'>October</option>
																<option value='11'>November</option>
																<option value='12'>December</option>
															</select>
														</td>
														<td width="10"></td>
														<td>
															<select class="form-control day-dropdown">
																<option>Day</option>
																<?php for ($day = 1; $day <= date("t"); $day++) {
																	echo '<option>' . $day . '</option>';
																} ?>
															</select>
														</td>
													</tr>
												</table>
											</div>
											<div class="form-group">
												<label>DURATION</label>
												<select class="form-control w100 duration-dropdown">
													<option>1 Hour</option>
													<option>2 Hour</option>
													<option>3 Hour</option>
												</select>
											</div>
											<div class="form-group" id="booking-type">
												<label class="c-radio">INCALL
													<input type="radio" value="INCALL" name="rr" checked="checked">
													<span class="checkmark"></span>
												</label>
												<label class="c-radio">OUTCALL
													<input type="radio" value="OUTCALL" name="rr">
													<span class="checkmark"></span>
												</label>
											</div>

											<div class="book-btn">
												<button class="btn" onclick="booking.bookClick(event)">BOOK NOW!
												</button>
											</div>
										</div>
									</div>
									<div class="col-sm-6 col-md-3">
										<div id="image" style="background-image: url(<?php echo $selected_image; ?>)"
										     class="background-block">
											<img src="/wp-content/themes/berest/images/blank-bg1.png" alt=""/>
										</div>
									</div>
								</div>
							</div>
						</div>
					</section>
				</article>
			</div>
		</div>
	</div>

<?php
//get_sidebar();
get_footer();
