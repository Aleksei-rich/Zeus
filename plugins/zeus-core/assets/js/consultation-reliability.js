/**
 * Consultation form UX.
 *
 * Client-side limits stop invalid photo sets before a large upload starts.
 * Valid submissions use XHR so network/server failures can be shown inside
 * the form instead of leaving the visitor on an admin-post.php error page.
 * Server-side validation remains authoritative.
 */
( function () {
	'use strict';

	var form = document.querySelector( '[data-zeus-consultation-form]' );
	if ( ! form ) {
		return;
	}

	// Allows older theme JS to detect that the reliable handler owns submit UX.
	window.ZeusConsultationReliabilityActive = true;

	function primeHostGateCookie() {
		document.cookie = 'hc_js_gate=1;path=/;SameSite=Lax;Max-Age=3600';
	}
	primeHostGateCookie();

	var input = form.querySelector( '#zeus-uploads' );
	var status = form.querySelector( '[data-zeus-upload-status]' );
	var submit = form.querySelector( '[data-zeus-submit]' );
	var maxFiles = 5;
	var maxPerFile = 10 * 1024 * 1024;
	var maxTotal = 15 * 1024 * 1024;
	var allowedExtensions = [ 'jpg', 'jpeg', 'png', 'webp', 'heic', 'heif', 'pdf' ];
	var request = null;
	var submissionId = '';

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
		if ( ! input || ! status ) {
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

	function makeSubmissionId() {
		if ( submissionId ) {
			return submissionId;
		}
		if ( window.crypto && typeof window.crypto.randomUUID === 'function' ) {
			submissionId = window.crypto.randomUUID();
		} else {
			submissionId = 'zeus-' + Date.now() + '-' + Math.random().toString( 36 ).slice( 2 ) + Math.random().toString( 36 ).slice( 2 );
		}
		return submissionId;
	}

	function resetSubmitButton() {
		if ( ! submit ) {
			return;
		}
		submit.disabled = false;
		submit.removeAttribute( 'aria-disabled' );
		submit.textContent = 'Request Free Consultation';
	}

	function failRequest( message ) {
		resetSubmitButton();
		showAlert( message );
		if ( input && selectedFiles().length ) {
			setUploadMessage( selectedFiles().length + ( selectedFiles().length === 1 ? ' file is' : ' files are' ) + ' still selected. You can try again.', true );
		}
	}

	function serverErrorMessage( payload, xhr ) {
		var errors = payload && payload.data && payload.data.errors ? payload.data.errors : null;
		if ( errors ) {
			if ( errors.uploads ) {
				setUploadMessage( errors.uploads, true );
			}
			var messages = Object.keys( errors ).map( function ( key ) { return errors[ key ]; } ).filter( Boolean );
			if ( messages.length ) {
				return messages.join( ' ' );
			}
		}

		if ( xhr && xhr.status === 413 ) {
			return 'The selected photos are too large for one request. Please keep the total at 15MB or less and each file at 10MB or less.';
		}
		if ( xhr && xhr.status === 429 ) {
			return 'Several requests were sent recently. Please wait a little and try again.';
		}
		if ( xhr && xhr.status >= 500 ) {
			return 'The server could not finish the request. Your information is still on this page. Please try again.';
		}
		if ( xhr && /Checking your browser/i.test( xhr.responseText || '' ) ) {
			return 'The hosting security check blocked this upload before it reached the form handler. Please refresh the page and try again.';
		}
		return 'We could not send your request. Please review the form and try again.';
	}

	if ( input ) {
		input.addEventListener( 'change', function () {
			clearAlert();
			validateFiles();
		} );
	}

	form.addEventListener( 'submit', function ( event ) {
		if ( ! window.XMLHttpRequest || ! window.FormData ) {
			if ( ! validateFiles() ) {
				event.preventDefault();
				if ( input ) {
					input.focus();
				}
			}
			return;
		}

		event.preventDefault();
		event.stopImmediatePropagation();
		clearAlert();

		if ( ! validateFiles() ) {
			resetSubmitButton();
			if ( input ) {
				input.focus();
				input.reportValidity();
			}
			return;
		}

		if ( ! form.checkValidity() ) {
			resetSubmitButton();
			form.reportValidity();
			return;
		}

		if ( request ) {
			return;
		}

		primeHostGateCookie();
		var data = new FormData( form );
		data.set( 'zeus_ajax', '1' );
		data.set( 'zeus_submission_id', makeSubmissionId() );

		request = new XMLHttpRequest();
		request.open( 'POST', form.action, true );
		request.withCredentials = true;
		request.setRequestHeader( 'X-Zeus-Async', '1' );
		request.setRequestHeader( 'Accept', 'application/json' );
		request.timeout = 180000;

		if ( submit ) {
			submit.disabled = true;
			submit.setAttribute( 'aria-disabled', 'true' );
			submit.textContent = selectedFiles().length ? 'Uploading…' : 'Sending…';
		}
		if ( selectedFiles().length ) {
			setUploadMessage( 'Preparing upload…', false );
		}

		request.upload.addEventListener( 'progress', function ( progress ) {
			if ( ! progress.lengthComputable ) {
				return;
			}
			var percent = Math.max( 0, Math.min( 100, Math.round( ( progress.loaded / progress.total ) * 100 ) ) );
			if ( submit ) {
				submit.textContent = 'Uploading… ' + percent + '%';
			}
			if ( status && selectedFiles().length ) {
				setUploadMessage( 'Uploading ' + selectedFiles().length + ( selectedFiles().length === 1 ? ' file' : ' files' ) + ': ' + percent + '%', false );
			}
		} );

		request.addEventListener( 'load', function () {
			var xhr = request;
			request = null;
			var payload = null;
			try {
				payload = xhr.responseText ? JSON.parse( xhr.responseText ) : null;
			} catch ( ignore ) {
				payload = null;
			}

			if ( xhr.status >= 200 && xhr.status < 300 && payload && payload.success ) {
				if ( submit ) {
					submit.textContent = 'Request received';
				}
				setUploadMessage( 'Upload complete. Your request was received.', false );
				window.location.assign( payload.data && payload.data.redirect ? payload.data.redirect : '/thank-you/' );
				return;
			}

			failRequest( serverErrorMessage( payload, xhr ) );
		} );

		request.addEventListener( 'error', function () {
			request = null;
			failRequest( 'We could not confirm the upload because the connection to the server was interrupted. Your information and selected files are still here. Please try again. Re-trying will not create a duplicate if the first attempt was already received.' );
		} );

		request.addEventListener( 'timeout', function () {
			request = null;
			failRequest( 'The upload is taking too long. Your information and selected files are still here. Please check your connection and try again. If needed, use fewer or smaller photos.' );
		} );

		request.addEventListener( 'abort', function () {
			request = null;
			failRequest( 'The upload was interrupted. Your information is still here and you can try again.' );
		} );

		request.send( data );
	}, true );
} )();
