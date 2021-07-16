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
	return wc_get_product_id_by_sku($skuCode);
}

function createProducts() {
	$woocommerce = getWoocommerceConfig();
	$products    = getJsonFromFile();
	$imgCounter = 0;
	foreach ( $products as $product ) {
		$productExist = checkProductBySku( $product['sku'] );
		print_r($product);exit;
		$imagesFormated = array();
		$url_array =explode('/',$product['url']);
		$slug= end($url_array);
		/*Main information */
		$name          = $product['name'];
		$sku           = $product['sku'];
		$description   = $product['description'];
		$images        = $product['images'];
		$articulos     = $product['articulos'];
		$categories    = $product['categorias'];
		$categoriesIds = array();
		foreach ( $images as $image ) {
			$imagesFormated[] = [
				'src'      => $image,
				'position' => 0
			]; /* TODO: FIX POSITON */
			$imgCounter ++;
		}


		/* Prepare categories */
		foreach ( $categories as $category ) {
			$categoriesIds[] = [ 'id' => getCategoryIdByName( $category ) ];
		}
		$finalProduct = [
			'name'        => $name,
			'slug'        => $slug,
			'sku'         => $sku,
			'description' => $description,
			'images'      => $imagesFormated,
			'categories'  => $categoriesIds,
			'attributes'  => getProductAttributesNames( $articulos )

		];

		print_r($finalProduct);exit;


		if ( ! $productExist ) {
			$productResult = $woocommerce->post( 'products', $finalProduct );
		} else {
			/*Update product information */
			$idProduct = $productExist['idProduct'];
			$woocommerce->put( 'products/' . $idProduct, $finalProduct );
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
	return get_cat_ID($categoryName);
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