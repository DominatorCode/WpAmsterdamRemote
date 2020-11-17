<?php
/**
 * Template part for pagination blog/posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package berest
 */

?>
<nav aria-label="Page navigation" class="pagination-div aos-init aos-animate"
     data-aos="fade-in">
	<ul class="pagination">

		<?php

		use DirectoryCustomFields\ConfigurationParameters;

		$paged = (get_query_var('paged')) ?: '1';

		$args = array(
			'post_type' => 'post',
			'post_status' => 'publish',
			'posts_per_page' => ConfigurationParameters::$count_pagination_posts,
			'paged' => $paged,
			'order' => 'ASC');

		// The Query
		$query = new WP_Query($args);

		// The Loop
		if ($query->have_posts()) {

			if ($paged <=> 1 && get_previous_post_link() ) {
				echo '<li class="active"><span aria-hidden="true">';
				previous_posts_link('<img src="/wp-content/themes/berest/images/left-arrow.png">');
				echo '</span></li>';
			}

			while ($query->have_posts()) : $query->the_post();
				$count_post = $query->current_post + ($paged - 1) *
					ConfigurationParameters::$count_pagination_posts;
				?>
				<li p="<?php echo $count_post; ?>"
				    class="active">
					<a href="<?php the_permalink(); ?>"><?php echo $count_post + 1; ?></a>
				</li>

			<?php endwhile;

			if ($paged <=> $query->max_num_pages && get_next_post_link()) {
				echo '<li class="active"><span aria-hidden="true">';
				next_posts_link('<img src="/wp-content/themes/berest/images/right-arrow.png">',
					$query->max_num_pages);
				echo '</span></li>';
			}

		}
		// Reset Query
		wp_reset_postdata();
		?>

	</ul>
</nav>
