<?php

function wporg_register_taxonomy_recruitment() {

	$labels = [
		'name'                 => _x('Recruitment', 'taxonomy general name'),
		'singular_name'        => _x('Recruitment', 'taxonomy singular name'),
		'search_items'         => __('Search Recruitment'),
		'all_items'            => __('All Recruitment'),
		'parent_item'          => __('Parent Recruitment'),
		'parent_item_colon'    => __('Parent Recruitment'),
		'edit_item'            => __('Edit Recruitment'),
		'update_item'          => __('Update Recruitment'),
		'add_new_item'         => __('Add New Recruitment'),
		'new_item_name'        => __('New Recruitment Name'),
		'menu_name'            => __('Recruitment'),
	];

	$args = [
		'hierarchical'         => true, // make it hierarchical (like categories)
		'tax_position' 		   => true,
		'labels'               => $labels,
		'show_ui'              => true,
		'show_in_menu'         => true,
		'show_admin_column'    => true,
		'query_var'            => true,
		'rewrite'              => ['slug' => 'recruitment'],
		'show_in_rest'         => true
	];
	register_taxonomy('recruitment', ['directory'], $args);
}

add_action('init', 'wporg_register_taxonomy_recruitment');
