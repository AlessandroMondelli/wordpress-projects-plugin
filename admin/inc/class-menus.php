<?php
/**
 * Class per nuovi sub-menu plugin
 * 
 */

class AmProjectMenus {
    public function add_options_submenu( $parent_slug, $page_title, $menu_title, $user_cap, $menu_slug, $callback_function, $position ) {
        add_submenu_page( 
            $parent_slug,
            $page_title,
            $menu_title,
            $user_cap,
            $menu_slug,
            $callback_function,
            $position
        );
    }

    public function setting_section( $id, $section_title, $function_callback, $page ) {
        add_settings_section( 
            $id, //ID univoco
            $section_title, //Titolo
            $function_callback, //funzione di callback
            $page //page
        );
    }

    public function setting_field( $id, $title, $function_callback, $page, $section_name, $args ) {
        add_settings_field( 
            $id,
            $title,
            $function_callback,
            $page,
            $section_name,
            $args
        );
    }

    public function register_fields( $option_group, $option_name, $args ) {
        register_setting(
            $option_group,
            $option_name,
            $args
        );
    }
}
