<?php
/**
 * All classes should extend this abstract class so that we can add helpers to the main one.
 *
 * @package colis/hide-attachments
 */

namespace HideAttachments;

defined( 'ABSPATH' ) || die();

/**
 * Interface Registrable
 *
 * @package colis/hide-attachments
 */
abstract class Plugin {
	/**
	 * Holds the instance of the main plugin to be re-used across other classes.
	 *
	 * @var object Main plugin instance.
	 */
	protected $plugin;

	/**
	 * Option name
	 *
	 * @var string
	 */
	const OPTION_NAME = 'hide-attachments-by-category';

	/**
	 * Stores the plugin main class.
	 *
	 * @param object $plugin Instance of the main class.
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;
	}
}
