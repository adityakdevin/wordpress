<?php
get_header(); ?>
    <div class="wrap">
        <div id="primary" class="content-area">
            <main id="main" class="site-main">

				<?php
				if ( have_posts() ) :
					/* Start the Loop */
					while ( have_posts() ) : the_post();

						/**
						 * Run the loop for the search to output the results.
						 * If you want to overload this in a child theme then include a file
						 * called content-search.php and that will be used instead.
						 */
						get_template_part( 'template-parts/post/content', 'excerpt' );

					endwhile; // End of the loop.

					the_posts_pagination( array(
						'prev_text'          => '<span class="opal-icon-angle-left"></span><span>' . esc_html__( 'Previous', 'huntor' ) . '</span>',
						'next_text'          => '<span>' . esc_html__( 'Next', 'huntor' ) . '</span><span class="opal-icon-angle-right"></span>',
						'before_page_number' => '<span class="meta-nav screen-reader-text">' . esc_html__( 'Page', 'huntor' ) . ' </span>',
					) );

				else : ?>
                    <div class="post-content">
                        <?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'huntor' ); ?>
                    </div>

				<?php
				endif;
				?>

            </main><!-- #main -->
        </div><!-- #primary -->
		<?php get_sidebar(); ?>
    </div><!-- .wrap -->

<?php get_footer();
