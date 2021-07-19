<?php
/*
Template Name: Product Import From JSON
Template Post Type: post, page, event
*/

require_once( ABSPATH . "wp-admin" . '/includes/image.php' );
require_once( ABSPATH . "wp-admin" . '/includes/file.php' );
require_once( ABSPATH . "wp-admin" . '/includes/media.php' );
function getWoocommerceConfig() {
	return WC();
}

function getJsonFromFile() {
	$file     = 'https://extensionsell.com/app/xml/export2.php?shop=cutting-edge-products-inc-dev&output=json&save=1';
	$products = json_decode( file_get_contents( $file ), true )['Product'];
	$product  = array_slice( $products, 0, 2 );

	return $product;
}

function checkProductBySku( $sku ) {
	global $wpdb;

	$product_id = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $sku ) );

	if ( $product_id ) {
		return new WC_Product( $product_id );
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
				$imagesFormatted[] = strtok( $image, '?' );
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
			'post_author'  => 1,
			'post_title'   => $name,
			'post_type'    => 'product',
			'post_status'  => 'publish',
			'post_content' => $description,
		];

		if ( ! empty( $product ) ) {
			$url_array = explode( '/', $product['url'] );
			$slug      = end( $url_array );
		}

		$price = (float)str_replace($product['compare_at_price'],'',$product['price']);
		$in_stock = ($product['in_stock']=='In Stock') ? 'instock' : 'no';
		$data = [ 'sku' => $sku,'price'=>$price,'quantity'=>$product['quantity'],'in_stock'=>$in_stock,'weight'=>$product['weight'] ];
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
	wp_set_object_terms( $post_id, 'simple', 'product_type' );
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
	$file          = array();
	$i             = 0;
	$image_id_array = [];
	foreach ( $images as $url ) {
		$file['name']     = $url;
		var_dump($url);exit();
		$file['tmp_name'] = download_url('https://cdn.shopify.com/s/files/1/0491/3266/7044/products/12TKR.jpg');
		if ( is_wp_error( $file['tmp_name'] ) ) {
			@unlink( $file['tmp_name'] );
			var_dump( $file['tmp_name']->get_error_messages( ) );
		} else {
			$attachmentId = media_handle_sideload( $file, $post_id );
			if ( is_wp_error( $attachmentId ) ) {
				@unlink( $file['tmp_name'] );
				var_dump( $attachmentId->get_error_messages( ) );
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
		if(sizeof($image_id_array) > 1) {
			array_shift($image_id_array);
			update_post_meta($post_id, '_product_image_gallery', implode(',',$image_id_array));
		}
	}
}

function createCategories() {
	$categoryValues = getCategories();
	foreach ( $categoryValues as $value ) {
		if ( ! checkCategoryByname( $value ) ) {
			wp_insert_term( $value, 'product_cat' );
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

function getCategories() {
	$products = getJsonFromFile();

	return array_unique( array_column( $products, 'category' ) );
}

function getCategoryIdByName( $categoryName ) {
	return get_cat_ID( $categoryName );
}

function getProductAttributesNames( $articulos ) {
	$keys = array();
	foreach ( $articulos as $articulo ) {
		$terms = $articulo['config'];
		foreach ( $terms as $key => $term ) {
			array_push( $keys, $key );
		}
	}
	/* remove repeted keys*/
	$keys       = array_unique( $keys );
	$configlist = array_column( $articulos, 'config' );
	$options    = array();
	foreach ( $keys as $key ) {
		$attributes = array(
			array(
				'name'      => $key,
				'slug'      => 'attr_' . $key,
				'visible'   => true,
				'variation' => true,
				'options'   => getTermsByKeyName( $key, $configlist )
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

echo "<pre>";
print_r( createProducts() );