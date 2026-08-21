/**
 * Minimal frontend JS: mobile nav drawer toggle only. No jQuery, no
 * framework. FAQ accordions use native <details>/<summary> and need no
 * script. Fails gracefully — the toggle button/drawer degrade to normal
 * document flow if this file doesn't load.
 */
( function () {
	'use strict';

	var toggle = document.querySelector( '[data-zeus-menu-toggle]' );
	var drawer = document.getElementById( 'zeus-mobile-nav' );

	if ( ! toggle || ! drawer ) {
		return;
	}

	function closeDrawer() {
		drawer.setAttribute( 'data-open', 'false' );
		toggle.setAttribute( 'aria-expanded', 'false' );
		document.body.style.overflow = '';
	}

	function openDrawer() {
		drawer.setAttribute( 'data-open', 'true' );
		toggle.setAttribute( 'aria-expanded', 'true' );
		document.body.style.overflow = 'hidden';
		var firstLink = drawer.querySelector( 'a' );
		if ( firstLink ) {
			firstLink.focus();
		}
	}

	toggle.addEventListener( 'click', function () {
		var isOpen = drawer.getAttribute( 'data-open' ) === 'true';
		if ( isOpen ) {
			closeDrawer();
		} else {
			openDrawer();
		}
	} );

	var closeBtn = drawer.querySelector( '[data-zeus-menu-close]' );
	if ( closeBtn ) {
		closeBtn.addEventListener( 'click', closeDrawer );
	}

	document.addEventListener( 'keydown', function ( e ) {
		if ( e.key === 'Escape' && drawer.getAttribute( 'data-open' ) === 'true' ) {
			closeDrawer();
			toggle.focus();
		}
	} );
} )();
