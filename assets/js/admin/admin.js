jQuery( document ).ready(function($) {

	jQuery( '.ccbpress-required-services.button' ).click( function() {
		jQuery('button#contextual-help-link').trigger('click');
		return false;
	});

	jQuery( '#ccbpress-ccb-service-check-button' ).click( function() {
		jQuery( '#ccbpress-ccb-service-check-loading' ).show();
		jQuery( '#ccbpress-ccb-service-check-button' ).attr('disabled', true);
		data = {
			action: 'ccbpress_check_services',
			nonce: ccbpress_vars.nonce
		};

		jQuery.post( ajaxurl, data,  function( response ) {
			jQuery( '#ccbpress-ccb-service-check-results' ).html( response );
			jQuery( '#ccbpress-ccb-service-check-loading' ).hide();
			jQuery( '#ccbpress-ccb-service-check-button' ).attr('disabled', false);
		});
		return false;
	});

	jQuery(document).on('widget-updated widget-added', function() {
		jQuery('#widgets-right .ccbpress-select select').chosen({width: "100%", disable_search_threshold: 10});
	} );

	if (typeof jQuery('#widgets-right .ccbpress-select select').chosen === "function") {
		jQuery('#widgets-right .ccbpress-select select').chosen({width: "100%", disable_search_threshold: 10});
	}

});
