<?php

use DirectoryCustomFields\ConfigurationParameters;

function get_next_single_page_link($paged, $label = null, $max_page = 0)
{
	global $wp_query;

	if (!$max_page) {
		$max_page = $wp_query->max_num_pages;
	}

	if (is_singular()) {

		$next_page = (int)$paged + 1;

		if (null === $label) {
			$label = __('Next Page &raquo;');
		}

		if (($next_page <= $max_page)) {
			return '<a href="' . get_single_pagination_link($next_page) . '">' . $label . '</a>';
		}

	}
}

function get_previous_single_page_link($paged, $label = null)
{

	if (is_singular()) {

		$prev_page = (int)$paged - 1;

		if (null === $label) {
			$label = __('&laquo; Previous Page');
		}

		if (($prev_page > 0)) {
			return '<a href="' . get_single_pagination_link($prev_page) . '">' . $label . '</a>';
		}

	}

}

function get_single_pagination_link($page_num = 1)
{
	global $wp_rewrite;

	if ($wp_rewrite->permalink_structure === '/%postname%/' && is_singular()) {

		$page_num = (int)$page_num;

		$post_id = get_queried_object_id();
		$request = get_permalink($post_id);

		if ($page_num > 1) {
			$request = trailingslashit($request) . user_trailingslashit($page_num);
		}

		return esc_url($request);

	}

	wp_die('<strong>The function get_single_pagination_link() requires that your permalinks are set to /%postname%/</strong>');
}

function get_related_author_posts($paged = -1)
{

	if ($paged === -1) {
		$paged = (get_query_var('page')) ?: 1;
	}

	$args = array(
		'post_type' => 'post',
		'post_status' => 'publish',
		'posts_per_page' => ConfigurationParameters::$count_pagination_posts,
		'paged' => $paged,
		'order' => 'ASC');
	$authors_posts = new WP_Query($args);

	$output = '';

	if ($authors_posts->have_posts()) {

		if ($paged <=> 1 && get_previous_post_link()) {
			$output.= '<li class="active"><span aria-hidden="true">' .
				get_previous_single_page_link($paged, '<img src="/wp-content/themes/berest/images/left-arrow.png">') .
				'</span></li>';
		}


		while ($authors_posts->have_posts()) {
			$authors_posts->the_post();
			$count_post = $authors_posts->current_post + ($paged - 1) *
				ConfigurationParameters::$count_pagination_posts + 1;
			$output .= '<li p="' . ($count_post - 1) . '" class="active"><a href="' . get_permalink() . '">' . $count_post . '</a>' . '</li>';

		}

		if ($paged <=> $authors_posts->max_num_pages && get_next_post_link()) {
			$output .= '<li class="active"><span aria-hidden="true">';
			$output .= get_next_single_page_link($paged, '<img src="/wp-content/themes/berest/images/right-arrow.png">', $authors_posts->max_num_pages);
			$output .= "</span></li>";
		}

		wp_reset_postdata();
	}

	return $output;

}

//remove_filter('template_redirect', 'redirect_canonical');