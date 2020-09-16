/**

 * The image upload and edit functionality of the 'Image Gallery' meta box.

 *

 * Uses the default WordPress media uploader.

 *

 * @since      1.0.0

 *

 */

 

(function( $ ) {



	function resetIndex() {

		

		$( '#gallery-metabox-list li' ).each( function( i ) {

			$( this ).find( 'input:hidden' ).attr( 'name', '_igmb_image_gallery_id[' + i + ']' );

		} );

	

	}



	function makeSortable() {

		

		$( '#gallery-metabox-list' ).sortable( {

			opacity: 0.8,

			stop: function() {

				resetIndex();

			}

		} );

		

	}

	

	// Bind to our click event in order to remove an image from the gallery.

	$( document ).on( 'click.igmbRemoveMedia', '#image-gallery-meta-box a.remove-image', function( e ) {

		

		// Prevent the default action from occuring.

		e.preventDefault();

		

		$( this ).parents( 'li' ).animate( { opacity: 0 }, 200, function() {

			$( this ).remove();

			resetIndex();

		} );

		

	} );

	

	//imgareaselect-selection

    $(document).ready(function () {

    	$('#demo').awesomeCropper(

        	{ width: 1024, height: 1400, debug: false }

        );

    });

	$( document ).ready( function() {

		makeSortable();

	} );



} ) ( jQuery );

