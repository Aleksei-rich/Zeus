/**
 * Admin-only: wires the native wp.media picker to the single-image and
 * gallery meta box fields in inc/meta-fields.php. Never loaded on the
 * frontend (see inc/enqueue.php admin_enqueue_scripts hook).
 */
( function ( $ ) {
	'use strict';

	$( document ).on( 'click', '.zeus-image-field__select', function ( e ) {
		e.preventDefault();
		var field = $( this ).closest( '.zeus-image-field' );
		var frame = wp.media( { multiple: false } );
		frame.on( 'select', function () {
			var attachment = frame.state().get( 'selection' ).first().toJSON();
			field.find( '.zeus-image-field__input' ).val( attachment.id );
			field.find( '.zeus-image-field__preview' ).html(
				'<img src="' + attachment.url + '" style="max-width:100px;height:auto;display:block;">'
			);
		} );
		frame.open();
	} );

	$( document ).on( 'click', '.zeus-image-field__clear', function ( e ) {
		e.preventDefault();
		var field = $( this ).closest( '.zeus-image-field' );
		field.find( '.zeus-image-field__input' ).val( '' );
		field.find( '.zeus-image-field__preview' ).empty();
	} );

	$( document ).on( 'click', '.zeus-gallery-field__select', function ( e ) {
		e.preventDefault();
		var field = $( this ).closest( '.zeus-gallery-field' );
		var frame = wp.media( { multiple: true } );
		frame.on( 'select', function () {
			var attachments = frame.state().get( 'selection' ).toJSON();
			var ids = attachments.map( function ( a ) { return a.id; } );
			field.find( '.zeus-gallery-field__input' ).val( ids.join( ',' ) );
			var previewHtml = attachments
				.map( function ( a ) {
					return '<img src="' + a.url + '" style="max-width:80px;height:auto;display:inline-block;margin:2px;">';
				} )
				.join( '' );
			field.find( '.zeus-gallery-field__preview' ).html( previewHtml );
		} );
		frame.open();
	} );
} )( jQuery );
