<?php 

function wporg_register_taxonomy_location() {

    $labels = [
        'name'                 => _x('Location', 'taxonomy general name'),
        'singular_name'        => _x('Location', 'taxonomy singular name'),
        'search_items'         => __('Search Location'),
        'all_items'            => __('All Location'),
        'parent_item'          => __('Parent Location'),
        'parent_item_colon'    => __('Parent Location'),
        'edit_item'            => __('Edit Location'),
        'update_item'          => __('Update Location'),
        'add_new_item'         => __('Add New Location'),
        'new_item_name'        => __('New Location Name'),
        'menu_name'            => __('Location'),
    ];
    
    $args = [
        'hierarchical'         => true, // make it hierarchical (like categories)
        'labels'               => $labels,
        'show_ui'              => true,
        'show_in_menu'         => true,
        'show_admin_column'    => true,
        'query_var'            => true,
        'rewrite'              => ['slug' => 'location'],
        'show_in_rest'         => true
        
        
    ];
    register_taxonomy('location', ['directory'], $args);
}
/* codmy */
add_action('init', 'wporg_register_taxonomy_location', 0);



?>