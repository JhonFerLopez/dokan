<?php
$get_data = wp_unslash( $_GET );
$action   = isset( $get_data['action'] ) ? sanitize_key( $get_data['action'] ) : 'listing';

if ( $action == 'edit' ) {
    do_action( 'dokan_render_product_edit_template', $action );
} elseif( $action == 'edit-complete' ) {//Ziel
    do_action( 'dokan_render_product_edit_complete_template', $action );
} else {
    do_action( 'dokan_render_product_listing_template', $action );
}
