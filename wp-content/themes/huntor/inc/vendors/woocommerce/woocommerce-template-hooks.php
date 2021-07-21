<?php
/**
 * homefinder WooCommerce hooks
 *
 * @package homefinder
 */


/**
 * Styles
 *
 * @see  huntor_woocommerce_scripts()
 */
remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);
remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);

// Check if compare button is enabled and enabled in yith settings
if (class_exists('YITH_Woocompare_Frontend')) {
    global $yith_woocompare;
    if (!is_admin()) {
        remove_action('woocommerce_single_product_summary', array($yith_woocompare->obj, 'add_compare_link'), 35);
        add_action('woocommerce_after_add_to_cart_button', array($yith_woocompare->obj, 'add_compare_link'), 15);
    }
}

if (huntor_is_woocommerce_extension_activated('YITH_WCWL')) {
    add_action('woocommerce_after_add_to_cart_button', 'huntor_woocommerce_product_loop_wishlist_button', 15);
}


add_action('woocommerce_before_main_content', 'huntor_before_content', 10);
add_action('woocommerce_after_main_content', 'huntor_after_content', 10);
add_action('huntor_content_top', 'huntor_shop_messages', 15);
add_action('huntor_content_top', 'woocommerce_breadcrumb', 10);

add_action('woocommerce_after_shop_loop', 'huntor_product_columns_wrapper_close', 40);

add_filter('loop_shop_columns', 'huntor_loop_columns');


remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
add_action('woocommerce_before_shop_loop_item_title', 'huntor_template_loop_product_thumbnail', 10);


add_action('woocommerce_before_shop_loop', 'huntor_sorting_wrapper', 1);
//add_action('woocommerce_before_shop_loop', 'huntor_sorting_group', 1);
add_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 2);
//add_action('woocommerce_before_shop_loop', 'huntor_sorting_group_close', 3);

//add_action('woocommerce_before_shop_loop', 'huntor_sorting_group', 5);
add_action('woocommerce_before_shop_loop', 'huntor_button_grid_list_layout', 5);
add_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 5);
//add_action('woocommerce_before_shop_loop', 'huntor_sorting_group_close', 7);
add_action('woocommerce_before_shop_loop', 'huntor_sorting_wrapper_close', 7);


add_action('woocommerce_single_product_summary', 'huntor_woocommerce_single_product_summary_inner_start', -1);
add_action('woocommerce_single_product_summary', 'huntor_woocommerce_single_product_summary_inner_end', 99999);


//remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );


//add_action('woocommerce_single_product_summary', 'huntor_woocommerce_single_deal', 25);
add_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 4);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);
//add_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 20 );
add_action('woocommerce_before_single_product_summary', 'huntor_woocommerce_before_single_product_summary_inner_start', 5);
add_action('woocommerce_after_single_product_summary', 'huntor_woocommerce_before_single_product_summary_inner_end', -1);
add_action('woocommerce_before_add_to_cart_quantity', 'huntor_single_product_quantity_label', 10);

//Social
add_action('woocommerce_single_product_summary', 'huntor_single_product_social', 41);


/**
 * Remove Action
 */
remove_action('woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15);
remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);

add_action('woocommerce_after_single_product', 'woocommerce_output_product_data_tabs', 10);
add_action('woocommerce_after_single_product', 'huntor_upsell_display', 15);
add_action('woocommerce_after_single_product', 'huntor_output_related_products', 20);

$product_single_tab_style = get_theme_mod('huntor_woocommerce_single_product_tab_style', 'tab');
if ($product_single_tab_style == 'accordion') {
    remove_action('woocommerce_after_single_product', 'woocommerce_output_product_data_tabs', 10);
    add_action('woocommerce_single_product_summary', 'huntor_output_product_data_accordion', 55);
}


$product_single_style = get_theme_mod('huntor_woocommerce_single_product_style', 1);

switch ($product_single_style) {
    case 1:
        // Support lightbox
        add_action('after_setup_theme', array(huntor_WooCommerce::getInstance(), 'add_support_gallery_all'));
        break;
    case 2:
        // Supports Single Image
        add_action('after_setup_theme', array(huntor_WooCommerce::getInstance(), 'add_support_lightbox'));
        add_filter('woocommerce_single_product_image_thumbnail_html', 'huntor_woocommerce_single_product_image_thumbnail_html', 10, 2);
        break;

    case 3:
        // Supports Single Image
        add_action('after_setup_theme', array(huntor_WooCommerce::getInstance(), 'add_support_lightbox'));
        add_filter('woocommerce_single_product_image_thumbnail_html', 'huntor_woocommerce_single_product_image_thumbnail_html', 10, 2);
        break;

    case 4 :
        // Support lightbox
        add_action('after_setup_theme', array(huntor_WooCommerce::getInstance(), 'add_support_gallery_all'));
        break;

}

//Single video
add_action('woocommerce_product_thumbnails', 'huntor_single_product_video', 30);

add_action('woocommerce_review_comment_text', 'huntor_single_product_review_author', 20);


if (defined('WC_VERSION') && version_compare(WC_VERSION, '2.3', '>=')) {
    add_filter('woocommerce_add_to_cart_fragments', 'huntor_cart_link_fragment');
} else {
    add_filter('add_to_cart_fragments', 'huntor_cart_link_fragment');
}

/**
 * Checkout Page
 *
 * @see huntor_checkout_before_customer_details_container
 * @see huntor_checkout_after_customer_details_container
 * @see huntor_checkout_after_order_review_container
 */

add_action('woocommerce_checkout_before_customer_details', 'huntor_checkout_before_customer_details_container', 1);
add_action('woocommerce_checkout_after_customer_details', 'huntor_checkout_after_customer_details_container', 1);
add_action('woocommerce_checkout_after_order_review', 'huntor_checkout_after_order_review_container', 1);
add_action('woocommerce_checkout_order_review', 'huntor_woocommerce_order_review_heading', 1);


// Cart Page
remove_action('woocommerce_cart_collaterals', 'woocommerce_cross_sell_display');
add_action('huntor_after_form_cart', 'huntor_woocommerce_cross_sell_display');

// Layout Product
function huntor_include_hooks_product_blocks() {

    remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10);
    remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);
    remove_action('woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10);
    remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5);
    remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);

    add_action('woocommerce_before_shop_loop_item', 'huntor_woocommerce_product_loop_start', -1);

    add_action('woocommerce_after_shop_loop_item', 'huntor_woocommerce_product_loop_end', 999);
    add_action('woocommerce_before_shop_loop', 'huntor_product_columns_wrapper', 40);

    add_action('woocommerce_before_shop_loop_item_title', 'huntor_woocommerce_product_loop_image_start', 5);


    add_action('woocommerce_before_shop_loop_item_title', 'huntor_woocommerce_product_loop_label_start', 10);
    add_action('woocommerce_before_shop_loop_item_title', 'huntor_woocommerce_get_product_label_stock', 10);
    add_action('woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_sale_flash', 15);
    add_action('woocommerce_before_shop_loop_item_title', 'huntor_woocommerce_get_product_label_new', 25);
    add_action('woocommerce_before_shop_loop_item_title', 'huntor_woocommerce_product_loop_label_end', 30);

    add_action('woocommerce_before_shop_loop_item_title', 'huntor_woocommerce_product_loop_image_end', 100);
    add_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_link_open', 99);
    add_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_link_close', 99);
    add_action('woocommerce_shop_loop_item_title', 'huntor_woocommerce_product_loop_caption_start', 0);
    add_action('huntor_woocommerce_product_loop_footer', 'woocommerce_template_loop_add_to_cart', 10);
    add_action('woocommerce_after_shop_loop_item_title', 'huntor_woocommerce_product_loop_caption_end', 99);

    add_action('woocommerce_shop_loop_item_title', 'huntor_woocommerce_product_rating', 0);
    add_action('woocommerce_before_shop_loop_item_title', 'huntor_woocommerce_product_loop_action', 40);

    add_action('woocommerce_after_shop_loop_item_title', 'huntor_woocommerce_product_loop_footer', 99);

    // Wishlist
    add_action('huntor_woocommerce_product_loop_action', 'huntor_woocommerce_product_loop_wishlist_button', 10);

    // QuickView
    if (huntor_is_woocommerce_extension_activated('YITH_WCQV')) {
        remove_action('woocommerce_after_shop_loop_item', array(
            YITH_WCQV_Frontend::get_instance(),
            'yith_add_quick_view_button'
        ), 15);
        add_action('huntor_woocommerce_product_loop_action', array(
            YITH_WCQV_Frontend::get_instance(),
            'yith_add_quick_view_button'
        ), 20);
    }

    // Compare
    add_action('huntor_woocommerce_product_loop_action', 'huntor_woocommerce_product_loop_compare_button', 15);

}

/**
 * Cart widget
 */
remove_action('woocommerce_widget_shopping_cart_buttons', 'woocommerce_widget_shopping_cart_button_view_cart', 10);
remove_action('woocommerce_widget_shopping_cart_buttons', 'woocommerce_widget_shopping_cart_proceed_to_checkout', 20);

add_action('woocommerce_widget_shopping_cart_buttons', 'huntor_woocommerce_widget_shopping_cart_button_view_cart', 10);
add_action('woocommerce_widget_shopping_cart_buttons', 'huntor_woocommerce_widget_shopping_cart_proceed_to_checkout', 20);


if (isset($_GET['action']) && $_GET['action'] === 'elementor') {
    return;
}

huntor_include_hooks_product_blocks();

if (isset($_GET['display']) && $_GET['display'] === 'list') {
    add_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_single_excerpt', 5);

    // Wishlist
    remove_action('huntor_woocommerce_product_loop_action', 'huntor_woocommerce_product_loop_wishlist_button', 10);
    add_action('huntor_woocommerce_product_loop_footer', 'huntor_woocommerce_product_loop_wishlist_button', 10);

    // Compare
    remove_action('huntor_woocommerce_product_loop_action', 'huntor_woocommerce_product_loop_compare_button', 15);
    add_action('huntor_woocommerce_product_loop_footer', 'huntor_woocommerce_product_loop_compare_button', 15);

}


function huntor_update_setting_yith_plugin() {
    if (get_option('yith_woocompare_compare_button_in_product_page') == 'yes') {
        update_option('yith_woocompare_compare_button_in_product_page', 'no');
    }

    if (get_option('yith_woocompare_compare_button_in_products_list') == 'yes') {
        update_option('yith_woocompare_compare_button_in_products_list', 'no');
    }

    if (get_option('yith_wcwl_button_position') != 'shortcode') {
        update_option('yith_wcwl_button_position', 'shortcode');
    }
}

add_action('yit_framework_after_print_wc_panel_content', 'huntor_update_setting_yith_plugin');
