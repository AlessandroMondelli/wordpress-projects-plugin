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
            'layout_section', //ID sezione
            [
                'name' => 'n_columns',
                'id' => 'n_columns',
                'value' => get_option( 'n_columns' )
            ],
        );

        register_setting(
            'layout_group',
            'n_columns',
            [ $this, 'validate_n_columns' ]
        );

        //Stile progetto - Colore sfondo
        add_settings_section( 
            'layout_background_section',
            'Colore Background titolo',
            [ $this, 'add_background_section_function' ],
            'layout_progetti'
        );

        add_settings_field(
            'background_field',
            'Colore background',
            [ $this, 'add_background_function' ],
            'layout_progetti',
            'layout_background_section',
            [
                'name' => 'project_background',
                'id' => 'project_background',
                'value' => get_option( 'project_background' )
            ],
        );

        register_setting(
            'layout_group',
            'project_background',
            [ $this, 'check_background_title_file' ],
        );

        add_settings_field(
            'text_color_field',
            'Colore testo',
            [ $this, 'add_text_color_function' ],
            'layout_progetti',
            'layout_background_section',
            [
                'name' => 'project_text_color',
                'id' => 'project_text_color',
                'value' => get_option( 'project_text_color' )
            ],
        );

        register_setting(
            'layout_group',
            'project_text_color',
            [ $this, 'check_color_title_file' ],
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
            'lazyload_section', //ID sezione,
            [
                'name' => 'lazyload_bool',
                'id' => 'lazyload_bool',
                'value' => get_option( 'lazyload_bool' ),
                'options' => [
                    'false' => 'Disattivato',
                    'true' => 'Attivato'
                ]
            ]
        );

        register_setting(
            'layout_group',
            'lazyload_bool',
            [ $this, 'validate_lazyload' ]
        );
    }

    public function add_layout_section_function() {
        echo 'Scegli il layout che preferisci per i tuoi progetti';
    }

    //Funzione campo input numero di colonne griglia
    public function add_layout_fields_function( $args ) {
        $name = ( isset( $args[ 'name' ] ) ) ? $args[ 'name' ] : '';
        $id = ( isset( $args[ 'id' ] ) ) ? $args[ 'id' ] : '';
        $value = ( isset( $args[ 'value' ] ) ) ? $args[ 'value' ] : '';

        ?>
        <input name="<?php echo $name ?>" id="<?php echo $id ?>" type="number" min="1" max="6" value="<?php echo $value ?>">
        <?php
    }

    public function validate_n_columns( $args ) {
        if( ! ( isset( $args ) ) || empty( $args ) || $args < '1' || $args > '6' ) {
            $value = 3; //setto valore base da ritornare
            add_settings_error( 'layout_errors', 'invalid_n_columns', 'Qualcosa è andata storto, riprova.' );
        } else {
            $value = sanitize_text_field( $args );
        }

        return $value;
    }

    public function add_lazyload_section_function() {
        echo 'Abilita il lazyload delle copertine per ottimizzare il caricamento della pagina';
    }

    //Funzione campo background color
    public function add_background_section_function() {
        echo 'Scegli il colore di sfondo per il titolo del progetto';
    }

    public function add_background_function( $args ) {
        $name = ( isset( $args[ 'name' ] ) ) ? $args[ 'name' ] : '';
        $id = ( isset( $args[ 'id' ] ) ) ? $args[ 'id' ] : '';
        $color = ( isset( $args[ 'value' ] ) ) ? $args[ 'value' ] : '';
        ?>
        <input name="<?php echo $name ?>" id="<?php echo $id ?>" type="color" value="<?php echo $color ?>">
        <?php
    }

    //Funzione campo text color
    public function add_text_color_function( $args ) {
        $name = ( isset( $args[ 'name' ] ) ) ? $args[ 'name' ] : '';
        $id = ( isset( $args[ 'id' ] ) ) ? $args[ 'id' ] : '';
        $color = ( isset( $args[ 'value' ] ) ) ? $args[ 'value' ] : '';
        ?>
        <input name="<?php echo $name ?>" id="<?php echo $id ?>" type="color" value="<?php echo $color ?>">
        <?php
    }

    //Funzioni per aggiornare file css
    public function check_background_title_file( $args ) {
        $file_name = PLUGIN_DIR . 'css/project-active-style.css';

        if( ! ( isset( $args ) ) || empty( $args ) ) {
            $color = 'rgba( 0, 0, 0, 0.8 )';
        } else {
            $color = $args;     
        }

        $class_to_replace = '\.amproj-content-wrap\.active{ background-color: .{0,30} }';
        $style = '.amproj-content-wrap.active{ background-color: ' . $color .' }';  

        $this -> replace_file_text( $class_to_replace, $style, $file_name );

        return $color;
    }

    public function check_color_title_file( $args ) {
        $file_name = PLUGIN_DIR . 'css/project-active-style.css';

        if( ! ( isset( $args ) ) || empty( $args ) ) {
            $color = '#000';
        } else {
            $color = $args;
        }

        $class_to_replace = '\.amproj-content-wrap .amproj-title{ color: .{0,30} }';
        $style = '.amproj-content-wrap .amproj-title{ color: ' . $color .' }';  

        $this -> replace_file_text( $class_to_replace, $style, $file_name );

        return $color;
    }

    public function replace_file_text( $old_string, $new_string, $file_name ) {
        $str = file_get_contents( $file_name );
        
        if( !empty( $str ) ) {
            $out = [];
            $var = preg_match( '/' . $old_string . '/', $str, $out );
            $str = str_replace( $out[0], $new_string, $str );
        } else {
            $str = $new_string;
        }
        
        file_put_contents( $file_name, $str );
    }

    //Funzione campo input lazyload
    public function add_lazyload_fields_function( $args ) {
        $name = ( isset( $args[ 'name' ] ) ) ? $args[ 'name' ] : '';
        $id = ( isset( $args[ 'id' ] ) ) ? $args[ 'id' ] : '';
        $lazy_value = ( isset( $args[ 'value' ] ) ) ? $args[ 'value' ] : '';

        $options = ( isset( $args[ 'options' ] ) && is_array( $args[ 'options' ] ) ) ? $args[ 'options' ] : [];
        ?>
        <select name="<?php echo $name ?>" id="<?php echo $id ?>">
        <?php
            foreach( $options as $key => $option ) {
        ?>
                <option <?php selected( $lazy_value, $key ) ?> value="<?php echo $key ?>"><?php echo $option ?></option>
        <?php
            }
        ?>  
        </select>
        <?php
    }

    public function validate_lazyload($args) {
        if( ! ( isset( $args ) ) || empty( $args ) || ( strlen( $args ) > 5 ) ) {
            $value = 'false'; //setto valore base da ritornare
            add_settings_error( 'layout_errors', 'invalid_lazyload', 'Qualcosa è andata storto, riprova.' );
        } else {
            $value = sanitize_text_field( $args );
        }

        return $value;
    }
}