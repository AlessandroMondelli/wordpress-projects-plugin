<?php
/**
 * Classe che crea contiene metodi riguardanti i progetti
 */

class AmProjectFunctions {
    public function __construct() {
        $this->set_hooks();
    }

    protected function set_hooks() {
        add_action( 'init', [$this, 'am_register_projects' ] );
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
            'rewrite'     => array( 'slug' => 'projects', 'with_front' => false, ),
            'supports' => array( 'title', 'editor', 'author', 'thumbnail', 
            'page-attributes'),
            )
        );

        flush_rewrite_rules();
    }

    public function am_projects_html() {
        $posts = get_posts(array( //Prendo pagine progetti
            'numberposts'	=> -1,
            'post_type'		=> 'am_projects',
            'post_status'   => 'publish',		
            'orderby'        => 'menu_order', 
            'order'          => 'ASC', 
        ));
    
        $htmlStr = "";
    
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
                $htmlStr .= '<div class="custom-posts__item post projects-col n-col-' . $n_col . ' col-sm-12 col-md-6 col-lg-4 col-xl-4 clearfix">'; 
    
                $n_col++;
            }
            
            $htmlStr .= '<div class="post-inner';
            
            //@TODO Aggiungere taxonomies per creare filtri front-end
            // if( (isset($discipl) && (!empty($discipl))) ) {
            //     for($i = 0; $i < count($discipl); $i++) { //Scorro array discipline
            //         if($discipl[$i] != null && $discipl[$i] != "")
            //             $htmlStr .= ' ' . $discipl[$i]->name; //Inserisco come classe
            //     }
            // }
    
            // if( (isset($anni) && (!empty($anni))) ) {
            //     for($i = 0; $i < count($anni); $i++) { //Scorro array anni
            //         if($anni[$i] != null && $anni[$i] != "")
            //             $htmlStr .= ' ' . $anni[$i]->name; //Inserisco come classe
            //     }
            // }
            
            if($pageImg != '') {
                $htmlStr .= '"><div class="post-thumbnail"> <a href="' . $pageLink . '" class="post-thumbnail__link"><img class="lazyload post-thumbnail__img" src="' . $pageImg . '" data-src="' . $pageImg . '" alt="' . $pageTitle . '"></a></div>'; 
            }
    
            $htmlStr .= '<div class="post-content-wrap"><a href="' . $pageLink . '" title="' . $pageTitle . '"><span>' . $pageTitle . '</span></a></div></div>';
    
            if(($j + 1) % $n_elements_column == 0) {
                $htmlStr .= '</div>';
            }
    
            $j++;
        }
    
        return $htmlStr;
    }
}