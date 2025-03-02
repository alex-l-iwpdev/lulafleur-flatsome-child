<?php
/**
 * Woocommerce Checkout change.
 *
 * @package iwpdev/flatsome-child
 */

namespace Iwpdev\FlatsomeChild\WooCommerce;

use WC_Checkout;

/**
 * Checkout class file.
 */
class Checkout {
	/**
	 * Checkout construct.
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
		add_action( 'woocommerce_after_checkout_billing_form', [ $this, 'add_unknown_address_checkbox' ] );

		add_action( 'woocommerce_checkout_update_order_meta', [ $this, 'save_date_unknown_address' ] );
	}

	/**
	 * Add Unknown Address Checkbox.
	 *
	 * @param WC_Checkout $checkout Checkout class.
	 *
	 * @return void
	 */
	public function add_unknown_address_checkbox( WC_Checkout $checkout ): void {
		echo '<div id="unknown_address_checkbox">';
		woocommerce_form_field(
			'unknown_address',
			[
				'type'  => 'checkbox',
				'class' => [ 'form-row-wide' ],
				'label' => __( 'I don\'t know the recipient\'s address, contact by phone', 'flatsome' ),
			],
			$checkout->get_value( 'unknown_address' )
		);

		echo '<div id="receiver_phone_field" style="display:none;">';
		woocommerce_form_field(
			'receiver_phone',
			[
				'type'        => 'tel',
				'class'       => [ 'form-row-wide' ],
				'label'       => __( 'Phone of the recipient', 'flatsome' ),
				'required'    => true,
				'placeholder' => __( 'Enter your phone number', 'flatsome' ),
			],
			$checkout->get_value( 'receiver_phone' )
		);
		echo '</div></div>';
	}

	/**
	 * Save Data Unknown Address.
	 *
	 * @param int $order_id Order ID.
	 *
	 * @return void
	 */
	public function save_date_unknown_address( int $order_id ): void {
		update_post_meta( $order_id, '_unknown_address', ! empty( $_POST['unknown_address'] ) ? 'yes' : 'no' );

		if ( ! empty( $_POST['receiver_phone'] ) ) {
			update_post_meta( $order_id, '_receiver_phone', sanitize_text_field( wp_unslash( $_POST['receiver_phone'] ) ) );
		}
	}
}
