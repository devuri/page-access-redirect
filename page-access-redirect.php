<?php
/**
 * Plugin Name:       Page Access Redirect
 * Plugin URI:        https://wpbrisko.com/wordpress-plugins/
 * Description:       Allows selection of pages that should be accessible, redirecting all others to the homepage.
 * Version:           0.3.1
 * Requires at least: 5.3.0
 * Requires PHP:      7.3.5
 * Author:            uriel
 * Author URI:        https://wpbrisko.com
 * Text Domain:       page-access-redirect
 * Domain Path:       /languages
 * License:           GPLv2
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

// start plugin.
if ( ! \defined( 'ABSPATH' ) ) {
    exit;
}

// Setup access to the plugin dir path.
\define( 'PAR_DIR_PATH', plugin_dir_path( __FILE__ ) );

require_once PAR_DIR_PATH . 'SelectivePageAccess.php';

// init class.
PageAccessRedirect\PageAccess::init();
