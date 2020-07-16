<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package berest
 */
require_once get_template_directory() . '/inc/acf-local-fields/AcfFrontPageDisplay.php';
require_once get_template_directory() . '/inc/acf-local-fields/RawFrontPageDisplay.php';

use DirectoryCustomFields\RawFrontPageDisplay as RawCF;
?>

<article>
    <section class="bio-section paddingTB50" data-aos="fade-in">
      <div class="container">
       <div class="bookme"><a href="#" onclick="booking.redirect(event, '<?php echo get_the_ID(); ?>')">BOOK ME</a></div>
        <h2 class="section-title2"><span><?php the_title(); ?>'s PROFILE</span></h2>
       	<div class="row">
       		<div class="col-sm-5">
     		 	<div class="bottom-sm-img">
                    <?php
                        echo do_shortcode('[gallery_metabox_shortcode]');
                     ?>
     		 	</div>
      		</div>
			<!--col-sm-5-->
       		<div class="col-sm-7">
       			<div class="right-side-div">
       				<div class="block-div">
       					<h3>STATISTICS</h3>
       					<div class="content-div">
       						<table>
                                <?php

                                //<editor-fold desc="statistics data">
                                $name_taxonomy = 'statistics'; // name of using taxonomy
                                $idPost = $post->ID;

                                // get used terms for current post
                                RawCF::GetCfDataAndPrint($idPost, $name_taxonomy);

                                //</editor-fold>

                                ?>

                                <?php
                                //<editor-fold desc="Location data display">
                                $namePrintTax = "Location (Disabled)";
                                $name_taxonomy = 'location'; // name of using taxonomy

                                //<editor-fold desc="get location taxonomy name">
                                $args = array(
                                    'public'   => true,
                                    'name' => $name_taxonomy
                                );
                                $taxonomies = get_taxonomies( $args , 'objects');
                                // find "location" taxonomy displayed name
                                if ( $taxonomies ) {
                                    $namePrintTax = $taxonomies[$name_taxonomy]->labels->name;
                                }
                                //</editor-fold>

                                // get used cities
                                $term_objects = wp_get_post_terms($post->ID, 'location', array('fields' => 'all', 'orderby' => 'parent'));
//var_dump($term_objects);
                                // Get Country name for chosen cities
                                $nameCountryPrevious = '';
                                $value_locations = '';

                                // make location list with country and cities
                                foreach ($term_objects as $termCity) {
                                    // if new country then append its name
                                    $idParent = $termCity->parent;

                                    // if term is city not a country
                                    if ($idParent > 0) {
                                        $nameCountry = get_term_by('id', $idParent, $name_taxonomy)->name;

                                        // check if new country name
                                        if ($nameCountry !== $nameCountryPrevious) {
                                            $nameCountryPrevious = '';
                                        }

                                        if (empty($nameCountryPrevious)) {
                                            $value_locations .= ', ' . $nameCountry . ': ' . $termCity->name;
                                            $nameCountryPrevious = $nameCountry;
                                        }
                                        else {
                                            $value_locations .= ', ' . $termCity->name;
                                        }
                                    }
                                }

                                $value_locations = ltrim($value_locations, ', ');

                                // print results
                                echo '<tr>';
                                    echo '<td class="text-left">' . $namePrintTax . ":" . '</td>';
                                    echo '<td class="text-right">' . $value_locations . '</td>';
                                    echo '</tr>';
                                //</editor-fold>
                                ?>

       						</table>
       					</div>
       				</div>

       				<div class="block-div">
       					<h3>RATES</h3>
       					<div class="content-div">
       						<table>
       							<thead>
       								<tr>
       									<th></th>
										<th class="text-right"> <strong>IN</strong>  </th>
										<th class="text-right"><strong>OUT</strong></th>
       								</tr>
       							</thead>
       							<tbody>
                                                      <tr>
                                                      <?php 
                                                            $add_hour_in = get_post_meta( get_the_ID(), 'add_hour_in', true );
                                                            $add_hour_out = get_post_meta( get_the_ID(), 'add_hour_out', true );
                                                            // check if the custom field has a value
                                                            if( ! empty( $add_hour_in . $add_hour_out ) ) {
                                                            echo "<th >Additional Hour: </th>";
                                                            echo "<td class='text-right'>$add_hour_in</td>";
                                                            echo "<td class='text-right'>$add_hour_out</td>";
                                                            } 
                                                      ?>
                                                      </tr>
                                                      <tr>
                                                      <?php 
                                                            $one_hour_in = get_post_meta( get_the_ID(), 'one_hour_in', true );
                                                            $one_hour_out = get_post_meta( get_the_ID(), 'one_hour_out', true );
                                                            // check if the custom field has a value
                                                            if( ! empty( $one_hour_in . $one_hour_out ) ) {
                                                            echo "<th >1 Hour: </th>";
                                                            echo "<td class='text-right'>$one_hour_in</td>";
                                                            echo "<td class='text-right'>$one_hour_out</td>";
                                                            } 
                                                      ?>
                                                      </tr>
                                                      <tr>
                                                      <?php 
                                                            $dinner_date_in = get_post_meta( get_the_ID(), 'dinner_date_in', true );
                                                            $dinner_date_out = get_post_meta( get_the_ID(), 'dinner_date_out', true );
                                                            // check if the custom field has a value
                                                            if( ! empty( $dinner_date_in . $dinner_date_out ) ) {
                                                            echo "<th >Dinner date:  </th>";
                                                            echo "<td class='text-right'>$dinner_date_in</td>";
                                                            echo "<td class='text-right'>$dinner_date_out</td>";
                                                            } 
                                                      ?>
                                                      </tr>
                                                      <tr>
                                                      <?php 
                                                            $overnight_in = get_post_meta( get_the_ID(), 'overnight_in', true );
                                                            $overnight_out = get_post_meta( get_the_ID(), 'overnight_out', true );
                                                            // check if the custom field has a value
                                                            if( ! empty( $overnight_in . $overnight_out ) ) {
                                                            echo "<th >Overnight:  </th>";
                                                            echo "<td class='text-right'>$overnight_in</td>";
                                                            echo "<td class='text-right'>$overnight_out</td>";
                                                            } 
                                                      ?>
                                                      </tr>
                                                     

       							</tbody>
       							 
       						</table>
       					</div>
       				</div>
       				
       				<div class="block-div">
       					<h3>ABOUT</h3>
       					<div class="content-div">
       						<p> <?php the_content(); ?> </p>
       					</div>
       				</div>
       				
       				<div class="block-div">
       					<h3>SERVICES</h3>
       					<div class="content-div">
       						<table>
                                <?php

                                //<editor-fold desc="services data">
                                $name_taxonomy = 'services'; // name of using taxonomy
                                $idPost = $post->ID;

                                RawCF::GetCfDataAndPrint($idPost, $name_taxonomy);

                                //</editor-fold>

                                    ?>
       						</table>
       					</div>
       				</div>
       			</div>
       		</div>
       	</div>
      </div>
    </section>
  </article>





	<footer class="entry-footer">
		<?php berest_entry_footer(); ?>
	</footer><!-- .entry-footer -->
<!-- #post-<?php the_ID(); ?> -->
