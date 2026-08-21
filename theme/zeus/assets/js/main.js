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

	// Everything outside the drawer becomes inert while it's open, so
	// Tab can't leave the drawer into content that's visually hidden
	// behind it (no hand-rolled focus trap needed — `inert` handles
	// both focus and AT exposure). Supported in all current browsers.
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
			// Un-inerting isn't synchronously reflected for focus() in
			// every browser — defer one frame so the toggle is actually
			// focusable by the time we call it.
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
		var isOpen = drawer.getAttribute( 'data-open' ) === 'true';
		if ( isOpen ) {
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
