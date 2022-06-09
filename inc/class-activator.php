<?php
/**
 * Classe attivatore
 */

class AmActivator {
    public static function activator() {
        flush_rewrite_rules();
    }
}