<?php
/**
 * Classe disattivatore
 */

class AmDeactivator {
    public static function deactivator() {
        self::am_remove_projects();
        flush_rewrite_rules();
    }

    public static function am_remove_projects() {
        unregister_post_type('am_projects');
    }
}