<?php
/**
 * Plugin Name:       AM Projects
 * Description:       Crea un custom post type e mette a disposizione uno shortcode per stamparne il contenuto
 * Version:           1.0
 * Author:            Alessandro Mondelli
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       am-projects
 */

if( !defined('WPINC') ) {
    die;
}

define( 'PLUGIN_DIR', plugin_dir_path( __FILE__ ) ); //Setto costante con path plugin
define( 'PLUGIN_URL', plugin_dir_url(  __FILE__ ) ); //Setto costante con url plugin

register_activation_hook( __FILE__, '\\am_activation' );
function am_activation() {
    require_once PLUGIN_DIR . '/inc/class-activator.php';

    AmActivator::activator();
}

register_deactivation_hook( __FILE__, '\\am_deactivation' );
function am_deactivation() {
    require_once PLUGIN_DIR . '/inc/class-deactivator.php';

    AmDeactivator::deactivator();
}

require_once PLUGIN_DIR . '/inc/class-plugin.php';
$am_projects = new AmProject();