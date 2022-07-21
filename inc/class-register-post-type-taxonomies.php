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
            'name'              => _x( 'Discipline', 'Discipline progetto', 'am-projects' ),
            'singular_name'     => _x( 'Disciplina', 'Disciplina progetto', 'am-projects' ),
            'search_items'      => __( 'Cerca disciplina', 'am-projects' ),
            'all_items'         => __( 'Tutte le discipline', 'am-projects' ),
            'parent_item'       => __( 'Disciplina padre', 'am-projects' ),
            'parent_item_colon' => __( 'Disciplina padre:', 'am-projects' ),
            'edit_item'         => __( 'Modifica disciplina', 'am-projects' ),
            'update_item'       => __( 'Aggiorna disciplina', 'am-projects' ),
            'add_new_item'      => __( 'Aggiungi nuova disciplina', 'am-projects' ),
            'new_item_name'     => __( 'Nome nuova disciplina', 'am-projects' ),
            'menu_name'         => __( 'Discipline', 'am-projects' ),
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
            'name'              => _x( 'Anno', 'Anno progetto', 'am-projects' ),
            'singular_name'     => _x( 'Anno', 'Anno progetto', 'am-projects' ),
            'search_items'      => __( 'Cerca anno', 'am-projects' ),
            'all_items'         => __( 'Tutti gli anni', 'am-projects' ),
            'edit_item'         => __( 'Modifica anno', 'am-projects' ),
            'update_item'       => __( 'Aggiorna anno', 'am-projects' ),
            'add_new_item'      => __( 'Aggiungi nuovo anno', 'am-projects' ),
            'new_item_name'     => __( 'Nuovo anno', 'am-projects' ),
            'menu_name'         => __( 'Anni', 'am-projects' ),
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