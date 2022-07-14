<?php
/**
 * File disinstallazione plugin
 * 
 */

if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

//Lista opzioni create con plugin
$options = [ 'n_columns', 'project_background', 'project_text_color', 'project_text_size', 'lazyload_bool' ];

//Elimino opzioni
for( $i = 0; $i < count( $options ); $i++ ) {
    delete_option( $options[ $i ] );
}

//Elimino dati custom post type
$all_projects = get_posts( array('post_type'=>'am_projects','numberposts'=>-1) );

foreach ($all_projects as $project) {
  wp_delete_post( $project->ID, true );
}
