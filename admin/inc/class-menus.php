<?php
/**
 * Class per nuovi sub-menu plugin
 * 
 */

require_once ADMIN_PLUGIN_DIR . '/inc/admin-functions.php';

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

        //Stile progetto
        add_settings_section( 
            'layout_background_section',
            'Colore Background titolo',
            [ $this, 'add_background_section_function' ],
            'layout_progetti'
        );

        //Colore sfondo
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

        //Colore testo
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

        //Dimensione testo
        add_settings_field(
            'font_size_field',
            'Dimensione testo',
            [ $this, 'add_text_size_function' ],
            'layout_progetti',
            'layout_background_section',
            [
                'name' => 'project_text_size',
                'id' => 'project_text_size',
                'value' => get_option( 'project_text_size' )
            ],
        );

        register_setting(
            'layout_group',
            'project_text_size',
            [ $this, 'check_project_text_size_file' ],
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

    //Funzione campo background color
    public function add_background_section_function() {
    ?>
        <p>Scegli i colori per il titolo del progetto</p>
        <img class="amprojects-admin-img" src="<?php echo ADMIN_PLUGIN_URL . '/src/media/projects-colors.jpg' ?>">
    <?php
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

    //Funzione campo size testo
    public function add_text_size_function( $args ) {
        $name = ( isset( $args[ 'name' ] ) ) ? $args[ 'name' ] : '';
        $id = ( isset( $args[ 'id' ] ) ) ? $args[ 'id' ] : '';
        $size = ( isset( $args[ 'value' ] ) ) ? $args[ 'value' ] : '';
        ?>
        <input name="<?php echo $name ?>" id="<?php echo $id ?>" type="number" min="1" max="100" value="<?php echo $size ?>">
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

        replace_file_text( $class_to_replace, $style, $file_name );

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

        replace_file_text( $class_to_replace, $style, $file_name );

        return $color;
    }

    public function check_project_text_size_file( $args ) {
        $file_name = PLUGIN_DIR . 'css/project-active-style.css';
        $size_value = intval( $args );

        if( ! ( isset( $args ) ) || empty( $args ) || ( $size_value  < 1 ) || ( $size_value  > 100 ) ) {
            $size = 16;
        } else {
            $size = $size_value;
        }

        $class_to_replace = '\.amproj-content-wrap span{ font-size: .{0,30} }';
        $style = '.amproj-content-wrap span{ font-size: ' . $size .'px }';  

        replace_file_text( $class_to_replace, $style, $file_name );

        return $size;
    }

    //Funzione campo input lazyload
    public function add_lazyload_section_function() {
    ?>
        <p>Abilita il lazyload delle copertine per ottimizzare il caricamento della pagina</p>
        <video class="amprojects-admin-video" controls src="<?php echo ADMIN_PLUGIN_URL . '/src/media/video_lazyload.mp4' ?>" type="video/mp4"></video>
    <?php
    }

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
            add_settings_error( 'layout_errors', 'invalid_lazyload', 'Qualcosa è andata storto con l\'opzione "Abilita lazyload", riprova.' );
        } else {
            $value = sanitize_text_field( $args );
        }

        return $value;
    }
}