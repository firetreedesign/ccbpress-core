jQuery( document ).ready(function($) {

	/**
	 * CCBPress Import
	 */
	var CCBPress_Import = new function() {

		this.init = function() {
			var self = this;
			jQuery( '#ccbpress-manual-import-button' ).on( 'click', { self: self }, self.startImport );
			if ( 'running' == jQuery( '#ccbpress-manual-import-button' ).attr('data-ccbpress-status') ) {
				self.disableButton();
				self.checkProgress( self );
			}
		};

		this.startImport = function( event ) {
			var self = event.data.self;
			self.disableButton();
			data = {
				action: 'ccbpress_import',
				nonce: ccbpress_vars.nonce
			};
			jQuery.post( ajaxurl, data,  function( response ) {
				if ( 'started' === response ) {
					self.updateProgress( [{'text':'Process is running...'}], 'running');
					self.checkProgress( self );
				} else {
					self.enableButton();
				}
			});
			return false;
		}

		this.updateProgress = function( content, status ) {
			var container	= jQuery('#ccbpress-import-status');
			var notice		= jQuery('<div />');
			var p			= jQuery('<p />');
			var spinner		= jQuery('<span />');
			var check		= jQuery('<span />');

			notice.attr('class', 'notice notice-info');
			spinner.attr('class', 'spinner is-active');
			check.attr('class', 'dashicons dashicons-yes');

			for ( var key in content ) {
				if ( content.hasOwnProperty(key) ) {
					var br = jQuery('<br />');
					if ( content[key].hasOwnProperty('element') ) {
						var element = jQuery('<' + content[key]['element'] + ' />');
						element.text( content[key]['text'] );
						p.append(element);
						p.append(br);
					} else {
						p.text( content[key]['text'] );
						p.append(br);
					}
				}
			}

			switch( status ) {
				case 'running':
					p.prepend(spinner);
					break;
				case 'done':
					p.prepend(check);
					break;
			}
			notice.append(p);
			container.html('');
			container.append(notice);
		}

		this.progress = function( content ) {
			jQuery('#ccbpress-import-status').html( content );
		}

		this.enableButton = function() {
			jQuery( '#ccbpress-manual-import-button' ).attr('disabled', false);
			jQuery( '#ccbpress-manual-import-button' ).attr('disabled', false);
		}

		this.disableButton = function() {
			jQuery( '#ccbpress-manual-import-button' ).attr('disabled', true);
			jQuery( '#ccbpress-manual-import-button' ).attr('disabled', true);
		}

		this.checkProgress = function( self ) {
			var checkProgressHandle = setInterval( function() {
				data = {
					action: 'ccbpress_import_status',
					nonce: ccbpress_vars.nonce
				};

				jQuery.post( ajaxurl, data,  function( response ) {
					if ( 'false' === response ) {
						clearInterval( checkProgressHandle );
						self.getLastImport();
						self.enableButton();
						self.updateProgress( [{'text':' Done'}], 'done');
						setTimeout(function(){
							self.progress('');
						}, 3000);
						return;
					}
					self.updateProgress( response, 'running');
				} );
			}, 3000 );
		}

		this.getLastImport = function() {
			data = {
				action: 'ccbpress_last_import',
				nonce: ccbpress_vars.nonce
			};
			jQuery.post( ajaxurl, data,  function( response ) {
				jQuery('.ccbpress-last-import').text( response ).css( 'font-weight', 'bold' );
				setTimeout(function(){
	                jQuery('.ccbpress-last-import').css( 'font-weight', 'normal' );;
	            }, 3000);
			});
		}

	}
	CCBPress_Import.init();

});
