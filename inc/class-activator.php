<?php
/**
 * Classe attivatore
 */

class AmActivator {
    public static function activator() {
        flush_rewrite_rules();

        update_option( 'n_columns', 3 );
        update_option( 'project_background', 'rgba(0, 0, 0, 0.8)' );
        update_option( 'lazyload_bool', 'false' );
    }
}