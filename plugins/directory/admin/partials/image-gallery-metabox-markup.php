<?php



/**

 * Provide an admin area view for the meta boxes.

 *

 * This file is used to markup the 'Image Gallery' meta box.

 * @since      1.0

 * @package    Image_Gallery_Metabox

 * @subpackage Image_Gallery_Metabox/admin/partials

 */



?>



<table class="form-table">

	<tbody>

		<tr valign="top">

			<td>

				<ul id="gallery-metabox-list">

				<?php

				if ( $gallery_stored_meta ) : foreach ( $gallery_stored_meta as $key => $value ) : 

					//echo $value;

					$image = wp_get_attachment_image_src( $value,'crop_size');

					$original_image = wp_get_attachment_image_src( $value,'large_size');

					// echo '<pre>'; print_r($image); echo '</pre>';

					// echo '<pre>'; print_r($original_image); echo '</pre>';

					?>

					<li>

						<input type="hidden" name="_igmb_image_gallery_id[<?php echo $key; ?>]" value="<?php echo $value; ?>">

						<img class="image-previe" src="<?php echo $original_image[0]; ?>" style="display:none;">

						<img class="image-preview" src="<?php echo $image[0]; ?>">

						<a class="edit-image" href="#" title="<?php _e('Edit/Change Image', 'image-gallery-metabox'); ?>"></a>

						<a class="remove-image" href="#" title="<?php _e('Remove Image', 'image-gallery-metabox'); ?>"></a>

					</li>

				<?php endforeach; endif; ?>

				</ul>

				<div>

					<input id="demo" type="hidden">

					<!-- <a class="gallery-add button button-primary" href="#"><?php //_e('Add Images', 'image-gallery-metabox'); ?></a> -->

				</div>

			</td>

		</tr>

	</tbody>

</table>

<div id="loading-image" style="display:none;">
	<img src="<?php echo plugin_dir_url( __FILE__ ); ?>/loadings.gif" class="loading-image">
</div>	



<style>
	#loading-image {
		position: absolute;
		left: 0;
		right: 0;
		text-align: center;
		top: 50%;
		transform: translateY(-50%);
	}
</style>