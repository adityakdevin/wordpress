<?php
/**
 * @return string
 */
function huntor_custom_css() {

	$css = <<<CSS
CSS;
	/**
	 * Filters Huntor custom colors CSS.
	 *
	 * @param string $css Base theme colors CSS.
	 *
	 * @since Huntor 1.0
	 *
	 */
	$css = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css );
	$css = str_replace( ': ', ':', $css );
	$css = str_replace( array( "\r\n", "\r", "\n", "\t", '  ', '    ', '    ' ), '', $css );

	return apply_filters( 'ezboozt_theme_customizer_css', $css );
}