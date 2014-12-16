Wordpress Extended Template Hierarchy
=====================================

Chuck this into your functions to make Wordpress apply custom templates for posts within specific categories.

Usually Wordpress only looks for `single.php` and `single-{$post_type}.php` (matches `single-post.php`).
This function adds taxonomies and terms to the filename checks.

## Filename Usage

	single-{$post_type}-{$taxonomy_slug}-{$term_slug}.php

The post ID is also available:

	single-{$post_type}-{$post_id}.php

## Example Filenames
Here is an example of the order that Wordpress might look for files.
This example would be for a site with a custom post type `product` that has two taxonomies `category` & `size`.

Product with ID 1234 in category `camera`, and term `large` in the taxonomy `size`:

	single-product-1234.php
	single-product-category-camera.php
	single-product-size-large.php
	single-product.php
	single.php

Product with ID 2345 in category `speaker`, and term `small` in the taxonomy `size`:

	single-product-2345.php
	single-product-category-speaker.php
	single-product-size-small.php
	single-product.php
	single.php

## Notes
This function only looks for templates with a single term in a single single category.
This means you could not, using the example scenario above,
have a template that applies to `category:speaker` and `size:large` at the same time.
This is because doing so would require checking for an exponentially growing number of templates.
In those cases you would probably be better off simply using the `single-{$post_type}-{$post_id}.php` template filename.

## Todo
Look into ways to specify a taxonomy order (eg. `size` before `category` in the example above).
