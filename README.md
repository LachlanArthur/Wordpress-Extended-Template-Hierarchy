Wordpress Extended Template Hierarchy
=====================================

Chuck this into your functions to make Wordpress apply custom templates for posts within specific categories.

Usually Wordpress only looks for `single.php` and `single-{$post_type}.php` (matches `single-post.php`).
This function adds taxonomies and terms to the filename checks.

## Filename Usage

	single-{$post_type}-{$taxonomy_slug}-{$term_slug}.php

The post ID is also available:

	single-{$post_type}-{$post_id}.php

Also works with WooCommerce products.

## Example Filenames
Here are some examples of the order that Wordpress might look for files.

Post where `id=100`, `category=blog,news` and `tag=travel`:

	single-post-100.php
	single-post-category-blog.php
	single-post-category-news.php
	single-post-tag-travel.php
	single-post.php
	single.php

Custom post type `book` where `id=1234`, `genre=horror`, and `length=long`:

	single-book-1234.php
	single-book-genre-horror.php
	single-book-length-long.php
	single-book.php
	single.php

WooCommerce Product where `id=2345`, `product_cat=speaker`, and `manufacturer=acme`:

	woocommerce.php
	single-product-2345.php
	woocommerce/single-product-2345.php
	single-product-product_cat-speaker.php
	woocommerce/single-product-product_cat-speaker.php
	single-product-manufacturer-acme.php
	woocommerce/single-product-manufacturer-acme.php
	single-product.php
	woocommerce/single-product.php

## Ordering Taxonomies
If you want to change the order of the taxonomies, use the filter `la_single_template_with_category_tax_order` and return an array of taxonomy slugs (`array('category', 'tag')`).
Two example functions are provided to override the taxonomy order.

## Notes
This function only looks for templates with a single term in a single category.
This means you could not, using the book example above,
have a template that applies to both `genre=horror` and `length=long` at the same time.
This is because doing so would require checking for an exponentially growing number of templates.
In those cases you would probably be better off simply using the `single-{$post_type}-{$post_id}.php` template filename.
