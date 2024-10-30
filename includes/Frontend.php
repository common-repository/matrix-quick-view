<?php

namespace MatrixAddons\MatrixQuickView;

if (!defined('ABSPATH')) exit;

class Frontend
{

    function __construct()
    {

        if (matrix_quick_view_is_mobile_request() && !matrix_quick_view_mobile_enable()) {
            return;
        }
        add_action('wp_enqueue_scripts', array($this, 'frontend_assets'));
        add_action('woocommerce_after_shop_loop_item', array($this, 'quick_view_button'));
        add_action('wp_footer', array($this, 'quick_view_model'));

        add_action('matrix_quick_view_show_product_sale_flash', 'woocommerce_show_product_sale_flash');
        add_action('matrix_quick_view_show_product_images', array($this, 'show_product_images'));
        add_action('matrix_quick_view_product_data', 'woocommerce_template_single_title');
        add_action('matrix_quick_view_product_data', 'woocommerce_template_single_rating');
        add_action('matrix_quick_view_product_data', 'woocommerce_template_single_price');
        add_action('matrix_quick_view_product_data', 'woocommerce_template_single_excerpt');
        add_action('matrix_quick_view_product_data', 'woocommerce_template_single_add_to_cart');
        add_action('matrix_quick_view_product_data', 'woocommerce_template_single_meta');
        //Frontend Ajax
        add_action('wp_ajax_matrix_quick_view_get_product', array($this, 'get_product_template'));
        add_action('wp_ajax_nopriv_matrix_quick_view_get_product', array($this, 'get_product_template'));

    }

    public function show_product_images()
    {

        global $post, $product, $woocommerce;

        ?>
        <div class="images">
            <?php

            if (has_post_thumbnail()) {
                $attachment_count = count($product->get_gallery_image_ids());
                $gallery = $attachment_count > 0 ? '[product-gallery]' : '';
                $props = wc_get_product_attachment_props(get_post_thumbnail_id(), $post);

                echo apply_filters('woocommerce_single_product_image_html', sprintf('<a href="%s" itemprop="image" class="woocommerce-main-image zoom" title="%s" data-rel="prettyPhoto' . esc_attr($gallery) . '">%s</a>', esc_url_raw($props['url']), esc_attr($props['caption']), get_the_post_thumbnail($post->ID, apply_filters('single_product_large_thumbnail_size', 'shop_single'), array(
                    'title' => $props['title'],
                    'alt' => $props['alt'],
                ))), esc_html($post->ID));
            } else {
                echo apply_filters('woocommerce_single_product_image_html', sprintf('<img src="%s" alt="%s" />', esc_url_raw(wc_placeholder_img_src()), esc_attr__('Placeholder', 'matrix-quick-view')), esc_html($post->ID));
            }


            $attachment_ids = $product->get_gallery_image_ids();
            if ($attachment_ids) :
                $loop = 0;
                $columns = apply_filters('woocommerce_product_thumbnails_columns', 3);
                ?>
                <div class="thumbnails <?php echo 'columns-' . esc_attr($columns); ?>"><?php
                    foreach ($attachment_ids as $attachment_id) {
                        $classes = array('thumbnail');
                        if ($loop === 0 || $loop % $columns === 0)
                            $classes[] = 'first';
                        if (($loop + 1) % $columns === 0)
                            $classes[] = 'last';
                        $image_link = wp_get_attachment_url($attachment_id);
                        if (!$image_link)
                            continue;
                        $image_title = esc_attr(get_the_title($attachment_id));
                        $image_caption = esc_attr(get_post_field('post_excerpt', $attachment_id));

                        $image_class = esc_attr(implode(' ', $classes));
                        echo apply_filters('woocommerce_single_product_image_thumbnail_html', sprintf('<a href="%s" class="%s" title="%s" >%s</a>', esc_url_raw($image_link), esc_attr($image_class), esc_attr($image_caption), wp_get_attachment_image($attachment_id, apply_filters('single_product_small_thumbnail_size', 'shop_thumbnail'), 0, $attr = array(
                            'title' => $image_title,
                            'alt' => $image_title
                        ))), $attachment_id, $post->ID, $image_class);
                        $loop++;
                    }
                    ?>

                </div>
            <?php endif; ?>
        </div>
        <?php
    }


    public function frontend_assets()
    {
        if (!class_exists('WooCommerce')) {
            return;
        }

        wp_enqueue_style('matrix_quick_view_remodal_default_css', MATRIX_QUICK_VIEW_ASSETS_URI . 'css/matrix-quick-view.css');
        wp_register_script('matrix_quick_view_frontend_js', MATRIX_QUICK_VIEW_ASSETS_URI . 'js/matrix-quick-view.js', array('jquery'), '1.0', true);
        $frontend_data = array(

            'matrix_quick_view_nonce' => wp_create_nonce('matrix_quick_view_nonce'),
            'ajaxurl' => admin_url('admin-ajax.php'),
            'matrix_quick_view_plugin_dir_url' => MATRIX_QUICK_VIEW_ASSETS_URI


        );

        wp_localize_script('matrix_quick_view_frontend_js', 'matrix_quick_view_frontend_obj', $frontend_data);
        wp_enqueue_script('jquery');
        wp_enqueue_script('matrix_quick_view_frontend_js');
        wp_register_script('matrix_quick_view_remodal_js', MATRIX_QUICK_VIEW_ASSETS_URI . 'vendor/js/remodal.js', array('jquery'), '1.0', true);
        wp_enqueue_script('matrix_quick_view_remodal_js');

        global $woocommerce;


        wp_enqueue_script('prettyPhoto', MATRIX_QUICK_VIEW_ASSETS_URI . 'vendor/js/jquery.prettyPhoto.min.js', array('jquery'), '3.1.6', true);
        wp_enqueue_style('woocommerce_prettyPhoto_css', $woocommerce->plugin_url() . '/assets/css/prettyPhoto.css');

        wp_enqueue_script('wc-add-to-cart-variation');
        wp_enqueue_script('thickbox');


        $custom_css = '
	    .matrix-quick-view-remodal .remodal-close{
	    	color:' . esc_attr(matrix_quick_view_close_button_color()) . ';
	    }
	    .matrix-quick-view-remodal .remodal-close:hover{
	    	background-color:' . esc_attr(matrix_quick_view_close_button_hover_background_color()) . ';
	    }
	    .woocommerce .remodal{
	    	background-color:' . esc_attr(matrix_quick_view_modal_background_color()) . ';
	    }
	    .matrix_quick_view_prev h4,.matrix_quick_view_next h4{
	    	color :' . esc_attr(matrix_quick_view_navigation_box_text_color()) . ';
	    }
	    .matrix_quick_view_prev,.matrix_quick_view_next{
	    	background :' . esc_attr(matrix_quick_view_navigation_box_background_color()) . ';
	    }
       ';
        wp_add_inline_style('matrix_quick_view_remodal_default_css', $custom_css);


    }

    public function quick_view_model()
    {
        ?>
        <div class="remodal matrix-quick-view-remodal" data-remodal-id="matrix-quick-view-modal" role="dialog"
             aria-labelledby="modalTitle"
             aria-describedby="modalDesc">
            <button data-remodal-action="close" class="remodal-close" aria-label="Close"></button>
            <div id="matrix_quick_view_modal_container" class="woocommerce single-product"></div>
        </div>
        <?php
    }

    public function quick_view_button()
    {

        global $product;

        if (!($product instanceof \WC_Product)) {
            return;
        }

        $product_id = $product->get_id();

        if (!apply_filters('matrix_quick_view_show_quick_view_button', true, $product_id)) {
            return;
        }

        if (!$product_id) {
            return;
        }

        $label = matrix_quick_view_button_label();

        $button = '<a href="#" class="button matrix-quick-view-button" data-product-id="' . esc_attr($product_id) . '"><span>' . esc_html($label) . '</span></a>';

        $button = apply_filters('matrix_quick_veiw_button_html', $button, $label, $product);

        echo wp_kses($button,
            array('a' => array('class' => array(), 'data-product-id' => array(), 'target' => array()),
                'span' => array('class' => array()),
                'button' => array('class' => array(), 'data-product-id' => array()),
            )
        );
    }


    public function get_product_template()
    {


        $product_id = intval($_POST['product_id']);


        wp('p=' . $product_id . '&post_type=product');


        $product = wc_get_product($product_id);


        if (!($product instanceof \WC_Product)) {
            return;
        }

        $product_id_number = $product->get_id();

        if ($product_id_number !== $product_id) {
            return;
        }
        $post = get_post($product_id);
        $next_post = get_next_post();
        $prev_post = get_previous_post();
        $next_post_id = ($next_post != null) ? $next_post->ID : '';
        $prev_post_id = ($prev_post != null) ? $prev_post->ID : '';
        $next_post_title = ($next_post != null) ? $next_post->post_title : '';
        $prev_post_title = ($prev_post != null) ? $prev_post->post_title : '';


        wc_get_template('quick-view-content.php', array(
            'product_id' => $product_id,
            'matrix_quick_view_next_id' => $next_post_id,
            'matrix_quick_view_next_title' => $next_post_title,
            'matrix_quick_view_next_product' => $next_post,
            'matrix_quick_view_prev_id' => $prev_post_id,
            'matrix_quick_view_prev_title' => $prev_post_title,
            'matrix_quick_view_prev_product' => $prev_post
        ), '', MATRIX_QUICK_VIEW_PLUGIN_DIR . 'templates/');

        exit;
    }

}

?>