<?php
/**
 * Plugin Name: Hide Attachments
 * Plugin URI:  https://github.com/colis/hide-attachments
 * Description: Hide attachments assigned to a specific category term from the media gallery.
 * Version:     0.1.0
 * Author:      Carmine Colicino
 * Author URI:  https://github.com/colis
 * Text Domain: hide-attachments
 * Domain Path: /languages
 *
 * @package colis/hide-attachments
 */

// Useful global constants.
define( 'HIDE_ATTACHMENTS_VERSION', '0.1.0' );
define( 'HIDE_ATTACHMENTS_URL', plugin_dir_url( __FILE__ ) );
define( 'HIDE_ATTACHMENTS_PATH', plugin_dir_path( __FILE__ ) );
define( 'HIDE_ATTACHMENTS_INC', HIDE_ATTACHMENTS_PATH . 'includes/' );

// Include files.
require_once HIDE_ATTACHMENTS_INC . 'functions/core.php';

// Activation/Deactivation.
register_activation_hook( __FILE__, '\HideAttachments\Core\activate' );
register_deactivation_hook( __FILE__, '\HideAttachments\Core\deactivate' );

// Bootstrap.
HideAttachments\Core\setup();

// Require Composer autoloader if it exists.
if ( file_exists( HIDE_ATTACHMENTS_PATH . '/vendor/autoload.php' ) ) {
	require_once HIDE_ATTACHMENTS_PATH . 'vendor/autoload.php';
}

new HideAttachments\Main( __FILE__ );
