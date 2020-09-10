<?php
/*
Template Name: Blog Page Template
*/

/**
 * Returns data of last published post
 * @return int|mixed|WP_Post
 */
function get_last_post_data()
{

	$args = array(
		'numberposts' => '1',
	);
	return wp_get_recent_posts($args)[0];

}

get_header();
?>

<div class="container main_content">
	<div class="row">

		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<div class="entry-content">

				<article class="blog-page-article">
					<section class="our-blog-section paddingB50 aos-init aos-animate" data-aos="fade-in">
						<div class="container">
							<h1 class="title text-center">ESCORT BLOG</h1>
							<div class="blog-posts-div aos-init aos-animate" data-aos="fade-up">
								<div class="row">
									<div class="col-sm-4">
										<div class="img-div"
										     style="background-image: url(https://berlinescort.com/wp-content/uploads/2019/06/b-post-img.png)">
											<a href="#"><img loading="lazy"
											                 src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'full'); ?>"
											                 width="427" height="255" alt=""> </a>
										</div>
									</div>
									<div class="col-sm-8">
										<div class="content-div">
											<div class="inner-div">
												<div class="right-icon"><img loading="lazy"
												                             src="https://berlinescort.com/wp-content/themes/berest/images/edit-icon.png"
												                             width="33" height="30" alt=""></div>
												<?php the_content(); ?>
												<div class="tages-div">
													<ul>
														<li><a href="#">Sexy Escort</a></li>
														<li><a href="#">Escort Babe</a></li>
														<li><a href="#">New Escort</a></li>
													</ul>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>


							<!--<editor-fold desc="Custom Pagination">-->
							<nav aria-label="Page navigation" class="pagination-div aos-init aos-animate"
							     data-aos="fade-in">
								<ul class="pagination">
									<li class="selected"><span aria-hidden="true"><img
													src="https://www.berlinescort.com/wp-content/themes/berest/images/left-arrow.png"></span>
									</li>

									<?php
									$paged = (get_query_var('paged')) ?: 1;
									query_posts(
										array(
											'post_type' => 'post',
											'posts_per_page' => 3,
											'paged' => $paged,
											'order' => 'ASC')
									);
									// The Loop
									$number_page = 1;
									while (have_posts()) : the_post(); ?>
										<li p="<?php echo $number_page ?>" class="active">
											<a href="<?php the_permalink(); ?>"><?php echo $number_page++ ?></a>
										</li>

									<?php endwhile;

									// Reset Query
									wp_reset_query();
									?>
									<?php $url_post_last = get_permalink(get_last_post_data()['ID']); ?>
									<li p="<?php echo $number_page ?>" class="active"><span aria-hidden="true">
											<a href="<?php echo $url_post_last ?>">
												<img src="https://www.berlinescort.com/wp-content/themes/berest/images/right-arrow.png">
											</a></span>
									</li>
								</ul>
							</nav>
							<!--</editor-fold>-->

						</div>
					</section>
				</article>
			</div><!-- .entry-content -->

		</article><!-- #post-16 -->
	</div>
</div>

