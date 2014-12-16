<?php

// Add category templates to single posts & CPTs
add_filter('single_template', 'la_single_template_with_category');
function la_single_template_with_category() {
	$object = get_queried_object();
	$templates = array();
	if (!empty($object->post_type)) {
		$templates[] = "single-{$object->post_type}-{$object->ID}.php";
		$taxes = get_taxonomies(array('object_type' => array($object->post_type)));
		$taxes = array_fill_keys($taxes, array());
		foreach ($taxes as $tax_slug => &$tax_terms) {
			$post_terms = wp_get_object_terms($object->ID, $tax_slug);
			if (count($post_terms) > 0) {
				$tax_terms = wp_list_pluck($post_terms, 'slug');
				foreach ($tax_terms as $term_slug) {
					$templates[] = "single-{$object->post_type}-$tax_slug-$term_slug.php";
				}
			}
		}
		$templates[] = "single-{$object->post_type}.php";
	}
	$templates[] = "single.php";
	$template = locate_template( $templates );
	return $template;
}