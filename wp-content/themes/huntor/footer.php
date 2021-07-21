</div><!-- #content -->
</div><!-- .site-content-contain -->
<footer id="colophon" class="site-footer">
	<?php
	if ( huntor_is_footer_builder() ) {
		huntor_the_footer_builder();
	} else {
		get_template_part( 'template-parts/footer' );
	}

	do_action( 'opal-render-footer' );
	?>
</footer><!-- #colophon -->
</div><!-- #page -->
<?php do_action( 'opal_end_wrapper' ) ?>
</div><!-- end.opal-wrapper-->
<?php wp_footer(); ?>
</body>
</html>