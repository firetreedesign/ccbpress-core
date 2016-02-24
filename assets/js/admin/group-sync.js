jQuery( document ).ready(function($) {

	/**
	 * Group Profile Sync
	 */
	var CCBPress_Group_Profile_Sync = new function() {

		this.init = function() {
			var self = this;
			jQuery( '#ccbpress-manual-group-sync-button' ).on( 'click', { self: self }, self.startSync );
			if ( 'running' == jQuery( '#ccbpress-manual-group-sync-button' ).attr('data-ccbpress-status') ) {
				self.disableButton();
				self.checkProgress( self );
			}
		};

		this.startSync = function( event ) {
			var self = event.data.self;
			self.disableButton();
			data = {
				action: 'ccbpress_sync_groups',
				ccbpress_nonce: ccbpress_vars.ccbpress_nonce
			};
			jQuery.post( ajaxurl, data,  function( response ) {
				if ( 'started' === response ) {
					self.progress('<div class="notice notice-info"><p><span class="spinner is-active"></span>Process is running...</p></div>');
					self.checkProgress( self );
				} else {
					self.enableButton();
				}
			});
			return false;
		}

		this.progress = function( content ) {
			jQuery('#ccbpress-group-sync-status').html( content );
		}

		this.enableButton = function() {
			jQuery( '#ccbpress-manual-group-sync-button' ).attr('disabled', false);
		}

		this.disableButton = function() {
			jQuery( '#ccbpress-manual-group-sync-button' ).attr('disabled', true);
		}

		this.checkProgress = function( self ) {
			var checkProgressHandle = setInterval( function() {
				data = {
					action: 'ccbpress_sync_groups_status',
					ccbpress_nonce: ccbpress_vars.ccbpress_nonce
				};

				jQuery.post( ajaxurl, data,  function( response ) {
					if ( 'false' === response ) {
						clearInterval( checkProgressHandle );
						self.getLastSync();
						self.enableButton();
						self.progress('<div class="notice notice-info"><p><span class="dashicons dashicons-yes"></span> Done</p></div>');
						setTimeout(function(){
							self.progress('');
						}, 3000);
						return;
					}
					self.progress('<div class="notice notice-info"><p><span class="spinner is-active"></span>' + response + '</p></div>');
				} );
			}, 3000 );
		}

		this.getLastSync = function() {
			data = {
				action: 'ccbpress_last_group_sync',
				ccbpress_nonce: ccbpress_vars.ccbpress_nonce
			};
			jQuery.post( ajaxurl, data,  function( response ) {
				if ( 'Never' != response ) {
					jQuery('.notice.ccbpress-group-sync-never').slideUp();
				}
				jQuery('.ccbpress-last-group-sync').text( response ).css( 'font-weight', 'bold' );
				setTimeout(function(){
	                jQuery('.ccbpress-last-group-sync').css( 'font-weight', 'normal' );;
	            }, 3000);
			});
		}

	}
	CCBPress_Group_Profile_Sync.init();

});
