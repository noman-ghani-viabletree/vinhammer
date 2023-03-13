/**
 * GP Reload Form Front-end JS
 */
( function( $ ) {

	window.gwrf = window.GPReloadForm = function( args ) {

		var self = this;

		self.formId         = args.formId;
		self.spinnerUrl     = args.spinnerUrl;
		self.refreshTime    = args.refreshTime;
		self.refreshTimeout = null;

		// if we've already done the init for this form, don't do it again on subsequent gform_post_render calls
		if ( window[ 'gwrf_' + args.formId ] ) {
			return window[ 'gwrf_' + args.formId ];
		}

		self.formWrapper = $( '#gform_wrapper_' + self.formId );
		self.staticElem  = self.formWrapper.parent().add('#gf-cache-buster-form-container-' + self.formId).first();

		var clonedElem = $( '<div>' ).append( self.formWrapper.clone() );
		clonedElem.find( '.ginput_counter' ).remove();

		self.formHtml           = clonedElem.html();//.replace( /gform_post_render/g, 'XYZ' ); //$( '<div />' ).append( self.formWrapper.clone() ).html();
		self.spinnerInitialized = false;

		// Make sure we initialize multiple forms sharing the same wrapper
		if ( self.staticElem.data( 'gwrf_' + self.formId ) ) {
			return self.staticElem.data( 'gwrf_' + self.formId );
		}
		self.init = function() {

			$( document ).bind( 'gform_confirmation_loaded', function( event, formId ) {
				if ( formId != self.formId ) {
					return;
				}
								
				if (window['RELOAD_FORM_MARKUP_' + formId]) {
					self.formHtml = window['RELOAD_FORM_MARKUP_' + formId]
						/**
						 * See note in GP_Reload_Form::append_form_markup() regarding the Gravity Forms confirmation
						 * and why we need to escape/unescape this string.
						 */
						.replace(/gformGP_RELOAD_FORM_ESCAPEDRedirect\(\){/g, 'gformRedirect(){');
				}

				if ( self.refreshTime <= 0 || self.staticElem.find( '.form_saved_message' ).length > 0 ) {
					return;
				}

				self.refreshTimeout = setTimeout( function() {
					self.reloadForm();
				}, self.refreshTime * 1000 );

			} );

			self.staticElem.on( 'click', 'a.gws-reload-form', function( event ) {
				event.preventDefault();
				// Ensure we're calling the correct `reloadForm()`
				var linkFormId = parseInt( event.currentTarget.getAttribute( 'data-formId' ) );
				if ( self.formId !== linkFormId ) { // This was meant for a different form, re-direct call
					return window[ 'gwrf_' + linkFormId ].reloadForm();
				}
				self.reloadForm();
			} );

			self.staticElem.data( 'gwrf_' + self.formId, self );

		};

		self.reloadForm = function() {

			if ( self.refreshTimeout ) {
				clearTimeout( self.refreshTimeout );
			}

			var $replacingElem = self.staticElem.find( '#gform_confirmation_wrapper_' + self.formId + ', .gform_confirmation_message_' + self.formId + ', #gform_wrapper_' + self.formId );
			/**
			 * Filter which element will be replaced with the original form markup.
			 *
			 * @param {jQuery}       $replacingElem The element to be replaced with the original form markup.
			 * @param int            formId         The ID of the current form.
			 * @param {GPReloadForm} gprf           The current instance of GPReloadForm.
			 *
			 * @since 2.0.1
			 *
			 * @type {jQuery}
			 */
			$replacingElem = gform.applyFilters( 'gprf_replacing_elem', $replacingElem, self.formId, self );
			$replacingElem.replaceWith( self.formHtml );

			window[ 'gf_submitting_' + self.formId ] = false;
			gformInitSpinner( self.formId, self.spinnerUrl );

			self.clearGPNFEntries();

			$( document ).trigger( 'gform_post_render', [ parseInt( self.formId ), 1 ] );

			if ( window['gformInitDatepicker'] ) {
				gformInitDatepicker();
			}

		};

		/**
		 * Nested Forms tries to persist entries when a form is reloaded via AJAX to support multi-page forms using
		 * AJAX.
		 *
		 * This is problematic when Reload Forms reloads the form with AJAX, so we need to handle clearing out the entries
		 * ourselves.
		 *
		 * It's also worth noting that this will always happen regardless of "Preserve values from previous submission"
		 * being checked or not due to the nature of the previously submitted child entries being attached to the
		 * previously submitted parent entry.
		 */
		self.clearGPNFEntries = function () {
			self.formWrapper.find( '.gpnf-nested-entries-container' ).each( function () {
				var formFieldId = $( this ).siblings( 'input[type="hidden"][data-bind]' ).prop( 'id' ).replace( 'input_' + self.formId + '_', '' );
				var gpnf = window['GPNestedForms_{0}_{1}'.format( self.formId, formFieldId )];

				if ( gpnf ) {
					gpnf.entries = [];
				}
			} );
		}

		self.init();

	};

} )( jQuery );
