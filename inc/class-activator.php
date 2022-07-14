<?php
/**
 * Classe attivatore
 */

class AmActivator {
    public static function activator() {
        flush_rewrite_rules();

        update_option( 'n_columns', 3 );
        update_option( 'project_background', 'rgba(0, 0, 0, 0.8)' );
        update_option( 'project_text_color', '#000' );
        update_option( 'project_text_size', '16' );
        update_option( 'lazyload_bool', 'false' );

        file_put_contents( PLUGIN_DIR . '/css/project-active-style.css', '.amproj-content-wrap.active{ background-color: #FFF }   .amproj-content-wrap .amproj-title{ color: #000 }' );
    }
}