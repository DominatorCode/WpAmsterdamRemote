<?php
/*
Template Name: Blog Page Template
*/

use DirectoryCustomFields\ConfigurationParameters;

/**
 * Returns data of last published post
 * @return int|mixed|WP_Post
 */
function get_last_post_data()
{

    $args = array(
        'numberposts' => '1',
    );
    return wp_get_recent_posts($args)[0];
}

get_header();
?>

<div class="container main_content">
    <div class="row">

        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <div class="entry-content">

                <article class="blog-page-article">
                    <section class="our-blog-section paddingB50 aos-init aos-animate" data-aos="fade-in">
                        <div class="container">
                            <header class="entry-header">
                                <h1 class="title text-center">ESCORT BLOG</h1>
                            </header>
                            <div class="blog-posts-div aos-init aos-animate" data-aos="fade-up">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="img-div"
                                             style="background-image: url(https://www.eroticlondonescorts.com/wp-content/uploads/2019/06/b-post-img.png)">
                                            <a href="#"><img loading="lazy"
                                                             src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'medium_size'); ?>"
                                                             width="427" height="255" alt=""> </a>
                                        </div>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="content-div">
                                            <div class="inner-div">
                                                <div class="right-icon"><img loading="lazy"
                                                                             src="https://www.eroticlondonescorts.com/wp-content/themes/berest/images/edit-icon.png"
                                                                             width="33" height="30" alt=""></div>
                                                <?php the_content(); ?>
                                                <div class="tages-div">
                                                    <ul>
                                                        <li><a href="#">Sexy Escort</a></li>
                                                        <li><a href="#">Escort Babe</a></li>
                                                        <li><a href="#">New Escort</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php get_template_part('template-parts/content', 'pagination'); ?>

                        </div>
                    </section>
                </article>
            </div><!-- .entry-content -->

        </article><!-- #post-16 -->
    </div>
</div>

