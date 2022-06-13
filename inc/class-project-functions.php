<?php
/**
 * Classe che crea contiene metodi riguardanti i progetti
 */

class AmProjectFunctions {
    public function __construct() {
        $this->set_hooks();
    }

    protected function set_hooks() {
        add_action( 'init', [ $this, 'am_register_projects' ] );
        add_action( 'init', [ $this, 'am_register_taxonomies' ] );

        add_shortcode( 'am_projects_shortcode', [ $this, 'am_projects_html' ] );
    }

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

    public function am_projects_html() {
        $posts = get_posts(array( //Prendo pagine progetti
            'numberposts'	=> -1,
            'post_type'		=> 'am_projects',
            'post_status'   => 'publish',		
            'orderby'        => 'menu_order', 
            'order'          => 'ASC', 
        ));

        echo $this->am_projects_filters( $posts );
    
        $htmlStr = "<div id='am-projects-wrap' class='clearfix'>";
    
        $n_elements_column = ceil((count( $posts ) / 3));
    
        $j = 0; //Contatore progetti
        $n_col = 1; //Contatore colonne
    
        foreach($posts as $project) { //Scorro progetti
            $pageTitle = $project->post_title; //Prendo titolo
            $pageLink = get_permalink($project->ID); //Prendo link pagina progetto
            if(!empty(wp_get_attachment_image_src(get_post_thumbnail_id($project->ID), 'full')[0])) {
                $pageImg = wp_get_attachment_image_src(get_post_thumbnail_id($project->ID), 'full')[0]; //Prendo immagine di copertina
            } else {
                $pageImg = '';
            }
    
            $discipl = get_the_terms($project->ID, 'discipline'); //Prendo tag pagine progetti
            $anni = get_the_terms($project->ID, 'anni'); //Prendo tag pagine progetti
            
            if($j == 0 || $j % $n_elements_column == 0) {
                $htmlStr .= '<div class="projects-col n-col-' . $n_col . ' amproj-col-sm-12 amproj-col-md-6 amproj-col-lg-4 amproj-col-xl-4">'; 
    
                $n_col++;
            }
            
            $htmlStr .= '<div class="amproj-inner';
            
            //Stampo taxonomies come classi per filtro front-end
            if( (isset($discipl) && (!empty($discipl))) ) {
                for($i = 0; $i < count($discipl); $i++) { //Scorro array discipline
                    if($discipl[$i] != null && $discipl[$i] != "")
                        $htmlStr .= ' ' . $discipl[$i]->name; //Inserisco come classe
                }
            }
    
            if( (isset($anni) && (!empty($anni))) ) {
                for($i = 0; $i < count($anni); $i++) { //Scorro array anni
                    if($anni[$i] != null && $anni[$i] != "")
                        $htmlStr .= ' ' . $anni[$i]->name; //Inserisco come classe
                }
            }
            
            if($pageImg != '') {
                $htmlStr .= '"><div class="amproj-thumbnail"> <a href="' . $pageLink . '" class="amproj-thumbnail-link"><img class="amproj-thumbnail-img" src="' . $pageImg . '" data-src="' . $pageImg . '" alt="' . $pageTitle . '"></a></div>'; 
            }
    
            $htmlStr .= '<div class="amproj-content-wrap"><a href="' . $pageLink . '" title="' . $pageTitle . '"><span class="amproj-title">' . $pageTitle . '</span></a></div></div>';
    
            if(($j + 1) % $n_elements_column == 0) {
                $htmlStr .= '</div>';
            }
    
            $j++;
        }
        
        $htmlStr .= '</div>';
        return $htmlStr;
    }

    public function am_projects_filters( $projects ) {
        $htmlStr = '<div id="am-projects-filter">'; //Inizializzo stringa
        $htmlStr .= '<ul class="projects-filter all-disc">';
        
        $disc_array = []; //Array taxonomy discipline
        $years_array = []; //Array taxonomy anni

        foreach($projects as $project) { //Scorro progetti
            $discipline = get_the_terms($project->ID, 'discipline'); //Prendo tag pagine progetti

            if(isset($discipline) && is_array($discipline)) { //Se ancora non sono stati aggiunti nell'array
                for($i = 0; $i < count($discipline); $i++) {
                    if(($discipline[$i]->name != 'Projects') && (!(in_array($discipline[$i]->name, $disc_array)))) {
                        $disc_array[] = $discipline[$i]->name; //Aggiungo all'array
                        $htmlStr .= '<li class="project-filter discipline '. $discipline[$i]->name . '">' . $discipline[$i]->name . '</li>'; //Concateno a stringa
                    }
                }
            }

            $anni = get_the_terms($project->ID, 'anni'); //Prendo categorie pagine progetti

            if(isset($anni) && is_array($anni)) { //Se ancora non sono stati aggiunti nell'array
                for($i = 0; $i < count($anni); $i++) {
                    if(($anni[$i]->name != 'Projects') && (!(in_array($anni[$i]->name, $years_array)))) {
                        $years_array[] = $anni[$i]->name; //Aggiungo all'array
                    }
                }
            }
        }

        $htmlStr .= '</ul><ul class="projects-filter all-years">';

        sort($years_array); //Ordino array creato

        for($i = 0; $i < count($years_array); $i++) {
            $htmlStr .= '<li class="project-filter anni '. $years_array[$i] . '">' . $years_array[$i] . '</li>'; //Concateno a stringa
        }


        $htmlStr .= '</ul></div>';

        return $htmlStr;
    }
}