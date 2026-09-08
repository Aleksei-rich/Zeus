/**
 * Consultation form UX.
 *
 * Validate files in the browser before any upload begins, then let the
 * browser perform a normal multipart POST to the public consultation endpoint.
 * Native form submission is intentionally used here because the hosting layer
 * has proven reliable for normal POSTs but inconsistent for XHR multipart
 * requests from real browsers. Server-side validation remains authoritative.
 */
( function () {
	'use strict';

	var form = document.querySelector( '[data-zeus-consultation-form]' );
	if ( ! form ) {
		return;
	}

	window.ZeusConsultationReliabilityActive = true;

	var input = form.querySelector( '#zeus-uploads' );
	var status = form.querySelector( '[data-zeus-upload-status]' );
	var submit = form.querySelector( '[data-zeus-submit]' );
	var maxFiles = 5;
	var maxPerFile = 10 * 1024 * 1024;
	var maxTotal = 15 * 1024 * 1024;
	var allowedExtensions = [ 'jpg', 'jpeg', 'png', 'webp', 'heic', 'heif', 'pdf' ];
	var submitting = false;

	function mb( bytes ) {
		return ( bytes / ( 1024 * 1024 ) ).toFixed( 1 ).replace( '.0', '' );
	}

	function extension( name ) {
		var parts = String( name || '' ).toLowerCase().split( '.' );
		return parts.length > 1 ? parts.pop() : '';
	}

	function ensureAlert() {
		var alert = form.querySelector( '[data-zeus-form-client-alert]' );
		if ( ! alert ) {
			alert = document.createElement( 'div' );
			alert.className = 'zeus-form__alert';
			alert.setAttribute( 'role', 'alert' );
			alert.setAttribute( 'aria-live', 'assertive' );
			alert.setAttribute( 'data-zeus-form-client-alert', '' );
			alert.hidden = true;
			form.insertBefore( alert, form.firstChild );
		}
		return alert;
	}

	function clearAlert() {
		var alert = ensureAlert();
		alert.textContent = '';
		alert.hidden = true;
	}

	function showAlert( message ) {
		var alert = ensureAlert();
		alert.textContent = message;
		alert.hidden = false;
		alert.scrollIntoView( { behavior: 'smooth', block: 'center' } );
	}

	function setUploadMessage( message, isError ) {
		if ( ! status ) {
			return;
		}
		status.textContent = message || '';
		status.classList.toggle( 'zeus-form__error', !! isError );
	}

	function selectedFiles() {
		return input ? Array.prototype.slice.call( input.files || [] ) : [];
	}

	function validateFiles() {
		if ( ! input ) {
			return true;
		}

		var files = selectedFiles();
		var total = files.reduce( function ( sum, file ) { return sum + file.size; }, 0 );
		var message = '';
		var valid = true;
		var invalidType = null;
		var tooLarge = null;
		var emptyFile = null;

		files.some( function ( file ) {
			if ( allowedExtensions.indexOf( extension( file.name ) ) === -1 ) {
				invalidType = file;
				return true;
			}
			return false;
		} );

		files.some( function ( file ) {
			if ( file.size > maxPerFile ) {
				tooLarge = file;
				return true;
			}
			return false;
		} );

		files.some( function ( file ) {
			if ( file.size === 0 ) {
				emptyFile = file;
				return true;
			}
			return false;
		} );

		if ( files.length > maxFiles ) {
			message = 'You selected ' + files.length + ' files. Maximum is 5. Please remove ' + ( files.length - maxFiles ) + ( files.length - maxFiles === 1 ? ' file.' : ' files.' );
			valid = false;
		} else if ( tooLarge ) {
			message = '“' + tooLarge.name + '” is ' + mb( tooLarge.size ) + 'MB. Maximum is 10MB per file. Please choose a smaller photo or file.';
			valid = false;
		} else if ( total > maxTotal ) {
			message = 'Selected files total ' + mb( total ) + 'MB. Maximum total is 15MB. Please remove a file or choose smaller photos.';
			valid = false;
		} else if ( invalidType ) {
			message = '“' + invalidType.name + '” is not a supported file type. Please use JPG, PNG, WEBP, HEIC/HEIF, or PDF.';
			valid = false;
		} else if ( emptyFile ) {
			message = '“' + emptyFile.name + '” appears to be empty. Please choose the file again.';
			valid = false;
		} else if ( files.length ) {
			message = files.length + ( files.length === 1 ? ' file' : ' files' ) + ' selected (' + mb( total ) + 'MB total). Ready to send.';
		}

		setUploadMessage( message, ! valid );
		input.setCustomValidity( valid ? '' : message );
		return valid;
	}

	if ( input ) {
		input.addEventListener( 'change', function () {
			clearAlert();
			validateFiles();
		} );
	}

	// Capture phase prevents the theme's legacy submit handler from changing
	// the button state. We intentionally DO NOT preventDefault for valid forms:
	// the browser performs the multipart POST and follows the server redirect.
	form.addEventListener( 'submit', function ( event ) {
		if ( submitting ) {
			event.preventDefault();
			return;
		}

		event.stopImmediatePropagation();
		clearAlert();

		if ( ! validateFiles() ) {
			event.preventDefault();
			if ( input ) {
				input.focus();
				input.reportValidity();
			}
			return;
		}

		if ( ! form.checkValidity() ) {
			event.preventDefault();
			form.reportValidity();
			return;
		}

		submitting = true;
		var files = selectedFiles();

		if ( submit ) {
			submit.disabled = true;
			submit.setAttribute( 'aria-disabled', 'true' );
			submit.textContent = files.length ? 'Uploading files… Please wait' : 'Sending… Please wait';
		}

		if ( files.length ) {
			setUploadMessage( 'Uploading ' + files.length + ( files.length === 1 ? ' file' : ' files' ) + ' (' + mb( files.reduce( function ( sum, file ) { return sum + file.size; }, 0 ) ) + 'MB total). Please keep this page open.', false );
		}
	}, true );
} )();
