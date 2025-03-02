/* global jQuery */

jQuery( document ).ready( function( $ ) {
	$( '#unknown_address' ).change( function() {
		$( '#receiver_phone_field' ).toggle( this.checked );
	} );
} );
