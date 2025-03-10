<?php
/**
 * Invoice lulafleur.
 *
 * @package iwpdev/flatsome-child
 */

use Iwpdev\FlatsomeChild\CarbonFields\InvoiceSettings;
use NumberToWords\NumberToWords;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

$invoice_number = (int) get_transient( 'fl_invoice_number' );
if ( empty( $invoice_number ) ) {
	$invoice_number = 1;
	set_transient( 'fl_invoice_number', $invoice_number, MONTH_IN_SECONDS );
}

$order_id = $this->order->get_id();
?>
<table class="invoice-table">
	<tr>
		<td>
			<p class="mb">
				<strong><?php esc_html_e( 'Invoice number', 'flatsome' ); ?> </strong>
				<?php echo esc_html( $invoice_number ); ?><?php echo gmdate( '/m/Y' ); ?>
			</p>
		</td>
	</tr>
	<tr>
		<td>
			<p class="mn">
				<strong><?php esc_html_e( 'Date of issue:', 'flatsome' ); ?></strong>
				<?php echo esc_html( get_option( 'woocommerce_store_city', true ) ?? '' ); ?>
				, <?php echo gmdate( 'Y-m-d' ); ?>
			</p>
		</td>
	</tr>
	<tr>
		<td>
			<p class="mn">
				<strong><?php esc_html_e( 'Date of sale:', 'flatsome' ); ?></strong>
				<?php echo gmdate( 'Y-m-d' ); ?>
			</p>
		</td>
	</tr>
	<tr>
		<td>
			<p class="mn">
				<strong><?php esc_html_e( 'Maturity:', 'flatsome' ); ?></strong>
				<?php echo gmdate( 'Y-m-d', strtotime( "+1 day" ) ); ?>
			</p>
		</td>
	</tr>
	<tr>
		<td>
			<p class="mb">
				<strong><?php esc_html_e( 'Payment:', 'flatsome' ); ?></strong>
				<?php echo $this->order->get_payment_method_title(); ?>
			</p>
		</td>
	</tr>
	<tr>
		<td>
			<table class="invoice-price-contacts">
				<tr>
					<th class="tl w50"><?php esc_html_e( 'Seller', 'flatsome' ); ?></th>
					<th class="tl w50"><?php esc_html_e( 'Buyer', 'flatsome' ); ?></th>
				</tr>
				<tr>
					<td class="w50 vt">
						<p class="mn">
							<?php echo esc_html( carbon_get_theme_option( InvoiceSettings::FILED_PREFIX . 'store_name' ) ?? '' ); ?>
						</p>
					</td>
					<td class="w50 vt">
						<p class="mn">
							<?php
							$buyer_firm = get_post_meta( $order_id, '_company_name', true );
							if ( empty( $buyer_firm ) ) {
								$buyer_firm = $this->order->get_billing_first_name() . ' ' . $this->order->get_billing_last_name();
							}

							echo esc_html( $buyer_firm );
							?>
						</p>
					</td>
				</tr>
				<tr>
					<td class="w50 vt">
						<p class="mn">
							<?php echo esc_html( carbon_get_theme_option( InvoiceSettings::FILED_PREFIX . 'address_line' ) ?? '' ); ?>
						</p>
					</td>
					<td class="w50 vt">
						<p class="mn">
							<?php
							$buyer_address = get_post_meta( $order_id, '_company_address', true );

							if ( empty( $buyer_address ) ) {
								$buyer_address = $this->order->get_billing_address_1();
							}

							echo esc_html( $buyer_address );
							?>
						</p>
					</td>
				</tr>
				<tr>
					<td class="w50 vt mn">
						<p sclass="mn">
							<?php
							echo esc_html( 'NIP ' . carbon_get_theme_option( InvoiceSettings::FILED_PREFIX . 'nip' ) ?? '' );
							?>
						</p>
					</td>
					<td class="w50 vt mn">
						<p class="mn">
							<?php
							$nip = get_post_meta( $order_id, '_company_nip', true );
							if ( ! empty( $nip ) ) {
								esc_html_e( 'NIP: ', 'flatsome' );
							}
							echo esc_html( $nip ?? '' );
							?>
						</p>
					</td>
				</tr>
				<tr>
					<td class="w50 vt">
						<p class="mn">
							<?php
							$email = carbon_get_theme_option( InvoiceSettings::FILED_PREFIX . 'email' ) ?? '';
							?>
							<?php esc_html_e( 'Email: ', 'flatsome' ); ?>
							<a href="mailto:<?php echo esc_attr( $email ); ?>">
								<?php echo esc_attr( $email ); ?>
							</a>
						</p>
					</td>
					<td class="w50 vt"></td>
				</tr>
				<tr>
					<td class="w50 vt">
						<p class="mn">
							<?php
							$tel = carbon_get_theme_option( InvoiceSettings::FILED_PREFIX . 'tel' ) ?? '';
							?>
							<?php esc_html_e( 'tel.', 'flatsome' ); ?>
							<a href="tel:<?php echo esc_attr( $tel ); ?>"><?php echo esc_attr( $tel ); ?></a>
						</p>
					</td>
					<td class="w50 vt"></td>
				</tr>
				<tr>
					<td class="w50 vt">
						<p class="mn">
							<?php
							$account_number = carbon_get_theme_option( InvoiceSettings::FILED_PREFIX . 'account_number' ) ?? '';
							esc_html_e( 'Account number: ', 'flatsome' );
							echo esc_html( $account_number );
							?>
						</p>
					</td>
					<td class="w50 vt"></td>
				</tr>
				<tr>
					<td class="w50 vt">
						<p class="mn">
							<?php
							$bank_name = carbon_get_theme_option( InvoiceSettings::FILED_PREFIX . 'bank_name' ) ?? '';
							esc_html_e( 'Bank name: ', 'flatsome' );
							echo esc_html( $bank_name );
							?>
						</p>
					</td>
					<td class="w50 vt" valign="top"></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table class="invoice-price-table" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<th class="w40"><?php esc_html_e( 'LP', 'flatsome' ); ?></th>
					<th><?php esc_html_e( 'Name of the service of the service', 'flatsome' ); ?></th>
					<th><?php esc_html_e( 'Quantity', 'flatsome' ); ?></th>
					<th><?php esc_html_e( 'Price', 'flatsome' ); ?></th>
					<th><?php esc_html_e( 'Value', 'flatsome' ); ?></th>
				</tr>
				<?php
				if ( ! empty( $this->order->get_items() ) ) {
					$iterator = 1;
					foreach ( $this->order->get_items() as $item_id => $item ) {
						$product = $item->get_product();
						?>
						<tr>
							<td class="w40"><p style="margin:0;"><?php echo esc_html( $iterator ); ?></p></td>
							<td>
								<p class="mn">
									<?php echo esc_html( $item->get_name() ); ?>
								</p>
							</td>
							<td>
								<p class="mn"><?php echo esc_html( $item->get_quantity() ) . ' ' . InvoiceSettings::get_quantity_text(); ?></p>
							</td>
							<td><p class="mn">
									<?php
									echo wp_kses_post( number_format( $product->get_price(), 2 ) . ' ' . get_woocommerce_currency_symbol() ); ?>
								</p>
							</td>
							<td><p class="mn">
									<?php
									echo wp_kses_post( number_format( $product->get_price(), 2 ) . ' ' . get_woocommerce_currency_symbol() ); ?>
								</p>
							</td>
						</tr>
						<?php
						$iterator ++;
					}
					?>
					<tr>
						<?php
						foreach ( $this->order->get_shipping_methods() as $shipping ) {
							echo '<td class="w40 bn"></td>';
							printf( '<td>%s</td>', esc_html( $shipping->get_name() ) );
							echo '<td></td>';
							printf( '<td>%s</td>', esc_html( number_format( $this->order->get_shipping_total(), 2 ) . ' ' . get_woocommerce_currency_symbol() ) );
							printf( '<td>%s</td>', esc_html( number_format( $this->order->get_shipping_total(), 2 ) . ' ' . get_woocommerce_currency_symbol() ) );
						}
						?>
					</tr>
					<tr>
						<td class="w40 bn"></td>
						<td class="bn"></td>
						<td class="bn"></td>
						<td><strong><?php esc_html_e( 'Razem', 'flatsome' ); ?></strong></td>
						<td>
							<strong><?php echo esc_html( number_format( $this->order->get_total(), 2 ) . ' ' . get_woocommerce_currency_symbol() ); ?></strong>
						</td>
					</tr>
					<tr>
						<td class="w40 bn"></td>
						<td class="bn"></td>
						<td class="bn"></td>
						<td class="bn"><strong><?php esc_html_e( 'Total', 'flatsome' ); ?></strong></td>
						<td class="bn">
							<p class="mn"><?php echo wp_kses_post( wc_price( $this->order->get_total() ) ); ?></p>
						</td>
					</tr>
				<?php } ?>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table class="invoice-description-table" cellpadding="0" cellspacing="0">
				<tr>
					<td class="vt w200"></td>
					<td>
						<?php echo esc_html( carbon_get_theme_option( InvoiceSettings::FILED_PREFIX . 'vat_description' ) ?? '' ); ?>
					</td>
				</tr>
				<tr>
					<td class="vt w200">
						<?php esc_html_e( 'For payment ', 'flatsome' ); ?>
					</td>
					<td>
						<?php echo wp_kses_post( wc_price( $this->order->get_total() ) ); ?>
						<?php
						$numberToWords = new NumberToWords();
						$converter     = $numberToWords->getCurrencyTransformer( defined( 'ICL_LANGUAGE_CODE' ) ? ICL_LANGUAGE_CODE : 'en' );
						echo esc_html( $converter->toWords( round( $this->order->get_total() * 100 ), 'PLN' ) );
						?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr class="tr">
		<td><strong><?php esc_html_e( 'Name and surname', 'flatsome' ); ?></strong></td>
	</tr>
	<tr class="tr">
		<td>
			<?php echo esc_html( carbon_get_theme_option( InvoiceSettings::FILED_PREFIX . 'store_name' ) ?? '' ); ?>
		</td>
	</tr>
</table>
