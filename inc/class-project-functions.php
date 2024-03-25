<?php

/**
 * Classe che contiene metodi riguardanti i progetti
 */

class AmProjectFunctions
{
    public function __construct()
    {
        $this->set_hooks();
    }

    protected function set_hooks()
    {
        add_shortcode('am_projects_shortcode', [$this, 'am_projects_html']);
    }

    public function am_projects_html($atts = [])
    {
        //Recupero, se presente, le categorie da filtrare
        $category_filter = isset($atts['am_discipline']) ? $atts['am_discipline'] : null;

        //Parametri Query
        $query_args = [
            'numberposts'   => -1,
            'post_type'     => 'am_projects',
            'post_status'   => 'publish',
            'orderby'       => 'menu_order',
            'order'         => 'ASC',
        ];

        if ($category_filter !== null)
            $query_args['tax_query'] = [
                [
                    [
                        'taxonomy' => 'am_discipline',
                        'field' => 'name',
                        'terms' => $category_filter,
                    ]
                ]
            ];

        //Prendo pagine progetti
        $posts = get_posts($query_args);

        $layout = get_option('layout');
        $n_columns_user = get_option('n_columns');
        $lazyload_value = get_option('lazyload_bool');

        //Stampo filtri
        if (get_option('filters_activated') === '1') {
            $htmlStr = $this->am_projects_filters($posts);
        }

        if ($layout === 'grid') {
            $htmlStr .= $this->create_grid_layout_projects($posts, $n_columns_user, $lazyload_value);
        } else {
            $htmlStr .= $this->create_column_layout_projects($posts, $n_columns_user, $lazyload_value);
        }

        return $htmlStr;
    }

    public function create_grid_layout_projects($projects, $n_row_user, $lazyload)
    {
        $htmlStr = "<div id='am-projects-wrap' class='flex-layout amproj-row-" . $n_row_user . "'>";

        foreach ($projects as $project) { //Scorro progetti
            $pageTitle = $project->post_title; //Prendo titolo
            $pageLink = get_permalink($project->ID); //Prendo link pagina progetto
            if (!empty(wp_get_attachment_image_src(get_post_thumbnail_id($project->ID), 'full')[0])) {
                $pageImg = wp_get_attachment_image_src(get_post_thumbnail_id($project->ID), 'full')[0]; //Prendo immagine di copertina
            } else {
                $pageImg = '';
            }

            $discipl = get_the_terms($project->ID, 'am_discipline'); //Prendo tag pagine progetti
            $am_anni = get_the_terms($project->ID, 'am_anni'); //Prendo tag pagine progetti

            //Salvo in variabili i valori del custom metabox
            $can_open_project = get_post_meta($project->ID, 'no-link-select', true);

            $htmlStr .= "<div class='amproj-el'>";
            $htmlStr .= '<div class="amproj-inner';

            //Stampo taxonomies come classi per filtro front-end
            if ((isset($discipl) && (!empty($discipl)))) {
                for ($i = 0; $i < count($discipl); $i++) { //Scorro array am_discipline
                    if ($discipl[$i] != null && $discipl[$i] != "")
                        $htmlStr .= ' ' . $discipl[$i]->name; //Inserisco come classe
                }
            }

            if ((isset($am_anni) && (!empty($am_anni)))) {
                for ($i = 0; $i < count($am_anni); $i++) { //Scorro array am_anni
                    if ($am_anni[$i] != null && $am_anni[$i] != "")
                        $htmlStr .= ' ' . $am_anni[$i]->name; //Inserisco come classe
                }
            }

            if ($can_open_project != 'false') {
                if ($pageImg != '') {
                    if ($lazyload == 'true') {
                        $htmlStr .= '"><div class="amproj-thumbnail"> <a href="' . $pageLink . '" class="amproj-thumbnail-link"><img class="amproj-lazyload amproj-thumbnail-img" data-src="' . $pageImg . '" alt="' . $pageTitle . '" style="height:' . get_option('img_height') . 'px"></a></div>';
                    } else {
                        $htmlStr .= '"><div class="amproj-thumbnail"> <a href="' . $pageLink . '" class="amproj-thumbnail-link"><img class="amproj-thumbnail-img" src="' . $pageImg . '" data-src="' . $pageImg . '" alt="' . $pageTitle . '" style="height:' . get_option('img_height') . 'px"></a></div>';
                    }
                } else {
                    $htmlStr .= '">';
                }

                $htmlStr .= '<div class="amproj-content-wrap clickable"><a href="' . $pageLink . '" title="' . $pageTitle . '"><span class="amproj-title">' . $pageTitle . '</span></a></div></div>';
            } else {
                $info_1 = get_post_meta($project->ID, 'proj-info-1', true);
                $info_2 = get_post_meta($project->ID, 'proj-info-2', true);
                $proj_year = get_post_meta($project->ID, 'proj-year', true);
                $spotlight = get_post_meta($project->ID, 'proj-spotlight-switch', true);

                if ($pageImg != '') {
                    if ($lazyload == 'true') {
                        $htmlStr .= '"><div class="amproj-thumbnail"> <a href="' . $pageLink . '" class="amproj-thumbnail-link"><img class="amproj-lazyload amproj-thumbnail-img" data-src="' . $pageImg . '" alt="' . $pageTitle . '" style="height:' . get_option('img_height') . 'px"></a></div>';
                    } else {
                        $htmlStr .= '"><div class="amproj-thumbnail' . ($spotlight == 'true' ? " spotlight" : "") . '"><img class="amproj-thumbnail-img" src="' . $pageImg . '" data-src="' . $pageImg . '" alt="' . $pageTitle . '" style="height:' . get_option('img_height') . 'px"></a></div>';
                    }
                } else {
                    $htmlStr .= '">';
                }

                $htmlStr .= '<div class="amproj-content-wrap no-clickable' . ($spotlight == 'true' ? " spotlight-cursor" : "") . '"><p class="amproj-title"><span>' . $pageTitle . '</span>';

                if (isset($info_1) && !empty($info_1)) {
                    $htmlStr .= '<span>' . $info_1 . '</span>';
                }

                if (isset($info_2) && !empty($info_2)) {
                    $htmlStr .= '<span>' . $info_2 . '</span>';
                }

                if (isset($proj_year) && !empty($proj_year)) {
                    $htmlStr .= '<span>' . $proj_year . '</span>';
                }


                $htmlStr .= '</p></div></div>';
            }

            $htmlStr .= "</div>";
        }

        $htmlStr .= "</div>";

        return $htmlStr;
    }

    public function create_column_layout_projects($projects, $n_columns_user, $lazyload)
    {
        $n_elements_column = ceil((count($projects) / $n_columns_user));

        $j = 0; //Contatore progetti
        $n_col = 1; //Contatore colonne
        $col_count = 0; //Contatore per colonne

        $htmlStr = "<div id='am-projects-wrap' class='clearfix'>";

        foreach ($projects as $project) { //Scorro progetti
            $pageTitle = $project->post_title; //Prendo titolo
            $pageLink = get_permalink($project->ID); //Prendo link pagina progetto
            if (!empty(wp_get_attachment_image_src(get_post_thumbnail_id($project->ID), 'full')[0])) {
                $pageImg = wp_get_attachment_image_src(get_post_thumbnail_id($project->ID), 'full')[0]; //Prendo immagine di copertina
            } else {
                $pageImg = '';
            }

            $discipl = get_the_terms($project->ID, 'am_discipline'); //Prendo tag pagine progetti
            $am_anni = get_the_terms($project->ID, 'am_anni'); //Prendo tag pagine progetti

            //Salvo in variabili i valori del custom metabox
            $can_open_project = get_post_meta($project->ID, 'no-link-select', true);

            if ($j == 0 || ($j % $n_elements_column == 0)) {
                $htmlStr .= '<div class="projects-col n-col-' . $n_col . ' amproj-col-sm-12 amproj-col-md-6 ';

                switch ($n_columns_user) {
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
            if ((isset($discipl) && (!empty($discipl)))) {
                for ($i = 0; $i < count($discipl); $i++) { //Scorro array am_discipline
                    if ($discipl[$i] != null && $discipl[$i] != "")
                        $htmlStr .= ' ' . $discipl[$i]->name; //Inserisco come classe
                }
            }

            if ((isset($am_anni) && (!empty($am_anni)))) {
                for ($i = 0; $i < count($am_anni); $i++) { //Scorro array am_anni
                    if ($am_anni[$i] != null && $am_anni[$i] != "")
                        $htmlStr .= ' ' . $am_anni[$i]->name; //Inserisco come classe
                }
            }

            if ($can_open_project != 'false') {
                if ($pageImg != '') {
                    if ($lazyload == 'true') {
                        if ($col_count < 2) {
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
                $info_1 = get_post_meta($project->ID, 'proj-info-1', true);
                $info_2 = get_post_meta($project->ID, 'proj-info-2', true);
                $proj_year = get_post_meta($project->ID, 'proj-year', true);
                $spotlight = get_post_meta($project->ID, 'proj-spotlight-switch', true);

                if ($pageImg != '') {
                    if ($lazyload == 'true') {
                        if ($col_count < 2) {
                            $htmlStr .= '"><div class="amproj-thumbnail' . ($spotlight == 'true' ? " spotlight" : "") . '"><img class="amproj-thumbnail-img" src="' . $pageImg . '" data-src="' . $pageImg . '" alt="' . $pageTitle . '"></a></div>';
                        } else {
                            $htmlStr .= '"><div class="amproj-thumbnail"> <a href="' . $pageLink . '" class="amproj-thumbnail-link"><img class="amproj-lazyload amproj-thumbnail-img" data-src="' . $pageImg . '" alt="' . $pageTitle . '"></a></div>';
                        }
                    } else {
                        $htmlStr .= '"><div class="amproj-thumbnail' . ($spotlight == 'true' ? " spotlight" : "") . '"><img class="amproj-thumbnail-img" src="' . $pageImg . '" data-src="' . $pageImg . '" alt="' . $pageTitle . '"></a></div>';
                    }
                }

                $htmlStr .= '<div class="amproj-content-wrap no-clickable' . ($spotlight == 'true' ? " spotlight-cursor" : "") . '"><p class="amproj-title"><span>' . $pageTitle . '</span>';

                if (isset($info_1) && !empty($info_1)) {
                    $htmlStr .= '<span>' . $info_1 . '</span>';
                }

                if (isset($info_2) && !empty($info_2)) {
                    $htmlStr .= '<span>' . $info_2 . '</span>';
                }

                if (isset($proj_year) && !empty($proj_year)) {
                    $htmlStr .= '<span>' . $proj_year . '</span>';
                }


                $htmlStr .= '</p></div></div>';
            }

            if (($j + 1) % $n_elements_column == 0) {
                $htmlStr .= '</div>';
            }

            $col_count++;
            $j++;
        }

        $htmlStr .= '</div>';
        return $htmlStr;
    }

    public function am_projects_filters($projects)
    {
        $htmlStr = '<div id="am-projects-filter">'; //Inizializzo stringa
        $htmlStr .= '<ul class="projects-filter all-disc">';

        $disc_array = []; //Array taxonomy am_discipline
        $years_array = []; //Array taxonomy am_anni

        foreach ($projects as $project) { //Scorro progetti
            $am_discipline = get_the_terms($project->ID, 'am_discipline'); //Prendo tag pagine progetti

            if (isset($am_discipline) && is_array($am_discipline)) { //Se ancora non sono stati aggiunti nell'array
                for ($i = 0; $i < count($am_discipline); $i++) {
                    if (($am_discipline[$i]->name != 'Projects') && (!(in_array($am_discipline[$i]->name, $disc_array)))) {
                        $disc_array[] = $am_discipline[$i]->name; //Aggiungo all'array
                        $htmlStr .= '<li class="project-filter am_discipline ' . $am_discipline[$i]->name . '">' . $am_discipline[$i]->name . '</li>'; //Concateno a stringa
                    }
                }
            }

            $am_anni = get_the_terms($project->ID, 'am_anni'); //Prendo categorie pagine progetti

            if (isset($am_anni) && is_array($am_anni)) { //Se ancora non sono stati aggiunti nell'array
                for ($i = 0; $i < count($am_anni); $i++) {
                    if (($am_anni[$i]->name != 'Projects') && (!(in_array($am_anni[$i]->name, $years_array)))) {
                        $years_array[] = $am_anni[$i]->name; //Aggiungo all'array
                    }
                }
            }
        }

        $htmlStr .= '</ul><ul class="projects-filter all-years">';

        sort($years_array); //Ordino array creato

        for ($i = 0; $i < count($years_array); $i++) {
            $htmlStr .= '<li class="project-filter am_anni ' . $years_array[$i] . '">' . $years_array[$i] . '</li>'; //Concateno a stringa
        }


        $htmlStr .= '</ul></div>';

        return $htmlStr;
    }
}
