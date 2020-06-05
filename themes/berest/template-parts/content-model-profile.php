<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package berest
 */



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
                                <?php /**
                                 * @param string $name_cf
                                 * @param string $value_cf
                                 * @return void
                                 */
                                function PrintCfTable(string $name_cf, string $value_cf): void
                                {
                                    // printing in two column way name | value
                                    echo '<tr>';
                                    echo '<td class="text-left">' . $name_cf . ":" . '</td>';
                                    echo '<td class="text-right">' . $value_cf . '</td>';
                                    echo '</tr>';

                                }

                                /**
                                 * @param array $subfields
                                 * @param string $name_taxonomy
                                 * @return array
                                 */
                                function AcfExtractDataAndPrint(array $subfields, string $name_taxonomy): array
                                {
                                    $result = [];
                                    foreach ($subfields as $key => $value) {
                                        if (!empty($value)) {
                                            $field = get_sub_field_object($key);


                                            // select all child tax ids for current parent tax from common taxes array
                                            // Setup blank array
                                            $arr_ids = array();
                                            foreach ($field['value'] as $id_child) {
                                                // get parent tax name of child term
                                                $child_term = get_term($id_child, $name_taxonomy);
                                                $term_parent = get_term($child_term->parent, $name_taxonomy)->name;

                                                // compare parent name with loop parent
                                                if (strcmp($term_parent, $field['label']) == 0)
                                                    array_push($arr_ids, $id_child);
                                            }

                                            $get_terms_args = array(
                                                'taxonomy' => $name_taxonomy,
                                                'hide_empty' => 0,
                                                'include' => $arr_ids,
                                            );
                                            // get selected terms
                                            $terms = get_terms($get_terms_args);

                                            $value_term = '';
                                            if ($terms) :

                                                foreach ($terms as $term) :
                                                    $value_term .= $term->name . ', ';
                                                endforeach;
                                                $value_term = rtrim($value_term, ', ');



                                            endif;
                                            $result[] = ['name_field' => $field['label'], 'name_value' => $value_term];
                                        }
                                    }
                                    return array($result);
                                }

                                if ( have_rows( 'group_statistics' ) ) : /* mycode */
                                    while ( have_rows( 'group_statistics' ) ) : the_row();
                                        if( $subfields = get_row() ) {
                                            $name_taxonomy = 'statistics'; // name of using taxonomy
                                            $arr_data_acf = AcfExtractDataAndPrint($subfields, $name_taxonomy);

                                            // print results
                                            foreach ($arr_data_acf[0] as $row_data_acf) {
                                                PrintCfTable($row_data_acf['name_field'], $row_data_acf['name_value']);

                                            }

                                        }
                                    endwhile;
                                endif; ?>
                                <?php if ( have_rows( 'group_location' ) ) : ?>
                                    <?php while ( have_rows( 'group_location' ) ) : the_row();
                                        if( $subfields = get_row() ) {
                                            $name_taxonomy = 'location'; // name of using taxonomy
                                            $arr_data_acf = AcfExtractDataAndPrint($subfields, $name_taxonomy);
                                            // print results
                                            $value_locations = '';

                                            $namePrintTax = "Location";
                                            foreach ($arr_data_acf[0] as $row_data_acf)
                                                $value_locations .= $row_data_acf['name_field'] . ', ' . $row_data_acf['name_value'] . ', ';
                                            }
                                        $value_locations = rtrim($value_locations, ', ');
                                        echo '<tr>';
                                        echo '<td class="text-left">' . $namePrintTax . ":" . '</td>';
                                        echo '<td class="text-right">' . $value_locations . '</td>';
                                        echo '</tr>';

                                    endwhile; ?>
                                <?php endif; ?>
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
                                <?php if ( have_rows( 'group_services' ) ) : /* mycode */
                                    while ( have_rows( 'group_services' ) ) : the_row();
                                        if( $subfields = get_row() ) {
                                            foreach ($subfields as $key => $value) {
                                                if ( !empty($value) ) {
                                                    $field = get_sub_field_object( $key );
                                                    echo '<tr>';
                                                    echo '<td class="text-left">' . $field['label'] . ":" . '</td>';
                                                    echo '<td class="text-right">' . $value. '</td>';
                                                    echo '</tr>';
                                                 }
                                            }
                                         }
                                        endwhile;
                                    endif; ?>
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
