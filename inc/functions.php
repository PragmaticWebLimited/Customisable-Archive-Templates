<?php
/**
 * Customisable Archive Templates loader.
 */

declare( strict_types = 1 );

namespace Pragmatic\Customisable_Archive_Templates;

/**
 * Set up plugin.
 *
 * Register actions and filters.
 */
function init_plugin() : void {

	// Settings.
	\add_action( 'admin_init', __NAMESPACE__ . '\Settings\add_term_options' );
	\add_action( 'edited_term', __NAMESPACE__ . '\Settings\save_term_fields', 10, 3 );
}
