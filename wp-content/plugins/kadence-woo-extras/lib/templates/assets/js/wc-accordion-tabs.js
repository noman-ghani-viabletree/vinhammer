/**
 * Create an accordion form tabs.
 */
 jQuery( function( $ ) {
	$( '.kwt-tabs-style-accordion' ).each( function( a ) {
		var startClosed = $( this ).hasClass( 'kwt-tabs-accordion-start-closed' );
		$( this ).find( '.wc-tabs > li' ).each( function() {
			var tabId = $( this ).attr( 'aria-controls' );
			var activeclass;
			if ( $( this ).hasClass( 'active' ) ) {
				activeclass = 'active';
				if ( startClosed ) {
					activeclass = 'inactive';
					$( this ).closest( '.wc-tabs-wrapper' ).find( '#' + tabId ).hide();
				}
			} else {
				activeclass = 'inactive';
			}
			$( this ).closest( '.wc-tabs-wrapper' ).find( '#' + tabId ).before( '<div class="kwt-accordion-title kwt-accordion-' + tabId + ' ' + activeclass + '">' + $( this ).html() + '</div>' );
			$( this ).closest( '.wc-tabs-wrapper' ).find( '.kwt-accordion-' + tabId + ' a' ).addClass('scroll-ignore');
			$( this ).closest( '.wc-tabs-wrapper' ).find( '.kwt-accordion-' + tabId + ' a' ).append( '<span class="kwt-accordion-trigger"></span>' );
		} );
		$( '.kwt-accordion-title a' ).on( 'click', function( e ) {
			e.preventDefault();
			var tabId = $( this ).attr( 'href' );
			var tabListId = tabId.replace( '#tab-' , '' );
			if ( $( this ).closest( '.kwt-accordion-title' ).hasClass( 'active' ) ) {
				$( this ).closest( '.wc-tabs-wrapper' ).find( tabId ).hide();
				$( this ).closest( '.kwt-accordion-title' ).removeClass( 'active' ).addClass( 'inactive' );
			} else {
				$( this ).closest( '.wc-tabs-wrapper' ).find( '.wc-tabs li' ).removeClass( 'active' );
				$( this ).closest( '.wc-tabs-wrapper' ).find( '.wc-tabs tab-title-' + tabListId ).addClass( 'active' );
				$( this ).closest( '.wc-tabs-wrapper' ).find( tabId ).show();
				$( this ).closest( '.kwt-accordion-title' ).addClass( 'active' ).removeClass( 'inactive' );
			}
			window.dispatchEvent(new Event('resize'));
		} );
	} );
} );