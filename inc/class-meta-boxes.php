<?php

/**
 * Classe che contiene meta boxes personalizzate
 */

class AmProjectMetaBoxes
{
    public function __construct()
    {
        $this->set_hooks();
    }

    protected function set_hooks()
    {
        add_action('add_meta_boxes', [$this, 'am_projects_add_metaboxes']);
        
        add_action('save_post_am_projects', [$this, 'am_save_project_subtitle'], 10, 3);
        add_action('save_post_am_projects', [$this, 'am_save_no_link_proj_box'], 10, 3);
        add_action('save_post_am_projects', [$this, 'am_save_projects_details'], 10, 3);
        add_action('save_post_am_projects', [$this, 'am_save_secondary_image'], 10, 3);
        add_action('save_post_am_projects', [$this, 'am_save_projects_gallery'], 10, 3);
        add_action('save_post_am_projects', [$this, 'am_save_icons_tooltip'], 10, 3);
    }

    //Creo nuovo metabox
    public function am_projects_add_metaboxes()
    {
        add_meta_box('am-project-seceondary-image', 'Immagine Secondaria', [$this, 'am_secondary_image']);
        add_meta_box('am-project-subtitle-box', 'Sottotitolo', [$this, 'am_project_subtitle_markup']);
        add_meta_box('no-link-proj-box', 'Nascondi link progetto', [$this, 'am_no_link_proj_box_markup'], 'am_projects');
        add_meta_box('projects-details', 'Dettagli Progetto', [$this, 'am_projects_details'], 'am_projects');
        add_meta_box('projects-gallery', 'Galleria Progetto', [$this, 'am_gallery_markup'], 'am_projects');
        add_meta_box('projects-icons-tooltip', 'Icone con tooltip', [$this, 'am_icons_tooltip'], 'am_projects');
    }

    //Markup metabox no link
    public function am_project_subtitle_markup($post)
    {
        //Creo nonce
        wp_nonce_field('am_project_subtitle_action', 'am_project_subtitle_nonce');

        //Prendo valore salvato nel database
        $subtitle = get_post_meta($post->ID, 'am-project-subtitle', true);
    ?>
        <div id="am-project-subtitle">
            <label for="am-project-subtitle">Sottotitolo progetto</label>
            <input name="am-project-subtitle" type="text" id="am-project-subtitle" value="<?php echo $subtitle ?>" style="display:block; margin-top:10px">
        </div>
    <?php
    }

    //Markup metabox no link
    public function am_no_link_proj_box_markup($post)
    {
        //Creo nonce
        wp_nonce_field('am_no_link_metabox_action', 'am_no_link_metabox_nonce');

        //Prendo valore salvato nel database
        $no_link_value = get_post_meta($post->ID, 'no-link-select', true);
    ?>
        <div id="no-link-box">
            <label for="no-link-select" style="display:block; font-weight:bold;">Seleziona interazione progetto</label>
            <select name="no-link-select" id="no-link-select" style="margin-top: 10px">
                <option value="true" <?php selected($no_link_value, 'true'); ?>>Cliccabile</option>
                <option value="false" <?php selected($no_link_value, 'false'); ?>>Non cliccabile</option>
            </select>
        </div>
        <?php
        //Se il progetto è settato come non cliccabile
        if ($no_link_value == 'false') {
            //Cerco campi extra
            $proj_info_1 = get_post_meta($post->ID, "proj-info-1", true);
            $proj_info_2 = get_post_meta($post->ID, "proj-info-2", true);
            $proj_year = get_post_meta($post->ID, "proj-year", true);
            $proj_spotlight = get_post_meta($post->ID, "proj-spotlight-switch", true);

        ?>
            <div class="proj-extra-data-wrap" style="margin: 10px 0">
                <label for="proj-info-1" style="font-weight:bold;">Inserisci prima riga info</label>
                <input name="proj-info-1" type="text" id="proj-info-1" value="<?php echo $proj_info_1 != '' ? $proj_info_1 : '' ?>" style="display:block; margin-top:10px">
            </div>
            <div class="proj-extra-data-wrap" style="margin: 10px 0">
                <label for="proj-info-2" style="font-weight:bold;">Inserisci seconda riga info</label>
                <input name="proj-info-2" type="text" id="proj-info-2" value="<?php echo $proj_info_2 != '' ? $proj_info_2 : '' ?>" style="display:block; margin-top:10px">
            </div>
            <div class="proj-extra-data-wrap" style="margin: 10px 0">
                <label for="proj-year" style="font-weight:bold;">Inserisci anno</label>
                <input name="proj-year" type="text" id="proj-year" value="<?php echo $proj_year != '' ? $proj_year : '' ?>" style="display:block; margin-top:10px">
            </div>
            <div class="proj-extra-data-wrap" style="margin: 20px 0">
                <label for="proj-spotlight-switch" style="font-weight:bold;">Lightbox con click</label>
                <select name="proj-spotlight-switch" id="proj-spotlight-switch" style="display:block; margin-top:10px">
                    <option value="true" <?php selected($proj_spotlight, 'true'); ?>>Attivo</option>
                    <option value="false" <?php selected($proj_spotlight, 'false'); ?>>Disattivato</option>
                </select>
            </div>

    <?php
        }
    }

    //Markup metabox dettagli progetto
    public function am_projects_details($post) 
    {
        //Creo nonce
        wp_nonce_field('am_projects_details_metabox_action', 'am_projects_details_metabox_nonce');

        //Recupero dati da db
        $committente = get_post_meta($post->ID, "projects-details-committente", true);
        $cliente = get_post_meta($post->ID, "projects-details-cliente", true);
        $importo_lavori = get_post_meta($post->ID, "projects-details-importo-lavori", true);
        $anno = get_post_meta($post->ID, "projects-details-anno", true);
        $luogo = get_post_meta($post->ID, "projects-details-luogo", true);
        $fase = get_post_meta($post->ID, "projects-details-fase", true);
        $discipline = get_post_meta($post->ID, "projects-details-discipline", true);
        $complessita = get_post_meta($post->ID, "projects-details-complessita", true);
        $innovazione = get_post_meta($post->ID, "projects-details-innovazione", true);
    ?>
    <div class="projects-details-box-wrapper" style="display: flex; flex-direction: column;">
        <div id="projects-details-box" style="margin-top: 10px;">
            <label for="projects-details-committente" style="font-weight:bold">Committente</label>
            <input name="projects-details-committente" id="projects-details-committente" type="text" style="display: block; margin-top: 10px;" value="<?php echo $committente ?>">
        </div>
        <div id="projects-details-box" style="margin-top: 10px;">
            <label for="projects-details-cliente" style="font-weight:bold">Cliente</label>
            <input name="projects-details-cliente" id="projects-details-cliente" type="text" style="display: block; margin-top: 10px" value="<?php echo $cliente ?>">
        </div>
        <div id="projects-details-box" style="margin-top: 10px;">
            <label for="projects-details-importo-lavori" style="font-weight:bold">Importo lavori</label>
            <input name="projects-details-importo-lavori" id="projects-details-importo-lavori" type="text" style="display: block; margin-top: 10px" value="<?php echo $importo_lavori ?>">
        </div>
        <div id="projects-details-box" style="margin-top: 10px;">
            <label for="projects-details-anno" style="font-weight:bold">Anno</label>
            <input name="projects-details-anno" id="projects-details-anno" type="number" style="display: block; margin-top: 10px" value="<?php echo $anno ?>">
        </div>
        <div id="projects-details-box" style="margin-top: 10px;">
            <label for="projects-details-luogo" style="font-weight:bold">Luogo</label>
            <input name="projects-details-luogo" id="projects-details-luogo" type="text" style="display: block; margin-top: 10px" value="<?php echo $luogo ?>">
        </div>
        <div id="projects-details-box" style="margin-top: 10px;">
            <label for="projects-details-fase" style="font-weight:bold">Fase</label>
            <input name="projects-details-fase" id="projects-details-fase" type="text" style="display: block; margin-top: 10px" value="<?php echo $fase ?>">
        </div>
        <div id="projects-details-box" style="margin-top: 10px;">
            <label for="projects-details-discipline" style="font-weight:bold">Discipline</label>
            <input name="projects-details-discipline" id="projects-details-discipline" type="text" style="display: block; margin-top: 10px" value="<?php echo $discipline ?>">
        </div>
        <div id="projects-details-box" style="margin-top: 10px;">
            <label for="projects-details-complessita" style="font-weight:bold">Complessità (%)</label>
            <input name="projects-details-complessita" id="projects-details-complessita" type="number" min="0" max="100" style="display: block; margin-top: 10px" value="<?php echo $complessita ?>">
        </div>
        <div id="projects-details-box" style="margin-top: 10px;">
            <label for="projects-details-innovazione" style="font-weight:bold">Innovazione (%)</label>
            <input name="projects-details-innovazione" id="projects-details-innovazione" type="number" min="0" max="100" style="display: block; margin-top: 10px" value="<?php echo $innovazione ?>">
        </div>
    </div>
    <?php
    }

    public function am_secondary_image($post) 
    {
        //Creo nonce
        wp_nonce_field('am_projects_secondary_image_action', 'am_projects_secondary_image_nonce');
        
        //Recupero immagine da db
        $secondary_image = get_post_meta($post->ID, 'am-project-secondary-image', true);    
        ?>
        <a href="#" id="am_project_secondary_image" class="button" style="margin-top:25px">Seleziona Immagine</a>

        <div class="am-secondary-image-current am-images-container">
            <p style="margin-top:25px; font-weight:bold;">Immagine secondaria</p>
            <div class="am-secondary-image-preview" style="margin-top: 15px; max-width: 250px;">
            <?php
                if($secondary_image != "") {
            ?>
                    <div class="am-secondary-image img-preview-el" data-id="<?php echo $secondary_image ?>" style="position:relative">
                    <img class="remove-el" style="position:absolute; width: 35px; top: -1.5rem; right: -2rem; z-index: 99; cursor: pointer" src="<?php echo PLUGIN_URL . 'template/media/remove-icon.svg' ?>">
                    <img src="<?php echo wp_get_attachment_url($secondary_image) ?>" style="max-width: 250px;" />
                </div>
            <?php
                }
            ?>
            </div>
        </div>
        
        <input type="hidden" name="am-project-secondary-image" id="am-project-secondary-image" class="projects-images-hidden-input" value="<?php echo esc_attr($secondary_image)?>" />
        <?php
    }

    public function am_gallery_markup($post) 
    {
        //Creo nonce
        wp_nonce_field('am_projects_gallery_metabox_action', 'am_projects_gallery_metabox_nonce');
        
        //Recupero immagini da db
        $media_ids = get_post_meta($post->ID, 'projects-gallery-images', true);
        $media_ids_array = !empty($media_ids) ? explode(',', $media_ids) : array();

        //Recupero auto scroll
        $auto_scroll = get_post_meta($post->ID, 'projects-auto-scroll', true);
        ?>
        <a href="#" id="am_gallery_button" class="button" style="margin-top:25px">Seleziona Immagini</a>
        <label for="projects-auto-scroll" style="display:block; font-weight:bold; margin-top:25px">Secondi Auto Scroll (0 per disattivare)</label>
        <input type="number" step="0.1" min="0" max="10" name="projects-auto-scroll" id="projects-auto-scroll" style="margin-top:10px" value="<?php echo $auto_scroll != "" ? $auto_scroll : 0 ?>">

        <div class="am-gallery-current am-images-container">
            <p style="margin-top:25px; font-weight:bold;">Immagini presenti nella galleria</p>
            <div class="am-gallery-preview" style="display: flex; flex-wrap: wrap; gap: 50px; margin-top: 15px">
            <?php
                //Stampa preview icone con tooltip
                foreach ($media_ids_array as $media_id) {
                ?>
                    <div class="gallery-el img-preview-el draggable" data-id="<?php echo $media_id ?>" style="position:relative">
                        <img class="remove-el" style="position:absolute; width: 35px; top: -1.5rem; right: -2rem; z-index: 99; cursor: pointer" src="<?php echo PLUGIN_URL . 'template/media/remove-icon.svg' ?>">
                        <img src="<?php echo wp_get_attachment_url($media_id) ?>" style="max-width: 250px;" />
                    </div>
                <?php
                }
            ?>
            </div>
        </div>

        <input type="hidden" name="projects-gallery-images" id="projects-gallery-images" class="projects-images-hidden-input" value="<?php echo esc_attr($media_ids)?>" />
        <?php
    }

    public function am_icons_tooltip($post) 
    {
        //Creo nonce
        wp_nonce_field('am_projects_icons_tooltip_metabox_action', 'am_projects_gallery_metabox_nonce');

        //Recupero immagini da db
        $icons_ids = get_post_meta($post->ID, 'projects-icons-tooltip-input', true);
        $icons_ids_array = !empty($icons_ids) ? explode(',', $icons_ids) : array();

        $icons_ids_active = get_post_meta($post->ID, 'projects-icons-active', true);
        $icons_ids_active_array = !empty($icons_ids_active) ? explode(',', $icons_ids_active) : array();
        ?>
         <a href="#" id="am_icons_tooltip_button" class="button" style="margin-top:25px">Seleziona Icone</a>

        <div class="am-icons-tooltip-current am-images-container">
            <p style="margin-top:25px; font-weight:bold;">Icone presenti</p>
            <div class="am-icons-tooltip-preview" style="display: flex; flex-wrap: wrap; gap: 50px; margin-top: 15px">
            <?php
                //Stampa preview icone con tooltip
                foreach ($icons_ids_array as $icon_id) {
                ?>
                    <div class="am-icons-tooltip-preview-el img-preview-el draggable" data-id="<?php echo $icon_id ?>" style="position:relative">
                        <img class="remove-el" style="position:absolute; width: 35px; top: -1.5rem; right: -2rem; z-index: 99; cursor: pointer" src="<?php echo PLUGIN_URL . 'template/media/remove-icon.svg' ?>">
                        <img src="<?php echo wp_get_attachment_url($icon_id) ?>" style="max-width: 250px; <?php echo in_array($icon_id, $icons_ids_active_array) ? "filter:contrast(1);" : "filter:contrast(0);" ?> cursor: pointer" />
                    </div>
                <?php
                }
            ?>
            </div>
        </div>

        <input type="hidden" name="projects-icons-tooltip-input" id="projects-icons-tooltip-input" class="projects-images-hidden-input" value="<?php echo esc_attr($icons_ids)?>" />
        <input type="hidden" name="projects-icons-active" id="projects-icons-active" value="<?php echo esc_attr($icons_ids_active)?>" />
    <?php
    }

    //Metodo per salvare nel database i dati presi dal metabox
    public function am_save_project_subtitle($post_id, $post, $update) 
    {
        //Controllo prima di inviare i dati...
        //Se l'utente è abilitato a modificare i post
        if (!current_user_can('edit_post', $post_id))

            //Se il salvataggio non sia un autosalvataggio di WP
            if (defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
                return $post_id;

        //Se il custom post type è esatto
        $cpt_check = "am_projects";
        if ($cpt_check != $post->post_type)
            return $post_id;

        if (is_null($_POST['am_project_subtitle_nonce']))
            return $post_id;

        //Controllo Nonce
        if ($update && !wp_verify_nonce($_POST['am_project_subtitle_nonce'], 'am_project_subtitle_action')) {
            echo 'Errore, Nonce non verificato';
            exit;
        }

        //Procedo al salvataggio dei dati
        $subtitle_value = '';
        if (isset($_POST['am-project-subtitle'])) {
            $subtitle_value = sanitize_text_field($_POST['am-project-subtitle']);

            update_post_meta($post->ID, 'am-project-subtitle', $subtitle_value);
        }
    }

    //Metodo per salvare nel database i dati presi dal metabox
    public function am_save_no_link_proj_box($post_id, $post, $update) 
    {
        //Controllo prima di inviare i dati...
        //Se l'utente è abilitato a modificare i post
        if (!current_user_can('edit_post', $post_id))

            //Se il salvataggio non sia un autosalvataggio di WP
            if (defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
                return $post_id;

        //Se il custom post type è esatto
        $cpt_check = "am_projects";
        if ($cpt_check != $post->post_type)
            return $post_id;

        if (is_null($_POST['am_no_link_metabox_nonce']))
            return $post_id;

        //Controllo Nonce
        if ($update && !wp_verify_nonce($_POST['am_no_link_metabox_nonce'], 'am_no_link_metabox_action')) {
            echo 'Errore, Nonce non verificato';
            exit;
        }

        //Procedo al salvataggio dei dati
        $no_link_select_value = '';
        if (isset($_POST['no-link-select'])) {
            $no_link_select_value = $_POST['no-link-select'];

            update_post_meta($post_id, 'no-link-select', $no_link_select_value);
        }

        if ($no_link_select_value == 'false') {
            $info_1 = '';
            if (isset($_POST['proj-info-1'])) {
                $info_1 = sanitize_text_field($_POST['proj-info-1']);

                update_post_meta($post_id, 'proj-info-1', $info_1);
            }

            $info_2 = '';
            if (isset($_POST['proj-info-2'])) {
                $info_2 = sanitize_text_field($_POST['proj-info-2']);

                update_post_meta($post_id, 'proj-info-2', $info_2);
            }

            $proj_year = '';
            if (isset($_POST['proj-year'])) {
                $proj_year = sanitize_text_field($_POST['proj-year']);

                update_post_meta($post_id, 'proj-year', $proj_year);
            }

            $proj_spotlight = '';
            if (isset($_POST['proj-spotlight-switch'])) {
                $proj_spotlight = $_POST['proj-spotlight-switch'];

                update_post_meta($post_id, 'proj-spotlight-switch', $proj_spotlight);
            }
        }
    }

    //Metodo per salvare nel database i dettagli del progetto
    public function am_save_projects_details($post_id, $post, $update) 
    {
        //Controllo prima di inviare i dati...
        //Se l'utente è abilitato a modificare i post
        if (!current_user_can('edit_post', $post_id))

            //Se il salvataggio non sia un autosalvataggio di WP
            if (defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
                return $post_id;

        //Se il custom post type è esatto
        $cpt_check = "am_projects";
        if ($cpt_check != $post->post_type)
            return $post_id;             

        if (is_null($_POST['am_no_link_metabox_nonce']))
            return $post_id;

        //Controllo Nonce
        if ($update && !wp_verify_nonce($_POST['am_projects_details_metabox_nonce'], 'am_projects_details_metabox_action')) {
            echo 'Errore, Nonce non verificato';
            exit;
        }

        $committente = '';
        if (isset($_POST['projects-details-committente'])) {
            $committente = $_POST['projects-details-committente'];

            update_post_meta($post_id, 'projects-details-committente', $committente);
        }

        $cliente = '';
        if (isset($_POST['projects-details-cliente'])) {
            $cliente = $_POST['projects-details-cliente'];

            update_post_meta($post_id, 'projects-details-cliente', $cliente);
        }

        $importo_lavori = '';
        if (isset($_POST['projects-details-importo-lavori'])) {
            $importo_lavori = $_POST['projects-details-importo-lavori'];

            update_post_meta($post_id, 'projects-details-importo-lavori', $importo_lavori);
        }

        $anno = 0;
        if (isset($_POST['projects-details-anno'])) {
            $anno = $_POST['projects-details-anno'];

            update_post_meta($post_id, 'projects-details-anno', $anno);
        }

        $luogo = '';
        if (isset($_POST['projects-details-luogo'])) {
            $luogo = $_POST['projects-details-luogo'];

            update_post_meta($post_id, 'projects-details-luogo', $luogo);
        }

        $fase = '';
        if (isset($_POST['projects-details-fase'])) {
            $fase = $_POST['projects-details-fase'];

            update_post_meta($post_id, 'projects-details-fase', $fase);
        }

        $discipline = '';
        if (isset($_POST['projects-details-discipline'])) {
            $discipline = $_POST['projects-details-discipline'];

            update_post_meta($post_id, 'projects-details-discipline', $discipline);
        }

        $complessita = 0;
        if (isset($_POST['projects-details-complessita'])) {
            $complessita = $_POST['projects-details-complessita'];

            update_post_meta($post_id, 'projects-details-complessita', $complessita);
        }

        $innovazione = 0;
        if (isset($_POST['projects-details-innovazione'])) {
            $innovazione = $_POST['projects-details-innovazione'];

            update_post_meta($post_id, 'projects-details-innovazione', $innovazione);
        }
    }

    //Salvataggio scelta galleria immagini
    function am_save_secondary_image($post_id) 
    {    
        if (isset($_POST['am-project-secondary-image'])) {
            $secondary_image = sanitize_text_field($_POST['am-project-secondary-image']); 
            update_post_meta($post_id, 'am-project-secondary-image', $secondary_image);
        }
    }

    //Salvataggio scelta galleria immagini
    function am_save_projects_gallery($post_id) 
    {    
        if (isset($_POST['projects-gallery-images'])) {
            $media_ids = sanitize_text_field($_POST['projects-gallery-images']); 
            update_post_meta($post_id, 'projects-gallery-images', $media_ids);
        }

        if(isset($_POST['projects-auto-scroll'])) {
            $auto_scroll = $_POST['projects-auto-scroll'];
            update_post_meta($post_id, 'projects-auto-scroll', $auto_scroll);
        }
    }

    //Salvataggio icone con tooltip
    function am_save_icons_tooltip($post_id) 
    {    
        if (isset($_POST['projects-icons-tooltip-input'])) {
            $tooltips_ids = sanitize_text_field($_POST['projects-icons-tooltip-input']); 
            update_post_meta($post_id, 'projects-icons-tooltip-input', $tooltips_ids);
        }

        if (isset($_POST['projects-icons-active'])) {
            $active_icons_ids = sanitize_text_field($_POST['projects-icons-active']); 
            update_post_meta($post_id, 'projects-icons-active', $active_icons_ids);
        }
    }
}
