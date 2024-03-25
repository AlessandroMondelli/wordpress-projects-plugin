<?php
/**
 * File con funzioni varie
 * 
 */

function replace_file_text( $old_string, $new_string, $file_name ) {
    $str = file_get_contents( $file_name );

    $out = [];
    $var = preg_match( '/' . $old_string . '/', $str, $out );
    
    if( !empty( $str ) && !empty($out) ) {
        $str = str_replace( $out[0], $new_string, $str );

        file_put_contents( $file_name, $str );
    } else {
        file_put_contents( $file_name, '' );
        file_put_contents( $file_name, '.amproj-content-wrap.active{ background-color: #ffffff }   .amproj-content-wrap .amproj-title{ color: #000000 } .amproj-content-wrap span{ font-size: 16px }' );

        add_settings_error( 'layout_errors', 'invalid_font_size', 'Qualcosa è andata storto con l\'opzione "Dimensione testo", riprova.' );
    }
}