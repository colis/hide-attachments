<?php
/**
 * Add settings page in the wp-admin area.
 *
 * @package colis/hide-attachments
 */

namespace HideAttachments\Settings;

use HideAttachments\Registrable;
use HideAttachments\Plugin;

defined( 'ABSPATH' ) || die();

/**
 * Class Settings
 *
 * @package colis/hide-attachments
 */
class Settings extends Plugin implements Registrable {
	/**
	 * User must have this capability to use this page.
	 *
	 * @var string
	 */
	const CAPABILITY = 'manage_options';

	/**
	 * The settings group name.
	 *
	 * @var string
	 */
	const SETTINGS_NAME = 'media';

	/**
	 * Register into WordPress
	 */
	public function register() {
		add_action( 'admin_init', [ $this, 'hide_attachments_settings' ], 100, 0 );
	}

	/**
	 * Register the fields necessary to set the Hide Attachments settings.
	 */
	public function hide_attachments_settings() {
		$section_name = 'hide-attachments-product-category-section';

		register_setting( self::SETTINGS_NAME, self::OPTION_NAME );

		add_settings_section(
			$section_name,
			__( 'Hide Attachments by Product Category', 'hide-attachments' ),
			[ $this, 'options_callback' ],
			self::SETTINGS_NAME
		);

		add_settings_field(
			self::OPTION_NAME,
			__( 'Product Category Terms', 'hide-attachments' ),
			[ $this, 'render_options_fields' ],
			self::SETTINGS_NAME,
			$section_name,
			[
				'class' => self::OPTION_NAME,
			]
		);
	}

	/**
	 * Render options fields.
	 */
	public function render_options_fields() {
		// Retrieve the settings.
		$settings = $this->get_plugin_settings();

		// Retrieve the product category terms.
		$product_terms = $this->get_product_categories();

		foreach ( $product_terms as $product_term ) {
			$setting_name = self::OPTION_NAME . "[{$product_term->term_id}]";

			// Echo the product category terms checkboxes.
			echo sprintf(
				'<label for="%1$s"><input id="%1$s" name="%1$s" type="checkbox" value="1" %2$s>%3$s</label>',
				esc_attr( $setting_name ),
				esc_attr( checked( $settings[ $product_term->term_id ], '1', false ) ),
				esc_attr( $product_term->name )
			);
		}
	}

	/**
	 * Add a message above the fields.
	 */
	public function options_callback() {
		esc_html_e( 'The attachments assigned to the selected product category terms below will not be visible in the Media Library.', 'hide-attachments' );
	}

	/**
	 * Get plugin settings stored in wp_options.
	 *
	 * @return array Plugin settings array.
	 */
	public function get_plugin_settings() : array {
		$option_value = get_option( self::OPTION_NAME );

		return ( ! empty( $option_value ) ) ? $option_value : [];
	}

	/**
	 * Retrieve all the product categories.
	 *
	 * @return array The product category terms.
	 */
	public function get_product_categories() : array {
		$product_terms = get_terms(
			[
				'taxonomy'   => 'product_cat',
				'hide_empty' => false,
			]
		);

		if ( is_wp_error( $terms ) ) {
			return [];
		}

		return $product_terms;
	}
}
