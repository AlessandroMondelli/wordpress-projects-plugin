<?php
/**
 * File template per pagina admin menu con form per scegliere layout - utilizzo di settings API
 * 
 */

if( !defined( 'ABSPATH' ) ) {
    die;
}
?>

<form method="post" action="options.php">
    <?php settings_errors() ?>

    <?php settings_fields( 'layout_group' ) ?>
    <?php do_settings_sections( 'layout_progetti' ) ?>

    <?php submit_button() ?>
</form>