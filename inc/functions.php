<?php
/**
 * Customisable Archive Templates loader.
 */

declare( strict_types = 1 );

namespace Pragmatic\Customisable_Archive_Templates;

use Pragmatic\Customisable_Archive_Templates\Settings;

/**
 * Set up plugin.
 *
 * Register actions and filters.
 */
function init_plugin() : void {

	// Settings.
	\add_action( 'admin_init', __NAMESPACE__ . '\Settings\add_term_options' );
	\add_action( 'edited_term', __NAMESPACE__ . '\Settings\save_term_fields', 10, 3 );

	// Template override.
	\add_filter( 'template_include', __NAMESPACE__ . '\maybe_render_template', 20 );
}

/**
 * If on a customised taxonomy term's archive, and the template is enabled, override the template.
 *
 * @param string $default_template Path to the template to load.
 *
 * @return string Updated path to the template to load.
 */
function maybe_render_template( string $default_template ) : string {

	// Allow forcing the page to return a 404. Does not set status code. That will have to be done externally, way
	// earlier.
	if ( apply_filters( 'override_with_404_response', false ) ) {
		return locate_template( '404.php' );
	}

	if ( ! \is_archive() && ! \is_tax() && ! \is_category() && ! \is_tag() ) {
		return $default_template;
	}

	if ( \is_category() ) {
		$taxonomy  = 'category';
		$term_id   = $GLOBALS['wp_query']->query_vars['cat'];
		$term_slug = $GLOBALS['wp_query']->query_vars['category_name'];

	} elseif ( \is_tag() ) {
		$taxonomy  = 'post_tag';
		$term_id   = $GLOBALS['wp_query']->query_vars['tag_id'];
		$term_slug = $GLOBALS['wp_query']->query_vars['tag'];

	} else {
		// TODO: figure out how to handle custom taxonomy archive type.
		return $default_template;
	}

	if ( ! Settings\is_taxonomy_supported( $taxonomy ) || ! Settings\get_if_term_has_template_enabled( $term_id ) ) {
		// Check the taxonomy and term have CAT support enabled.
		return $default_template;
	}

	$template_id  = Settings\get_term_template_id( $term_id );
	if ( $template_id === 0 || \get_post_status( $template_id ) !== 'publish' ) {
		return $default_template;
	}

	$template_obj = \get_post( $template_id );
	if ( $template_obj === null ) {
		// Check a valid post template is set.
		return $default_template;
	}

	global $wp_query;
	$wp_query->comment_status = 'closed';
	$wp_query->post           = $template_obj;
	$wp_query->post_count++;
	array_unshift( $wp_query->posts, $wp_query->post );

	// phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited -- required to fix get_post_type() after the $wp_query change.
	$GLOBALS['post'] = $wp_query->posts[0];

	return \locate_template(
		[
			"pragcat-{$taxonomy}.php",
			"taxonomy-{$taxonomy}-{$term_slug}.php",
			"taxonomy-{$taxonomy}.php",
			"taxonomy.php",
			"archive.php",
			"index.php",
		]
	);

	/*
	TODO: Keep this here until we are more sure on above approach.

	return \locate_template(
		[
			"single-{$template_obj->post_type}-{$term_slug}.php",
			"single-{$template_obj->post_type}.php",
			"single.php",
			"singular.php",
			"index.php",
		]
	);
	*/
}
