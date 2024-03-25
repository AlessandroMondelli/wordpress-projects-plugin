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
        add_action( 'wp_enqueue_scripts',[ $this, 'am_register_scripts' ] ); //Enqueue script
        add_action( 'init',[ $this, 'am_register_style'] ); //Enqueue style
        add_filter('single_template',[ $this, 'am_projects_single_template'] ); //Attivo template
        add_action('admin_enqueue_scripts', [ $this, 'am_enqueue_media_uploader_scripts'] ); //Enqueue script per gallery picker


        new AmProjectPostTypeAndTaxonomies();
        new AmProjectFunctions();
        new AmProjectMetaBoxes();
        new AmProjectsMenuShortcode();
        new AmProjectLayoutMenu();
    }

    //Registro jQuery
    public function am_register_scripts() {
        wp_enqueue_script( 'amprojects_jquery', PLUGIN_URL . 'js/script.js', ['jquery'] );
        wp_enqueue_script( 'amprojects_template_jquery', PLUGIN_URL . 'template/js/script.js', ['jquery'] );
    }

    //Registro CSS
    public function am_register_style() {
        wp_register_style( 'amprojects_style', PLUGIN_URL . 'css/style.css' );
        wp_enqueue_style( 'amprojects_style' );

        wp_register_style( 'amprojects_admin_style', ADMIN_PLUGIN_URL . '/css/style.css' );
        wp_enqueue_style( 'amprojects_admin_style' );

        wp_register_style( 'amprojects_user_styles', PLUGIN_URL . 'css/project-active-style.css' );
        wp_enqueue_style( 'amprojects_user_styles');

        wp_register_style( 'amprojects_template_style', PLUGIN_URL . 'template/css/style.css' );
        wp_enqueue_style( 'amprojects_template_style' );
    }

    //Aggiungo template
    public function am_projects_single_template($am_project_template) {
        global $post;

        if($post->post_type == 'am_projects') {
            $am_project_template = PLUGIN_DIR . 'template/single-am_projects.php';          
        }

        return $am_project_template;
    }

    //Aggiungo supporto script per upload galleria
    function am_enqueue_media_uploader_scripts() {
        global $typenow;
        
        if ($typenow == 'am_projects') {
            wp_enqueue_media();
            
            // Enqueue your custom script that opens the media uploader
            wp_enqueue_script('your_custom_media_script', PLUGIN_URL . 'js/custom-media-uploader.js', array('jquery'), null, true);
        }
    }
}