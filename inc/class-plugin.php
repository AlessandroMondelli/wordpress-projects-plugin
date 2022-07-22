<?php
/**
 * Classe principale plugin
 */

require_once PLUGIN_DIR . '/inc/class-register-post-type-taxonomies.php';
require_once PLUGIN_DIR . '/inc/class-project-functions.php';
require_once PLUGIN_DIR . '/inc/class-meta-boxes.php';
require_once ADMIN_PLUGIN_DIR . '/inc/class-shortcode-menu.php';
require_once ADMIN_PLUGIN_DIR . '/inc/class-layout-menu.php';


class AmProject {
    function __construct() {
        add_action( 'wp_enqueue_scripts', array( $this, 'am_register_scripts' )); //Enqueue script
        add_action( 'init', array( $this, 'am_register_style' )); //Enqueue style

        $project_post_type = new AmProjectPostTypeAndTaxonomies();
        $projects_functions = new AmProjectFunctions();
        $projects_meta_boxes = new AmProjectMetaBoxes();
        $projects_shortcode_menu = new AmProjectsMenuShortcode();
        $projects_layout_menu = new AmProjectLayoutMenu();
    }

    //Registro jQuery
    public function am_register_scripts() {
        wp_enqueue_script( 'amprojects_jquery', PLUGIN_URL . 'js/script.js', array( 'jquery' ) );
    }

    //Registro Css
    public function am_register_style() {
        wp_register_style( 'amprojects_style', PLUGIN_URL . 'css/style.css' );
        wp_enqueue_style( 'amprojects_style' );

        wp_register_style( 'amprojects_admin_style', ADMIN_PLUGIN_URL . '/css/style.css' );
        wp_enqueue_style( 'amprojects_admin_style' );

        wp_register_style( 'amprojects_user_styles', PLUGIN_URL . 'css/project-active-style.css' );
        wp_enqueue_style( 'amprojects_user_styles');
    }
}