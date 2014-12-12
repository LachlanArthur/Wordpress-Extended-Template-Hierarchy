<?php

// Add category templates to single posts & CPTs
add_filter('single_template', 'la_single_template_with_category');
function la_single_template_with_category() {
	$object = get_queried_object();
	$templates = array();
	if (!empty($object->post_type)) {
		$taxes = get_taxonomies(array('object_type' => array($object->post_type)));
		$all_terms = get_terms($taxes);
		$taxes = array_fill_keys($taxes, array());
		foreach ($taxes as $tax_slug => &$tax_terms) {
			$tax_terms = wp_list_pluck(wp_list_filter($all_terms, array('taxonomy' => $tax_slug)), 'slug');
			foreach ($tax_terms as $term_slug) {
				$templates[] = "single-{$object->post_type}-$tax_slug-$term_slug.php";
			}
		}
		$templates[] = "single-{$object->post_type}.php";
	}
	$templates[] = "single.php";
	$template = locate_template( $templates );
	return $template;
}