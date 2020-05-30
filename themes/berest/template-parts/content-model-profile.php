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
       						<table cellpadding="0" cellspacing="0" width="100%">
                                <?php /*Girls params data*/
                                $fields = acf_get_fields('375');

                                $values = get_fields();

                                if( $fields ) {
                                    $show_next = true;
                                    foreach ($fields as $field) {
                                        $value = $values[$field['name']];
                                        if ($show_next) {
                                            // the field is not a true false field
                                            // but the last true false field was true
                                            // output field
                                            echo '<tr>';
                                            echo '<td>' . $field['label'] . '</td>';
                                            echo '<td class="text-right">' . $value . '</td>';
                                            echo '</tr>';
                                        }
                                    }
                                }
                                ?>
                                <?php /*Girls location*/
                                $fields = acf_get_fields('392');

                                $values = get_fields();

                                if( $fields ) {
                                    $show_next = true;
                                    foreach ($fields as $field) {
                                        $value = $values[$field['name']];
                                        if ($show_next) {
                                            //print_r($value);
                                            echo '<tr>';
                                            echo '<td>' . $field['label'] . '</td>';
                                            $cities = '';
                                            foreach ($value as $lValue) {
                                                $cities = $cities . get_term( $lValue )->name . ' ';
                                            }
                                            echo '<td class="text-right">' . $cities. '</td>';
                                            echo '</tr>';
                                        }
                                    }
                                }
                                ?>
       						</table>
       					</div>
       				</div>

       				<div class="block-div">
       					<h3>RATES</h3>
       					<div class="content-div">
       						<table cellpadding="0" cellspacing="0" width="100%">
       							<thead>
       								<tr>
       									<th></th>
										<th class="text-right"> <strong>IN</strong>  </th>
										<th width="30%" class="text-right"><strong>OUT</strong></th>
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
                                <?php if ( have_rows( 'group_services' ) ) : /* mycode */?>
                                    <?php while ( have_rows( 'group_services' ) ) : the_row();
                                        if( $subfields = get_row() ) { ?>
                                        <ul>
                                            <?php
                                            foreach ($subfields as $key => $value) {
                                                if ( !empty($value) ) { $field = get_sub_field_object( $key ); ?>
                                                    <?php
                                                    echo '<tr>';
                                                    echo '<td class="text-left">' . $field['label'] . '</td>';
                                                    echo '<td class="text-right">' . $value. '</td>';
                                                    echo '</tr>';
                                                    ?>
                                                <?php }
                                            } ?>
                                        </ul>
                                        <?php }
                                    endwhile; ?>
                                <?php endif; ?>
       							 
       						</table>
       					</div>
       				</div>
       			</div>
       		</div>
       	</div>
      </div>
    </section>
  </article>



	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php berest_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->
