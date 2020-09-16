<?php 

function wporg_register_taxonomy_services() {

    $labels = [
        'name'                 => _x('Services', 'taxonomy general name'),
        'singular_name'        => _x('Services', 'taxonomy singular name'),
        'search_items'         => __('Search Services'),
        'all_items'            => __('All Services'),
        'parent_item'          => __('Parent Services'),
        'parent_item_colon'    => __('Parent Services'),
        'edit_item'            => __('Edit Services'),
        'update_item'          => __('Update Services'),
        'add_new_item'         => __('Add New Services'),
        'new_item_name'        => __('New Services Name'),
        'menu_name'            => __('Services'),
    ];
    
    $args = [
        'hierarchical'         => true, // make it hierarchical (like categories)
        'tax_position' 		   => true,
        'labels'               => $labels,
        'show_ui'              => true,
        'show_in_menu'         => true,
        'show_admin_column'    => true,
        'query_var'            => true,
        'rewrite'              => ['slug' => 'services'],
        'show_in_rest'         => true
    ];
    register_taxonomy('services', ['directory'], $args);
}
// modmy
add_action('init', 'wporg_register_taxonomy_services');

?>