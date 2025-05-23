/* global jQuery */

jQuery( document ).ready( function( $ ) {
	const unknownAddress = $( '#unknown_address' );
	if ( unknownAddress.length ) {
		unknownAddress.change( function() {
			$( '#receiver_phone_field' ).toggle( this.checked );
		} );
	}

	const notVatInvoice = $( '#not_vat' );
	if ( notVatInvoice.length ) {
		notVatInvoice.change( function() {
			$( '#company_name_field' ).toggle( this.checked );
			$( '#company_address_field' ).toggle( this.checked );
			$( '#company_nip_field' ).toggle( this.checked );
		} );
	}
} );
