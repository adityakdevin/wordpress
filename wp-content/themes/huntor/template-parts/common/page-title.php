<?php

if (!huntor_page_enable_breadcrumb()) {
    return;
}

// Get the query & post information
global $post, $wp_query;

$otf_sep   = ' > ';
$otf_class = 'breadcrumbs clearfix';
$otf_home  = esc_html__('Home', 'huntor');
$otf_blog  = esc_html__('Blog', 'huntor');
$otf_shop  = esc_html__('Shop', 'huntor');
$otf_title = '';

// Get the query & post information
global $post, $wp_query;

// Get post category
$otf_category = get_the_category();

// Get product category
$otf_product_cat = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));

if ($otf_product_cat) {
    $otf_tax_title = $otf_product_cat->name;
}

if (!function_exists('bcn_display')) {
    $otf_output = '';

    if (!is_front_page()) {
        $otf_output .= '<ul class="' . esc_attr($otf_class) . ' list-inline m-0">';
        $otf_output .= '<li class="list-inline-item item home"><a href="' . esc_url(get_home_url()) . '" title="' . esc_attr($otf_home) . '">' . wp_kses_post($otf_home) . '</a></li>';
        $otf_output .= '<li class="list-inline-item separator"> ' . wp_kses_post($otf_sep) . ' </li>';
        if (is_home()) {
            // Home page
            $otf_output .= '<li class="list-inline-item separator"> ' . wp_kses_post($otf_blog) . ' </li>';
            $otf_title  = $otf_blog;

        } elseif (function_exists('is_shop') && is_shop()) {

            $otf_output .= '<li class="list-inline-item item current">' . wp_kses_post($otf_shop) . '</li>';
            $otf_title  = $otf_shop;

        } elseif (function_exists('is_product') && is_product() || function_exists('is_cart') && is_cart() || function_exists('is_checkout') && is_checkout() || function_exists('is_account_page') && is_account_page()) {
            $otf_title  = get_the_title();
            $otf_output .= '<li class="list-inline-item item"><a href="' . esc_url(get_post_type_archive_link('product')) . '" title="' . esc_attr($otf_home) . '">' . wp_kses_post($otf_shop) . '</a></li>';
            $otf_output .= '<li class="list-inline-item separator"> ' . wp_kses_post($otf_sep) . ' </li>';
            $otf_output .= '<li class="list-inline-item item current">' . wp_kses_post($otf_title) . '</li>';
        } elseif (function_exists('is_product_category') && is_product_category()) {

            $otf_output .= '<li class="list-inline-item item"><a href="' . esc_url(get_post_type_archive_link('product')) . '" title="' . esc_attr($otf_home) . '">' . wp_kses_post($otf_shop) . '</a></li>';
            $otf_output .= '<li class="list-inline-item separator"> ' . wp_kses_post($otf_sep) . ' </li>';
            $otf_output .= '<li class="list-inline-item item current">' . wp_kses_post($otf_tax_title) . '</li>';
            $otf_title  = $otf_tax_title;

        } elseif (function_exists('is_product_tag') && is_product_tag()) {
            $otf_output .= '<li class="list-inline-item item"><a href="' . esc_url(get_post_type_archive_link('product')) . '" title="' . esc_attr($otf_home) . '">' . wp_kses_post($otf_shop) . '</a></li>';
            $otf_output .= '<li class="list-inline-item separator"> ' . wp_kses_post($otf_sep) . ' </li>';
            $otf_output .= '<li class="list-inline-item item current">' . wp_kses_post($otf_tax_title) . '</li>';
            $otf_title  = $otf_tax_title;

        } elseif (is_single()) {
            $otf_title    = get_the_title();
            $post_type    = get_post_type();
            $otf_category = get_the_category();
            if ('post' == $post_type && !empty($otf_category)) {
                // First post category
                $otf_output .= '<li class="list-inline-item item"><a href="' . esc_url(get_category_link($otf_category[0]->term_id)) . '" title="' . esc_attr($otf_category[0]->cat_name) . '">' . esc_html($otf_category[0]->cat_name) . '</a></li>';
                $otf_output .= '<li class="list-inline-item separator"> ' . wp_kses_post($otf_sep) . ' </li>';

            }
            $otf_output .= '<li class="list-inline-item item current">' . wp_kses_post($otf_title) . '</li>';

        } elseif (is_archive() && is_tax() && !is_category() && !is_tag()) {
            $tax_object = get_queried_object();
            if (!empty($tax_object)) {
                $otf_title  = esc_html($tax_object->name);
                $otf_output .= '<li class="list-inline-item item current">' . wp_kses_post($otf_title) . '</li>';
            }
        } elseif (is_category()) {
            // Home page
            $otf_title = single_cat_title('', false);
            // Category page
            $otf_output .= '<li class="list-inline-item item current">' . wp_kses_post($otf_title) . '</li>';

        } elseif (is_page()) {
            $otf_title = get_the_title();
            // Standard page
            if ($post->post_parent) {

                // If child page, get parents
                $otf_anc = get_post_ancestors($post->ID);

                // Get parents in the right order
                $otf_anc = array_reverse($otf_anc);

                // Parent page loop
                foreach ($otf_anc as $otf_ancestor) {
                    $otf_parents = '<li class="list-inline-item item"><a href="' . esc_url(get_permalink($otf_ancestor)) . '" title="' . esc_attr(get_the_title($otf_ancestor)) . '">' . get_the_title($otf_ancestor) . '</a></li>';
                    $otf_parents .= '<li class="list-inline-item separator"> ' . wp_kses_post($otf_sep) . ' </li>';
                }

                // Display parent pages
                $otf_output .= $otf_parents;

                // Current page
                $otf_output .= '<li class="list-inline-item item current"> ' . wp_kses_post($otf_title) . '</li>';

            } else {

                // Just display current page if not parents
                $otf_output .= '<li class="list-inline-item item current"> ' . wp_kses_post($otf_title) . '</li>';

            }

        } elseif (is_tag()) {
            // Get tag information
            $otf_term_id  = get_query_var('tag_id');
            $otf_taxonomy = 'post_tag';
            $otf_args     = 'include=' . esc_attr($otf_term_id);
            $otf_terms    = get_terms($otf_taxonomy, $otf_args);

            // Display the tag name
            if (isset($otf_terms[0]->name)) {
                $otf_title  = $otf_terms[0]->name;
                $otf_output .= '<li class="list-inline-item item current">' . esc_html($otf_terms[0]->name) . '</li>';
            }

        } elseif (is_day()) {
            $otf_title = esc_html__('Day', 'huntor');
            // Day archive

            // Year link
            $otf_output .= '<li class="list-inline-item item"><a href="' . esc_url(get_year_link(get_the_time('Y'))) . '" title="' . esc_attr(get_the_time('Y')) . '">' . get_the_time('Y') . '</a></li>';
            $otf_output .= '<li class="list-inline-item separator"> ' . wp_kses_post($otf_sep) . ' </li>';

            // Month link
            $otf_output .= '<li class="list-inline-item item"><a href="' . esc_url(get_month_link(get_the_time('Y'), get_the_time('m'))) . '" title="' . esc_attr(get_the_time('M')) . '">' . get_the_time('M') . '</a></li>';
            $otf_output .= '<li class="list-inline-item separator"> ' . wp_kses_post($otf_sep) . ' </li>';

            // Day display
            $otf_output .= '<li class="list-inline-item item current"> ' . get_the_time('jS') . ' ' . get_the_time('M') . '</li>';

        } elseif (is_month()) {
            $otf_title = get_the_time('F');
            // Month Archive

            // Year link
            $otf_output .= '<li class="list-inline-item item"><a href="' . esc_url(get_year_link(get_the_time('Y'))) . '" title="' . esc_attr(get_the_time('Y')) . '">' . get_the_time('Y') . '</a></li>';
            $otf_output .= '<li class="list-inline-item separator"> ' . wp_kses_post($otf_sep) . ' </li>';

            // Month display
            $otf_output .= '<li class="list-inline-item item">' . wp_kses_post($otf_title) . '</li>';

        } elseif (is_year()) {
            $otf_title = get_the_time('Y');
            // Display year archive
            $otf_output .= '<li class="list-inline-item item current">' . wp_kses_post($otf_title) . '</li>';

        } elseif (is_author()) {
            global $author;
            if (!empty($author->ID)) {
                $otf_userdata = get_userdata($author->ID);
                $otf_title    = esc_html__('Author: ', 'huntor') . wp_kses_post($otf_userdata->display_name);
                // Get the author information
                $otf_output .= '<li class="list-inline-item item current">' . esc_html__('Author: ', 'huntor') . '<a href="' . get_author_posts_url($otf_userdata->ID, $otf_userdata->nice_name) . '">' . esc_html($otf_userdata->display_name) . '</a></li>';
            }

        } elseif (get_query_var('paged')) {
            // Paginated archives
            $otf_output .= '<li class="list-inline-item item current">' . esc_html__('Page', 'huntor') . ' ' . get_query_var('paged', '1') . '</li>';

        } elseif (is_search()) {
            $otf_title  = esc_html__('Search', 'huntor');
            $otf_output .= '<li class="list-inline-item item current">' . esc_html__('Keyword: ', 'huntor') . get_search_query() . '</li>';

        } elseif (is_404()) {
            $otf_title  = esc_html__('Error 404', 'huntor');
            $otf_output .= '<li class="list-inline-item item current">' . wp_kses_post($otf_title) . '</li>';
        }
    }
    $otf_output .= '</ul>';
} elseif
(!is_front_page()) {
    if (is_home()) {
        $otf_title = $otf_blog;
    } elseif (function_exists('is_shop') && is_shop()) {
        $otf_title = $otf_shop;

    } elseif (function_exists('is_product') && is_product() || function_exists('is_cart') && is_cart() || function_exists('is_checkout') && is_checkout() || function_exists('is_account_page') && is_account_page()) {
        $otf_title = get_the_title();
    } elseif (function_exists('is_product_category') && is_product_category()) {

        $otf_title = $otf_tax_title;

    } elseif (function_exists('is_product_tag') && is_product_tag()) {
        $otf_title = $otf_tax_title;

    } elseif (is_page()) {
        $otf_title = get_the_title();

    } elseif (is_single()) {
        $otf_title = get_the_title();

    } elseif (is_archive() && is_tax() && !is_category() && !is_tag()) {
        $tax_object = get_queried_object();
        if (!empty($tax_object)) {
            $otf_title = esc_html($tax_object->name);
        }
    } elseif (is_category()) {
        // Home page
        $otf_title = single_cat_title('', false);

    } elseif (is_tag()) {
        // Get tag information
        $otf_term_id  = get_query_var('tag_id');
        $otf_taxonomy = 'post_tag';
        $otf_args     = 'include=' . esc_attr($otf_term_id);
        $otf_terms    = get_terms($otf_taxonomy, $otf_args);

        // Display the tag name
        if (isset($otf_terms[0]->name)) {
            $otf_title = $otf_terms[0]->name;
        }

    } elseif (is_day()) {
        $otf_title = get_the_time('D');
    } elseif (is_month()) {
        $otf_title = get_the_time('F');
    } elseif (is_year()) {
        $otf_title = get_the_time('Y');
    } elseif (is_author()) {
        global $author;
        if (!empty($author->ID)) {
            $otf_title = esc_html__('Author', 'huntor');
        }

    } elseif (get_query_var('paged')) {
    } elseif (is_search()) {
        $otf_title = esc_html__('Search', 'huntor');

    } elseif (is_404()) {
        $otf_title = esc_html__('Error 404', 'huntor');
    }
}
?>

<div class="container">
    <div class="wrap w-100 d-flex align-items-left">
        <div class="page-title-bar-inner d-flex flex-column align-items-left w-100">
            <?php if (is_page()) { ?>
                <div class="page-header">
                    <?php the_title('<h1 class="page-title typo-heading">', '</h1>'); ?>
                </div>
            <?php } elseif (is_404() || is_archive() || is_search() || is_home()) { ?>
                <div class="page-header">
                    <?php echo '<h1 class="page-title typo-heading">' . wp_kses_post($otf_title) . '</h1>'; ?>
                </div>
            <?php } ?>

            <div class="breadcrumb">
                <?php if (function_exists('bcn_display')): ?>
                    <?php bcn_display(); ?>
                <?php else: ?>
                    <?php echo wp_kses_post($otf_output); // WPCS: XSS ok. ?>
                <?php endif; ?>
            </div>

        </div>
    </div>
</div>
