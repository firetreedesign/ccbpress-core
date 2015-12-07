jQuery( document ).ready(function($) {
	jQuery( '.ccbpress_multi_select_add' ).click( function() {
		// Get the All select box
		var selectBoxAll = jQuery(this);
		selectBoxAll = '#' + jQuery( selectBoxAll ).attr( 'id' ).replace( '_add', '_all' );

		// Get the Selected select box
		var selectBoxSelected = jQuery( this );
		selectBoxSelected = '#' + jQuery( selectBoxSelected ).attr( 'id' ).replace( '_add', '_selected' );
		jQuery( selectBoxAll + ' option:selected' ).remove().appendTo( selectBoxSelected );

		// Get the Values input box
		var selectBoxValues = jQuery( this );
		selectBoxValues = '#' + jQuery( selectBoxValues ).attr( 'id' ).replace( '_add', '_values' );
		var newSelectBoxValues = new Array();
		jQuery( selectBoxSelected + ' > option' ).each(function() {
			newSelectBoxValues.push( this.value );
		});
		jQuery( selectBoxValues ).val( newSelectBoxValues.join( ',' ) );
		return false;
	});

	jQuery( '.ccbpress_multi_select_remove' ).click( function() {
		// Get the button that was clicked
		var selectBoxAll = jQuery( this );
		selectBoxAll = '#' + jQuery( selectBoxAll ).attr( 'id' ).replace( '_remove', '_all' );

		// Get the img element where the preview image is displayed
		var selectBoxSelected = jQuery( this );
		selectBoxSelected = '#' + jQuery( selectBoxSelected ).attr( 'id' ).replace( '_remove', '_selected' );
		jQuery( selectBoxSelected + ' option:selected' ).remove().appendTo( selectBoxAll );

		// Get the Values input box
		var selectBoxValues = jQuery( this );
		selectBoxValues = '#' + jQuery( selectBoxValues ).attr( 'id' ).replace( '_remove', '_values' );
		var newSelectBoxValues = new Array();
		jQuery( selectBoxSelected + ' option' ).each( function() {
			newSelectBoxValues.push( this.value );
		});
		jQuery( selectBoxValues ).val( newSelectBoxValues.join( ',' ) );
		return false;
	});

	jQuery( '.ccbpress_list_select_add' ).click( function() {
		// Get the input field
		var inputText = jQuery( this );
		inputText = '#' + jQuery( inputText ).attr( 'id' ).replace( '_add', '_input' );

		// Get the All select box
		var selectBoxAll = jQuery( this );
		selectBoxAll = '#' + jQuery( selectBoxAll ).attr( 'id' ).replace( '_add', '_select' );

		// Get the Selected select box
		var selectBoxSelected = jQuery( this );
		selectBoxSelected = '#' + jQuery( selectBoxSelected ).attr( 'id' ).replace( '_add', '_selected' );

		if ( jQuery( selectBoxAll ).val() == 'none' ) {
			return false;
		}

		jQuery( selectBoxSelected ).append( new Option( jQuery( inputText ).val(), jQuery( selectBoxAll ).val(), false, false ) );

		// Get the Values input box
		var selectBoxValues = jQuery( this );
		selectBoxValues = '#' + jQuery( selectBoxValues ).attr( 'id' ).replace( '_add', '_values' );

		var newSelectBoxValues = new Array();
		jQuery( selectBoxSelected + ' > option' ).each( function() {
			var optionText = this.text;
			var optionValue = this.value;
			newSelectBoxValues.push( optionText + '==' + optionValue );
		});
		jQuery( selectBoxValues ).val( newSelectBoxValues.join( '&&' ) );

		jQuery( inputText ).val( '' );
		jQuery( inputText ).focus();
		jQuery( selectBoxAll ).prop( 'selectedIndex', 0 );
		return false;
	});

	jQuery( '.ccbpress_list_select_edit' ).click( function() {
		// Get the input field
		var inputText = jQuery( this );
		inputText = '#' + jQuery( inputText ).attr( 'id' ).replace( '_edit', '_input' );

		// Get the All select box
		var selectBoxAll = jQuery( this );
		selectBoxAll = '#' + jQuery( selectBoxAll ).attr( 'id' ).replace( '_edit', '_select' );

		// Get the Selected select box
		var selectBoxSelected = jQuery( this );
		selectBoxSelected = '#' + jQuery( selectBoxSelected ).attr( 'id' ).replace( '_edit', '_selected' );

		jQuery( inputText ).val( jQuery( selectBoxSelected + ' :selected' ).text() );
		jQuery( selectBoxAll ).val( jQuery( selectBoxSelected ).val() );
		jQuery( selectBoxSelected + ' :selected' ).remove();
		jQuery( inputText ).focus().select();

		// Get the Values input box
		var selectBoxValues = jQuery( this );
		selectBoxValues = '#' + jQuery( selectBoxValues ).attr( 'id' ).replace( '_remove', '_values' );

		var newSelectBoxValues = new Array();
		jQuery( selectBoxSelected + ' > option' ).each( function() {
			var optionText = this.text;
			var optionValue = this.value;
			newSelectBoxValues.push( optionText + '==' + optionValue );
		});
		jQuery( selectBoxValues ).val( newSelectBoxValues.join( '&&' ) );

		return false;
	} );

	jQuery('.ccbpress_list_select_remove').click( function() {
		// Get the button that was clicked
		var selectBoxAll = jQuery( this );
		selectBoxAll = '#' + jQuery( selectBoxAll ).attr( 'id' ).replace( '_remove', '_all' );

		// Get the img element where the preview image is displayed
		var selectBoxSelected = jQuery( this );
		selectBoxSelected = '#' + jQuery( selectBoxSelected ).attr( 'id' ).replace( '_remove', '_selected' );

		jQuery( selectBoxSelected + ' option:selected' ).remove();

		// Get the Values input box
		var selectBoxValues = jQuery( this );
		selectBoxValues = '#' + jQuery( selectBoxValues ).attr( 'id' ).replace( '_remove', '_values' );

		var newSelectBoxValues = new Array();
		jQuery( selectBoxSelected + ' > option' ).each( function() {
			var optionText = this.text;
			var optionValue = this.value;
			newSelectBoxValues.push( optionText + '==' + optionValue );
		});
		jQuery( selectBoxValues ).val( newSelectBoxValues.join( '&&' ) );
		return false;
	});

});
