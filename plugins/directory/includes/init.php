<?php 

function directory_init() {

    $labels = array(
        'name'                  => _x( 'Directory', 'Post type general name', 'directory' ),
        'singular_name'         => _x( 'Directories', 'Post type singular name', 'directory' ),
        'menu_name'             => _x( 'Directory', 'Admin Menu text', 'directory' ),
        'name_admin_bar'        => _x( 'Directories', 'Add New on Toolbar', 'directory' ),
        'add_new'               => __( 'Add New', 'directory' ),
        'add_new_item'          => __( 'Add New', 'directory' ),
        'new_item'              => __( 'New directorys', 'directory' ),
        'edit_item'             => __( 'Edit directorys', 'directory' ),
        'view_item'             => __( 'View directorys', 'directory' ),
        'all_items'             => __( 'All Listing', 'directory' ),
        'search_items'          => __( 'Search directory', 'directory' ),
        'parent_item_colon'     => __( 'Parent directory:', 'directory' ),
        'not_found'             => __( 'No Listing found.', 'directory' ),
        'not_found_in_trash'    => __( 'No Listing found in Trash.', 'directory' ),
        'featured_image'        => _x( 'Model Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'directory' ),
        'set_featured_image'    => _x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'directory' ),
        'remove_featured_image' => _x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'directory' ),
        'use_featured_image'    => _x( 'Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'directory' ),
        'archives'              => _x( 'Directory archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'directory' ),
        'insert_into_item'      => _x( 'Insert into directorys', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'directory' ),
        'uploaded_to_this_item' => _x( 'Uploaded to this directorys', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'directory' ),
        'filter_items_list'     => _x( 'Filter directory list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'directory' ),
        'items_list_navigation' => _x( 'Directory list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'directory' ),
        'items_list'            => _x( 'Directory list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'directory' ),
    );
 
       $args                     = array(
        'labels'                 => $labels,
        'description'            => 'A custom post type for directories',
        'public'                 => true,
        'publicly_queryable'     => true,
        'show_ui'                => true,
        'show_in_menu'           => true,
        'query_var'              => true,
        'rewrite'                => array( 'slug' => 'directory' ),
        'capability_type'        => 'post',
        'has_archive'            => true,
        'hierarchical'           => false,
        'menu_position'          => 20,
        'supports'               => [ 'title', 'editor', 'author', 'thumbnail'],
        'taxonomies'             => [ 'category'],
        'show_in_rest'           => true
    );
 
    register_post_type( 'directory', $args );

}

?>