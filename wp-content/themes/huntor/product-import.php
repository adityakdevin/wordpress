<?php
/*
Template Name: Product Import From JSON
Template Post Type: post, page, event
*/
require_once( ABSPATH . 'wp-load.php' );
require_once( ABSPATH . "wp-admin" . '/includes/image.php' );
require_once( ABSPATH . "wp-admin" . '/includes/file.php' );
require_once( ABSPATH . "wp-admin" . '/includes/media.php' );
function getWoocommerceConfig() {
	return WC();
}

function getJsonFromFile() {
	$file     = 'https://extensionsell.com/app/xml/export2.php?shop=cutting-edge-products-inc-dev&output=json&save=1';
	$products = json_decode( file_get_contents( $file ), true )['Product'];

	return $product = array_slice( $products, 0, 3 );
}

function checkProductBySku( $sku ) {
	global $wpdb;

	$product_id = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $sku ) );

	if ( $product_id ) {
		$product = new WC_Product( $product_id );

		return $product->get_id();
	}

	return null;
}

function createProducts() {
	$woocommerce = getWoocommerceConfig();
	$products    = getJsonFromFile();
	$imgCounter  = 0;
	foreach ( $products as $product ) {
		$productExist    = checkProductBySku( $product['sku'] );
		$imagesFormatted = array();
		$name            = $product['name'];
		$sku             = $product['sku'];
		$description     = $product['description'];
		$images          = $product['images']['image'];
		$category        = $product['category'];
		$categoriesIds   = array();

		if ( ! empty( $images ) ) {
			foreach ( $images as $image ) {
				$url               = strtok( $image, '?' );
				$imagesFormatted[] = (string) trim( $url );
				$imgCounter ++;
			}
		}
		/*
		if ( ! empty( $categories ) ) {
			foreach ( $categories as $category ) {
				$categoriesIds[] = [ 'id' => getCategoryIdByName( $category ) ];
			}
		}
		*/

		$finalProduct = [
			'post_author'  => 3,
			'post_title'   => $name,
			'post_type'    => 'product',
			'post_status'  => 'publish',
			'post_content' => $description,
		];

		if ( ! empty( $product ) ) {
			$url_array = explode( '/', $product['url'] );
			$slug      = end( $url_array );
		}

		$price    = (float) str_replace( $product['compare_at_price'], '', $product['price'] );
		$in_stock = ( $product['in_stock'] == 'In Stock' ) ? 'instock' : 'no';
		$data     = [
			'sku'      => $sku,
			'price'    => $price,
			'quantity' => $product['quantity'],
			'in_stock' => $in_stock,
			'weight'   => $product['weight']
		];
		if ( ! $productExist ) {
			$post_id = wp_insert_post( $finalProduct );
			update_product_fields( $post_id, $data );
		} else {
			$post_id = $productExist->get_id();
			update_product_fields( $post_id, $data );
		}
		attach_images( $post_id, $imagesFormatted );
	}
}

function update_product_fields( $post_id, $data ) {
	wp_set_object_terms( $post_id, 'variable', 'product_type' );
	update_post_meta( $post_id, '_visibility', 'visible' );
	update_post_meta( $post_id, '_stock_status', $data['in_stock'] );
	update_post_meta( $post_id, '_downloadable', 'no' );
	update_post_meta( $post_id, '_virtual', 'no' );
	update_post_meta( $post_id, '_regular_price', $data['price'] );
	update_post_meta( $post_id, '_weight', $data['weight'] );
	update_post_meta( $post_id, '_sku', $data['sku'] );
	update_post_meta( $post_id, '_product_attributes', array() );
	update_post_meta( $post_id, '_sale_price_dates_from', '' );
	update_post_meta( $post_id, '_sale_price_dates_to', '' );
	update_post_meta( $post_id, '_price', $data['price'] );
	update_post_meta( $post_id, '_manage_stock', 'yes' );
	update_post_meta( $post_id, '_backorders', 'no' );
	update_post_meta( $post_id, '_stock', $data['quantity'] );
}

function attach_images( $post_id, $images ) {
	$file           = array();
	$i              = 0;
	$image_id_array = [];
	foreach ( $images as $url ) {
		$file['name']     = $url;
		$file['tmp_name'] = download_url( $url );
		if ( is_wp_error( $file['tmp_name'] ) ) {
			@unlink( $file['tmp_name'] );
			var_dump( $file['tmp_name']->get_error_messages() );
		} else {
			$attachmentId = media_handle_sideload( $file, $post_id );
			if ( is_wp_error( $attachmentId ) ) {
				@unlink( $file['tmp_name'] );
				var_dump( $attachmentId->get_error_messages() );
			} else {
				$image_id_array[] = $attachmentId;
				if ( $i == 0 ) {
					set_post_thumbnail( $post_id, $attachmentId );
				}
				$i ++;
			}
		}
	}
	if ( ! empty( $attachmentIds ) ) {
		if ( sizeof( $image_id_array ) > 1 ) {
			array_shift( $image_id_array );
			update_post_meta( $post_id, '_product_image_gallery', implode( ',', $image_id_array ) );
		}
	}
}

function getProductAttributesNames( $articles ) {
	$keys = array();
	foreach ( $articles as $article ) {
		$terms = $article['config'];
		foreach ( $terms as $key => $term ) {
			array_push( $keys, $key );
		}
	}
	$keys       = array_unique( $keys );
	$configs    = array_column( $articles, 'config' );
	$attributes = array();
	foreach ( $keys as $key ) {
		$attributes = array(
			array(
				'name'      => $key,
				'slug'      => 'attr_' . $key,
				'visible'   => true,
				'variation' => true,
				'options'   => getTermsByKeyName( $key, $configs )
			)
		);
	}

	return $attributes;
}

function getTermsByKeyName( $keyName, $configList ) {
	$options = array();
	foreach ( $configList as $config ) {
		foreach ( $config as $key => $term ) {
			if ( $key == $keyName ) {
				array_push( $options, $term );
			}
		}
	}

	return $options;
}

function prepareInitialConfig() {
	echo ( 'Importing data, wait...' ) . "\n";
	createCategories();
	createProducts();
	echo ( 'Done!' ) . "\n";
}

/**
 * @throws WC_Data_Exception
 */
function createNewProducts( $product ) {
	$product_id = checkProductBySku( $product['sku'] );
	$objProduct = ! empty( $product_id ) ? new WC_Product_Variable( $product_id ) : new WC_Product_Variable();
	$price      = (float) str_replace( $product['compare_at_price'], '', $product['price'] );
	$is_stock   = $product['in_stock'] == 'In Stock';
	$in_stock   = $is_stock ? 'instock' : 'outstock';
	$categories = get_category_ids( explode( ',', $product['category'] ) );

	$objProduct->set_name( $product['name'] );
	$objProduct->set_status( "publish" );
	$objProduct->set_catalog_visibility( "visible" );
	$objProduct->set_description( $product['description'] );
	$objProduct->set_sku( $product['sku'] );
	$objProduct->set_weight( $product['weight'] );
	$objProduct->set_price( $price );
	$objProduct->set_regular_price( $price );
	$objProduct->set_manage_stock( $is_stock );
	$objProduct->set_stock_quantity( $product['quantity'] );
	$objProduct->set_stock_status( $in_stock );
	$objProduct->set_backorders( 'no' );
	$objProduct->set_reviews_allowed( true );
	$objProduct->set_sold_individually( false );
	$objProduct->set_category_ids( $categories );
	$images           = formatted_images( $product );
	$productImagesIDs = get_image_ids( $images );
	/*
	  $productImagesIDs = array();
	  foreach ( $images as $image ) {
		$mediaID = uploadMedia( $image );
		if ( $mediaID ) {
			$productImagesIDs[] = $mediaID;
		}
	}
	*/
	if ( $productImagesIDs ) {
		$objProduct->set_image_id( $productImagesIDs[0] );
		if ( count( $productImagesIDs ) > 1 ) {
			array_shift( $productImagesIDs );
			$objProduct->set_gallery_image_ids( $productImagesIDs );
		}
	}
	$product_id = $objProduct->save();

	$attributes = array(
		array(
			"name"      => "Size",
			"options"   => array( "S", "L", "XL", "XXL" ),
			"position"  => 1,
			"visible"   => 1,
			"variation" => 1
		),
		array(
			"name"      => "Color",
			"options"   => array( "Red", "Blue", "Black", "White" ),
			"position"  => 2,
			"visible"   => 1,
			"variation" => 1
		)
	);
	if ( $attributes ) {
		$productAttributes = array();
		foreach ( $attributes as $attribute ) {
			$attr = wc_sanitize_taxonomy_name( stripslashes( $attribute["name"] ) ); // remove any unwanted chars and return the valid string for taxonomy name
			$attr = 'pa_' . $attr; // woocommerce prepend pa_ to each attribute name
			if ( $attribute["options"] ) {
				foreach ( $attribute["options"] as $option ) {
					wp_set_object_terms( $product_id, $option, $attr, true ); // save the possible option value for the attribute which will be used for variation later
				}
			}
			$productAttributes[ sanitize_title( $attr ) ] = array(
				'name'         => sanitize_title( $attr ),
				'value'        => $attribute["options"],
				'position'     => $attribute["position"],
				'is_visible'   => $attribute["visible"],
				'is_variation' => $attribute["variation"],
				'is_taxonomy'  => 1
			);
		}
		update_post_meta( $product_id, '_product_attributes', $productAttributes ); // save the meta entry for product attributes
	}

	if ( ! empty( $product['variant'] ) ) {
		$variants   = $product['variant'];
		$variations = [];
		if ( count( $variants ) == count( $variants, COUNT_RECURSIVE ) ) {
			$variant_name  = ( trim( $variants['name'] ) == 'Default Title' ) ? $objProduct->get_name( 'edit' ) : $variants['combine_name'];
			$variant_price = (float) str_replace( $variants['compare_at_price'], '', $variants['price'] );
			$variations[]  =
				array(
					"name"           => $variant_name,
					"price"          => $variant_price,
					"sku"            => $variants['sku'],
					"attributes"     => array(
						array( "name" => "Size", "option" => "L" ),
						array( "name" => "Color", "option" => "Red" )
					),
					"manage_stock"   => 1,
					"stock_quantity" => $variants['quantity']
				);
		} else {
			foreach ( $variants as $variant ) {
				$variant_name  = ( trim( $variant['name'] ) == 'Default Title' ) ? $objProduct->get_name( 'edit' ) : $variant['combine_name'];
				$variant_price = (float) str_replace( $variant['compare_at_price'], '', $variant['price'] );
				$variations[]  =
					array(
						"name"           => $variant_name,
						"price"          => $variant_price,
						"sku"            => $variant['sku'],
						"attributes"     => array(
							array( "name" => "Size", "option" => "L" ),
							array( "name" => "Color", "option" => "Red" )
						),
						"manage_stock"   => 1,
						"stock_quantity" => $variant['quantity']
					);
			}
		}

		if ( ! empty( $variations ) ) {
			try {
				foreach ( $variations as $variation ) {
					$variant_product_id = checkProductBySku( $variation["sku"] );
					$objVariation       = ! empty( $variant_product_id ) ? new WC_Product_Variation( $variant_product_id ) : new WC_Product_Variation();
					$objVariation       = new WC_Product_Variation();
					$objVariation->set_name( $variation["name"] );
					$objVariation->set_price( $variation["price"] );
					$objVariation->set_regular_price( $variation["price"] );
					$objVariation->set_parent_id( $product_id );
					if ( ! empty( $variation["sku"] ) ) {
						$objVariation->set_sku( $variation["sku"] );
					}
					$objVariation->set_manage_stock( $variation["manage_stock"] );
					$objVariation->set_stock_quantity( $variation["stock_quantity"] );
					$objVariation->set_stock_status( $variation['stock_status'] ); // in stock or out of stock value
					$var_attributes = array();
					foreach ( $variation["attributes"] as $attribute ) {
						$taxonomy                    = "pa_" . wc_sanitize_taxonomy_name( stripslashes( $attribute["name"] ) );
						$attr_val_slug               = wc_sanitize_taxonomy_name( stripslashes( $attribute["option"] ) );
						$var_attributes[ $taxonomy ] = $attr_val_slug;
					}
					$objVariation->set_attributes( $var_attributes );
					$objVariation->save();
				}
			} catch ( Exception $e ) {
				var_dump($e->getMessage());
			}
		}
	}
}

function uploadMedia( $image_url ) {
	$media       = media_sideload_image( $image_url, 0 );
	$attachments = get_posts( array(
		'post_type'   => 'attachment',
		'post_status' => null,
		'post_parent' => 0,
		'orderby'     => 'post_date',
		'order'       => 'DESC'
	) );

	return $attachments[0]->ID;
}

function wp_file_exists( $filename ) {
	global $wpdb;

	return intval( $wpdb->get_var( "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_value LIKE '%/$filename'" ) );
}

function get_image_ids( $images ) {
	$image_id_array = [];
	foreach ( $images as $url ) {
		$tmp = download_url( $url );
		preg_match( '/[^\?]+\.(jpg|JPG|jpe|JPE|jpeg|JPEG|gif|GIF|png|PNG)/', $url, $matches );
		$file['name']     = basename( $matches[0] );
		$file['tmp_name'] = $tmp;
		if ( null == ( $thumb_id = wp_file_exists( $file['name'] ) ) ) {
			if ( is_wp_error( $tmp ) ) {
				@unlink( $file['tmp_name'] );
				$file['tmp_name'] = '';
			}
			$thumb_id = media_handle_sideload( $file );
			if ( is_wp_error( $thumb_id ) ) {
				@unlink( $file['tmp_name'] );
				$file['tmp_name'] = '';
			}
		} else {
			@unlink( $file['tmp_name'] );
		}
		$image_id_array[] = $thumb_id;

		/*
		$file['name']     = $url;
		$file['tmp_name'] = download_url( $url );
		if ( is_wp_error( $file['tmp_name'] ) ) {
			@unlink( $file['tmp_name'] );
			var_dump( $file['tmp_name']->get_error_messages() );
		} else {
			$attachmentId = media_handle_sideload( $file );
			if ( is_wp_error( $attachmentId ) ) {
				@unlink( $file['tmp_name'] );
				var_dump( $attachmentId->get_error_messages() );
			} else {
				$image_id_array[] = $attachmentId;
			}
		}
		*/
	}

	return $image_id_array;
}

function formatted_images( $product ) {
	$images          = $product['images']['image'];
	$imagesFormatted = [];
	if ( ! empty( $images ) ) {
		foreach ( $images as $image ) {
			$url               = strtok( $image, '?' );
			$imagesFormatted[] = (string) trim( $url );
		}
	}

	return $imagesFormatted;
}

function createCategories( $categories ) {
	foreach ( $categories as $category ) {
		if ( ! checkCategoryByname( $category ) ) {
			wp_insert_term( $category, 'product_cat' );
		}
	}
}

function checkCategoryByName( $categoryName ) {
	$categories = get_categories( array( 'taxonomy' => 'product_cat', 'hide_empty' => 0 ) );
	foreach ( $categories as $category ) {
		if ( $category->name === $categoryName ) {
			return true;
		}
	}

	return false;
}

function getCategories( $products ) {
	return array_unique( array_column( $products, 'category' ) );
}

function getCategoryIdByName( $categoryName ) {
	$term = get_term_by( 'name', trim( $categoryName ), 'product_cat' );
	if ( $term ) {
		return $term->term_id;
	}

	return false;
}

function get_category_ids( $categories ) {
	$category_ids = [];
	if ( ! empty( $categories ) ) {
		foreach ( $categories as $category ) {

			$category_ids[] = getCategoryIdByName( $category );
		}
	}

	return empty( $category_ids ) ? [ 1 ] : $category_ids;
}

/**
 */
function product_import_init() {
	$products = getJsonFromFile();
	if ( ! empty( $products ) ) {
		$categories = getCategories( $products );
		if ( ! empty( $categories ) ) {
			createCategories( $categories );
		}
		foreach ( $products as $product ) {
			try {
				createNewProducts( $product );
			} catch ( WC_Data_Exception $e ) {

			}
		}
	}
}

try {
	echo "<pre>";
	product_import_init();
} catch ( WC_Data_Exception $e ) {
	//				var_dump(print_r($e->getMessage()));
}