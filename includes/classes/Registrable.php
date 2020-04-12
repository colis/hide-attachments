<?php
/**
 * Classes that need to hook into WordPress must implement this interface.
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
interface Registrable {
	/**
	 * Register WordPress hooks.
	 */
	public function register();
}
