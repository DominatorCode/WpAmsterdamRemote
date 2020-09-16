<?php 

function wporg_register_taxonomy_statistics() {

    $labels = [
        'name'                 => _x('Statistics', 'taxonomy general name'),
        'singular_name'        => _x('Statistics', 'taxonomy singular name'),
        'search_items'         => __('Search Statistics'),
        'all_items'            => __('All Statistics'),
        'parent_item'          => __('Parent Statistics'),
        'parent_item_colon'    => __('Parent Statistics'),
        'edit_item'            => __('Edit Statistics'),
        'update_item'          => __('Update Statistics'),
        'add_new_item'         => __('Add New Statistics'),
        'new_item_name'        => __('New Statistics Name'),
        'menu_name'            => __('Statistics'),
    ];
    
    $args = [
        'hierarchical'         => true, // make it hierarchical (like categories)
        'labels'               => $labels,
        'show_ui'              => true,
        'show_in_menu'         => true,
        'show_admin_column'    => true,
        'query_var'            => true,
        'rewrite'              => ['slug' => 'statistics'],
        'show_in_rest'         => true
    ];
    register_taxonomy('statistics', ['directory'], $args);
}

add_action('init', 'wporg_register_taxonomy_statistics');

?>