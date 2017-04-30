jQuery( document ).ready( function() {
	var selectedFields  = {},
		selectedModules = {},
		fieldInputs     = jQuery( '.form-wrapper .field-types' ).find( 'input[type="checkbox"]' );
		moduleInputs    = jQuery( '.form-wrapper .modules' ).find( 'input[type="checkbox"]' );

	// Make sure the body doesn't have the extra class.
	jQuery( 'body' ).removeClass( 'generating-kirki-build' );
	jQuery( '#download-build' ).hide();

	// Populate the selectedFields.
	_.each( fieldInputs, function( input ) {
		var $input    = jQuery( input ),
			fieldType = $input.val(),
			checked   = $input.attr( 'checked' );

		// Get initial value for the checkbox.
		selectedFields[ fieldType ] = false;
		if ( 'undefined' !== typeof checked && checked ) {
			selectedFields[ fieldType ] = true;
		}

		// Add selected fields to the selectedFields var.
		$input.on( 'click', function() {
			var checked = jQuery( this ).attr( 'checked' );

			// Get modified value for the checkbox.
			selectedFields[ fieldType ] = false;
			if ( 'undefined' !== typeof checked && checked ) {
				selectedFields[ fieldType ] = true;
			}
		});
	});

	// Populate the selectedModules.
	_.each( moduleInputs, function( input ) {
		var $input         = jQuery( input ),
			selectedModule = $input.val(),
			checked        = $input.attr( 'checked' );

		// Get initial value for the checkbox.
		selectedModules[ selectedModule ] = false;
		if ( 'undefined' !== typeof checked && checked ) {
			selectedModules[ selectedModule ] = true;
		}

		// Add selected fields to the selectedModules var.
		$input.on( 'click', function() {
			var checked = jQuery( this ).attr( 'checked' );

			// Get modified value for the checkbox.
			selectedModules[ selectedModule ] = false;
			if ( 'undefined' !== typeof checked && checked ) {
				selectedModules[ selectedModule ] = true;
			}
		});
	});

	// Actions to run on submit.
	jQuery( '#submit-me' ).on( 'click', function( event ) {
		var data = {
				'action': 'kirki_generator_submit_form',
				'fields': selectedFields,
				'modules': selectedModules,
				'core': true
			};

		// Prevent the default action when clicking the button (do not refresh).
		event.preventDefault();

		// Add a class to the body.
		// Will be used for styling.
		jQuery( 'body' ).addClass( 'generating-kirki-build' );

		// The ajax request.
		jQuery.post( ajaxurl, data, function( responseJSON ) {
			response = jQuery.parseJSON( responseJSON );
			console.log( response );
			// Remove the class from the body.
			jQuery( 'body' ).removeClass( 'generating-kirki-build' );
			jQuery( 'body' ).addClass( 'kirki-build-complete' );

			// Change the submit link to download link.
			jQuery( '#download-build' ).attr( 'href', response.downloadURL );
			jQuery( '#download-build' ).show();
		});
	});
});
