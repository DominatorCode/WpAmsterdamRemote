<?php

/**
 * Template Name: Gallery
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package berest
 */

get_header();

// function getList( $term_id, $taxonomy = 'statistics' ) {
//  $list = get_terms( array(
//      'taxonomy' => $taxonomy,
//      'hide_empty' => false,
//      'parent' => $term_id
//  ) );
//  $keypair = [];
//  foreach ($list as $key => $value) {
//      $keypair[$value->term_id] = $value->name;
//  }
//  return $keypair;
// }

// $services = get_terms( array(
//     'taxonomy' => 'services',
//     'hide_empty' => false,
//     'parent' => 0
// ) );

// $age_list = getList(17);
// $height_list = getList(20);
// $hair_list = getList(43);
// $body_list = getList(47);
// $services_list = getList(0,'services');

?>
    
    <div class="test-output"></div>
    
    <div class="container main_content">
        <div class="row">
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <!--Gallery Page Sections-->
                <article>
                    <section>
                        <div class="logo-div" data-aos="fade-in">
                            <div class="container">
                                <div class="row justify-content-md-center">
                                    <div class="col-lg-10">
                                        <div class="input-group gallery-right-dropdown">
                                        
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <section class="hot-modal-section paddingTB50" data-aos="fade-in">
                        <div class="container">
                            <h2 class="section-title text-center"><span>HOT MODELS</span></h2>
                            <ul class="col5-row content-holder">
                            </ul>
                        </div>
                    </section>
                    
                    <!-- pagination -->
                    <section>
                        <nav aria-label="Page navigation" class="pagination-div aos-init aos-animate"
                             data-aos="fade-in">
                            <ul class="pagination">
                            </ul>
                        </nav>
                    </section>
                
                </article>
                <!--end here-->
            </article><!-- #post-<?php the_ID(); ?> -->
        </div>
    </div>

<?php
//get_sidebar();
get_footer();
