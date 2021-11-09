<?php

use WeDevs\Dokan\Walkers\CategoryDropdownSingle;
use WeDevs\Dokan\Walkers\TaxonomyDropdown;

global $post;

$from_shortcode = false;

if ( !isset( $post->ID ) && ! isset( $_GET['product_id'] ) ) {
    wp_die( esc_html__( 'Access Denied, No product found', 'dokan-lite' ) );
}


if ( isset( $post->ID ) && $post->ID && 'product' == $post->post_type ) {
    $post_id      = $post->ID;
    $post_title   = $post->post_title;
    $post_content = $post->post_content;
    $post_excerpt = $post->post_excerpt;
    $post_status  = $post->post_status;
    $product      = wc_get_product( $post_id );
}

if ( isset( $_GET['product_id'] ) ) {
    $post_id        = intval( $_GET['product_id'] );
    $post           = get_post( $post_id );
    $post_title     = $post->post_title;
    $post_content   = $post->post_content;
    $post_excerpt   = $post->post_excerpt;
    $post_status    = $post->post_status;
    $product        = wc_get_product( $post_id );
    $from_shortcode = true;
}

if ( ! dokan_is_product_author( $post_id ) ) {
    wp_die( esc_html__( 'Access Denied', 'dokan-lite' ) );
    exit();
}

$_regular_price         = get_post_meta( $post_id, '_regular_price', true );
$_sale_price            = get_post_meta( $post_id, '_sale_price', true );
$is_discount            = !empty( $_sale_price ) ? true : false;
$_sale_price_dates_from = get_post_meta( $post_id, '_sale_price_dates_from', true );
$_sale_price_dates_to   = get_post_meta( $post_id, '_sale_price_dates_to', true );

$_sale_price_dates_from = !empty( $_sale_price_dates_from ) ? date_i18n( 'Y-m-d', $_sale_price_dates_from ) : '';
$_sale_price_dates_to   = !empty( $_sale_price_dates_to ) ? date_i18n( 'Y-m-d', $_sale_price_dates_to ) : '';
$show_schedule          = false;

if ( !empty( $_sale_price_dates_from ) && !empty( $_sale_price_dates_to ) ) {
    $show_schedule = true;
}

$_featured        = get_post_meta( $post_id, '_featured', true );
$terms            = wp_get_object_terms( $post_id, 'product_type' );
$product_type     = ( ! empty( $terms ) ) ? sanitize_title( current( $terms )->name ): 'simple';
$variations_class = ($product_type == 'simple' ) ? 'dokan-hide' : '';
$_visibility      = ( version_compare( WC_VERSION, '2.7', '>' ) ) ? $product->get_catalog_visibility() : get_post_meta( $post_id, '_visibility', true );

if ( ! $from_shortcode ) {
    get_header();
}

if ( ! empty( $_GET['errors'] ) ) {
    dokan()->dashboard->templates->products->set_errors( array_map( 'sanitize_text_field', wp_unslash( $_GET['errors'] ) ) );
}

/**
 *  dokan_dashboard_wrap_before hook
 *
 *  @since 2.4
 */
do_action( 'dokan_dashboard_wrap_before', $post, $post_id );
?>

<?php do_action( 'dokan_dashboard_wrap_start' ); ?>

    <div class="dokan-dashboard-wrap">

        <?php

            /**
             *  dokan_dashboard_content_before hook
             *  dokan_before_product_content_area hook
             *
             *  @hooked get_dashboard_side_navigation
             *
             *  @since 2.4
             */
            do_action( 'dokan_dashboard_content_before' );
            do_action( 'dokan_before_product_content_area' );
            
        ?>

        <div class="dokan-dashboard-content dokan-product-edit">

            <?php

                /**
                 *  dokan_product_content_inside_area_before hook
                 *
                 *  @since 2.4
                 */
                do_action( 'dokan_product_content_inside_area_before' );
            ?>
                <iframe src="https://tura.store/wp-admin/post.php?post=<?php echo $post_id; ?>&action=edit" 
                        marginwidth="0" marginheight="0" name="ventana_iframe" scrolling="si" 
                        border="0" 
                        frameborder="0" style="width: 100%;height: 600px;">
                </iframe>
            
            <?php

                /**
                 *  dokan_product_content_inside_area_after hook
                 *
                 *  @since 2.4
                 */
                do_action( 'dokan_product_content_inside_area_after' );
            ?>
        </div>

        <?php

            /**
             *  dokan_dashboard_content_after hook
             *  dokan_after_product_content_area hook
             *
             *  @since 2.4
             */
            do_action( 'dokan_dashboard_content_after' );
            do_action( 'dokan_after_product_content_area' );
        ?>

    </div><!-- .dokan-dashboard-wrap -->

<?php do_action( 'dokan_dashboard_wrap_end' ); ?>

<div class="dokan-clearfix"></div>

<?php

    /**
     *  dokan_dashboard_content_before hook
     *
     *  @since 2.4
     */
    do_action( 'dokan_dashboard_wrap_after', $post, $post_id );

    wp_reset_postdata();

    if ( ! $from_shortcode ) {
        get_footer();
    }
?>
