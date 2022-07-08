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
        add_action( 'admin_menu', [ $this, 'add_layout_options_menu' ] );
        
        add_action( 'admin_init', [ $this, 'add_layout_options' ] );
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

    public function add_layout_options_menu() {
        add_submenu_page( 
            'edit.php?post_type=am_projects',
            'Layout Progetti',
            'Layout progetti',
            'manage_options',
            'layout_progetti',
            [ $this, 'add_layout_submenu' ]
        );
    }

    public function add_layout_submenu() {
        require_once ADMIN_PLUGIN_DIR . '/templates/plugin-layout-menu.php';
    }

    public function add_layout_options() {
        //Numero colonne
        add_settings_section(
            'layout_section', //ID univoco
            'Layout progetti', //Titolo
            [ $this, 'add_layout_section_function' ], //funzione di callback
            'layout_progetti' //page
        );
        
        add_settings_field(
            'layout_field', //ID univoco 
            'Numero colonne progetti', //Titolo
            [ $this, 'add_layout_fields_function' ], //funzione di callback
            'layout_progetti', //page
            'layout_section' //ID sezione
        );

        register_setting(
            'layout_group',
            'n_columns',
        );  

        //Lazyload
        add_settings_section(
            'lazyload_section', //ID univoco
            'Lazyload', //Titolo
            [ $this, 'add_lazyload_section_function' ], //funzione di callback
            'layout_progetti' //page
        );
        
        add_settings_field(
            'lazyload_field', //ID univoco 
            'Abilita Lazyload', //Titolo
            [ $this, 'add_lazyload_fields_function' ], //funzione di callback
            'layout_progetti', //page
            'lazyload_section' //ID sezione
        );

        register_setting(
            'layout_group',
            'lazyload_bool',
        );
    }

    public function add_layout_section_function() {
        echo 'Scegli il layout che preferisci per i tuoi progetti';
    }

    public function add_layout_fields_function() {
        ?>
        <input name="n_columns" id="n_columns" type="number" min="1" max="6" value="<?php echo get_option('n_columns')?>">
        <?php
    }

    public function add_lazyload_section_function() {
        echo 'Abilita il lazyload delle copertine per ottimizzare il caricamento della pagina';
    }

    public function add_lazyload_fields_function() {
        $lazy_value = get_option('lazyload_bool');
        ?>
        <select name="lazyload_bool" id="lazyload_bool">
            <option value="false" <?php echo $lazy_value == 'false' ? "selected" : ''  ?>>Disattivato</option>
            <option value="true" <?php echo $lazy_value == 'true' ? "selected" : ''  ?> >Attivato</option>
        </select>
        <?php
    }
}