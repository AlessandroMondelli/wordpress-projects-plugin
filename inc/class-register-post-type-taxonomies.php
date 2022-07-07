<?php
/**
 * Classe con metodi per creare custom post type e taxonomies progetti
 */

class AmProjectPostTypeAndTaxonomies {
    public function __construct() {
        $this->set_hooks();
    }

    protected function set_hooks() {
        add_action( 'init', [ $this, 'am_register_projects' ] );
        add_action( 'init', [ $this, 'am_register_taxonomies' ] );

        add_shortcode( 'am_projects_shortcode', [ $this, 'am_projects_html' ] );
    }

    //Metodo per registare custom post type
    public function am_register_projects() {
        register_post_type('am_projects',
            array(
                'labels'      => array(
                    'name'          => __('Progetti', 'am-projects'),
                    'singular_name' => __('Progetto', 'am-projects'),
                    'add_new_item' 	=> __('Aggiungi nuovo progetto', 'am-projects'),
                ),
            'hierarchical' => true,
            'public'      => true,
            'menu_icon' => 'dashicons-book',
            'rewrite'     => array( 'slug' => 'projects', 'with_front' => false, ),
            'supports' => array( 'title', 'editor', 'author', 'thumbnail', 
            'page-attributes'),
            )
        );

        flush_rewrite_rules();
    }

    //Nuove taxonomies
    function am_register_taxonomies() {
        $labels_discipl = array(
            'name'              => _x( 'Discipline', 'Discipline progetto' ),
            'singular_name'     => _x( 'Disciplina', 'Disciplina progetto' ),
            'search_items'      => __( 'Cerca disciplina' ),
            'all_items'         => __( 'Tutte le discipline' ),
            'parent_item'       => __( 'Disciplina padre' ),
            'parent_item_colon' => __( 'Disciplina padre:' ),
            'edit_item'         => __( 'Modifica disciplina' ),
            'update_item'       => __( 'Aggiorna disciplina' ),
            'add_new_item'      => __( 'Aggiungi nuova disciplina' ),
            'new_item_name'     => __( 'Nome nuova disciplina' ),
            'menu_name'         => __( 'Discipline' ),
        );

        $args_discipl   = array(
            'hierarchical'      => true,
            'labels'            => $labels_discipl,
            'show_ui'           => true,
            'show_admin_column' => true,
            'show_in_rest'      => true,
            'query_var'         => true,
            'rewrite'           => [ 'slug' => 'disciplina' ],
        );

        $labels_years = array(
            'name'              => _x( 'Anno', 'Anno progetto' ),
            'singular_name'     => _x( 'Anno', 'Anno progetto' ),
            'search_items'      => __( 'Cerca anno' ),
            'all_items'         => __( 'Tutti gli anni' ),
            'edit_item'         => __( 'Modifica anno' ),
            'update_item'       => __( 'Aggiorna anno' ),
            'add_new_item'      => __( 'Aggiungi nuovo anno' ),
            'new_item_name'     => __( 'Nuovo anno' ),
            'menu_name'         => __( 'Anni' ),
        );

        $args_years   = array(
            'hierarchical'      => false,
            'labels'            => $labels_years,
            'show_ui'           => true,
            'show_admin_column' => true,
            'show_in_rest'      => true,
            'query_var'         => true,
            'rewrite'           => [ 'slug' => 'anno' ],
        );

        register_taxonomy( 'discipline', [ 'am_projects' ], $args_discipl );
        register_taxonomy( 'anni', [ 'am_projects' ], $args_years );
    }
}