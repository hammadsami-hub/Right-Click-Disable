/**
 * Right Click Disable - frontend script.
 * Blocks the context menu and shows a temporary notification.
 */
( function () {
	'use strict';

	var settings = window.rcdSettings || {};
	var designs = [ 'dark', 'light', 'green', 'red', 'blue' ];
	var message = settings.message || 'Right click is disabled!';
	var design = designs.indexOf( settings.design ) !== -1 ? settings.design : 'dark';
	var duration = 2500; // Milliseconds the notification stays visible.
	var toast = null;
	var hideTimer = null;

	function getToast() {
		if ( toast ) {
			return toast;
		}

		toast = document.createElement( 'div' );
		toast.className = 'rcd-toast rcd-toast--' + design;
		toast.setAttribute( 'role', 'status' );
		toast.setAttribute( 'aria-live', 'polite' );
		document.body.appendChild( toast );

		return toast;
	}

	function showToast() {
		var el = getToast();

		el.textContent = message; // textContent keeps the message safe from HTML injection.

		window.clearTimeout( hideTimer );

		// Force a reflow so the fade-in also runs on the very first click.
		void el.offsetWidth;
		el.classList.add( 'is-visible' );

		hideTimer = window.setTimeout( function () {
			el.classList.remove( 'is-visible' );
		}, duration );
	}

	document.addEventListener( 'contextmenu', function ( event ) {
		event.preventDefault();
		showToast();
	} );
}() );
