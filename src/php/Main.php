<?php
/**
 * Main theme class.
 *
 * @package iwpdev/flatsome-child
 */

namespace Iwpdev\FlatsomeChild;

use Iwpdev\FlatsomeChild\WooCommerce\Checkout;

/**
 * Main class.
 */
class Main {
	/**
	 * Theme version.
	 */
	const FL_VERSION = '1.0.0';

	/**
	 * Main construct.
	 */
	public function __construct() {
		$this->init();

		new Checkout();
	}

	/**
	 * Init actions and filters.
	 *
	 * @return void
	 */
	private function init(): void {

		add_action( 'wp_enqueue_scripts', [ $this, 'add_scripts_and_styles' ] );
	}

	/**
	 * Add styles and scripts.
	 *
	 * @return void
	 */
	public function add_scripts_and_styles(): void {
		$url = get_stylesheet_directory_uri();
		$min = '.min';

		if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
			$min = '';
		}

		wp_enqueue_script( 'fl_main', $url . '/assets/js/main' . $min . '.js', [ 'jquery' ], self::FL_VERSION, true );

		wp_enqueue_style( 'fl_main', $url . '/assets/css/main' . $min . '.css', [], self::FL_VERSION );
	}
}
