<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @since      1.0
 *
 * @package    Image_Gallery_Metabox
 * @subpackage Image_Gallery_Metabox/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Image_Gallery_Metabox
 * @subpackage Image_Gallery_Metabox/admin
 */
class Image_Gallery_Metabox_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0
	 * @param    string    $plugin_name		The name of this plugin.
	 * @param    string    $version			The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0
	 */
	public function enqueue_styles($hook) {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/image-gallery-metabox-admin.min.css', array(), date("h:i:s"), 'all' );
		if($hook == 'post.php' || $hook == 'post-new.php'){
			wp_enqueue_style('bootstrap-css', plugin_dir_url( __FILE__ ) . 'css/bootstrap.min.css', array(), $this->version, 'all' );
		}
		wp_enqueue_style( 'awesome-cropper', plugin_dir_url( __FILE__ ) . 'css/jquery.awesome-cropper.css', array(), date("h:i:s"), 'all' );
	}
	
	/**
	 * Register the stylesheets for the front area.
	 *
	 * @since    1.0
	 */
	public function front_enqueue_styles() { 
		wp_enqueue_style( 'front-style', plugin_dir_url( __FILE__ ) . 'css/front-style.css', array(), $this->version, 'all' );
		wp_enqueue_script( 'jquery-js', 'https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js', array(), date("h:i:s"), false );
		wp_enqueue_script(  'frontend-js', plugin_dir_url( __FILE__ ) . 'js/frontend.js', array(), date("h:i:s"), true );
	}

	/**
	 * Register, localize and enqueue the JavaScript for the image gallery meta box.
	 *
	 * @since    1.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_media();
		wp_enqueue_script( 'media-grid' );
		wp_enqueue_script( 'media' );	
		wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/image-gallery-metabox.js', array(), date("h:i:s"), true );    
		wp_enqueue_script( 'bootstrap-js', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js', array(), date("h:i:s"), false );
		wp_enqueue_script(  'imgareaselect-area-js', plugin_dir_url( __FILE__ ) . 'js/jquery.imgareaselect.js', array(), date("h:i:s"), true );
		wp_enqueue_script(  'awesome-cropper-js', plugin_dir_url( __FILE__ ) . 'js/jquery.awesome-cropper.js', array(), date("h:i:s"), true );
		wp_localize_script( 'awesome-cropper-js', 'my_ajax_object',array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
		wp_enqueue_script(  'jquery-base-js', plugin_dir_url( __FILE__ ) . 'js/jquery.base64.min.js', array(), date("h:i:s"), true );
		wp_localize_script( $this->plugin_name, 'image_gallery_metabox',
            array(
                'add_title' => __( 'Add Images to Gallery', 'image-gallery-metabox' ),
                'add_button' => __( 'Add to Gallery', 'image-gallery-metabox' ),
                'edit_title' => __( 'Edit or Change Image', 'image-gallery-metabox' ),
                'edit_button' => __( 'Update Image', 'image-gallery-metabox' ),
                'link_edit_title' => __( 'Edit/Change Image', 'image-gallery-metabox' ),
                'link_remove_title' => __( 'Remove Image', 'image-gallery-metabox' ),
            )
        );
		wp_enqueue_script( $this->plugin_name );
		
	}
	
	/**
	 * Register the image gallery meta box.
	 *
	 * @since    1.0
	 */
	public function add_image_gallery_meta_box() {
		
		// Get post ID
		$post_id = isset( $_GET['post'] ) ? $_GET['post'] : isset( $_POST['post_ID'] );

		// Get Front page ID
		$frontpage_id = get_option( 'page_on_front' );
		
		// Get Posts page ID
		$postspage_id = get_option( 'page_for_posts' );
		
		// Get page template
		$page_template = get_post_meta( $post_id, '_wp_page_template', true );
		
		// Define default values
		$default = apply_filters( 'igmb_display_meta_box', array(
			'title'			 => __( 'Image Gallery', 'image-gallery-metabox' ),
			'post_type'		 => array( 'directory' ),
			'post_id'		 => array(),
			'page_template'	 => array(),
			'page_on_front'	 => false,
			'page_for_posts' => false,
			'priority'		 => 'high',
		) );
		
		// Sanitize default filter values
		$title = !empty( $default['title'] ) ? $default['title'] : __( 'Image Gallery', 'image-gallery-metabox' );
		$post_type = !empty( $default['post_type'] ) ? $default['post_type'] : array();
		$ids = !empty( $default['post_id'] ) ? $default['post_id'] : array();
		$templates = !empty( $default['page_template'] ) ? $default['page_template'] : array();
		$frontpage = isset( $default['page_on_front'] ) ? $default['page_on_front'] : false;
		$postspage = isset( $default['page_for_posts'] ) ? $default['page_for_posts'] : false;
		$priority = !empty( $default['priority'] ) ? $default['priority'] : 'low'; // mycode CHANGED 'high'
		
		// Add meta box to specific post types
		if ( $post_type ) {
		
			add_meta_box( 'image-gallery-meta-box', $title, array( $this, 'image_gallery_meta_box' ), $post_type, 'normal', $priority );
		}
		
		// Add meta box to specific post IDs
		if ( $ids && in_array( $post_id, $ids ) ) {
			
			foreach ( $ids as $value ) {
				
				// Get post type
				$post_type = get_post_type( $value );
				
				add_meta_box( 'image-gallery-meta-box', $title, array( $this, 'image_gallery_meta_box' ), $post_type, 'normal', $priority );
				
			}
			
		}
		
		// Add meta box to specific page templates
		if ( $templates && in_array( $page_template, $templates ) ) {
			
			add_meta_box( 'image-gallery-meta-box', $title, array( $this, 'image_gallery_meta_box' ), 'page', 'normal', $priority );
			
		}
		
		// Add meta box to Front page or Posts page
		if ( ( $frontpage && true == $frontpage && $post_id === $frontpage_id ) ||
			 ( $postspage && true == $postspage && $post_id === $postspage_id ) ) {
			
			add_meta_box( 'image-gallery-meta-box', $title, array( $this, 'image_gallery_meta_box' ), 'page', 'normal', $priority );
			
		}
		
	}
	
	/**
	 * Define the image gallery meta box.
	 *
	 * @since    1.0
	 */
	public function image_gallery_meta_box( $post ) {
		
		wp_nonce_field( basename( __FILE__ ), 'igmb_image_gallery_nonce' );

		$gallery_stored_meta = get_post_meta( $post->ID, '_igmb_image_gallery_id', true );
		
		// Meta box markup
		include( plugin_dir_path( __FILE__ ) . 'partials/image-gallery-metabox-markup.php' );
			
	}
	
	
	/**
	 * Save the image gallery meta box values.
	 *
	 * @since    1.0
	 */
	public function save_meta_box_values( $post_id ) {
		
		// Check save status
		$is_autosave = wp_is_post_autosave( $post_id );
		$is_revision = wp_is_post_revision( $post_id );
		$is_valid_nonce = ( isset( $_POST[ 'igmb_image_gallery_nonce' ] ) && wp_verify_nonce( $_POST[ 'igmb_image_gallery_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';
		
		// Exit depending on save status
		if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
			return;
		}
		
		// Check for input and sanitize/save if needed
		if ( isset( $_POST[ '_igmb_image_gallery_id' ] ) ) {
			update_post_meta( $post_id, '_igmb_image_gallery_id', array_map( 'absint', $_POST[ '_igmb_image_gallery_id' ] ) );
		} else {
			delete_post_meta( $post_id, '_igmb_image_gallery_id' );
		}
		
	}

	// define the function to be fired for logged in users
	public function my_cropeimage_url() {
		add_image_size('large_size',get_option('large_size_w'),get_option('large_size_h'),true);
		add_image_size('medium_size',get_option('medium_size_w'),get_option('medium_size_h'),true);
		add_image_size('thumbnail_size',get_option('thumbnail_size_w'),get_option('thumbnail_size_h'),true);
		$upload_dir = wp_upload_dir();
		// @new
		//HANDLE UPLOADED FILE
		 require_once(ABSPATH . '/wp-admin/includes/media.php');
		if( !function_exists( 'wp_handle_sideload' ) ) {
		  require_once(ABSPATH.'wp-admin/includes/file.php' );
		}

		// Without that I'm getting a debug error!?
		if( !function_exists( 'wp_get_current_user' ) ) {
		  require_once(ABSPATH.'wp-includes/pluggable.php' );
		}
		//@new
		// upload file to server
		// @new use $file instead of $image_upload
		$file = $_FILES['files'];

		$file_return = wp_handle_sideload( $file, array( 'test_form' => false ) );
		
		$filename = $file_return['file'];

		$attachment = array(
		 'post_mime_type' => $file_return['type'],
		 'post_title' => preg_replace('/\.[^.]+$/', '', basename($filename)),
		 'post_content' => '',
		 'post_status' => 'inherit',
		 'guid' => $upload_dir['url'] . '/' . basename($filename)
		 );
		//$parent_attach_id
		$attach_id = wp_insert_attachment( $attachment, $filename);
		require_once( ABSPATH . 'wp-admin/includes/image.php' );
		$attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
		wp_update_attachment_metadata( $attach_id,  $attach_data );
		echo json_encode($attach_id);
	   	die();
	}

	public function my_cropeimage_edit_url() {
		add_image_size('large_size',get_option('large_size_w'),get_option('large_size_h'),true);
		add_image_size('medium_size',get_option('medium_size_w'),get_option('medium_size_h'),true);
		add_image_size('thumbnail_size',get_option('thumbnail_size_w'),get_option('thumbnail_size_h'),true);
		// // @new
		$upload_dir = wp_upload_dir();
		$attachementid = $_POST['attachementid'];

		//HANDLE UPLOADED FILE

		require_once(ABSPATH . '/wp-admin/includes/media.php');
		if( !function_exists( 'wp_handle_sideload' ) ) {
		  require_once(ABSPATH.'wp-admin/includes/file.php' );
		}

		// Without that I'm getting a debug error!?
		if( !function_exists( 'wp_get_current_user' ) ) {
		  require_once(ABSPATH.'wp-includes/pluggable.php' );
		}
		// @new
		// upload file to server
		// @new use $file instead of $image_upload
		$file = $_FILES['files'];
		$file_return = wp_handle_sideload( $file, array( 'test_form' => false ) );

		$filename = $file_return['file'];
		$attachment = array(
		 	'post_mime_type' => $file_return['type'],
		 	'post_title' => preg_replace('/\.[^.]+$/', '', basename($filename)),
		 	'post_content' => '',
		 	'post_status' => 'inherit',
		 	'guid' => $upload_dir['url'] . '/' . basename($filename)
		);


		$attach_id = update_attached_file( $attachementid, $filename );
		$attach_data = wp_generate_attachment_metadata( $attachementid, $filename );
		wp_update_attachment_metadata( $attachementid,  $attach_data );
		echo json_encode( $attachementid);
	   die();
	}

	// Gallery Post View
	public function image_gallery_metabox_shortcode(){
		// Attachment IDs
		ob_start();
		echo $images = get_post_meta( get_the_ID(), '_igmb_image_gallery_id', true );
		// Display attachments
		if ( $images ) { 
		    $outdata = '<div class="attachment-images">';
			    $count = 1; 
		        foreach( $images as $image ) {
		            $class='';
		            $attachment = wp_get_attachment_image_src( $image , 'medium_size');	
		            $thumb = wp_get_attachment_image_src( $image , 'thumbnail_size');	
		            if($count == 1){ 
					  	$outdata .= '<div class="large-image-thumnail">
					  		<img src="'.$attachment[0].'" id="largeImage" class="largeImage" />
					  		<div class="loader-image" style="display:none;">
								<img src="'.plugin_dir_url( __FILE__ ).'images/preloader.gif">
							</div>
					  	</div>';
					$class="hide_thumb";
					} 
		            $outdata .='<div class="thumbnails '.$class.'" id="thumb_'.$count.'">
		            	<img id="thumb_count_'.$count.'" src="'.$thumb[0].'" data-src="'.$attachment[0].'" class="thumb" />
		            </div>';
					 $count++;
		    	} 
		    $outdata .= '</div>';    
		}
 		ob_end_clean(); 
		return $outdata;
	}
}
