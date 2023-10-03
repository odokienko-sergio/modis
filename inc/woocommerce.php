<?php
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

	// WooCommerce Support
	function modis_add_woocommerce_support() {
		add_theme_support( 'woocommerce' );
	}

	add_action( 'after_setup_theme', 'modis_add_woocommerce_support' );

	// Remove breadcrumbs
	remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

	// Personalize breadcrumbs
	add_filter( 'woocommerce_breadcrumb_defaults', 'wcc_change_breadcrumb_delimiter' );
	function wcc_change_breadcrumb_delimiter( $defaults ) {
		// Change the breadcrumb delimeter from '/' to '>'
		$defaults['delimiter']   = ' &nbsp; ';
		$defaults['wrap_before'] = '<p class="breadcrumbs"><span>';
		$defaults['wrap_after']  = '</span></p>';

		return $defaults;
	}

	// Sales
	add_filter( 'woocommerce_sale_flash', 'add_percentage_to_sale_badge', 20, 3 );
	function add_percentage_to_sale_badge( $html, $post, $product ) {

		if ( $product->is_type( 'variable' ) ) {
			$percentages = array();

			// Get all variation prices
			$prices = $product->get_variation_prices();

			// Loop through variation prices
			foreach ( $prices['price'] as $key => $price ) {
				// Only on sale variations
				if ( $prices['regular_price'][ $key ] !== $price ) {
					// Calculate and set in the array the percentage for each variation on sale
					$percentages[] = round( 100 - ( floatval( $prices['sale_price'][ $key ] ) / floatval( $prices['regular_price'][ $key ] ) * 100 ) );
				}
			}
			// We keep the highest value
			$percentage = max( $percentages ) . '%';

		} elseif ( $product->is_type( 'grouped' ) ) {
			$percentages = array();

			// Get all variation prices
			$children_ids = $product->get_children();

			// Loop through variation prices
			foreach ( $children_ids as $child_id ) {
				$child_product = wc_get_product( $child_id );

				$regular_price = (float) $child_product->get_regular_price();
				$sale_price    = (float) $child_product->get_sale_price();

				if ( $sale_price != 0 || ! empty( $sale_price ) ) {
					// Calculate and set in the array the percentage for each child on sale
					$percentages[] = round( 100 - ( $sale_price / $regular_price * 100 ) );
				}
			}
			// We keep the highest value
			$percentage = max( $percentages ) . '%';

		} else {
			$regular_price = (float) $product->get_regular_price();
			$sale_price    = (float) $product->get_sale_price();

			if ( $sale_price != 0 || ! empty( $sale_price ) ) {
				$percentage = round( 100 - ( $sale_price / $regular_price * 100 ) ) . '%';
			} else {
				return $html;
			}
		}

		return '<span class="status">' . $percentage . '</span>';
	}


	// Remove before/after loop
	//remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
	//remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);

	// Remove sidebar
	//remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

	// Product content
	remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
	remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );

	add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_link_open', 5 );
	add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_link_close', 15 );

	// Product data
	remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );

	function my_custom_title() {
		echo '<h3><a href="' . get_the_permalink() . '">' . get_the_title() . '</a></h3>';
	}

	add_action( 'woocommerce_shop_loop_item_title', 'my_custom_title', 15 );

	// Single img
	//Single Product Slider for Woocommerce
	add_action( 'after_setup_theme', 'ale_woocommerse_plugin_setup' );

	function ale_woocommerse_plugin_setup() {
		//add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		//add_theme_support( 'wc-product-gallery-slider' );
	}

	// SIngle Product Right Part
	function modis_single_title() {
		echo '<h3>' . get_the_title() . '</h3>';
	}

	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
	add_action( 'woocommerce_single_product_summary', 'modis_single_title', 5 );

	remove_action( 'woocommerce_single_variation', 'woocommerce_single_variation', 10 );
	add_action( 'woocommerce_before_variations_form', 'woocommerce_single_variation', 11 );
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta' );

	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );

	//Cart
	remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
	add_action( 'woocommerce_after_cart', 'woocommerce_cross_sell_display' );
	add_filter( 'woocommerce_cross_sells_columns', 'modis_change_cross_sells_columns' );

	function modis_change_cross_sells_columns( $columns ) {
		return 4;
	}

	function woo_dequeue_select2() {
		if ( class_exists( 'woocommerce' ) ) {
			wp_dequeue_style( 'select2' );
			wp_register_style( 'select2' );

			wp_dequeue_script( 'selectWoo' );
			wp_register_script( 'selectWoo' );
		}
	}

	add_action( 'wp_enquseue_scripts', 'woo_dequeue_select2', 100 );

	remove_action('woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10);

	remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);
	remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
}