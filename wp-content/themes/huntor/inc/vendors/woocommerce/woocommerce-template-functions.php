<?php

if (!function_exists('huntor_woocommerce_widget_shopping_cart_button_view_cart')) {

    /**
     * Output the view cart button.
     */
    function huntor_woocommerce_widget_shopping_cart_button_view_cart() {
        echo '<a href="' . esc_url(wc_get_cart_url()) . '" class="button wc-forward"><span>' . esc_html__('View cart', 'huntor') . '</span></a>';
    }
}

if (!function_exists('huntor_woocommerce_widget_shopping_cart_proceed_to_checkout')) {

    /**
     * Output the proceed to checkout button.
     */
    function huntor_woocommerce_widget_shopping_cart_proceed_to_checkout() {
        echo '<a href="' . esc_url(wc_get_checkout_url()) . '" class="button checkout wc-forward"><span>' . esc_html__('Checkout', 'huntor') . '</span></a>';
    }
}

if (!function_exists('huntor_woocommerce_version_check')) {
    function huntor_woocommerce_version_check($version = '3.3') {
        if (huntor_is_woocommerce_activated()) {
            global $woocommerce;
            if (version_compare($woocommerce->version, $version, ">=")) {
                return true;
            }
        }

        return false;
    }
}

if (!function_exists('huntor_before_content')) {
    /**
     * Before Content
     * Wraps all WooCommerce content in wrappers which match the theme markup
     *
     * @return  void`
     * @since   1.0.0
     */
    function huntor_before_content() {
        ?>
        <div class="wrap">
        <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">
        <?php
        if (is_product_category()) {
            $cate      = get_queried_object();
            $cateID    = $cate->term_id;
            $banner_id = get_term_meta($cateID, 'product_cat_banner_id', true);

            if ($banner_id) {
                echo '<div class="product-category-banner">';
                echo wp_get_attachment_image($banner_id, 'full');
                echo '</div>';
            }
        }
    }
}

if (!function_exists('huntor_after_content')) {
    /**
     * After Content
     * Closes the wrapping divs
     *
     * @return  void
     * @since   1.0.0
     */
    function huntor_after_content() {
        ?>
        </main><!-- #main -->
        </div><!-- #primary -->
        <?php get_sidebar(); ?>
        </div>
        <?php
    }
}

if (!function_exists('huntor_cart_link_fragment')) {
    /**
     * Cart Fragments
     * Ensure cart contents update when products are added to the cart via AJAX
     *
     * @param array $fragments Fragments to refresh via AJAX.
     *
     * @return array            Fragments to refresh via AJAX
     */
    function huntor_cart_link_fragment($fragments) {
        global $woocommerce;

        ob_start();
        $fragments['a.cart-contents .amount']     = huntor_cart_amount();
        $fragments['a.cart-contents .count']      = huntor_cart_count();
        $fragments['a.cart-contents .count-text'] = huntor_cart_count_text();

        ob_start();
        huntor_handheld_footer_bar_cart_link();
        $fragments['a.footer-cart-contents'] = ob_get_clean();

        return $fragments;
    }
}

if (!function_exists('huntor_cart_link')) {
    /**
     * Cart Link
     * Displayed a link to the cart including the number of items present and the cart total
     *
     * @return string
     * @since  1.0.0
     */
    function huntor_cart_link() {
        if (!empty(WC()->cart) && WC()->cart instanceof WC_Cart) {
            $items = '';
            $items .= '<a data-toggle="toggle" class="cart-contents header-button" href="' . esc_url(wc_get_cart_url()) . '" title="' . __("View your shopping cart", "huntor") . '">';
            $items .= '<i class="opal-icon-cart" aria-hidden="true"></i>';
            $items .= '<span class="count">' . wp_kses_data(WC()->cart->get_cart_contents_count()) . '</span>';
            $items .= '</a>';

            return $items;
        }

        return '';
    }
}

if (!function_exists('huntor_cart_amount')) {
    /**
     *
     * @return string
     *
     */
    function huntor_cart_amount() {
        if (!empty(WC()->cart) && WC()->cart instanceof WC_Cart) {
            return '<span class="amount 1">' . wp_kses_data(WC()->cart->get_cart_subtotal()) . '</span>';
        }

        return '';
    }
}

if (!function_exists('huntor_cart_count')) {
    /**
     *
     * @return string
     *
     */
    function huntor_cart_count() {
        if (!empty(WC()->cart) && WC()->cart instanceof WC_Cart) {
            return '<span class="count">' . wp_kses_data(WC()->cart->get_cart_contents_count()) . '</span>';
        }

        return '';
    }
}

if (!function_exists('huntor_cart_count_text')) {
    /**
     *
     * @return string
     *
     */
    function huntor_cart_count_text() {
        if (!empty(WC()->cart) && WC()->cart instanceof WC_Cart) {
            return '<span class="count-text">' . wp_kses_data(_n("item", "items", WC()->cart->get_cart_contents_count(), "huntor")) . '</span>';
        }

        return '';
    }
}

if (!function_exists('huntor_upsell_display')) {
    /**
     * Upsells
     * Replace the default upsell function with our own which displays the correct number product columns
     *
     * @return  void
     * @since   1.0.0
     * @uses    woocommerce_upsell_display()
     */
    function huntor_upsell_display() {
        global $product;
        $number = count($product->get_upsell_ids());
        if ($number <= 0) {
            return;
        }
        $columns = absint(get_theme_mod('huntor_woocommerce_single_upsell_columns', 3));
        if ($columns < $number) {
            echo '<div class="woocommerce-product-carousel owl-theme" data-columns="' . esc_attr($columns) . '">';
        } else {
            echo '<div class="columns-' . esc_attr($columns) . '">';
        }
        woocommerce_upsell_display();
        echo '</div>';
    }
}

if (!function_exists('huntor_output_related_products')) {
    /**
     * Related
     *
     * @return  void
     * @since   1.0.0
     * @uses    woocommerce_related_products()
     */
    function huntor_output_related_products() {
        $columns = absint(get_theme_mod('huntor_woocommerce_single_related_columns', 3));
        $number  = absint(get_theme_mod('huntor_woocommerce_single_related_number', 3));
        if ($columns < $number) {
            echo '<div class="woocommerce-product-carousel owl-theme" data-columns="' . esc_attr($columns) . '">';
        } else {
            echo '<div class="columns-' . esc_attr($columns) . '">';
        }
        woocommerce_related_products($args = array(
            'posts_per_page' => $number,
            'columns'        => $columns,
            'orderby'        => 'rand',
        ));
        echo '</div>';
    }
}

if (!function_exists('huntor_sorting_wrapper')) {
    /**
     * Sorting wrapper
     *
     * @return  void
     * @since   1.4.3
     */
    function huntor_sorting_wrapper() {
        echo '<div class="osf-sorting-wrapper"><div class="osf-sorting">';
    }
}

if (!function_exists('huntor_sorting_wrapper_close')) {
    /**
     * Sorting wrapper close
     *
     * @return  void
     * @since   1.4.3
     */
    function huntor_sorting_wrapper_close() {
        echo '</div></div>';
    }
}

if (!function_exists('huntor_sorting_group')) {
    /**
     * Sorting wrapper
     *
     * @return  void
     * @since   1.4.3
     */
    function huntor_sorting_group() {
        echo '<div class="osf-sorting-group col-lg-6 col-sm-12">';
    }
}

if (!function_exists('huntor_sorting_group_close')) {
    /**
     * Sorting wrapper close
     *
     * @return  void
     * @since   1.4.3
     */
    function huntor_sorting_group_close() {
        echo '</div>';
    }
}


if (!function_exists('huntor_product_columns_wrapper')) {
    /**
     * Product columns wrapper
     *
     * @return  void
     * @since   2.2.0
     */
    function huntor_product_columns_wrapper() {
        $columns = huntor_loop_columns();
        if (isset($_GET['display']) && $_GET['display'] === 'list') {
            $columns = 1;
        }
        echo '<div class="columns-' . intval($columns) . '">';
    }
}

if (!function_exists('huntor_loop_columns')) {
    /**
     * Default loop columns on product archives
     *
     * @return integer products per row
     * @since  1.0.0
     */
    function huntor_loop_columns() {
        $columns = get_theme_mod('huntor_woocommerce_archive_columns', 4);

        return intval(apply_filters('huntor_products_columns', $columns));
    }
}

if (!function_exists('huntor_product_columns_wrapper_close')) {
    /**
     * Product columns wrapper close
     *
     * @return  void
     * @since   2.2.0
     */
    function huntor_product_columns_wrapper_close() {
        echo '</div>';
    }
}

if (!function_exists('huntor_shop_messages')) {
    /**
     * homefinder shop messages
     *
     * @since   1.4.4
     * @uses    huntor_do_shortcode
     */
    function huntor_shop_messages() {
        if (!is_checkout()) {
            echo wp_kses_post(huntor_do_shortcode('woocommerce_messages'));
        }
    }
}

if (!function_exists('huntor_woocommerce_pagination')) {
    /**
     * homefinder WooCommerce Pagination
     * WooCommerce disables the product pagination inside the woocommerce_product_subcategories() function
     * but since homefinder adds pagination before that function is excuted we need a separate function to
     * determine whether or not to display the pagination.
     *
     * @since 1.4.4
     */
    function huntor_woocommerce_pagination() {
        if (woocommerce_products_will_display()) {
            woocommerce_pagination();
        }
    }
}


if (!function_exists('huntor_handheld_footer_bar_search')) {
    /**
     * The search callback function for the handheld footer bar
     *
     * @since 2.0.0
     */
    function huntor_handheld_footer_bar_search() {
        echo '<a href="">' . esc_attr__('Search', 'huntor') . '</a>';
        huntor_product_search();
    }
}

if (!function_exists('huntor_handheld_footer_bar_cart_link')) {
    /**
     * The cart callback function for the handheld footer bar
     *
     * @since 2.0.0
     */
    function huntor_handheld_footer_bar_cart_link() {
        ?>
        <a class="footer-cart-contents" href="<?php echo esc_url(wc_get_cart_url()); ?>"
           title="<?php esc_attr_e('View your shopping cart', 'huntor'); ?>">
            <span class="count"><?php echo wp_kses_data(WC()->cart->get_cart_contents_count()); ?></span>
        </a>
        <?php
    }
}

if (!function_exists('huntor_handheld_footer_bar_account_link')) {
    /**
     * The account callback function for the handheld footer bar
     *
     * @since 2.0.0
     */
    function huntor_handheld_footer_bar_account_link() {
        echo '<a href="' . esc_url(get_permalink(get_option('woocommerce_myaccount_page_id'))) . '">' . esc_attr__('My Account', 'huntor') . '</a>';
    }
}


if (!function_exists('huntor_checkout_before_customer_details_container')) {
    function huntor_checkout_before_customer_details_container() {
        if (WC()->checkout()->get_checkout_fields()) {
            echo '<div class="row"><div class="col-lg-7 col-md-12 col-sm-12"><div class="inner">';
        }
    }
}

if (!function_exists('huntor_checkout_after_customer_details_container')) {
    function huntor_checkout_after_customer_details_container() {
        if (WC()->checkout()->get_checkout_fields()) {
            echo '</div></div><div class="col-lg-5 col-md-12 col-sm-12"><div class="inner order_review_inner"> ';
        }
    }
}

if (!function_exists('huntor_checkout_after_order_review_container')) {
    function huntor_checkout_after_order_review_container() {
        if (WC()->checkout()->get_checkout_fields()) {
            echo '</div></div></div>';
        }
    }
}

if (!function_exists('huntor_woocommerce_single_product_add_to_cart_before')) {
    function huntor_woocommerce_single_product_add_to_cart_before() {
        echo '<div class="woocommerce-cart"><div class="inner woocommerce-cart-inner">';
    }
}

if (!function_exists('huntor_woocommerce_single_product_add_to_cart_after')) {
    function huntor_woocommerce_single_product_add_to_cart_after() {
        echo '</div></div>';
    }
}

if (!function_exists('huntor_woocommerce_single_product_4_wrap_start')) {
    function huntor_woocommerce_single_product_4_wrap_start() {
        echo '<div class="single-style-4-wrap">';
    }
}

if (!function_exists('huntor_woocommerce_single_product_4_wrap_end')) {
    function huntor_woocommerce_single_product_4_wrap_end() {
        echo '</div>';
    }
}

if (!function_exists('huntor_woocommerce_before_single_product_summary_inner_start')) {
    function huntor_woocommerce_before_single_product_summary_inner_start() {
        echo '<div class="product-inner">';
    }
}

if (!function_exists('huntor_woocommerce_before_single_product_summary_inner_end')) {
    function huntor_woocommerce_before_single_product_summary_inner_end() {
        echo '</div>';
    }
}

if (!function_exists('huntor_woocommerce_single_product_summary_inner_start')) {
    function huntor_woocommerce_single_product_summary_inner_start() {
        echo '<div class="inner">';
    }
}

if (!function_exists('huntor_woocommerce_single_product_summary_inner_end')) {
    function huntor_woocommerce_single_product_summary_inner_end() {
        echo '</div>';
    }
}

if (!function_exists('huntor_woocommerce_product_best_selling')) {
    function huntor_woocommerce_product_best_selling() {
        ?>
        <div class="best-selling">
            <div class="best-selling-inner">
                <h4 class="best-selling-title"><?php echo esc_html__('Trending', 'huntor'); ?></h4>
                <ul class="product_list_widget product-best-selling">
                    <?php
                    $args = array(
                        'post_type'      => 'product',
                        'meta_key'       => 'total_sales',
                        'orderby'        => 'meta_value_num',
                        'posts_per_page' => 6
                    );
                    $loop = new WP_Query($args);
                    if ($loop->have_posts()) {
                        while ($loop->have_posts()) : $loop->the_post();
                            wc_get_template_part('content', 'widget-product');
                        endwhile;
                    } else {
                        echo __('No products found', 'huntor');
                    }
                    wp_reset_postdata();
                    ?>
                </ul>
            </div>
        </div>
        <?php
    }
}

if (!function_exists('huntor_template_loop_product_thumbnail')) {
    function huntor_template_loop_product_thumbnail($size = 'woocommerce_thumbnail', $deprecated1 = 0, $deprecated2 = 0) {
        echo huntor_get_loop_product_thumbnail();

    }
}
if (!function_exists('huntor_woocommerce_order_review_heading')) {
    function huntor_woocommerce_order_review_heading() {
        echo ' <h3 class="order_review_heading">' . esc_attr__('Your order', 'huntor') . '</h3>';
    }
}


if (!function_exists('huntor_get_loop_product_thumbnail')) {
    function huntor_get_loop_product_thumbnail($size = 'woocommerce_thumbnail', $deprecated1 = 0, $deprecated2 = 0) {
        global $product;
        if (!$product) {
            return '';
        }
        $gallery    = $product->get_gallery_image_ids();
        $hover_skin = get_theme_mod('huntor_woocommerce_product_hover', 'none');
        if ($hover_skin == '0' || count($gallery) <= 0) {
            echo '<div class="product-image">' . $product->get_image('shop_catalog') . '</div>';

            return '';
        }
        $image_featured = '<div class="product-image">' . $product->get_image('shop_catalog') . '</div>';
        $image_featured .= '<div class="product-image second-image">' . wp_get_attachment_image($gallery[0], 'shop_catalog') . '</div>';

        echo <<<HTML
<div class="product-img-wrap {$hover_skin}">
    <div class="inner">
        {$image_featured}
    </div>
</div>
HTML;
    }
}

if (!function_exists('huntor_woocommerce_product_loop_image_start')) {
    function huntor_woocommerce_product_loop_image_start() {
        echo '<div class="product-transition">';
    }
}

if (!function_exists('huntor_woocommerce_product_loop_image_end')) {
    function huntor_woocommerce_product_loop_image_end() {
        echo '</div>';
    }
}

if (!function_exists('huntor_woocommerce_product_loop_action')) {
    function huntor_woocommerce_product_loop_action() {
        ?>
        <div class="group-action">
            <div class="shop-action">
                <?php do_action('huntor_woocommerce_product_loop_action'); ?>
            </div>
        </div>
        <?php
    }
}

if (!function_exists('huntor_woocommerce_product_loop_footer')) {
    function huntor_woocommerce_product_loop_footer() {
        ?>
        <div class="product-footer">
            <div class="shop-action">
                <?php do_action('huntor_woocommerce_product_loop_footer'); ?>
            </div>
        </div>
        <?php
    }
}


if (!function_exists('huntor_woocommerce_product_loop_wishlist_button')) {
    function huntor_woocommerce_product_loop_wishlist_button() {
        if (huntor_is_woocommerce_extension_activated('YITH_WCWL')) {
            echo huntor_do_shortcode('yith_wcwl_add_to_wishlist');
        }
    }
}

if (!function_exists('huntor_woocommerce_product_loop_compare_button')) {
    function huntor_woocommerce_product_loop_compare_button() {
        if (huntor_is_woocommerce_extension_activated('YITH_Woocompare')) {
            echo huntor_do_shortcode('yith_compare_button');
        }
    }
}

if (!function_exists('huntor_woocommerce_change_path_shortcode')) {
    function huntor_woocommerce_change_path_shortcode($template, $slug, $name) {
        wc_get_template('content-widget-product.php', array('show_rating' => false));
    }
}

if (!function_exists('huntor_woocommerce_product_loop_start')) {
    function huntor_woocommerce_product_loop_start() {
        echo '<div class="product-block">';
    }
}

if (!function_exists('huntor_woocommerce_product_loop_end')) {
    function huntor_woocommerce_product_loop_end() {
        echo '</div>';
    }
}

if (!function_exists('huntor_woocommerce_product_loop_caption_start')) {
    function huntor_woocommerce_product_loop_caption_start() {
        echo '<div class="caption">';
    }
}

if (!function_exists('huntor_woocommerce_product_loop_caption_end')) {
    function huntor_woocommerce_product_loop_caption_end() {
        echo '</div>';
    }
}

if (!function_exists('huntor_woocommerce_product_loop_group_transititon_start')) {
    function huntor_woocommerce_product_loop_group_transititon_start() {
        echo '<div class="group-transition"><div class="group-transition-inner">';
    }
}

if (!function_exists('huntor_woocommerce_product_loop_group_transititon_end')) {
    function huntor_woocommerce_product_loop_group_transititon_end() {
        echo '</div></div>';
    }
}

if (!function_exists('huntor_woocommerce_product_loop_label_start')) {
    function huntor_woocommerce_product_loop_label_start() {
        echo '<div class="group-label">';
    }
}

if (!function_exists('huntor_woocommerce_product_loop_label_end')) {
    function huntor_woocommerce_product_loop_label_end() {
        echo '</div>';
    }
}

if (!function_exists('huntor_woocommerce_product_rating')) {
    function huntor_woocommerce_product_rating() {
        global $product;
        if (get_option('woocommerce_enable_review_rating') === 'no') {
            return;
        }
        if ($rating_html = wc_get_rating_html($product->get_average_rating())) {
            echo apply_filters('huntor_woocommerce_rating_html', $rating_html);
        } else {
            echo '<div class="star-rating"></div>';
        }
    }
}

if (!function_exists('oft_woocommerce_template_loop_product_excerpt')) {

    /**
     * Show the excerpt in the product loop.
     */
    function huntor_woocommerce_template_loop_product_excerpt() {
        global $product;
        echo '<div class="excerpt">' . get_the_excerpt() . '</div>';
    }
}
if (!function_exists('woocommerce_template_loop_product_title')) {

    /**
     * Show the product title in the product loop.
     */
    function woocommerce_template_loop_product_title() {
        echo '<h3 class="woocommerce-loop-product__title"><a href="' . esc_url_raw(get_the_permalink()) . '">' . get_the_title() . '</a></h3>';
    }
}


if (!function_exists('huntor_woocommerce_get_product_category')) {
    function huntor_woocommerce_get_product_category() {
        global $product;
        echo wc_get_product_category_list($product->get_id(), ', ', '<div class="posted_in">', '</div>');
    }
}

if (!function_exists('huntor_woocommerce_get_product_label_stock')) {
    function huntor_woocommerce_get_product_label_stock() {
        /**
         * @var $product WC_Product
         */
        global $product;
        if ($product->get_stock_status() == 'outofstock') {
            echo '<span class="stock-label outofstock"><span>' . esc_html__('Out Of Stock', 'huntor') . '</span></span>';
        } elseif ($product->get_stock_status() == 'instock') {
            echo '<span class="stock-label instock"><span>' . esc_html__('In Stock', 'huntor') . '</span></span>';
        } else {
            echo '<span class="stock-label onbackorder"><span>' . esc_html__('On backorder', 'huntor') . '</span></span>';
        }
    }
}

if (!function_exists('huntor_woocommerce_get_product_label_new')) {
    function huntor_woocommerce_get_product_label_new() {
        global $product;
        $newness_days = 30;
        $created      = strtotime($product->get_date_created());
        if ((time() - (60 * 60 * 24 * $newness_days)) < $created) {
            echo '<span class="new-label"><span>' . esc_html__('New!', 'huntor') . '</span></span>';
        }
    }
}

if (!function_exists('huntor_woocommerce_get_product_label_sale')) {
    function huntor_woocommerce_get_product_label_sale() {
        /**
         * @var $product WC_Product
         */
        global $product;
        if ($product->is_on_sale() && $product->is_type('simple')) {
            $sale  = $product->get_sale_price();
            $price = $product->get_regular_price();
            $ratio = round(($price - $sale) / $price * 100);
            echo '<span class="onsale">-' . esc_html($ratio) . '%</span>';
        }
    }
}

if (!function_exists('huntor_woocommerce_get_product_label_feature')) {
    function huntor_woocommerce_get_product_label_feature() {
        /**
         * @var $product WC_Product
         */
        global $product;
        if ($product->is_featured()) {
            echo '<span class="trend"><span>' . esc_html__('Trend', 'huntor') . '</span></span>';
        }
    }
}

if (!function_exists('huntor_woocommerce_set_register_text')) {
    function huntor_woocommerce_set_register_text() {
        echo '<div class="user-text">' . __("Creating an account is quick and easy, and will allow you to move through our checkout quicker.", "huntor") . '</div>';
    }
}


if (!function_exists('huntor_header_cart_nav')) {
    /**
     * Display Header Cart
     *
     * @return string
     * @uses   huntor_is_woocommerce_activated() check if WooCommerce is activated
     * @since  1.0.0
     */

    function huntor_header_cart_nav() {
        if (huntor_is_woocommerce_activated()) {
            $items = '';
            $items .= '<li class="megamenu-item menu-item  menu-item-has-children menu-item-cart site-header-cart " data-level="0">';
            $items .= huntor_cart_link();
            if (!is_cart() && !is_checkout()) {
                $items .= '<ul class="shopping_cart_nav shopping_cart"><li><div class="widget_shopping_cart_content"></div></li></ul>';
            }
            $items .= '</li>';

            return $items;
        }

        return '';
    }
}

if (!function_exists('huntor_woocommerce_add_woo_cart_to_nav')) {
    function huntor_woocommerce_add_woo_cart_to_nav($items, $args) {

        if ('top' == $args->theme_location) {
            global $huntor_header;
            if ($huntor_header && $huntor_header instanceof WP_Post) {
                if (huntor_get_metabox($huntor_header->ID, 'huntor_enable_cart', false)) {
                    $items .= huntor_header_cart_nav();
                }

                return $items;
            }

            if (get_theme_mod('huntor_header_layout_enable_cart_in_menu', true)) {
                $items .= huntor_header_cart_nav();
            }
        }

        return $items;
    }
}

if (!function_exists('huntor_woocommerce_list_get_excerpt')) {
    function huntor_woocommerce_list_show_excerpt() {
        echo '<div class="product-excerpt">' . get_the_excerpt() . '</div>';
    }
}

if (!function_exists('huntor_woocommerce_list_get_rating')) {
    function huntor_woocommerce_list_show_rating() {
        global $product;
        echo wc_get_rating_html($product->get_average_rating());
    }
}

if (!function_exists('huntor_woocommerce_time_sale')) {
    function huntor_woocommerce_time_sale() {
        /**
         * @var $product WC_Product
         */
        global $product;
        $time_sale = get_post_meta($product->get_id(), '_sale_price_dates_to', true);
        if ($time_sale) {
            wp_enqueue_script('otf-countdown');
            $time_sale += (get_option('gmt_offset') * 3600);
            echo '<div class="time">
                    <div class="deal-text d-none">' . esc_html__('Hurry up. Offer end in', 'huntor') . '</div>
                    <div class="opal-countdown clearfix typo-quaternary"
                        data-countdown="countdown"
                        data-days="' . esc_html__("days", "huntor") . '" 
                        data-hours="' . esc_html__("hours", "huntor") . '"
                        data-minutes="' . esc_html__("mins", "huntor") . '"
                        data-seconds="' . esc_html__("secs", "huntor") . '"
                        data-Message="' . esc_html__('Expired', 'huntor') . '"
                        data-date="' . date('m', $time_sale) . '-' . date('d', $time_sale) . '-' . date('Y', $time_sale) . '-' . date('H', $time_sale) . '-' . date('i', $time_sale) . '-' . date('s', $time_sale) . '">
                    </div>
            </div>';
        }
    }
}
if (!function_exists('huntor_output_product_data_accordion')) {
    function huntor_output_product_data_accordion() {
        $tabs = apply_filters('woocommerce_product_tabs', array());
        if (!empty($tabs)) : ?>
            <div id="osf-accordion-container" class="woocommerce-tabs wc-tabs-wrapper">
                <?php $_count = 0;
                $classopen    = $_count == 0 ? 'accordion open' : ''; ?>
                <?php foreach ($tabs as $key => $tab) : ?>
                    <div data-accordion class="<?php echo esc_attr($classopen); ?>">
                        <div data-control class="<?php echo esc_attr($key); ?>_tab"
                             id="tab-title-<?php echo esc_attr($key); ?>">
                            <?php echo apply_filters('woocommerce_product_' . $key . '_tab_title', esc_html($tab['title']), $key); ?>
                        </div>
                        <div data-content>
                            <?php call_user_func($tab['callback'], $key, $tab); ?>
                        </div>
                    </div>
                    <?php $_count++; ?>
                <?php endforeach; ?>
            </div>
        <?php endif;
    }
}


if (!function_exists('huntor_woocommerce_cross_sell_display')) {
    function huntor_woocommerce_cross_sell_display() {
        woocommerce_cross_sell_display(get_theme_mod('huntor_woocommerce_cart_cross_sells_limit', 4), get_theme_mod('huntor_woocommerce_cart_cross_sells_columns', 2));
    }
}


function huntor_woocommerce_single_product_image_thumbnail_html($image, $attachment_id) {
    return wc_get_gallery_image_html($attachment_id, true);
}


if (!function_exists('huntor_single_product_video')) {
    function huntor_single_product_video() {
        global $product;
        $video = get_post_meta($product->get_id(), 'huntor_products_video', true);
        if (!$video) {
            return;
        }
        $video_thumbnail = get_post_meta($product->get_id(), 'huntor_products_video_thumbnail_id', true);
        if ($video_thumbnail) {
            $video_thumbnail = wp_get_attachment_image_url($video_thumbnail, 'thumbnail');
        } else {
            $video_thumbnail = wc_placeholder_img_src();
        }
        $video = wc_do_oembeds($video);
        echo '<div data-thumb="' . esc_url_raw($video_thumbnail) . '" class="woocommerce-product-gallery__image">
    <a>
        ' . $video . '

    </a>
</div>';
    }
}

if (!function_exists('huntor_single_product_social')) {
    function huntor_single_product_social() {
        if (get_theme_mod('huntor_socials')) {
            $template      = HUNTOR_CORE_PLUGIN_DIR . 'templates/socials.php';
            $socials_label = true;
            if (file_exists($template)) {
                require $template;
            }
        }
    }
}

if (!function_exists('huntor_single_product_review_author')) {
    function huntor_single_product_review_author() {
        echo '<strong class="woocommerce-review__author">' . get_comment_author() . ' </strong>';
    }
}

if (!function_exists('huntor_single_product_quantity_label')) {
    function huntor_single_product_quantity_label() {
        echo '<label class="quantity_label">' . __('Quantity:', 'huntor') . ' </label>';
    }
}

/**
 * Check if a product is a deal
 *
 * @param int|object $product
 *
 * @return bool
 */

if (!function_exists('huntor_woocommerce_is_deal_product')) {
    function huntor_woocommerce_is_deal_product($product) {
        $product = is_numeric($product) ? wc_get_product($product) : $product;

        // It must be a sale product first
        if (!$product->is_on_sale()) {
            return false;
        }

        if (!$product->is_in_stock()) {
            return false;
        }

        // Only support product type "simple" and "external"
        if (!$product->is_type('simple') && !$product->is_type('external')) {
            return false;
        }

        $deal_quantity = get_post_meta($product->get_id(), '_deal_quantity', true);

        if ($deal_quantity > 0) {
            return true;
        }

        return false;
    }
}


/**
 * Display deal progress on shortcode
 */
if (!function_exists('huntor_woocommerce_deal_progress')) {
    function huntor_woocommerce_deal_progress() {
        global $product;

        $limit = get_post_meta($product->get_id(), '_deal_quantity', true);
        $sold  = intval(get_post_meta($product->get_id(), '_deal_sales_counts', true));
        if (empty($limit)) {
            return;
        }

        ?>

        <div class="deal-sold">
            <span class="deal-text d-block"><span><?php esc_html_e('Hurry! only', 'huntor') ?></span>
                <span class="c-primary"><?php echo esc_attr(trim($limit - $sold)) ?></span> <span><?php esc_html_e('left in stock.', 'huntor') ?></span></span>
            <div>
                <div class="deal-progress">
                    <div class="progress-bar">
                        <div class="progress-value" style="width: <?php echo trim($sold / $limit * 100) ?>%"></div>
                    </div>
                </div>
            </div>
        </div>

        <?php
    }
}

if (!function_exists('huntor_woocommerce_single_deal')) {
    function huntor_woocommerce_single_deal() {
        global $product;


        if (!huntor_woocommerce_is_deal_product($product)) {
            return;
        }
        ?>

        <div class="opal-woocommerce-deal deal">
            <?php
            huntor_woocommerce_deal_progress();
            huntor_woocommerce_time_sale();
            ?>
        </div>
        <?php
    }
}


function otf_wc_track_product_view() {

    if (!is_singular('product')) {
        return;
    }

    global $post;

    if (!isset($_COOKIE['otf_woocommerce_recently_viewed']) || isset($_COOKIE['otf_woocommerce_recently_viewed']) && empty($_COOKIE['otf_woocommerce_recently_viewed'])) {
        $viewed_products = array();
    } else {
        $viewed_products = (array)explode('|', $_COOKIE['otf_woocommerce_recently_viewed']);
    }

    // Unset if already in viewed products list.
    $keys = array_flip($viewed_products);
    if (isset($keys[$post->ID])) {
        unset($viewed_products[$keys[$post->ID]]);
    }

    $viewed_products[] = $post->ID;

    if (count($viewed_products) > 15) {
        array_shift($viewed_products);
    }

    // Store for session only.
    wc_setcookie('otf_woocommerce_recently_viewed', implode('|', $viewed_products));
}

add_action('template_redirect', 'otf_wc_track_product_view', 20);


function filter_yith_woocompare_main_script_localize_array($var) {
    $var['loader'] = '';

    return $var;
}

add_filter('yith_woocompare_main_script_localize_array', 'filter_yith_woocompare_main_script_localize_array', 10, 1);

function filter_yith_quick_view_loader_gif() {
    return '';
}

add_filter('yith_quick_view_loader_gif', 'filter_yith_quick_view_loader_gif', 10, 1);


function huntor_woocommerce_single_product_image_gallery_classes($array) {
    global $product;
    $gallery = $product->get_gallery_image_ids();
    if (count($gallery) > 0) {
        $array[] = 'huntor_has_image_gallery';
    } else {
        $array[] = 'huntor_no_image_gallery';
    }

    return $array;
}

add_filter('woocommerce_single_product_image_gallery_classes', 'huntor_woocommerce_single_product_image_gallery_classes', 10, 1);

function huntor_woocommerce_pagination_args($args) {
    $args['prev_text'] = '<span class="opal-icon-angle-left"></span>' . __('PREV', 'huntor');
    $args['next_text'] = __('NEXT', 'huntor') . '<span class="opal-icon-angle-right"></span>';
    $args['type']      = 'plain';

    return $args;
}

add_filter('woocommerce_pagination_args', 'huntor_woocommerce_pagination_args', 10, 1);

// define the woocommerce_empty_price_html callback
function filter_woocommerce_empty_price_html($var, $instance) {
    return esc_html__('Free', 'huntor');
}

;

// add the filter
add_filter('woocommerce_empty_price_html', 'filter_woocommerce_empty_price_html', 10, 2);

/**
 * @snippet       Display "Quantity: #" @ WooCommerce Single Product Page
 * @how-to        Get CustomizeWoo.com FREE
 * @author        Rodolfo Melogli
 * @testedwith    WooCommerce 3.6.2
 */

//add_filter( 'woocommerce_get_availability_text', 'woocommerce_custom_get_availability_text', 99, 2 );

function woocommerce_custom_get_availability_text($availability, $product) {
    $availability = '<span class="label">' . esc_html__('Availability: ', 'huntor') . '</span><span class="stock-availability">' . $availability . '</span>';

    return $availability;
}

if (!function_exists('huntor_button_grid_list_layout')) {
    function huntor_button_grid_list_layout() {
        ?>
        <div class="gridlist-toggle desktop-hide-down">
            <a href="<?php echo esc_url(add_query_arg('display', 'grid')); ?>" id="grid" class="<?php echo isset($_GET['display']) && $_GET['display'] == 'list' ? '' : 'active'; ?>" title="<?php echo esc_html__('Grid View', 'huntor'); ?>"><i class="opal-icon-th-grid" aria-hidden="true"></i></a>
            <a href="<?php echo esc_url(add_query_arg('display', 'list')); ?>" id="list" class="<?php echo isset($_GET['display']) && $_GET['display'] == 'list' ? 'active' : ''; ?>" title="<?php echo esc_html__('List View', 'huntor'); ?>"><i class="opal-icon-th-list" aria-hidden="true"></i></a>
        </div>
        <?php
    }
}


add_filter('woocommerce_sale_flash', 'huntor_woocommerce_show_product_sale_flash');
function huntor_woocommerce_show_product_sale_flash() {

    global $post, $product;
    if ($product->is_on_sale()) :

        return '<span class="onsale"><span>' . esc_html__('Sale!', 'huntor') . '</span></span>';
    endif;

}