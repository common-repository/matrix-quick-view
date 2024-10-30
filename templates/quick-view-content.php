<?php
/**
 * Quick view content.
 *
 * @author  Matrix
 * @version 1.0.2
 */

defined('MATRIX_QUICK_VIEW_VERSION') || exit; // Exit if accessed directly.

global $woocommerce;

while (have_posts()) :
    the_post();
    ?>

    <script>
        var url = <?php echo "'" . MATRIX_QUICK_VIEW_ASSETS_URI . "vendor/js/prettyPhoto.init.js'"; ?>;
        jQuery.getScript(url);
        var wc_add_to_cart_variation_params = {"ajax_url": "\/wp-admin\/admin-ajax.php"};
        jQuery.getScript("<?php echo esc_url_raw($woocommerce->plugin_url() . '/assets/js/frontend/add-to-cart-variation.min.js');?>");
    </script>
    <div class="product">

        <div id="product-<?php the_ID(); ?>" <?php post_class('product'); ?> >
            <?php do_action('matrix_quick_view_show_product_sale_flash'); ?>

            <?php do_action('matrix_quick_view_show_product_images'); ?>

            <div class="summary entry-summary scrollable">
                <div class="summary-content">
                    <?php

                    do_action('matrix_quick_view_product_data');

                    ?>
                </div>
            </div>
            <div class="scrollbar_bg"></div>

        </div>
    </div>

<?php
endwhile; // end of the loop.
?>
<div class="matrix_quick_view_prev_data"
     data-matrix-quick-view-prev-id="<?php echo esc_attr($matrix_quick_view_prev_id); ?>">
    <?php echo esc_html($matrix_quick_view_prev_title); ?>
    <?php echo ($matrix_quick_view_prev_product != null) ? get_the_post_thumbnail($matrix_quick_view_prev_id,
        'shop_thumbnail', '') : ''; ?>
</div>
<div class="matrix_quick_view_next_data"
     data-matrix-quick-view-next-id="<?php echo esc_attr($matrix_quick_view_next_id); ?>">
    <?php echo esc_html($matrix_quick_view_next_title); ?>
    <?php echo ($matrix_quick_view_next_product != null) ? get_the_post_thumbnail($matrix_quick_view_next_id,
        'shop_thumbnail', '') : ''; ?>
</div>
