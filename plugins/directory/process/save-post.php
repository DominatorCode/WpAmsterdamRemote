<?php 

function dr_save_post_admin( $post_id, $post, $update ){
    $directory_data  =    get_post_meta( $post_id, 'directory_data', true );
    $directory_data   =   empty($directory_data) ? [] : $directory_data;

    update_post_meta( $post_id, 'directory_data', $directory_data  );
}


?>