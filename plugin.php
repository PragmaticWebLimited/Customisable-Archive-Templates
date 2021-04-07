<?php
/**
 * Plugin Name: Customisable Archive Templates
 * Plugin URI: https://github.com/PragmaticWebLimited/Customisable-Archive-Templates
 * Description: Customise the WordPress term archive template with the block editor.
 * Author: Pragmatic Web Limited
 * Author URI: https://pragmatic.agency
 * Version: 0.7.2
 * License: GPL-3.0-only
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: customisable-archive-templates
 * Requires at least: 5.4
 * Requires PHP: 7.3.5
 */

declare( strict_types = 1 );

namespace Pragmatic\Customisable_Archive_Templates;

require_once __DIR__ . '/inc/settings/functions.php';
require_once __DIR__ . '/inc/functions.php';

\add_action( 'plugins_loaded', __NAMESPACE__ . '\init_plugin' );
