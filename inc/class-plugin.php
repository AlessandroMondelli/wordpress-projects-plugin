<?php
/**
 * Classe principale plugin
 */

require_once PLUGIN_DIR . '/inc/class-project-functions.php';

class AmProject {
    function __construct() {
        $projects_functions = new AmProjectFunctions();
    }
}