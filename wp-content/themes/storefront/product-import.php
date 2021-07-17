<?php
/*
Template Name: Product Import From JSON
Template Post Type: post, page, event
*/
function getWoocommerceConfig() {
	return WC();
}

function getJsonFromFile() {
	$file = 'https://extensionsell.com/app/xml/export2.php?shop=cutting-edge-products-inc-dev&output=json&save=1';

	return json_decode( file_get_contents( $file ), true )['Product'];
}

function checkProductBySku( $skuCode ) {
	return wc_get_product_id_by_sku( $skuCode );
}

function createProducts() {
	$woocommerce = getWoocommerceConfig();
	$products    = getJsonFromFile();
	$imgCounter  = 0;
	foreach ( $products as $product ) {
		$productExist = checkProductBySku( $product['sku'] );
		$imagesFormatted = array();
		$name          = $product['name'];
		$sku           = $product['sku'];
		$description   = $product['description'];
		$images        = $product['images']['image'];
		$category    = $product['category'];
		$categoriesIds = array();

		if ( ! empty( $images ) ) {
			foreach ( $images as $image ) {
				$imagesFormatted[] = [
					'src'      => $image,
					'position' => 0
				];
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
			'post_author' => 1,
			'post_title'        => $name,
			'post_type'=>'product',
			'post_status'=>'publish',
			'post_content' => $description,
		];
//
//		'images'      => $imagesFormatted,
//			'category'  => getCategoryIdByName( $category ),
		if ( ! empty( $product ) ) {
			$url_array            = explode( '/', $product['url'] );
			$slug = end( $url_array );
		}

		if ( ! $productExist ) {
			$post_id = wp_insert_post( $finalProduct );
			wp_set_object_terms( $post_id, 'simple', 'product_type' );
			update_post_meta( $post_id, '_visibility', 'visible' );
			update_post_meta( $post_id, '_stock_status', 'instock');
			update_post_meta( $post_id, 'total_sales', '0' );
			update_post_meta( $post_id, '_downloadable', 'no' );
			update_post_meta( $post_id, '_virtual', 'yes' );
			update_post_meta( $post_id, '_regular_price', '' );
			update_post_meta( $post_id, '_sale_price', '' );
			update_post_meta( $post_id, '_purchase_note', '' );
			update_post_meta( $post_id, '_featured', 'no' );
			update_post_meta( $post_id, '_weight', '' );
			update_post_meta( $post_id, '_length', '' );
			update_post_meta( $post_id, '_width', '' );
			update_post_meta( $post_id, '_height', '' );
			update_post_meta( $post_id, '_sku', $sku );
			update_post_meta( $post_id, '_product_attributes', array() );
			update_post_meta( $post_id, '_sale_price_dates_from', '' );
			update_post_meta( $post_id, '_sale_price_dates_to', '' );
			update_post_meta( $post_id, '_price', '' );
			update_post_meta( $post_id, '_sold_individually', '' );
			update_post_meta( $post_id, '_manage_stock', 'no' );
			update_post_meta( $post_id, '_backorders', 'no' );
			update_post_meta( $post_id, '_stock', '5' );
//			$productResult = $woocommerce->post( 'products', $finalProduct );
		} else {
			/*Update product information */
			$idProduct = $productExist['idProduct'];
//			$woocommerce->put( 'products/' . $idProduct, $finalProduct );
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