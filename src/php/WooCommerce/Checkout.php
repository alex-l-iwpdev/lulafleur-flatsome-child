<?php
/**
 * Woocommerce Checkout change.
 *
 * @package iwpdev/flatsome-child
 */

namespace Iwpdev\FlatsomeChild\WooCommerce;

use WC_Checkout;
use WC_Order;

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
		add_action( 'woocommerce_after_checkout_billing_form', [ $this, 'add_not_vat_invoice' ] );
		add_action( 'woocommerce_checkout_update_order_meta', [ $this, 'save_date_unknown_address' ] );
		add_action( 'woocommerce_checkout_update_order_meta', [ $this, 'save_date_not_vat_invoice' ] );
		add_action( 'woocommerce_admin_order_data_after_billing_address', [ $this, 'show_unknown_address_in_order' ] );
		add_action( 'woocommerce_admin_order_data_after_billing_address', [ $this, 'show_not_vat_in_order' ] );
		add_action( 'woocommerce_checkout_order_created', [ $this, 'set_invoice_number' ] );
		add_action( 'woocommerce_email_before_order_table', [ $this, 'set_phone_number_in_email' ] );
		add_action( 'wpo_wcpdf_document_is_allowed', [ $this, 'generate_invoice' ], 10, 2 );
		add_action( 'woocommerce_shipping_fields', [ $this, 'add_phone_shipping_address' ], 10, 2 );
		add_action( 'woocommerce_checkout_update_order_meta', [ $this, 'save_phone_shipping_address' ], 10, 2 );
		add_action(
			'woocommerce_admin_order_data_after_shipping_address',
			[
				$this,
				'display_phone_shipping_address',
			]
		);
	}

	/**
	 * Set phone number in email.
	 *
	 * @param WC_Checkout $checkout Woocommerce Checkout class.
	 *
	 * @return void
	 */
	public static function set_phone_number_in_email( $order ) {
		$receiver_phone         = get_post_meta( $order->get_id(), '_receiver_phone', true );
		$unknown_address        = get_post_meta( $order->get_id(), '_unknown_address', true );
		$phone_field            = get_post_meta( $order->get_id(), '_shipping_phone_number', true );
		$photo_to_email_address = get_post_meta( $order->get_id(), '_photo_to_email_address', true );

		if ( $unknown_address === 'yes' ) {
			echo '<p><strong>' . esc_html__( 'I don\'t know the recipient\'s address', 'flatsome' ) . ':</strong> ✔</p>';
			if ( $receiver_phone ) {
				echo '<p><strong>' . esc_html__( 'Phone of the recipient', 'flatsome' ) . ':</strong> ' . esc_html( $receiver_phone ) . '</p>';
			}
		}

		if ( ! empty( $phone_field ) ) {
			echo '<p><strong>' . esc_html__( 'Phone of the recipient', 'flatsome' ) . ':</strong> ' . esc_html( $phone_field ) . '</p>';
		}

		if ( 'yes' === $photo_to_email_address ) {
			echo '<p><strong>' . esc_html__( 'I want to receive a photo of the bouquet by e-mail ', 'flatsome' ) . ':</strong> ✔</p>';
		}
	}

	/**
	 * Generate invoice.
	 *
	 * @param $allowed
	 * @param $document
	 *
	 * @return false|mixed
	 */
	public static function generate_invoice( $allowed, $document ) {
		$nip = get_post_meta( $document->order->get_id(), '_company_nip', true );

		if ( empty( $nip ) ) {
			return false;
		}

		return $allowed;
	}

	/**
	 * Add Not VAT Invoice.
	 *
	 * @param WC_Checkout $checkout Woocommerce Checkout class.
	 *
	 * @return void
	 */
	public function add_not_vat_invoice( WC_Checkout $checkout ): void {
		echo '<div id="not_vat_checkbox">';
		woocommerce_form_field(
			'not_vat',
			[
				'type'  => 'checkbox',
				'class' => [ 'form-row-wide' ],
				'label' => esc_html__( 'Invoice (not VAT)', 'flatsome' ),
			],
			$checkout->get_value( 'not_vat' )
		);

		echo '<div id="company_name_field" style="display:none;">';
		woocommerce_form_field(
			'company_name',
			[
				'type'        => 'text',
				'class'       => [ 'form-row-wide' ],
				'label'       => esc_html__( 'Company Name', 'flatsome' ),
				'required'    => true,
				'placeholder' => esc_html__( 'Enter your company name', 'flatsome' ),
			],
			$checkout->get_value( 'company_name' )
		);
		echo '</div>';
		echo '<div id="company_address_field" style="display:none;">';
		woocommerce_form_field(
			'company_address',
			[
				'type'        => 'text',
				'class'       => [ 'form-row-wide' ],
				'label'       => esc_html__( 'Company Address', 'flatsome' ),
				'required'    => true,
				'placeholder' => esc_html__( 'Enter your company address', 'flatsome' ),
			],
			$checkout->get_value( 'company_address' )
		);
		echo '</div>';
		echo '<div id="company_nip_field" style="display:none;">';
		woocommerce_form_field(
			'company_nip',
			[
				'type'        => 'text',
				'class'       => [ 'form-row-wide' ],
				'label'       => esc_html__( 'Company NIP', 'flatsome' ),
				'required'    => true,
				'placeholder' => esc_html__( 'Enter your company NIP', 'flatsome' ),
			],
			$checkout->get_value( 'company_nip' )
		);
		echo '</div></div>';
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
				'label' => esc_html__( 'I don\'t know the recipient\'s address, contact by phone', 'flatsome' ),
			],
			$checkout->get_value( 'unknown_address' )
		);

		echo '<div id="receiver_phone_field" style="display:none;">';
		woocommerce_form_field(
			'receiver_phone',
			[
				'type'        => 'tel',
				'class'       => [ 'form-row-wide' ],
				'label'       => esc_html__( 'Phone of the recipient', 'flatsome' ),
				'required'    => true,
				'placeholder' => esc_html__( 'Enter your phone number', 'flatsome' ),
			],
			$checkout->get_value( 'receiver_phone' )
		);
		echo '</div></div>';
		echo '<div id="photo_to_address_checkbox">';
		woocommerce_form_field(
			'photo_to_email_address',
			[
				'type'  => 'checkbox',
				'class' => [ 'form-row-wide' ],
				'label' => esc_html__( 'I want to receive a photo of the bouquet by e-mail', 'flatsome' ),
			],
			$checkout->get_value( 'unknown_address' )
		);
		echo '</div>';
	}

	/**
	 * Save Data Not VAT Invoice.
	 *
	 * @param int $order_id Order ID.
	 *
	 * @return void
	 */
	public function save_date_not_vat_invoice( int $order_id ): void {
		update_post_meta( $order_id, '_not_vat', ! empty( $_POST['not_vat'] ) ? 'yes' : 'no' );

		if ( ! empty( $_POST['company_name'] ) ) {
			update_post_meta( $order_id, '_company_name', sanitize_text_field( wp_unslash( $_POST['company_name'] ) ) );
		}

		if ( ! empty( $_POST['company_address'] ) ) {
			update_post_meta( $order_id, '_company_address', sanitize_text_field( wp_unslash( $_POST['company_address'] ) ) );
		}

		if ( ! empty( $_POST['company_nip'] ) ) {
			update_post_meta( $order_id, '_company_nip', sanitize_text_field( wp_unslash( $_POST['company_nip'] ) ) );
		}
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
		update_post_meta( $order_id, '_photo_to_email_address', ! empty( $_POST['photo_to_email_address'] ) ? 'yes' : 'no' );

		if ( ! empty( $_POST['receiver_phone'] ) ) {
			update_post_meta( $order_id, '_receiver_phone', sanitize_text_field( wp_unslash( $_POST['receiver_phone'] ) ) );
		}
	}

	/**
	 * Show Not VAT in Order.
	 *
	 * @param WC_Order $order WooCommerce Order class.
	 *
	 * @return void
	 */
	public function show_not_vat_in_order( WC_Order $order ): void {
		$not_vat         = get_post_meta( $order->get_id(), '_not_vat', true );
		$company_name    = get_post_meta( $order->get_id(), '_company_name', true );
		$company_address = get_post_meta( $order->get_id(), '_company_address', true );
		$company_nip     = get_post_meta( $order->get_id(), '_company_nip', true );

		if ( $not_vat === 'yes' ) {
			echo '<p><strong>' . esc_html__( 'Invoice (not VAT)', 'flatsome' ) . ':</strong> ✔</p>';
			if ( $company_name ) {
				echo '<p><strong>' . esc_html__( 'Company Name', 'flatsome' ) . ':</strong> ' . esc_html( $company_name ) . '</p>';
			}

			if ( $company_address ) {
				echo '<p><strong>' . esc_html__( 'Company Address', 'flatsome' ) . ':</strong> ' . esc_html( $company_address ) . '</p>';
			}

			if ( $company_nip ) {
				echo '<p><strong>' . esc_html__( 'Company NIP', 'flatsome' ) . ':</strong> ' . esc_html( $company_nip ) . '</p>';
			}
		}
	}

	/**
	 * Show Unknown Address in Order.
	 *
	 * @param WC_Order $order WooCommerce Order class.
	 *
	 * @return void
	 */
	public function show_unknown_address_in_order( WC_Order $order ): void {
		$unknown_address        = get_post_meta( $order->get_id(), '_unknown_address', true );
		$receiver_phone         = get_post_meta( $order->get_id(), '_receiver_phone', true );
		$photo_to_email_address = get_post_meta( $order->get_id(), '_photo_to_email_address', true );

		if ( $unknown_address === 'yes' ) {
			echo '<p><strong>' . esc_html__( 'I don\'t know the recipient\'s address', 'flatsome' ) . ':</strong> ✔</p>';
			if ( $receiver_phone ) {
				echo '<p><strong>' . esc_html__( 'Phone of the recipient', 'flatsome' ) . ':</strong> ' . esc_html( $receiver_phone ) . '</p>';
			}
		}

		if ( 'yes' === $photo_to_email_address ) {
			echo '<p><strong>' . esc_html__( 'I want to receive a photo of the bouquet by e-mail ', 'flatsome' ) . ':</strong> ✔</p>';
		}
	}

	/**
	 * Set invoice number.
	 *
	 * @return void
	 */
	public function set_invoice_number(): void {
		$invoice_number = (int) get_transient( 'fl_invoice_number' );

		if ( ! empty( $invoice_number ) ) {
			$invoice_number ++;
			set_transient( 'fl_invoice_number', $invoice_number, MONTH_IN_SECONDS );
		}
	}

	/**
	 * Add  phone shipping address
	 *
	 * @param array $fields
	 *
	 * @return array
	 */
	public function add_phone_shipping_address( array $fields ): array {
		$fields['shipping_phone_number'] = [
			'label'       => esc_html__( 'Recipient\'s phone', 'flatsome' ),
			'placeholder' => esc_html__( 'Enter the recipient\'s phone', 'flatsome' ),
			'required'    => true,
			'class'       => [ 'form-row-wide' ],
			'clear'       => true,
		];

		return $fields;
	}

	/**
	 * Save phone shipping address.
	 *
	 * @param int $order_id Order ID.
	 *
	 * @return void
	 */
	public function save_phone_shipping_address( int $order_id ): void {
		if ( ! empty( $_POST['shipping_phone_number'] ) ) {
			update_post_meta( $order_id, '_shipping_phone_number', sanitize_text_field( $_POST['shipping_phone_number'] ) );
		}
	}

	/**
	 * Display phone shipping address.
	 *
	 * @param WC_Order $order WooCommerce Order class.
	 *
	 * @return void
	 */
	public function display_phone_shipping_address( $order ) {
		$custom_field = get_post_meta( $order->get_id(), '_shipping_phone_number', true );
		if ( $custom_field ) {
			echo '<p><strong>' . esc_html__( 'Recipient\'s phone:', 'flatsome' ) . '</strong> ' . esc_html( $custom_field ) . '</p>';
		}
	}
}
