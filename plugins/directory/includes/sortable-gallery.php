<?php 





// Sortable Gallery
class Sortable_Wordpress_Gallery {

    /**
    * Unique ID of the metabox
    * @var string
    */
    protected $id;


    /**
    * Metabox Title
    * @var string
    */
    protected $title = '';


    /**
    * Metabox Context
    * @var string
    */
    protected $context = 'advanced';



    /**
    * Metabox Priority
    * @var string
    */
    protected $priority = 'high';


    /**
    * Hook into the appropriate actions when the class is constructed
    *
    */
    public function __construct( $id, $title = '', $context = 'advanced', $priority = 'high' ) {

        // ID is required
        if ( $id == '') {
            return;
        }

        $this->id = $id;

        // Create title from ID if not provides
        if ( $title == '' ) {
            $this->title = ucfirst( $id );
        } else {
            $this->title = $title;
        }


        // Only valid context can be changed
        $available_context = array(
            'advanced',
            'side',
            'normal' );
        if ( in_array( $context, $available_context ) ) {
            $this->context = $context;
        }


        // Only valid priority can be changed
        $available_priority = array(
            'default',
            'high',
            'low' );
        if ( in_array( $priority, $available_priority ) ) {
            $this->priority = $priority;
        }

        add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
        add_action( 'save_post', array( $this, 'save' ) );
        add_filter( 'the_content', array( $this, 'render' ) );

    }


    /**
    * Adds the meta box container
    */
    public function add_meta_box( $post_type ) {

        // Limit meta box to certain post types.
        // Can be extend using filter ' sortable_wordpress_gallery_post_types' or
        // using a dynamic filter 'sortable_wordpress_gallery_METABOX-ID_post_types'
        $post_types = apply_filters( 'sortable_wordpress_gallery_post_types', array( 'post ' ) );
        $post_types = apply_filters( 'sortable_wordpress_gallery_' . $this->id . '_post_types', $post_types );


        if ( in_array( $post_type, $post_types ) ) {
            add_meta_box(
                $this->id,
                $this->title,
                array( $this, 'render_meta_box_content' ),
                $post_type,
                $this->context,
                $this->priority
            );
        }

    }



    /** Render Meta Box content.
    *
    * @param WP_Post $post The post object.
    */ 
    public function render_meta_box_content( $post ) {


        // Add an nonce field so we can check when saving
        wp_nonce_field( $this->id . '_metabox', $this->id . '_metabox_nonce' );

    // Get the gallery saved data
        $sortable_gallery = get_post_meta( $post->ID, '_' . $this->id . '_sortable_wordpress_gallery', true );

        
        ?>
        <table class="table">
            <tr>
                <th style="vertical-align: top;">
                    <?php _e( 'Slider Images', 'directory') ?>
                </th>
                <td>
                    <!-- Creating a dynamic ID using the metabox ID for Javascript  -->
                    <ul id="<?php echo $this->id; ?>_sortable_wordpress_gallery" class="sortable_wordpress_gallery">
                        <?php 
                        // Getting all the image IDs by creating an array from string ( 1,3,5 => array(1,3,5))
                        $gallery = explode(",", $sortable_gallery );

                    // If there is any ID, create the image for it
                        if ( count( $gallery ) > 0 && $gallery[0] != '' ) {
                            foreach ( $gallery as  $attachment_id ) {
                            
                            //Create a LI element
                                $output = '<li tabindex="0" role="checkbox" aria-label="' . get_the_title( $attachment_id ) . '" aria-checked="true" data-id="' . $attachment_id .  '" class="attachment save-ready selected details">';
                                // Create a container for the image. (Copeid from the WP Media Library Modal to use the same styling)
                                    $output .= '<div class="attachment-preview js--select-attachment type-image subtype-jpeg portrait">';
                                      $output .= '<div class="thumbnail">';

                                        $output .= '<div class="centered">';
                                        //  Get the URL to that image thumbnail
                                          $output .= '<img src="'  .  wp_get_attachment_thumb_url( $attachment_id ) . '" draggable = "false" alt="">';
                                        $output .= '</div>';

                                      $output .= '</div>' ;
                                    
                                    $output .= '</div>';
                                // Add the button to remove this image if wanted (we set the new data-gallery to target the correct gallery if there more than one) 
                                    $output .= '<button type="button" data-gallery="#' . $this->id . '_sortable_wordpress_gallery" class="button-link check remove-sortable-wordpress-gallery-image" tabindex="0"><span class="media-modal-icon"></span><span class="screen-reader-text">Deselect</span></button>';

                                  $output .= '</li>';
                                  echo $output;
                            }
                        }
                        ?>
                    </ul>
                   <!--  Hidden input used to save the gallery image IDs into the database -->
                   <!--  We are also creating dynamic IDs here so that we can easily target them in Javascript -->
                   <input type="hidden" id="<?php echo $this->id; ?>_sortable_wordpress_gallery_input" name="_<?php echo $this->id; ?>_sortable_wordpress_gallery" value="<?php echo $sortable_gallery; ?> "/>
                   <!-- Button used to open the Wordpress Media Library Modal -->
                   <button type="button" class="button button-primary add-sortable-wordpress-gallery" data-gallery="#<?php echo $this->id; ?>_sortable_wordpress_gallery"><?php _e('Add Images', 'directory'); ?></button>
                </td>
            </tr>
        </table>
        <?php

    }





    // Save the meta when the post is saved.
    /**
    * @param int $post_id The ID of the post if being saved.
    */
    public function save( $post_id ) {

        /*
        * We neee to verify this came from the our screen and with proper authorization,
        *  because save_post can be triggered at other times.
        */

        // Check if our nonce is set.
        if ( ! isset( $_POST[ $this->id . '_metabox_nonce'] ) ) {
            return $post_id;
        }

        $nonce = $_POST[ $this->id . '_metabox_nonce'];

        // Verify that the nonce is valid.
        if ( ! wp_verify_nonce( $nonce, $this->id . '_metabox' ) ) {
            return $post_id; // mycode CHANGED $post->id
        }

        /*
        * If this an autosave, our form has not been submitted,
        * so we don't want to do anything
        */
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }

        // Check the user's permission.
        if ( 'page' == $_POST['post_type'] ) {
            if ( ! current_user_can( 'edit_page', $post_id ) ) {
                return $post_id;
            }
        } else {
            if ( ! current_user_can( 'edit_post', $post_id  ) ) {
                return $post_id;
            }
        }

        /* OK, it's safe for us to save the data now. */
        $gallery = sanitize_text_field( $_POST[ '_' . $this->id . '_sortable_wordpress_gallery' ] );

        // Update the meta field
        update_post_meta( $post_id, '_' . $this->id . '_sortable_wordpress_gallery', $gallery );

    }



    /**
    * Rendering the gallery
    */
    public function render( $content ) {
        global $post;

        // Get our registered post types
        $post_types = apply_filters( 'sortable_wordpress_gallery_post_types', array( 'post' ) );
        $post_types = apply_filters( 'sortable_wordpress_gallery_' . $this->id . '_post_types', $post_types );

        // Get current post type
        $this_post_type = get_post_type( $post );

        // If the current post type is also a registered post type, create teh gallery
        if ( in_array( $this_post_type, $post_types ) ) {
            // Get all the image IDs
            $gallery_images = get_post_meta( $post->ID, '_' . $this->id . '_sortable_wordpress_gallery', true );
                if ( $gallery_images != '' ) {
                    // If the data retreived is not empty, create an array
                        $gallery_images_array = explode( ',', $gallery_images );
                        // Create a gallery container with the 3 column layout
                        $content .= '<div class="gallery gallery-column-3">';
                        // For each image ID, create the gallery image
                            foreach ($gallery_images_array as $image_id) {
                                
                            $html = wp_get_attachment_image( $image_id );
                    if ( $html == '' ) {
                        continue;
                    }

                    $content .= '<figure class="gallery-item">';
                        $content .= '<a href="' . get_permalink( $image_id ) . '" target="_blank">';
                            $content .= $html;
                        $content .= '</a>';
                    $content .= '</figure>';
                    }

                            $content .= '</div>';
                        }
        }


                return $content;
    }



    /**
    * Gallery Defaults
    * @var array
    */
    protected $gallery_defaults = array(
            'class'    =>   'Sortable_Wordpress_Gallery',
            'id'       =>   '',
            'title'    =>   '',
            'context'  =>   '',
            'priority' =>   '' );

    /**
    * Galleries that have been registered to use
    * @var array
    */
    protected $galleries = array();


    /**
    * Loaded galleries (Gallery objects)
    * @var array
    */
    protected $loaded_galleries = array();


    /**
    * Constructor method
    */
    /*public function __construct() { // CHANGED commented duplicate construct

        add_action( 'init', array( $this, 'collect_galleries') );
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
        add_action( 'admin_init', array( $this, 'create_galleries' ) );
        add_filter( 'the_content', array( $this, 'render_galleries' ) ); 

    }*/




    /**
    * Enqueuing admin scripts and styles
    */
    public function admin_scripts() {
        wp_enqueue_script( 'sortable-gallery-admin-js', plugin_dir_url( __FILE__). 'assets/admin.js', array( 'jquery' ) );
        wp_enqueue_style( 'sortable-gallery-admin-css', plugin_dir_url( __FILE__). 'assets/admin.css' );
    }

    /**
    * Collect galleries passed to our filter
    */
    public function collect_galleries() {

        $this->galleries = apply_filters( 'sortable_wordpress_galleries', $this->galleries );

    }


    /**
    * Hook our method load_galleries on edit post screens
    */
    public function create_galleries() {
        add_action( 'load-post.php',          array( $this, 'load_galleries' ) );
        add_action( 'load-post-new.php',      array( $this, 'load_galleries' ) );
    }


    /**
    * Create gallery objects to create metaboxes
    */
    public function load_galleries() {

        foreach ( $this->galleries as $gallery ) {
            $gallery = wp_parse_args( $gallery, $this->gallery_defaults );
            $class = $gallery['class'];
            if ( $class != '' ) {
                $this->loaded_galleries[] = new $class( $gallery['id'], $gallery['title'], $gallery['context'], $gallery['priority'] );
            }
        }
    }



    /**
    * Load all the galleries and render each gallery
    */
    public function render_galleries( $content ) {
        $this->load_galleries();

        foreach ($this->loaded_galleries as $gallery ) {
            
            $content = $gallery->render( $content );

        }

        return $content;
    }


    // Let's power this! Instantiate it where you want just by using this.
    //new Sortable_Wordpress_Gallery()




}




/**
* Gallery Image Metabox
*
*/
add_filter( 'sortable_wordpress_galleries', 'add_sortable_gallery' );

function add_sortable_gallery( $galleries ) {
        $galleries[] = array(
            'class'    =>    'Sortable_Wordpress_Gallery',
            'id'       =>    'post-metabox',
            'title'    =>    'Sortable Gallery'

        );

        return $galleries;

}




?>