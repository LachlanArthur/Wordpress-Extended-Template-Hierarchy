<?php

// Add category templates to single posts & CPTs
add_filter('single_template', 'la_single_template_with_category', 10, 1);
add_filter('template_include', 'la_single_template_with_category', 10, 1);
function la_single_template_with_category($template) {
	$object = get_queried_object();
	if (empty($object->post_type)) {
		return $template;
	}
	
	$is_wc = class_exists('WooCommerce') && $object->post_type === 'product';
	if ($is_wc && !is_single()) {
		return $template;
	}
	
	$templates = array();
	
	$file = "single-{$object->post_type}-{$object->ID}.php";
	$templates[] = $file;
	if ($is_wc) $templates[] = WC()->template_path() . $file;
	
	$default_tax_order = array(
		'category',
		'product_cat',
	);
	$taxes = get_taxonomies(array('object_type' => array($object->post_type)));
	$tax_order = apply_filters('la_single_template_with_category_tax_order', $default_tax_order, $object);
	if (!empty($tax_order)) {
		// Get rid of any invalid taxonomies
		$tax_order = array_intersect($tax_order, $taxes);
		// Start with the user's order, then add any additional taxonomies
		$taxes = array_merge($tax_order, array_diff($taxes, $tax_order));
	}
	
	$taxes = array_fill_keys($taxes, array());
	foreach ($taxes as $tax_slug => &$tax_terms) {
		$post_terms = wp_get_object_terms($object->ID, $tax_slug);
		if (count($post_terms) > 0) {
			$tax_terms = wp_list_pluck($post_terms, 'slug');
			foreach ($tax_terms as $term_slug) {
				$file = "single-{$object->post_type}-$tax_slug-$term_slug.php";
				$templates[] = $file;
				if ($is_wc) $templates[] = WC()->template_path() . $file;
			}
		}
	}
	
	$file = "single-{$object->post_type}.php";
	$templates[] = $file;
	if ($is_wc) $templates[] = WC()->template_path() . $file;
	
	if ($is_wc) {
		$templates = array_unshift($templates, 'woocommerce.php');
	} else {
		$templates[] = "single.php";
	}
	
	$template = locate_template( $templates );
	if ($is_wc && (!$template || WC_TEMPLATE_DEBUG_MODE)) {
		$template = WC()->plugin_path() . '/templates/' . $file;
	}
	
	return $template;
}

// Simple taxonomy ordering function
//add_filter('la_single_template_with_category_tax_order', 'la_single_template_with_category_tax_order_simple', 10, 1);
function la_single_template_with_category_tax_order_simple($tax_order, $object) {
	// Invalid taxonomies will be removed automatically, so they can be put in a single array for simplicity
	return array(
		// Post
		'category',
		'tag',
		
		// WooCommerce Product
		'product_cat',
	);
}

// Advanced taxonomy ordering function
//add_filter('la_single_template_with_category_tax_order', 'la_single_template_with_category_tax_order_advanced', 10, 1);
function la_single_template_with_category_tax_order_advanced($tax_order, $object) {
	switch ($object->post_type) {
		case 'post':
			$tax_order = array(
				'category',
				'tag',
			);
			break;
		case 'product':
			$tax_order = array(
				'product_cat',
			);
			break;
	}
	return $tax_order;
}
