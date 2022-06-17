<?php
/**
 * Classe che contiene meta boxes personalizzate
 */

class AmProjectMetaBoxes {
    public function __construct() {
        $this->set_hooks();
    }

    protected function set_hooks() {
        add_action( 'add_meta_boxes', [ $this, 'am_add_no_link_metabox' ] );

        add_action( 'save_post_am_projects', [ $this, 'am_save_no_link_proj_box' ], 10, 3 );
    }

    //Creo nuovo metabox
    public function am_add_no_link_metabox() {
        add_meta_box( 'no-link-proj-box', 'Nascondi link progetto', [ $this, 'am_no_link_proj_box_markup' ], 'am_projects' ); 
    }

    //Markup metabox
    public function am_no_link_proj_box_markup( $post ) {
        //Creo nonce
        wp_nonce_field( 'am_no_link_metabox_action', 'am_no_link_metabox_nonce' );

        //Prendo valore salvato nel database
        $no_link_value = get_post_meta( $post->ID, 'no-link-select', true ); 
        ?>
        <div id="no-link-box">
            <label for="no-link-select" style="font-weight:bold;">Seleziona interazione progetto</label>
            <select name="no-link-select" id="no-link-select" style="margin-top: 10px">
                <option value="true" <?php selected( $no_link_value, 'true' ); ?>>Cliccabile</option>
                <option value="false" <?php selected( $no_link_value, 'false' ); ?>>Non cliccabile</option>
            </select>
        </div>
        <?php
        //Se il progetto è settato come non cliccabile
        if( $no_link_value == 'false' ) {
            //Cerco campi extra
            $proj_info_1 = get_post_meta($post->ID, "proj-info-1", true);
            $proj_info_2 = get_post_meta($post->ID, "proj-info-2", true);
            $proj_year = get_post_meta($post->ID, "proj-year", true);
        ?>
            <div class="proj-extra-data-wrap" style="margin: 10px 0">
                <label for="proj-info-1" style="font-weight:bold;">Inserisci prima riga info</label>
                <input name="proj-info-1" type="text" id="proj-info-1" value="<?php echo $proj_info_1 != '' ? $proj_info_1 : '' ?>">
            </div>
            <div class="proj-extra-data-wrap" style="margin: 10px 0">
                <label for="proj-info-2" style="font-weight:bold;">Inserisci seconda riga info</label>
                <input name="proj-info-2" type="text" id="proj-info-2" value="<?php echo $proj_info_2 != '' ? $proj_info_2 : '' ?>">
            </div>
            <div class="proj-extra-data-wrap" style="margin: 10px 0">
                <label for="proj-year" style="font-weight:bold;">Inserisci anno</label>
                <input name="proj-year" type="text" id="proj-year" value="<?php echo $proj_year != '' ? $proj_year : '' ?>">
            </div>
        <?php
        }
    }

    //Metodo per salvare nel database i dati presi dal metabox
    public function am_save_no_link_proj_box( $post_id, $post, $update ) {
        //Controllo prima di inviare i dati...
        //Se l'utente è abilitato a modificare i post
        if( !current_user_can( 'edit_post', $post_id ) )

        //Se il salvataggio non sia un autosalvataggio di WP
        if( defined( "DOING_AUTOSAVE" ) && DOING_AUTOSAVE )
            return $post_id;

        //Se il custom post type è esatto
        $cpt_check = "am_projects";
        if( $cpt_check != $post->post_type )
            return $post_id;

        //Controllo Nonce
        if( $update && ! wp_verify_nonce( $_POST[ 'am_no_link_metabox_nonce' ], 'am_no_link_metabox_action' ) ) {
            echo 'Errore, Nonce non verificato';
            exit;
        }

        //Procedo al salvataggio dei dati
        $no_link_select_value = '';
        if( isset( $_POST[ 'no-link-select' ] ) ) {
            $no_link_select_value = $_POST[ 'no-link-select' ];
            
            update_post_meta( $post_id, 'no-link-select', $no_link_select_value );
        }

        if( $no_link_select_value == 'false' ) {
            $info_1 = '';
            if( isset( $_POST[ 'proj-info-1' ] ) ) {
                $info_1 = $_POST[ 'proj-info-1' ];

                update_post_meta( $post_id, 'proj-info-1', $info_1 );
            }

            $info_2 = '';
            if( isset( $_POST[ 'proj-info-2' ] ) ) {
                $info_2 = $_POST[ 'proj-info-2' ];

                update_post_meta( $post_id, 'proj-info-2', $info_2);
            }

            $proj_year = '';
            if( isset( $_POST[ 'proj-year' ] ) ) {
                $proj_year = $_POST[ 'proj-year' ];

                update_post_meta( $post_id, 'proj-year', $proj_year );
            }
        }
    }


}