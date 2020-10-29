<?php
/**
 * Template Name: Home Page
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

use DirectoryCustomFields\ConfigurationParameters;

get_header();
?>

	<div class="container main_content">
		<div class="row">
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<!--Home Page Sections-->
				<section class="home-banner">
					<div class="container" data-aos="fade-in">
						<div class="row">
							<?php

							// Show big models picture
							$id_big = ConfigurationParameters::GetTermIdByName(ConfigurationParameters::$name_term_featured,
								ConfigurationParameters::$name_slug_taxonomy_main);
							$query_big = 'cat=' . $id_big . '&showposts=1&order=DESC&orderby=rand&post_type=directory';
							$latest_posts = query_posts($query_big); ?>
							<?php if (have_posts()) : while (have_posts()) : the_post();
								$exclude_ids = get_the_ID();
								?>
								<div class="col-md-7">
									<?php
									$meta_values_ = get_post_meta(get_the_ID(), '_igmb_image_gallery_id', true);

									foreach ($meta_values_ as $_value) {
										$image_ = wp_get_attachment_image_src($_value, 'medium_size');
									}
									?>
									<a href="<?php the_permalink(); ?>">
										<div style="background-image: url('<?php echo $image_[0]; ?>')"
										     class="left-div">
											<div class="top-div">
												<?php the_title(); ?>
											</div>
											<div class="bottom-div">
												<?php

												// get used cities for model
												$arr_name_terms_model = get_post_terms_data($post->ID,
													ConfigurationParameters::$name_slug_taxonomy_location);

												echo $arr_name_terms_model[0];
												?>
											</div>
										</div>
									</a>
								</div>
							<?php endwhile; endif;
							wp_reset_query(); ?>
							<div class="col-md-5">
								<div class="featured-div">
									<h2>FEATURED <span>ESCORTS</span></h2>
									<div class="row">
										<?php

										//get data of models for FEATURED section
										$id_term_featured = ConfigurationParameters::GetTermIdByName(
											ConfigurationParameters::$name_term_featured,
											ConfigurationParameters::$name_slug_taxonomy_main);

										$args = array(
											'posts_per_page' => 4,
											'orderby' => 'rand',
											'order' => 'DESC',
											'post_type' => 'directory',
											'post_status' => 'publish',
											'cat' => $id_term_featured,
											'post__not_in' => array($exclude_ids)
										);
										$loop = new WP_Query($args);
										//$latest_posts = query_posts('cat=2&post_type=directory&showposts=4&order=DESC&orderby=rand&post__not_in ='.array($exclude_ids));
										?>

										<?php if ($loop->have_posts()) : while ($loop->have_posts()) : $loop->the_post(); ?>
											<div class="col-xs-6">
												<div class="thum-box-div" data-aos="zoom-in-up">
													<?php
													$meta_values_2 = get_post_meta(get_the_ID(), '_igmb_image_gallery_id', true);
													unset($image_2);
													foreach ($meta_values_2 as $_value2) {
														$image_2 = wp_get_attachment_image_src($_value2, 'thumbnail_size');
													}
													?>
													<a href="<?php the_permalink(); ?>">
														<div style="background-image: url('<?php echo $image_2[0]; ?>')"
														     class="img-div"></div>
													</a>
													<div class="top-div">
														<?php the_title(); ?>
													</div>
													<div class="bottom-div">
														<?php
														// get used cities for model
														$arr_name_terms_model = get_post_terms_data($post->ID, ConfigurationParameters::$name_slug_taxonomy_location);

														echo $arr_name_terms_model[0];
														?>
													</div>
												</div>
											</div>
										<?php endwhile; endif;
										wp_reset_query(); ?>

									</div>
								</div>
							</div>
						</div>
					</div>
				</section>
				<article>
					<section
							style="background-image: url(https://berlinescort.com/wp-content/themes/berest/images/welcom-bg.jpg)"
							class="welcome-div" data-aos="fade-in">
						<div class="container" data-aos="flip-up">
							<h2>WELCOME TO <img
										src="../wp-content/themes/berest/images/welcome-logo.png"
										alt="" width="334" height="53"></h2>
							<?php

							$page_id = ConfigurationParameters::GetPageIdByTitle(ConfigurationParameters::$name_page_home);
							$page_data = get_post($page_id);
							echo apply_filters('the_content', $page_data->post_content);
							?>

						</div>
					</section>

					<section class="hot-modal-section paddingTB50" data-aos="fade-in">
						<div class="container">
							<h2 class="section-title text-center"><span>HOT MODELS</span></h2>
							<ul class="col5-row">
								<?php

								// HOT MODELS section
								// create new loop for 5 models from 'Party Girl' category
								$id_exclude = ConfigurationParameters::GetTermIdByName(
									ConfigurationParameters::$name_term_featured,
									ConfigurationParameters::$name_slug_taxonomy_main);

								$arr_query =  array( 'category__not_in' => array($id_exclude), 'post_type' => 'directory',
									'orderby' => 'title', 'order' => 'DESC',
									'showposts' => 5);
								//$query_models = 'category__in=' . $id_exclude . '&post_type=directory&showposts=5&order=DESC';

								$latest_posts = query_posts($arr_query);
								?>

								<?php /**
								 * Returns used terms data for given post
								 * @param $id_post
								 * @param $slug_taxonomy
								 * @param string $type_fields
								 * @return array|WP_Error
								 */
								function get_post_terms_data($id_post, $slug_taxonomy,$type_fields = 'names')
								{
									return wp_get_post_terms($id_post,
										$slug_taxonomy,
										array('fields' => $type_fields, 'orderby' => 'parent'));
								}

								if (have_posts()) : while (have_posts()) : the_post(); ?>
									<li>
										<div class="post-block-div">
											<div class="thum-box-div" data-aos="zoom-in">
												<?php $meta_values_2 = get_post_meta(get_the_ID(), '_igmb_image_gallery_id', true);
												foreach ($meta_values_2 as $_value2) {
													$image_2 = wp_get_attachment_image_src($_value2, 'thumbnail_size');
												} ?>
												<a href="<?php the_permalink(); ?>">
													<div style="background-image: url('<?php echo $image_2[0]; ?>')"
													     class="img-div"></div>
												</a>
												<div class="top-div">
													<?php the_title(); ?>
												</div>
												<div class="bottom-div">
													<?php
													// get used cities for model
													$arr_name_terms_model = get_post_terms_data($post->ID, ConfigurationParameters::$name_slug_taxonomy_location);

													// display models first city
													echo $arr_name_terms_model[0];
													?>
												</div>
												<div class="new-tag"></div>
											</div>
											<div class="bottom-content">
												<h3><?php the_title(); ?></h3>
												<?php
												$arr_name_terms_model = get_post_terms_data($post->ID, ConfigurationParameters::$name_slug_taxonomy_main);
												echo $arr_name_terms_model[0]; ?>
											</div>
										</div>
									</li>
								<?php endwhile; endif;
								wp_reset_query(); ?>

							</ul>
						</div>
					</section>

					<section class="our-blog-section paddingB50" data-aos="fade-in">
						<div class="container">
							<h2 class="section-title text-center"><span>OUR BLOG</span></h2>
							<?php

							// display models from 'Blog' category
							$id_blog = ConfigurationParameters::GetTermIdByName(ConfigurationParameters::$name_term_blog,
								ConfigurationParameters::$name_slug_taxonomy_main);
							$query_blog = 'cat=' . $id_blog . '&showposts=2&order=DESC';
							$latest_posts = query_posts($query_blog);

							?>
							<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

								<div class="blog-posts-div" data-aos="fade-up">
									<div class="row">
										<div class="col-sm-4">
											<?php $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'single-post-thumbnail'); ?>
											<div class="img-div"
											     style="background-image: url('<?php echo $image[0]; ?>')">
												<a href="<?php the_permalink(); ?>"><img
															src="/wp-content/themes/berest/images/blank-blog-img.png"
															alt="" width="427" height="255"> </a>
											</div>
										</div>
										<div class="col-sm-8">
											<div class="content-div">
												<div class="inner-div">
													<div class="right-icon"><img
																src="/wp-content/themes/berest/images/edit-icon.png"
																alt="" width="33" height="30"></div>
													<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
													</h2>
													<p><?php the_excerpt(); ?></p>
													<a href="<?php the_permalink(); ?>" class="btn">READ MORE</a>
												</div>
											</div>
										</div>
									</div>
								</div>
							<?php endwhile; endif;
							wp_reset_query(); ?>

						</div>
					</section>
				</article>
				<!--end here-->
			</article><!-- #post-<?php the_ID(); ?> -->
		</div>
	</div>

<?php
//get_sidebar();
get_footer();
