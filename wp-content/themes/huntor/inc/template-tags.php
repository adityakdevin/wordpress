<?php
if (!function_exists('huntor_entry_meta')) :
    /**
     * Prints HTML with meta information for the current post-date/time and author.
     */
    function huntor_entry_meta() {
        echo '<div class="meta-inner">';
        // Get the author name; wrap it in a link.
        $byline = sprintf(
        /* translators: %s: post author */
            esc_html__('Post By', 'huntor') . ' %s',
            '<span class="author vcard"><a class="url fn n" href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . get_the_author() . '</a></span>'
        );

        if (is_single()) huntor_cat_links();
        echo '<span class="posted-on"><i class="fa fa-calendar-alt"></i> ' . huntor_time_link() . '</span><span class="byline"> ' . $byline . '</span>';

        // Finally, let's write all of this to the page.
        echo '</div>';
    }
endif;


if (!function_exists('huntor_cat_links')) :
    /**
     * Prints HTML with meta information for the current post-date/time and author.
     */
    function huntor_cat_links() {
        /* translators: used between list items, there is a space after the comma */
        $separate_meta = esc_html__(' ', 'huntor');

        // Get Categories for posts.
        $categories_list = osf_get_the_category_list($separate_meta);

        if ('post' === get_post_type()) {
            // Make sure there's more than one category before displaying.
            if ($categories_list && huntor_categorized_blog()) {
                echo '<span class="cat-links"><span class="screen-reader-text">' . esc_html__('Categories', 'huntor') . '</span>' . wp_kses_post($categories_list) . '</span>';
            }
        }
    }
endif;

if (!function_exists('huntor_count_comment')) :
    function huntor_count_comment() {
        echo '<span class="entry-comment" ><i class="fa fa-comments"></i> ' . get_comments_number() . ' ' . _n("Comment", "Comments", get_comments_number(), "huntor") . '</span>';
    }
endif;

if (!function_exists('huntor_time_link')) :
    /**
     * Gets a nicely formatted string for the published date.
     */
    function huntor_time_link() {
        $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
        if (get_the_time('U') !== get_the_modified_time('U')) {
            $time_string = '<a href="' . esc_url(get_permalink()) . '" rel="bookmark">' . '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time></a>';
        }

        $time_string = sprintf($time_string,
            get_the_date(DATE_W3C),
            get_the_date(),
            get_the_modified_date(DATE_W3C),
            get_the_modified_date()
        );

        // Wrap the time string in a link, and preface it with 'Posted on'.
        return $time_string;
    }
endif;

add_action('huntor_single_entry_footer', 'huntor_entry_footer', 10);
if (!function_exists('huntor_entry_footer')):
    /**
     * Prints HTML with meta information for the categories, tags and comments.
     */
    function huntor_entry_footer() {

        /* translators: Used between list items, there is a space after the comma. */
        $separate_meta = __(', ', 'huntor');

        // Get Tags for posts.
        $tags_list = get_the_tag_list('', ' ');

        // Get Categories for posts.
        $categories_list = get_the_category_list($separate_meta);

        // We don't want to output .entry-footer if it will be empty, so make sure its not.

        if ('post' === get_post_type()) {
            if ((huntor_is_osf_framework_activated() && get_theme_mod('osf_socials')) || ((huntor_categorized_blog() && $categories_list) || $tags_list)) {
                echo '<div class="entry-footer">';

                if ((huntor_is_osf_framework_activated() && get_theme_mod('osf_socials') || $tags_list)) {
                    echo '<div class="cat-tags-links">';
                    if ($tags_list) {
                        echo '<span class="tags-links"><span class="screen-reader-text">' . esc_html__('Tags: ', 'huntor') . '</span>' . wp_kses_post($tags_list) . '</span>'; // WPCS: XSS ok.
                    }

                    huntor_social_share();
                    echo '</div>';
                }

                echo '</div> <!-- .entry-footer -->';
            }

        }
    }
endif;


if (!function_exists('huntor_edit_link')) :
    /**
     * Returns an accessibility-friendly link to edit a post or page.
     *
     * This also gives us a little context about what exactly we're editing
     * (post or page?) so that users understand a bit more where they are in terms
     * of the template hierarchy and their content. Helpful when/if the single-page
     * layout with multiple posts/pages shown gets confusing.
     */
    function huntor_edit_link() {
        edit_post_link(
            sprintf(
            /* translators: %s: Name of current post */
                esc_html__('Edit', 'huntor') . '<span class="screen-reader-text"> "%s"</span>',
                get_the_title()
            ),
            '<span class="edit-link">',
            '</span>'
        );
    }
endif;

if (!function_exists('huntor_post_thumbnail')) :
    /**
     * Displays an optional post thumbnail.
     *
     * Wraps the post thumbnail in an anchor element on index views, or a div
     * element when on single views.
     */
    function huntor_post_thumbnail() {
        if (!huntor_can_show_post_thumbnail()) {
            return;
        }

        if (is_singular()) :
            ?>

            <figure class="post-thumbnail">
                <?php the_post_thumbnail(); ?>
            </figure><!-- .post-thumbnail -->

        <?php
        else :
            ?>

            <figure class="post-thumbnail">
                <a class="post-thumbnail-inner" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                    <?php the_post_thumbnail('post-thumbnail'); ?>
                </a>
            </figure>

        <?php
        endif; // End is_singular().
    }
endif;

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function huntor_categorized_blog() {
    $category_count = get_transient('huntor_categories');

    if (false === $category_count) {
        // Create an array of all the categories that are attached to posts.
        $categories = get_categories(array(
            'fields'     => 'ids',
            'hide_empty' => 1,
            // We only need to know if there is more than one category.
            'number'     => 2,
        ));

        // Count the number of categories that are attached to the posts.
        $category_count = count($categories);

        set_transient('huntor_categories', $category_count);
    }

    // Allow viewing case of 0 or 1 categories in post preview.
    if (is_preview()) {
        return true;
    }

    return $category_count > 1;
}


/**
 * Flush out the transients used in huntor_categorized_blog.
 */
function huntor_category_transient_flusher() {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    // Like, beat it. Dig?
    delete_transient('huntor_categories');
}

add_action('edit_category', 'huntor_category_transient_flusher');
add_action('save_post', 'huntor_category_transient_flusher');
