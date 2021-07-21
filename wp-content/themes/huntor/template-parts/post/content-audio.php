<?php
$content = apply_filters('the_content', get_the_content());
$audio   = false;

// Only get audio from the content if a playlist isn't present.
if (false === strpos($content, 'wp-playlist-script')) {
    $audio = get_media_embedded_in_content($content, array('audio'));
}
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div class="post-inner">
        <div class="post-content">
            <?php if (!is_single()) {
                huntor_post_thumbnail();
            } ?>

            <header class="entry-header">
                <?php
                if (!is_single()) huntor_cat_links();
                if ('post' === get_post_type()) : ?>
                    <div class="entry-meta">
                        <?php
                        huntor_entry_meta(); ?>
                    </div><!-- .entry-meta -->
                <?php endif;


                if (is_single()) {
                    the_title('<h1 class="entry-title">', '</h1>');
                } elseif (is_front_page() && is_home()) {
                    the_title('<h3 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h3>');
                } else {
                    the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>');
                } ?>

            </header><!-- .entry-header -->

            <?php if (is_single()) {
                huntor_post_thumbnail();
            } ?>

            <div class="entry-content">

                <?php
                if (!is_single()) {

                    // If not a single post, highlight the audio file.
                    if (!empty($audio)) {
                        foreach ($audio as $audio_html) {
                            echo '<div class="entry-audio">';
                            echo apply_filters('the_content', $audio_html);
                            echo '</div><!-- .entry-audio -->';
                        }
                    };

                };

                if (is_single() || empty($audio)) {

                    the_content(
                        sprintf(
                        /* translators: %s: Post title. */
                            __('<span>Read more</span><span class="screen-reader-text"> "%s"</span>', 'huntor'),
                            get_the_title()
                        )
                    );

                    wp_link_pages(
                        array(
                            'before'      => '<div class="page-links">' . esc_html__('Pages:', 'huntor'),
                            'after'       => '</div>',
                            'link_before' => '<span class="page-number">',
                            'link_after'  => '</span>',
                        )
                    );

                };
                ?>

            </div><!-- .entry-content -->

        </div> <!-- #Post-content -## -->
    </div> <!-- #Post-inner -## -->
</article><!-- #post-<?php the_ID(); ?> -->