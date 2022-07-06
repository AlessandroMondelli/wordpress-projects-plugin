<?php
/**
 * Class per nuovi sub-menu plugin
 * 
 */

class AmProjectMenus {
    public function __construct() {
        $this->set_hooks();
    }

    protected function set_hooks() {
        add_action( 'admin_menu', [ $this, 'add_options_submenu' ] );
    }

    public function add_options_submenu() {
        add_submenu_page( 
            'edit.php?post_type=am_projects',
            'Shortcode progetti',
            'Shortcode progetti',
            'manage_options',
            'shortcode_progetti',
            [ $this, 'add_shortcode_submenu' ]
        );
    }

    public function add_shortcode_submenu() {
        require_once ADMIN_PLUGIN_DIR . '/templates/plugin-shortcode.php';
    }
}