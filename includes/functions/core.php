<?php
/**
 * Core plugin functionality.
 *
 * @package colis/hide-attachments
 */

namespace HideAttachments\Core;

use \WP_Error as WP_Error;

/**
 * Default setup routine
 *
 * @return void
 */
function setup() {
	$n = function( $function ) {
		return __NAMESPACE__ . "\\$function";
	};

	add_action( 'init', $n( 'i18n' ) );
	add_action( 'init', $n( 'init' ) );
	add_action( 'admin_enqueue_scripts', $n( 'admin_styles' ) );

	do_action( 'hide_attachments_core_loaded' );
}

/**
 * Registers the default textdomain.
 *
 * @return void
 */
function i18n() {
	$locale = apply_filters( 'plugin_locale', get_locale(), 'hide-attachments' );
	load_textdomain( 'hide-attachments', WP_LANG_DIR . '/hide-attachments/hide-attachments-' . $locale . '.mo' );
	load_plugin_textdomain( 'hide-attachments', false, plugin_basename( HIDE_ATTACHMENTS_PATH ) . '/languages/' );
}

/**
 * Initializes the plugin and fires an action other plugins can hook into.
 *
 * @return void
 */
function init() {
	do_action( 'hide_attachments_core_init' );
}

/**
 * Enqueue styles for admin.
 *
 * @return void
 */
function admin_styles() {
	$css_file     = 'dist/css/admin.css';
	$css_fileuri  = HIDE_ATTACHMENTS_URL . $css_file;
	$css_filepath = HIDE_ATTACHMENTS_PATH . $css_file;

	if ( ! is_readable( $css_filepath ) ) {
		return;
	}

	// Register block editor styles for backend.
	wp_enqueue_style(
		'hide-attachments-admin-styles',
		$css_fileuri,
		[],
		filemtime( $css_filepath )
	);
}

/**
 * Activate the plugin
 *
 * @return void
 */
function activate() {
	// First load the init scripts in case any rewrite functionality is being loaded.
	init();
	flush_rewrite_rules();
}

/**
 * Deactivate the plugin
 *
 * Uninstall routines should be in uninstall.php
 *
 * @return void
 */
function deactivate() {

}
