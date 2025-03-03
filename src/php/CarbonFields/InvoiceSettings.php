<?php
/**
 * Invoice Settings.
 *
 * @package iwpdev/flatsome-child
 */

namespace Iwpdev\FlatsomeChild\CarbonFields;

use Carbon_Fields\Carbon_Fields;
use Carbon_Fields\Container;
use Carbon_Fields\Field;

/**
 * InvoiceSettings class.
 */
class InvoiceSettings {
	/**
	 * Fields prefix.
	 */
	const FILED_PREFIX = 'fl-invoice-';

	/**
	 * InvoiceSettings construct.
	 */
	public function __construct() {
		$this->init();
	}

	/**
	 * Init actions and filters.
	 *
	 * @return void
	 */
	private function init(): void {
		add_action( 'after_setup_theme', [ $this, 'load_boot_class' ] );
		add_action( 'carbon_fields_register_fields', [ $this, 'register_invoice_fields' ] );
	}

	/**
	 * Load boot class.
	 *
	 * @return void
	 */
	public function load_boot_class(): void {
		Carbon_Fields::boot();
	}

	/**
	 * Register Invoice Setting Fields.
	 *
	 * @return void
	 */
	public function register_invoice_fields(): void {
		Container::make( 'theme_options', __( 'Invoice Settings', 'flatsome' ) )
			->add_fields(
				[
					Field::make( 'text', self::FILED_PREFIX . 'store_name', __( 'Store Name', 'flatsome' ) ),
					Field::make( 'text', self::FILED_PREFIX . 'address_line', __( 'Address', 'flatsome' ) ),
					Field::make( 'text', self::FILED_PREFIX . 'nip', __( 'NIP', 'flatsome' ) ),
					Field::make( 'text', self::FILED_PREFIX . 'email', __( 'Email', 'flatsome' ) ),
					Field::make( 'text', self::FILED_PREFIX . 'tel', __( 'Tel', 'flatsome' ) ),
					Field::make( 'text', self::FILED_PREFIX . 'account_number', __( 'Account number', 'flatsome' ) ),
					Field::make( 'text', self::FILED_PREFIX . 'bank_name', __( 'Bank name', 'flatsome' ) ),
					Field::make( 'text', self::FILED_PREFIX . 'vat_description', __( 'Vat description', 'flatsome' ) ),
				]
			);
	}
}
