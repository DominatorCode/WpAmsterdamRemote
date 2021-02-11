<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package berest
 */

?>
	<footer  style="background-image: url('https://www.eroticlondonescorts.com/wp-content/themes/berest/images/welcom-bg.jpg')" data-aos="fade-in">
		<div class="container">
			<div class="row">
				<div class="col-sm-4">
					<div class="footer-logo text-center">
                        <?php 
                            if(is_active_sidebar( 'footer' )) {
                                dynamic_sidebar( 'footer' );
                            }
                        ?>
					</div>
				</div>
				<div class="col-sm-8">
					<div class="right-block">
                        <?php 
                            if(is_active_sidebar( 'footer-2' )) {
                                dynamic_sidebar( 'footer-2' );
                            }
                        ?>
					</div>
				</div>
			</div>
		</div>
   </footer>
</div>

<?php wp_footer(); ?>

</body>
</html>
