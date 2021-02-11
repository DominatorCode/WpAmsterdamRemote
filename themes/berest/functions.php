<?php

/**
 * berest functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package berest
 */

use DirectoryCustomFields\AcfRootGroupField;
use DirectoryCustomFields\ConfigurationParameters;

if (!function_exists('berest_setup')) :
    /**
     * Sets up theme defaults and registers support for various WordPress features.
     *
     * Note that this function is hooked into the after_setup_theme hook, which
     * runs before the init hook. The init hook is too late for some features, such
     * as indicating support for post thumbnails.
     */
    function berest_setup()
    {
        /*
         * Make theme available for translation.
         * Translations can be filed in the /languages/ directory.
         * If you're building a theme based on berest, use a find and replace
         * to change 'berest' to the name of your theme in all the template files.
         */
        load_theme_textdomain('berest', get_template_directory() . '/languages');

        // Add default posts and comments RSS feed links to head.
        add_theme_support('automatic-feed-links');

        /*
         * Let WordPress manage the document title.
         * By adding theme support, we declare that this theme does not use a
         * hard-coded <title> tag in the document head, and expect WordPress to
         * provide it for us.
         */
        add_theme_support('title-tag');

        /*
         * Enable support for Post Thumbnails on posts and pages.
         *
         * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
         */
        add_theme_support('post-thumbnails');

        // This theme uses wp_nav_menu() in one location.
        register_nav_menus(array(
            'menu-1' => esc_html__('Primary', 'berest'),
        ));

        /*
         * Switch default core markup for search form, comment form, and comments
         * to output valid HTML5.
         */
        add_theme_support('html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
        ));

        // Set up the WordPress core custom background feature.
        add_theme_support('custom-background', apply_filters('berest_custom_background_args', array(
            'default-color' => 'ffffff',
            'default-image' => '',
        )));

        // Add theme support for selective refresh for widgets.
        add_theme_support('customize-selective-refresh-widgets');

        /**
         * Add support for core custom logo.
         *
         * @link https://codex.wordpress.org/Theme_Logo
         */
        add_theme_support('custom-logo', array(
            'height' => 250,
            'width' => 250,
            'flex-width' => true,
            'flex-height' => true,
        ));
    }
endif;
add_action('after_setup_theme', 'berest_setup');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function berest_content_width()
{
    // This variable is intended to be overruled from themes.
    // Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
    // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
    $GLOBALS['content_width'] = apply_filters('berest_content_width', 640);
}

add_action('after_setup_theme', 'berest_content_width', 0);

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function berest_widgets_init()
{
    register_sidebar(array(
        'name' => esc_html__('Sidebar', 'berest'),
        'id' => 'sidebar-1',
        'description' => esc_html__('Add widgets here.', 'berest'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>',
    ));
    register_sidebar(array(
        'name' => esc_html__('Header', 'berest'),
        'id' => 'header',
        'description' => esc_html__('Add widgets here.', 'berest'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>',
    ));
    register_sidebars(2, array(
        'name' => esc_html__('Footer', 'berest'),
        'id' => 'footer',
        'description' => esc_html__('Add widgets here.', 'berest'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>',
    ));
}

add_action('widgets_init', 'berest_widgets_init');

/**
 * Enqueue scripts and styles.
 */
function berest_scripts()
{
    wp_enqueue_style('berest-style', get_stylesheet_uri());
    wp_enqueue_style('Bootstrap CSS', get_template_directory_uri() . '/css/bootstrap.min.css');
    wp_enqueue_style('Styles', get_template_directory_uri() . '/css/styles.css');
    wp_enqueue_style('AOS', get_template_directory_uri() . '/css/aos.css');
    wp_enqueue_style('Responsive', get_template_directory_uri() . '/css/responsive.css');

    wp_enqueue_script('jQuery', get_template_directory_uri() . '/js/jquery.min.js', array(), true);
    wp_enqueue_script('AOS JS', get_template_directory_uri() . '/js/aos.js', array(), true);
    wp_enqueue_script('Bootstrap JS', get_template_directory_uri() .
        '/js/bootstrap.min.js', array('jquery'), '3.3.7', true);

    wp_enqueue_script('Script JS', get_template_directory_uri() . '/js/scripts.js', array(), true);

    wp_enqueue_script(
        'berest-navigation',
        get_template_directory_uri() . '/js/navigation.js',
        array(),
        '20151215',
        true
    );
    wp_enqueue_script(
        'berest-skip-link-focus-fix',
        get_template_directory_uri() . '/js/skip-link-focus-fix.js',
        array(),
        '20151215',
        true
    );

    // UPDATE add option to disable comments
    $comments_enable = false;

    if ($comments_enable && is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}

add_action('wp_enqueue_scripts', 'berest_scripts');

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if (defined('JETPACK__VERSION')) {
    require get_template_directory() . '/inc/jetpack.php';
}

/***/
add_action('after_setup_theme', 'custom_sizes_');
function custom_sizes_()
{
    add_image_size('blog-image-size', 437, 261, true); // (cropped)
}

// Gallery Filter Start here ...
function my_enqueue()
{
    if (is_page('gallery')) {
        wp_enqueue_script('gallery', get_template_directory_uri() . '/js/gallery.js', array(), '1.0', true);
        wp_localize_script('gallery', 'my_ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
        
        // disable BS 3 and enable 4
       /* wp_dequeue_style('Bootstrap CSS');
        wp_deregister_style('Bootstrap CSS');
        
        wp_dequeue_script('Bootstrap JS');
        wp_deregister_script('Bootstrap JS');
    
        wp_enqueue_style('Bootstrap CSS', get_template_directory_uri() . '/css/bootstrap4/bootstrap.min.css');
        wp_enqueue_script(
            'Bootstrap JS',
            get_template_directory_uri() . '/js/bootstrap4/bootstrap.min.js',
            array('jquery'),
            '',
            true
        );*/
    }

    if (is_page('bookings')) {
        wp_enqueue_script('booking', get_template_directory_uri() . '/js/booking.js', array(), '1.0', true);
    }
}

add_action('wp_enqueue_scripts', 'my_enqueue');

function args_generator($term_id, $taxonomy = 'statistics')
{
    if ($taxonomy === 'services') {
        $parent_terms = get_terms($taxonomy, array('parent' => $term_id, 'orderby' => 'slug', 'hide_empty' => false));

        if (empty($parent_terms)) {
            return array(
                'relation' => 'AND',
                array(
                    'taxonomy' => $taxonomy,
                    'terms' => $term_id, //
                    'field' => 'term_id',
                    'operator' => 'AND',
                    'include_children' => true,
                ),
            );
        }

        $args = array(
            'relation' => 'OR'
        );

        foreach ($parent_terms as $key => $value) {
            $args[] = array(
                'taxonomy' => $taxonomy,
                'terms' => $value->term_id, //
                'field' => 'term_id',
                'operator' => 'AND',
                'include_children' => true,
            );
        }

        return $args;
    } else {
        return array(
            'relation' => 'AND',
            array(
                'taxonomy' => $taxonomy,
                'terms' => $term_id, //
                'field' => 'term_id',
                'operator' => 'AND',
                'include_children' => true,
            ),
        );
    }
}

function args_generator_field($min_value, $max_value, $field, $field2, $field3, $field4, $field5, $field6, $field7, $field8)
{
    return array(
        'relation' => 'OR',
        array(
            'key' => $field,
            'value' => array($min_value, $max_value),
            'compare' => 'BETWEEN',
            'type' => 'NUMERIC',
        ),
        array(
            'key' => $field2,
            'value' => array($min_value, $max_value),
            'compare' => 'BETWEEN',
            'type' => 'NUMERIC',
        ),
        array(
            'key' => $field3,
            'value' => array($min_value, $max_value),
            'compare' => 'BETWEEN',
            'type' => 'NUMERIC',
        ),
        array(
            'key' => $field4,
            'value' => array($min_value, $max_value),
            'compare' => 'BETWEEN',
            'type' => 'NUMERIC',
        ),
        array(
            'key' => $field5,
            'value' => array($min_value, $max_value),
            'compare' => 'BETWEEN',
            'type' => 'NUMERIC',
        ),
        array(
            'key' => $field6,
            'value' => array($min_value, $max_value),
            'compare' => 'BETWEEN',
            'type' => 'NUMERIC',
        ),
        array(
            'key' => $field7,
            'value' => array($min_value, $max_value),
            'compare' => 'BETWEEN',
            'type' => 'NUMERIC',
        ),
        array(
            'key' => $field8,
            'value' => array($min_value, $max_value),
            'compare' => 'BETWEEN',
            'type' => 'NUMERIC',
        ),
    );
}

function GalleryFeed()
{
    // WP_Query arguments

    if (isset($_POST['page'])) {
        // Sanitize the received page
        $page = sanitize_text_field($_POST['page']);
        $age = sanitize_text_field($_POST['age']);
        $hair = sanitize_text_field($_POST['hair']);
        $height = sanitize_text_field($_POST['height']);
        $bust = sanitize_text_field($_POST['bust']);
        $body = sanitize_text_field($_POST['body']);
        $service = sanitize_text_field($_POST['service']);
        $min_price = sanitize_text_field($_POST['min_price']);
        $max_price = sanitize_text_field($_POST['max_price']);

        $cur_page = $page;
        --$page;
        // Set the number of results to display
        $per_page = 10;
        $previous_btn = true;
        $next_btn = true;
        $first_btn = true;
        $last_btn = true;
        $start = $page * $per_page;

        $age_args = args_generator($age);
        $hair_args = args_generator($hair);
        $height_args = args_generator($height);
        $bust_args = args_generator($bust);
        $body_args = args_generator($body);
        $service_args = args_generator($service, 'services');

        $tax_query = array(
            'relation' => 'AND'
        );

        if (!empty($age)) {
            $tax_query[] = $age_args;
        }
        if (!empty($hair)) {
            array_push($tax_query, $hair_args);
        }
        if (!empty($height)) {
            array_push($tax_query, $height_args);
        }
        if (!empty($bust)) {
            array_push($tax_query, $bust_args);
        }
        if (!empty($body)) {
            array_push($tax_query, $body_args);
        }
        if (!empty($service)) {
            $tax_query[] = $service_args;
        }

        $meta_query = array(
            'relation' => 'OR'
        );

        if (!empty($min_price)) {
            $add_hour_in = args_generator_field(
                $min_price,
                $max_price,
                'add_hour_in',
                'add_hour_out',
                'one_hour_in',
                'one_hour_out',
                'dinner_date_in',
                'dinner_date_out',
                'overnight_in',
                'overnight_out'
            );
            $meta_query[] = $add_hour_in;
        }

        $args = array(
            'post_type' => array('directory'),
            'nopaging' => false,
            'paged' => $page,
            'posts_per_page' => $per_page,
            'order' => 'DESC',
            'orderby' => 'modified',
            'tax_query' => $tax_query,
            'meta_query' => $meta_query,
            'offset' => $start

        );

        $args_count = array(
            'post_type' => array('directory'),
            'nopaging' => false,
            'order' => 'DESC',
            'orderby' => 'modified',
            'tax_query' => $tax_query,
            'meta_query' => $meta_query,
        );

        $query = new WP_Query($args);

        $count = $query->found_posts;
        $content = [];
        // The Loop
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();

                $permalink = get_permalink();
                $post_id = get_the_ID();

                $meta = get_post_meta($post_id, '_igmb_image_gallery_id', true);
                $image = wp_get_attachment_image_src($meta[1]);
                $image = $image[0];

                $title = get_the_title();
                $publish = get_the_date('Y-m-d');
                $category = get_the_terms($post_id, 'category');
                $location = get_the_terms($post_id, 'location');

                // Use comparison operator to
                // compare dates
                $current_date = date('Y-m-d');
                $month_ago = date('Y-m-d', strtotime('-30 days', strtotime($current_date)));

                if ($publish > $month_ago) {
                    $is_new = '<div class="new-tag"></div>';
                }

                $category_name = $category[count($category) - 1]->name;
                $location_name = $location[count($location) - 1]->name;

                $inner_content = '
		        <li>
		        	<a href="' . $permalink . '">
		            <div class="post-block-div">
		              <div class="thum-box-div aos-init aos-animate" data-aos="zoom-in">
		                <div style="background-image: url(' . $image . ')" class="img-div"></div>
		                <div class="top-div"> ' . $title . ' </div>
		                <div class="bottom-div"> ' . $location_name . ' </div>
		                ' . $is_new . '
		              </div>
		              <div class="bottom-content">
		                <h3>' . $title . '</h3>
		                ' . $category_name . ' </div>
		            </div>
		            </a>
				</li>';

                array_push($content, $inner_content);
            }
        } else {
            array_push($content, '<h3>No results with this filter<h3>');
        }

        // This is where the magic happens
        $no_of_paginations = ceil($count / $per_page);

        if ($cur_page >= 7) {
            $start_loop = $cur_page - 3;
            if ($no_of_paginations > $cur_page + 3) {
                $end_loop = $cur_page + 3;
            } elseif ($cur_page <= $no_of_paginations && $cur_page > $no_of_paginations - 6) {
                $start_loop = $no_of_paginations - 6;
                $end_loop = $no_of_paginations;
            } else {
                $end_loop = $no_of_paginations;
            }
        } else {
            $start_loop = 1;
            if ($no_of_paginations > 7) {
                $end_loop = 7;
            } else {
                $end_loop = $no_of_paginations;
            }
        }

        // Pagination Buttons logic
        $pag_container = '';

        if ($previous_btn && $cur_page > 1) {
            $pre = $cur_page - 1;
            $pag_container .= "<li p='$pre' class='active'><span aria-hidden=\"true\"><img src=\"https://www.eroticlondonescorts.com/wp-content/themes/berest/images/left-arrow.png\"></span></li>";
        } elseif ($previous_btn) {
            $pag_container .= "<li class='inactive'><span aria-hidden=\"true\"><img src=\"https://www.eroticlondonescorts.com/wp-content/themes/berest/images/left-arrow.png\"></span></li>";
        }
        for ($i = $start_loop; $i <= $end_loop; $i++) {
            if ($cur_page == $i) {
                $pag_container .= "<li p='$i' class = 'selected' ><a>{$i}</a></li>";
            } else {
                $pag_container .= "<li p='$i' class='active'><a>{$i}</a></li>";
            }
        }

        if ($next_btn && $cur_page < $no_of_paginations) {
            $nex = $cur_page + 1;
            $pag_container .= "<li p='$nex' class='active'><span aria-hidden=\"true\"><img src=\"https://www.eroticlondonescorts.com/wp-content/themes/berest/images/right-arrow.png\"></span></li>";
        } elseif ($next_btn) {
            $pag_container .= "<li class='inactive'><span aria-hidden=\"true\"><img src=\"https://www.eroticlondonescorts.com/wp-content/themes/berest/images/right-arrow.png\"></span></li>";
        }

        $output = '';
        $navigation = '';

        $age_list = getList(9, 'statistics', $args_count);
        $hair_list = getList(10, 'statistics', $args_count);
        $height_list = getList(57, 'statistics', $args_count);
        $bust_list = getList(8, 'statistics', $args_count);
        $body_list = getList(68, 'statistics', $args_count);
        $services_list = getList(0, 'services', $args_count);
        
        // parent div constant
        $div_form_group_start = '';
        $div_form_group_end ='</select>';

        $navigation .= $div_form_group_start. '<select class="form-control age-filter ' . isEmpty($age_list) . '">
				        	<option value="0" >By Age</option>';
        foreach ($age_list as $key => $value) {
            $navigation .= ' <option value="' . $key . '" ' . isSelected($key, $age) . '>' . $value . '</option>';
        }
        $navigation .= $div_form_group_end;

        $navigation .= $div_form_group_start.'<select class="form-control hair-filter ' . isEmpty($hair_list) . '">
				        	<option value="0" >Hair</option>';
        foreach ($hair_list as $key => $value) {
            $navigation .= ' <option value="' . $key . '" ' . isSelected($key, $hair) . '>' . $value . '</option>';
        }
        $navigation .= $div_form_group_end;

        // $navigation .= '<select class="form-control height-filter '.isEmpty($height_list).'">
        //          <option value="0" >Height</option>';
        //              foreach ($height_list as $key => $value) $navigation .= ' <option value="'.$key.'" '. isSelected($key, $height) .'>'.$value.'</option>';
        // $navigation .= '</select>';
        
        

        $navigation .= $div_form_group_start.'<select class="form-control bust-filter ' . isEmpty($bust_list) . '">
				        	<option value="0" >Bust</option>';
        foreach ($bust_list as $key => $value) {
            $navigation .= ' <option value="' . $key . '" ' . isSelected($key, $bust) . '>' . $value . '</option>';
        }
        $navigation .= $div_form_group_end;

        $navigation .= $div_form_group_start.'<select class="form-control body-filter ' . isEmpty($body_list) . '">
				        	<option value="0" >Body</option>';
        foreach ($body_list as $key => $value) {
            $navigation .= ' <option value="' . $key . '" ' . isSelected($key, $body) . '>' . $value . '</option>';
        }
        $navigation .= $div_form_group_end;

        $navigation .= $div_form_group_start.'<select class="form-control service-filter ' . isEmpty($services_list) . '">
				        	<option value="0" >Service</option>';
        foreach ($services_list as $key => $value) {
            $navigation .= ' <option value="' . $key . '" ' . isSelected($key, $service) . '>' . $value . '</option>';
        }
        $navigation .= $div_form_group_end;

        $priceRange = array();

        array_push($priceRange, array('min' => 50, 'max' => 100));
        array_push($priceRange, array('min' => 150, 'max' => 300));
        array_push($priceRange, array('min' => 350, 'max' => 500));
        array_push($priceRange, array('min' => 500, 'max' => 1000));

        $navigation .= $div_form_group_start.'<select class="form-control price-filter ' . isEmptyPrice($priceRange, $args_count) . '">
				        	<option value="0" >Price</option>';
        foreach ($priceRange as $key => $value) {
            $count = getPriceCount($args_count, $value['min'], $value['max']);
            if ($count > 0) {
                $navigation .=
                    '<option 
								min-value="' . $value['min'] . '" 
								max-value="' . $value['max'] . '" ' . isSelectedPrice($value['min'], $value['max'], $min_price, $max_price) . '>'
                    . $value['min'] . '-' . $value['min'] . ' (' . $count . ')
							</option>';
            }
        }
        $navigation .= $div_form_group_end;

        foreach ($content as $key => $value) {
            $output .= $value;
        }
    
        try {
            echo json_encode(array(
                'navigation' => $navigation,
                'content' => $output,
                'pages' => $pag_container
            ), JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
        }
    
        wp_reset_postdata();
        die;
    }
}

function isEmpty($arr)
{
    return count($arr) > 0 ? '' : 'must_disable';
}

function isEmptyPrice($arr, $args_count)
{
    foreach ($arr as $key => $value) {
        $count = getPriceCount($args_count, $value['min'], $value['max']);
        if ($count > 0) {
            return '';
        }
    }

    return 'must_disable';
}

function isSelected($id, $selected_id)
{
    return $id == $selected_id ? 'selected' : '';
}

function isSelectedPrice($min, $max, $selected_min, $selected_max)
{
    return ($min == $selected_min && $max == $selected_max) ? 'selected' : '';
}

function getPriceCount($args, $min_price, $max_price)
{
    $meta_query = array(
        'relation' => 'OR'
    );

    $add_hour_in = args_generator_field($min_price, $max_price, 'add_hour_in', 'add_hour_out', 'one_hour_in', 'one_hour_out', 'dinner_date_in', 'dinner_date_out', 'overnight_in', 'overnight_out');

    array_push($meta_query, $add_hour_in);

    $args['meta_query'] = $meta_query;

    $query = new WP_Query($args);
    $count = $query->found_posts;

    return $count;
}

function getList($term_id, $taxonomy, $args_main)
{
    $list = get_terms(array(
        'taxonomy' => $taxonomy,
        'hide_empty' => true,
        'parent' => $term_id
    ));

    $keypair = [];

    $term_list = array_map(function ($o) {
        return $o->term_id;
    }, $list);

    foreach ($list as $key => $value) {
        $args = $args_main;

        $tax_query = $args['tax_query'];

        foreach ($args['tax_query'] as $key_i => $value_i) {
            # code...

            if (!is_array($value_i)) {
                continue;
            }

            foreach ($value_i as $key_q => $value_q) {
                # code...
                if (!is_array($value_q)) {
                    continue;
                }

                if (in_array($value_q['terms'], $term_list, true) || (($taxonomy === 'services') && ($value_q['taxonomy'] === 'services'))) { //
                    unset($tax_query[$key_i]);
                }
            }
        }

        $tax_query[] = args_generator($value->term_id, $taxonomy);

        $args['tax_query'] = $tax_query;

        $query = new WP_Query($args);
        $count = $query->found_posts;

        if ($count != 0) {
            $keypair[$value->term_id] = $value->name . ' (' . $count . ')';
        }
    }

    return $keypair;
}

// creating Ajax call for WordPress
add_action('wp_ajax_nopriv_GalleryFeed', 'GalleryFeed');
add_action('wp_ajax_GalleryFeed', 'GalleryFeed');

add_action('init', 'myStartSession', 1);
add_action('wp_logout', 'myEndSession');
add_action('wp_login', 'myEndSession');

function myStartSession()
{
    if (!session_id()) {
        session_start();
    }
}

function myEndSession()
{
    session_destroy();
}

// creating Ajax call for WordPress
add_action('wp_ajax_nopriv_BookingSession', 'BookingSession');
add_action('wp_ajax_BookingSession', 'BookingSession');

function BookingSession()
{
    if (isset($_POST['page_id'])) {
        $page_id = sanitize_text_field($_POST['page_id']);

        $_SESSION['booking_page_id'] = $page_id;
    }
}

add_action('phpmailer_init', 'send_smtp_email');
function send_smtp_email($phpmailer)
{
    $phpmailer->isSMTP();
    $phpmailer->Host = SMTP_HOST;
    $phpmailer->SMTPAuth = SMTP_AUTH;
    $phpmailer->Port = SMTP_PORT;
    $phpmailer->Username = SMTP_USER;
    $phpmailer->Password = SMTP_PASS;
    $phpmailer->SMTPSecure = SMTP_SECURE;
    $phpmailer->From = SMTP_FROM;
    $phpmailer->FromName = SMTP_NAME;
}

// creating Ajax call for WordPress
add_action('wp_ajax_nopriv_BookNow', 'BookNow');
add_action('wp_ajax_BookNow', 'BookNow');

function BookNow()
{
    if (isset($_POST['client_name'])) {
        $client_name = sanitize_text_field($_POST['client_name']);
        $client_email = sanitize_text_field($_POST['client_email']);
        $client_phone = sanitize_text_field($_POST['client_phone']);
        $client_location = sanitize_text_field($_POST['client_location']);
        $client_message = sanitize_text_field($_POST['client_message']);

        $booking_name = sanitize_text_field($_POST['booking_name']);
        $booking_date = sanitize_text_field($_POST['booking_date']);
        $booking_duration = sanitize_text_field($_POST['booking_duration']);
        $booking_type = sanitize_text_field($_POST['booking_type']);
        $booking_image = sanitize_text_field($_POST['booking_image']);
        $booking_day = sanitize_text_field($_POST['booking_day']);

        $to = $client_email;
        $subject = 'Directory Booking';
        $body = '

		<h2>Client Info: </h2>
		Name:  ' . $client_name . ' <br>
		Email:  ' . $client_email . ' <br>
		Phone: ' . $client_phone . ' <br>
		Location: ' . $client_location . ' <br>
		Message:  ' . $client_message . '


		<br>
		<br>

		<h2>Model Info: </h2>

		Name: ' . $booking_name . ' <br>
		Date: ' . $booking_date . '-' . $booking_day . '-' . date("Y") . ' <br>
		Duration: ' . $booking_duration . ' <br>
		Type: ' . $booking_type . ' <br>
		<br>
		<img src="' . $booking_image . '" />
		';

        $headers = array('Content-Type: text/html; charset=UTF-8');
        //      $headers[] = 'Cc: skyjay03k@gmail.com';
        $headers[] = 'From: Website <me@example.net>';

        wp_mail($to, $subject, $body, $headers);
        wp_mail('dcoderk@yahoo.com', $subject, $body, $headers);

        unset($_SESSION["booking_page_id"]);
    }
}

// ----------------------------------------------------------------------------------

//<editor-fold desc="Screen Options settings">
// move author box to sidebar
add_action('post_submitbox_misc_actions', 'move_author_to_publish_metabox');
function move_author_to_publish_metabox()
{
    global $post_ID;
    $post = get_post($post_ID);
    echo <<<'TAG'
<div id="author" class="misc-pub-section" style="border-top-style:solid;
border-top-width:1px; border-top-color:#eeeeee; border-bottom-width:0;">Author:
TAG;
    post_author_meta_box($post);
    echo '</div>';
}

// disable unnecessary elements
add_filter('hidden_meta_boxes', 'custom_hidden_meta_boxes');
/**
 * @param $hidden
 * @return mixed
 */
function custom_hidden_meta_boxes($hidden)
{
    $arrOptions = array('authordiv', 'locationdiv', 'statisticsdiv', 'servicesdiv', 'rates-1');
    foreach ($arrOptions as $rOption) {
        $hidden[] = $rOption;
    }

    // make rates metabox always enable
    /*$keyRates = array_search('rates-1', $hidden, true);
    unset( $hidden[ $keyRates ] );*/

    return $hidden;
}

//</editor-fold>

//<editor-fold desc="ACF code">
/* Disable admin ACF menu */
add_filter('acf/settings/show_admin', '__return_false');

// activate ACF
require get_template_directory() . '/inc/acf-local-fields/AcfRootGroupField.php';

// Create ACF data class
$acf_group_local = new AcfRootGroupField($_GET['post'], 'Model Parameters');
$acf_group_local->addNameTermExclude('Rates');

// Set custom meta for 'Rates' fields
$arr_rates_meta = array();
$arr_rates_meta[] = array('NameTerm' => 'Additional Hour Admin', 'NameMeta' => 'add_hour');
$arr_rates_meta[] = array('NameTerm' => '1 Hour', 'NameMeta' => 'one_hour');
$arr_rates_meta[] = array('NameTerm' => 'Dinner Date', 'NameMeta' => 'dinner_date');
$arr_rates_meta[] = array('NameTerm' => 'Overnight', 'NameMeta' => 'overnight');

$acf_group_local->setArrMetaTermsCustom($arr_rates_meta);

// init ACF
$acf_group_local->CreateAcfRootLocalGroup();
$acf_group_local->ApplyFilterTaxonomyFields();

// add custom JS to interact with and modify ACF fields and settings
add_action('acf/input/admin_enqueue_scripts', 'my_admin_enqueue_scripts');
function my_admin_enqueue_scripts()
{
    wp_enqueue_script('my-admin-js', get_template_directory_uri() . '/js/acf-me.js', array(), '1.0.0', true);
}

//</editor-fold>

//add_filter('acf/update_value/name=image', 'acf_set_featured_image_tt', 10, 3);
function acf_set_featured_image_tt($value, $post_id, $field)
{
    if (!empty($value)) {
        //Add the value which is the image ID to the _thumbnail_id meta data for the current post
        add_post_meta($post_id, '_thumbnail_id', $value);
    }

    return $value;
}



//<editor-fold desc="Customizer">
add_action('customize_register', 'my_theme_customize_register');

function my_theme_customize_register($wp_customize)
{

    //<editor-fold desc="Pagination">
    $wp_customize->add_section(
        'pagination-number',
        array(
            'title' => __('Pagination number', '_s'),
            'priority' => 30,
            'description' => __('Enter number of posts per page for pagination', '_s')
        )
    );

    $wp_customize->add_setting('pagination', array( 'default' => '5' ));
    $wp_customize->add_control(new WP_Customize_Control(
        $wp_customize,
        'pagination',
        array( 'label' => __('Pagination', '_s'),
            'type'     => 'number',
            'section' => 'pagination-number', 'settings' => 'pagination', )
    ));
    //</editor-fold>

    //<editor-fold desc="Email admin">
    $wp_customize->add_section(
        'email-admin',
        array(
            'title' => __('Submitting email', '_s'),
            'priority' => 20,
            'description' => __('Enter email to receive emails after form submitting', '_s')
        )
    );

    $wp_customize->add_setting('email-admin', array( 'default' => 'admin@email.com' ));
    $wp_customize->add_control(new WP_Customize_Control(
        $wp_customize,
        'email-admin',
        array( 'label' => __('Submitting email', '_s'),
            'type'     => 'email',
            'section' => 'email-admin', 'settings' => 'email-admin', )
    ));
    //</editor-fold>
}
//</editor-fold>



/*$headers = array('Content-Type: text/html; charset=UTF-8');
$html = 'Test message message test test test';
wp_mail("ceroff@mail.ru", "Test from Escort", $html, $headers);*/
