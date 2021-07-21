<?php
function huntor_register_required_plugins() {
	/**
	 * Array of plugin arrays. Required keys are name and slug.
	 * If the source is NOT from the .org repo, then source is also required.
	 */
	$plugins = array(
		array(
			'name'     => 'Huntor Core',
			'slug'     => 'huntor-core',
			'required' => true,
			'source'   => esc_url( 'http://source.wpopal.com/huntor/dummy_data/huntor-core.zip' ),
		),
		array(
			'name'     => 'Elementor',
			'slug'     => 'elementor',
            'version'  => '2.9.9', // Theme will work best when first installed and imported the content by installing a One-click demo template
			'required' => true,
		),
		array(
			'name'     => 'Woocommerce',
			'slug'     => 'woocommerce',
			'required' => true,
		),
		array(
			'name'     => 'Granular Controls Elementor',
			'slug'     => 'granular-controls-for-elementor',
			'required' => true,
		),
		array(
			'name'     => 'Breadcrumb NavXT',
			'slug'     => 'breadcrumb-navxt',
			'required' => true,
		),
		array(
			'name'     => 'SVG Support',
			'slug'     => 'svg-support',
			'required' => true,
		),
		array(
			'name'     => 'Contact Form 7',
			'slug'     => 'contact-form-7',
			'required' => true,
		),
		array(
			'name'     => 'Classic Editor',
			'slug'     => 'classic-editor',
			'required' => true,
		),
		array(
			'name'     => 'MailChimp',
			'slug'     => 'mailchimp-for-wp',
			'required' => false,
		),
		array(
			'name'     => 'Yith Compare',
			'slug'     => 'yith-woocommerce-compare',
			'required' => false,
		),
		array(
			'name'     => 'Yith Quick View',
			'slug'     => 'yith-woocommerce-quick-view',
			'required' => false,
		),
		array(
			'name'     => 'Yith Wishlist',
			'slug'     => 'yith-woocommerce-wishlist',
			'required' => false,
		),
		array(
			'name'     => 'Woo Variation Swatches',
			'slug'     => 'woo-variation-swatches',
			'required' => true,
		),
		array(
			'name'     => 'Slider Revolution',
			'slug'     => 'revslider',
			'required' => true,
			'source'   => esc_url( 'http://source.wpopal.com/plugins/new/revslider.zip' ),
		)
	);

	/*
	 * Array of configuration settings. Amend each line as needed.
	 *
	 * TGMPA will start providing localized text strings soon. If you already have translations of our standard
	 * strings available, please help us make TGMPA even better by giving us access to these translations or by
	 * sending in a pull-request with .po file(s) with the translations.
	 *
	 * Only uncomment the strings in the config array if you want to customize the strings.
	 */
	$config = array(
		'id'           => 'huntor',
		// Unique ID for hashing notices for multiple instances of TGMPA.
		'default_path' => '',
		// Default absolute path to bundled plugins.
		'has_notices'  => false,
		// Show admin notices or not.
		'dismissable'  => true,
		// If false, a user cannot dismiss the nag message.
		'dismiss_msg'  => '',
		// If 'dismissable' is false, this message will be output at top of nag.
		'is_automatic' => false,
		// Automatically activate plugins after installation or not.
		'message'      => '',
		// Message to output right before the plugins table.
	);

	tgmpa( $plugins, $config );
}

add_action( 'tgmpa_register', 'huntor_register_required_plugins' );