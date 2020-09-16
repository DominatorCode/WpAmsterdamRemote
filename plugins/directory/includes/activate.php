<?php 

function dr_activate_plugin(){
    if( version_compare( get_bloginfo( 'version'), '5.0', '<') ){
        wp_die( __( "You must update Worpdress to use this plugin", 'directory') );
    }
}

?>