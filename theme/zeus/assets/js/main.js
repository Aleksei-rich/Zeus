/**
 * Minimal frontend JS. No jQuery or framework.
 */
( function () {
	'use strict';

	var toggle = document.querySelector( '[data-zeus-menu-toggle]' );
	var drawer = document.getElementById( 'zeus-mobile-nav' );

	if ( ! toggle || ! drawer ) {
		return;
	}

	var inertSiblings = Array.prototype.filter.call(
		document.body.children,
		function ( el ) { return el !== drawer; }
	);

	function closeDrawer( returnFocus ) {
		drawer.setAttribute( 'data-open', 'false' );
		toggle.setAttribute( 'aria-expanded', 'false' );
		document.body.style.overflow = '';
		inertSiblings.forEach( function ( el ) { el.removeAttribute( 'inert' ); } );
		if ( returnFocus ) {
			window.requestAnimationFrame( function () {
				toggle.focus();
			} );
		}
	}

	function openDrawer() {
		drawer.setAttribute( 'data-open', 'true' );
		toggle.setAttribute( 'aria-expanded', 'true' );
		document.body.style.overflow = 'hidden';
		inertSiblings.forEach( function ( el ) { el.setAttribute( 'inert', '' ); } );
		var firstLink = drawer.querySelector( 'a' );
		if ( firstLink ) {
			firstLink.focus();
		}
	}

	toggle.addEventListener( 'click', function () {
		if ( drawer.getAttribute( 'data-open' ) === 'true' ) {
			closeDrawer( true );
		} else {
			openDrawer();
		}
	} );

	var closeBtn = drawer.querySelector( '[data-zeus-menu-close]' );
	if ( closeBtn ) {
		closeBtn.addEventListener( 'click', function () { closeDrawer( true ); } );
	}

	document.addEventListener( 'keydown', function ( e ) {
		if ( e.key === 'Escape' && drawer.getAttribute( 'data-open' ) === 'true' ) {
			closeDrawer( true );
		}
	} );
} )();

/**
 * Consultation upload UX. Server-side validation remains authoritative;
 * this only gives immediate mobile/desktop feedback before a large POST.
 */
( function () {
	'use strict';

	var form = document.querySelector( '[data-zeus-consultation-form]' );
	if ( ! form ) {
		return;
	}

	var input = form.querySelector( '#zeus-uploads' );
	var status = form.querySelector( '[data-zeus-upload-status]' );
	var submit = form.querySelector( '[data-zeus-submit]' );
	var maxFiles = 5;
	var maxPerFile = 10 * 1024 * 1024;
	var maxTotal = 15 * 1024 * 1024;

	function mb( bytes ) {
		return ( bytes / ( 1024 * 1024 ) ).toFixed( 1 ).replace( '.0', '' );
	}

	function validateFiles() {
		if ( ! input || ! status ) {
			return true;
		}

		var files = Array.prototype.slice.call( input.files || [] );
		var total = files.reduce( function ( sum, file ) { return sum + file.size; }, 0 );
		var tooLarge = files.some( function ( file ) { return file.size > maxPerFile; } );
		var message = '';
		var valid = true;

		if ( files.length > maxFiles ) {
			message = 'Please choose no more than 5 files.';
			valid = false;
		} else if ( tooLarge ) {
			message = 'Each file must be 10MB or smaller.';
			valid = false;
		} else if ( total > maxTotal ) {
			message = 'Selected files total ' + mb( total ) + 'MB. Maximum total is 15MB.';
			valid = false;
		} else if ( files.length ) {
			message = files.length + ( files.length === 1 ? ' file' : ' files' ) + ' selected (' + mb( total ) + 'MB total).';
		}

		status.textContent = message;
		status.classList.toggle( 'zeus-form__error', ! valid );
		input.setCustomValidity( valid ? '' : message );
		return valid;
	}

	if ( input ) {
		input.addEventListener( 'change', validateFiles );
	}

	form.addEventListener( 'submit', function ( event ) {
		if ( ! validateFiles() ) {
			event.preventDefault();
			if ( input ) {
				input.focus();
			}
			return;
		}

		if ( submit ) {
			submit.disabled = true;
			submit.setAttribute( 'aria-disabled', 'true' );
			submit.textContent = 'Sending…';
		}
	} );
} )();
