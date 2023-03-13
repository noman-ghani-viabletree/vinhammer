/* global GLightbox */
/**
 * File kb-glight-video-init.js.
 * Gets video lighbox working for video popup.
 */

(function() {
	'use strict';
	var kadenceBlocksProVideoLightbox = {
		cache: [],
		wrapper: [],
		trigger: [],
		/**
		 * Initiate the script to process all
		 */
		initAll: function() {
			kadenceBlocksProVideoLightbox.cache = document.querySelectorAll( '.kadence-video-popup-link.kadence-video-type-local' );
			for ( let i = 0; i < kadenceBlocksProVideoLightbox.cache.length; i++ ) {
				kadenceBlocksProVideoLightbox.wrapper[i] = document.getElementById( kadenceBlocksProVideoLightbox.cache[ i ].getAttribute( 'data-popup-id' ) );
				kadenceBlocksProVideoLightbox.cache[ i ].addEventListener( 'click', function( event ) {
					event.preventDefault();
					kadenceBlocksProVideoLightbox.trigger[i] = GLightbox({
						elements: [ {
							'href' : kadenceBlocksProVideoLightbox.wrapper[i].querySelector( '.kadence-local-video-popup' ).getAttribute( 'src' ),
							'type' : 'video',
							'source' : 'local',
						}],
						touchNavigation: true,
						skin: 'kadence-dark',
						loop: false,
						openEffect: 'fade',
						closeEffect: 'fade',
						autoplayVideos: true,
						plyr: {
							css: kadence_pro_video_pop.plyr_css,
							js: kadence_pro_video_pop.plyr_js,
							config: {
								hideControls: true,
							}
						}
					});
					kadenceBlocksProVideoLightbox.trigger[i].open();
				} );
			}
			GLightbox({
				selector: '.kadence-video-popup-link:not(.kadence-video-type-local)',
				touchNavigation: true,
				skin: 'kadence-dark',
				loop: false,
				openEffect: 'fade',
				closeEffect: 'fade',
				autoplayVideos: true,
				plyr: {
					css: kadence_pro_video_pop.plyr_css,
					js: kadence_pro_video_pop.plyr_js,
					config: {
						hideControls: true,
					}
				}
			});
		},
		// Initiate the menus when the DOM loads.
		init: function() {
			if ( typeof GLightbox == 'function' ) {
				kadenceBlocksProVideoLightbox.initAll();
			} else {
				var initLoadDelay = setInterval( function(){ if ( typeof GLightbox == 'function' ) { kadenceBlocksProVideoLightbox.initAll(); clearInterval(initLoadDelay); } }, 200 );
			}
		}
	}
	if ( 'loading' === document.readyState ) {
		// The DOM has not yet been loaded.
		document.addEventListener( 'DOMContentLoaded', kadenceBlocksProVideoLightbox.init );
	} else {
		// The DOM has already been loaded.
		kadenceBlocksProVideoLightbox.init();
	}
})();