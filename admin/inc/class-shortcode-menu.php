<?php
/**
 * Classe con metodi per stampare menu 
 * 
 */

require_once ADMIN_PLUGIN_DIR . '/inc/class-menus.php';

class AmProjectsMenuShortcode extends AmProjectMenus {
    public function __construct() {
        $this->set_hooks();
    }

    protected function set_hooks() {
        add_action( 'admin_menu', [ $this, 'shortcode_submenu' ] );
    }

    public function shortcode_submenu() {
        parent::add_options_submenu( 'edit.php?post_type=am_projects', 'Shortcode progetti', 'Shortcode progetti', 'manage_options', 'shortcode_progetti', [ $this, 'add_shortcode_submenu' ], '' );
    }

    public function add_shortcode_submenu() {
        require_once ADMIN_PLUGIN_DIR . '/templates/plugin-shortcode.php';
    }
}