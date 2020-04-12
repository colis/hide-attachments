<?php
/**
 * Main plugin class.
 *
 * @package colis/hide-attachments
 */

namespace HideAttachments;

/**
 * Class Main
 */
class Main {
	/**
	 * Plugin filename.
	 *
	 * @var string
	 */
	public $plugin_name;

	/**
	 * Plugin absolute path.
	 *
	 * @var string
	 */
	public $plugin_path;

	/**
	 * Plugin URL.
	 *
	 * @var string
	 */
	public $plugin_url;

	/**
	 * Constructor.
	 *
	 * @param string $plugin_name Plugin filename.
	 */
	public function __construct( string $plugin_name ) {
		$this->plugin_name = $plugin_name;
		$this->plugin_path = plugin_dir_path( $this->plugin_name );
		$this->plugin_url  = plugin_dir_url( $this->plugin_name );

		$this->load_dependencies();
		$this->bootstrap();
	}

	/**
	 * Returns an array of services to instantiate.
	 *
	 * @return array
	 */
	private function get_registrable_services() : array {
		return [
			MediaLibrary\MediaLibrary::class,
			Settings\Settings::class,
		];
	}

	/**
	 * Loads plugin dependencies. (No classes)
	 *
	 * @return void
	 */
	public function load_dependencies() {}

	/**
	 * Bootstraps the plugin functionality.
	 *
	 * @return void
	 */
	public function bootstrap() {
		// Hook services into WordPress.
		foreach ( $this->get_registrable_services() as $class_name ) {
			if ( ! class_exists( $class_name ) ) {
				// @TODO: throw error.
				continue;
			}

			$class = new $class_name( $this );

			if ( ! $class instanceof Registrable ) {
				// @TODO: throw error.
				continue;
			}

			$class->register();
		}
	}
}
