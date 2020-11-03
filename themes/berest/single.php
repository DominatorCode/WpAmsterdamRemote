<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package berest
 */

get_header();

?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">

			<?php
			while (have_posts()) :
				the_post();

				get_template_part('template-parts/content', 'blog');

				// If comments are open and enable or we have at least one comment, load up the comment template.
				$comments_enable = false;
				if ($comments_enable && (comments_open() || get_comments_number())) {
					comments_template();
				}

			endwhile; // End of the loop.
			?>

			<!--	Navigation		-->
			<nav aria-label="Page navigation" class="aos-init aos-animate"
			     data-aos="fade-in" style="text-align: center">
				<ul class="pagination">
					<?php

					if (get_next_post_link()) {
						echo get_next_post_link('<li class="active">%link </li>', '&laquo; Previous: %title');
					}
					if (get_previous_post_link()) {
						echo get_previous_post_link('<li class="active">%link </li>', 'Next: %title &raquo;');
					}

					?>
				</ul>
			</nav>
	</div>
	</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
