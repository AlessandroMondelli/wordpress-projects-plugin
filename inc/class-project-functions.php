<?php
/**
 * Classe che contiene metodi riguardanti i progetti
 */

class AmProjectFunctions {
    public function __construct() {
        $this->set_hooks();
    }

    protected function set_hooks() {
        add_shortcode( 'am_projects_shortcode', [ $this, 'am_projects_html' ] );
    }

    public function am_projects_html() {
        $posts = get_posts(array( //Prendo pagine progetti
            'numberposts'	=> -1,
            'post_type'		=> 'am_projects',
            'post_status'   => 'publish',		
            'orderby'        => 'menu_order', 
            'order'          => 'ASC', 
        ));

        echo $this->am_projects_filters( $posts ); //Stampo filtri
    
        $n_columns_user = get_option( 'n_columns' );
        $n_elements_column = ceil((count( $posts ) / $n_columns_user));

        $lazyload_value = get_option('lazyload_bool');
    
        $j = 0; //Contatore progetti
        $n_col = 1; //Contatore colonne
        $col_count = 0; //Contatore per colonne
        
        $htmlStr = "<div id='am-projects-wrap' class='clearfix'>";

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

            //Salvo in variabili i valori del custom metabox
            $can_open_project = get_post_meta( $project->ID, 'no-link-select', true );
            
            if($j == 0 || ( $j % $n_elements_column == 0 )) {
                $htmlStr .= '<div class="projects-col n-col-' . $n_col . ' amproj-col-sm-12 amproj-col-md-6 '; 
                
                switch ( $n_columns_user ) {
                    case 1: 
                        $htmlStr .= 'amproj-col-lg-12 amproj-col-xl-12">';
                    break;
                    case 2: 
                        $htmlStr .= 'amproj-col-lg-6 amproj-col-xl-6">';
                    break;
                    case 3: 
                        $htmlStr .= 'amproj-col-lg-4 amproj-col-xl-4">';
                    break;
                    case 4: 
                        $htmlStr .= 'amproj-col-lg-3 amproj-col-xl-3">';
                    break;
                    case 5: 
                        $htmlStr .= 'amproj-col-lg-2 amproj-col-xl-2">';
                    break;
                    case 6: 
                        $htmlStr .= 'amproj-col-lg-1 amproj-col-xl-1">';
                    break;
                    default:
                        $htmlStr .= 'amproj-col-lg-3 amproj-col-xl-3">';
                }

                $col_count = 0;
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
            
            if( $can_open_project != 'false' ) {
                if( $pageImg != '' ) {
                    if( $lazyload_value == 'true' ) {
                        if( $col_count < 2 ) {
                            $htmlStr .= '"><div class="amproj-thumbnail"> <a href="' . $pageLink . '" class="amproj-thumbnail-link"><img class="amproj-thumbnail-img" src="' . $pageImg . '" data-src="' . $pageImg . '" alt="' . $pageTitle . '"></a></div>'; 
                        } else {
                            $htmlStr .= '"><div class="amproj-thumbnail"> <a href="' . $pageLink . '" class="amproj-thumbnail-link"><img class="amproj-lazyload amproj-thumbnail-img" data-src="' . $pageImg . '" alt="' . $pageTitle . '"></a></div>'; 
                        }
                    } else {
                        $htmlStr .= '"><div class="amproj-thumbnail"> <a href="' . $pageLink . '" class="amproj-thumbnail-link"><img class="amproj-thumbnail-img" src="' . $pageImg . '" data-src="' . $pageImg . '" alt="' . $pageTitle . '"></a></div>'; 
                    }
                    
                }
        
                $htmlStr .= '<div class="amproj-content-wrap clickable"><a href="' . $pageLink . '" title="' . $pageTitle . '"><span class="amproj-title">' . $pageTitle . '</span></a></div></div>';
            } else {
                $info_1 = get_post_meta( $project->ID, 'proj-info-1', true );
                $info_2 = get_post_meta( $project->ID, 'proj-info-2', true );
                $proj_year = get_post_meta( $project->ID, 'proj-year', true );
                $spotlight = get_post_meta( $project->ID, 'proj-spotlight-switch', true );

                if($pageImg != '') {
                    if( $lazyload_value == 'true' ) {
                        if( $col_count < 2 ) {
                            $htmlStr .= '"><div class="amproj-thumbnail' . ( $spotlight == 'true' ? " spotlight" : "" ) . '"><img class="amproj-thumbnail-img" src="' . $pageImg . '" data-src="' . $pageImg . '" alt="' . $pageTitle . '"></a></div>';
                        } else {
                            $htmlStr .= '"><div class="amproj-thumbnail"> <a href="' . $pageLink . '" class="amproj-thumbnail-link"><img class="amproj-lazyload amproj-thumbnail-img" data-src="' . $pageImg . '" alt="' . $pageTitle . '"></a></div>'; 
                        }
                    } else {
                        $htmlStr .= '"><div class="amproj-thumbnail' . ( $spotlight == 'true' ? " spotlight" : "" ) . '"><img class="amproj-thumbnail-img" src="' . $pageImg . '" data-src="' . $pageImg . '" alt="' . $pageTitle . '"></a></div>';
                    }
 
                }

                $htmlStr .= '<div class="amproj-content-wrap no-clickable' . ( $spotlight == 'true' ? " spotlight-cursor" : "" ) . '"><p class="amproj-title"><span>' . $pageTitle . '</span>';

                if(isset( $info_1 ) && !empty( $info_1 )) {
                    $htmlStr .= '<span>' . $info_1 . '</span>';
                }
    
                if(isset( $info_2 ) && !empty( $info_2 )) {
                    $htmlStr .= '<span>' . $info_2 . '</span>';
                }
    
                if(isset( $proj_year ) && !empty( $proj_year )) {
                    $htmlStr .= '<span>' . $proj_year . '</span>';
                }


                $htmlStr .= '</p></div></div>';
            }
    
            if(($j + 1) % $n_elements_column == 0) {
                $htmlStr .= '</div>';
            }
            
            $col_count++;
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