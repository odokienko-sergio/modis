<?php
/**
 * Content wrappers
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/global/wrapper-start.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
} ?>
<div id="primary" class="content-area">
<select name="orderby" class="orderby" aria-label="Shop order">
	<option value="menu_order" selected="selected">Default sorting</option>
	<option value="popularity">Sort by popularity</option>
	<option value="rating">Sort by average rating</option>
	<option value="date">Sort by latest</option>
	<option value="price">Sort by price: low to high</option>
	<option value="price-desc">Sort by price: high to low</option>
</select>
<main id="main" class="site-main" role="main">

