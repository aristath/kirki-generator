<html>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width" />
</head>

<body <?php body_class(); ?>>
	<?php $field_types = kirki_generator_get_all_fields(); ?>
	<?php $modules     = kirki_generator_get_all_modules(); ?>
	<header class="page-header" style="background:url(<?php echo esc_url_raw( get_template_directory_uri() . '/assets/images/markus-spiske-109588-min.jpg' ); ?>)">
		<h1><?php esc_attr_e( 'Create custom builds for the Kirki WordPress plugin.', 'kirki-generator' ); ?></h1>
		<p><?php esc_attr_e( 'Choose the options below to generate a customized Kirki build to embed in your theme.', 'kirki-generator' ); ?></p>
	</header>

	<div class="form-wrapper">
		
		<div class="info">
			<h3><?php esc_attr_e( 'What is this?', 'kirki-generator' ); ?></h3>
			<div class="info-wrapper-1">
				<p><?php esc_attr_e( 'Select the fields and modules you want to include in your project and then click on the button below to generate your custom build.', 'kirki-generator' ); ?></p>
				<p><?php esc_attr_e( 'Initial selections reflect the most used options and are updated automatically.', 'kirki-generator' ); ?></p>
			</div>
			<div class="info-wrapper-2">
				<p><?php esc_attr_e( 'Refresh the page to build another package', 'kirki-generator' ); ?></p>
			</div>
					
			<div class="buttons-wrapper">
				<a href="#" id="submit-me"><?php esc_attr_e( 'Generate Custom Build', 'kirki-generator' ); ?></a>
				<a href="#" id="download-build"><?php esc_attr_e( 'Download Custom Build', 'kirki-generator' ); ?></a>
			</div>
		</div>

		<div class="field-types">
			<h3><?php esc_attr_e( 'Fields', 'kirki-generator' ); ?></h3>
			<?php $top_stats = kirki_generator_get_top_stats( 'fields', KIRKI_GENERATOR_POPULARITY_THRESHOLD ); ?>
			<?php foreach ( $field_types as $field_type => $args ) : ?>
				<?php if ( isset( $args['hidden'] ) && true === $args['hidden'] ) : ?>
					<?php continue; ?>
				<?php endif; ?>
				<label><input type="checkbox" value="<?php echo sanitize_key( $field_type ); ?>"<?php echo ( isset( $top_stats[ $field_type ] ) ) ? ' checked' : ''; ?>/><?php echo $args['label']; ?></label>
			<?php endforeach; ?>
		</div>

		<div class="modules">
			<h3><?php esc_attr_e( 'Modules', 'kirki-generator' ); ?></h3>
			<?php $top_stats = kirki_generator_get_top_stats( 'modules', KIRKI_GENERATOR_POPULARITY_THRESHOLD ); ?>
			<?php foreach ( $modules as $module => $args ) : ?>
				<label><input type="checkbox" value="<?php echo sanitize_key( $module ); ?>"<?php echo ( isset( $top_stats[ $module ] ) ) ? ' checked' : ''; ?>/><?php echo $args['label']; ?></label>
			<?php endforeach; ?>
		</div>

	</div>
	
	<style>
	/*
	Theme Name: Kirki Generator
	*/

	html {
	    font-size: 18px;
	    font-family: sans-serif;
	    -webkit-text-size-adjust: 100%;
	    -ms-text-size-adjust: 100%;
	    box-sizing: border-box;
	}

	body {
	    margin: 0;
	    font-size: 18px;
	    background: #272822;
	    color: #fff;
	    font-family: 'Rokkitt', serif;
	    font-weight: 400;
	    padding-bottom: 3em;
	}

	article, aside, details, figcaption, figure, footer, header, main, menu, nav, section, summary {
	    display: block;
	}

	a {
	    background-color: transparent;
	}

	a:active, a:hover {
	    outline: 0;
	}

	b, strong {
	    font-weight: bold;
	}

	h1 {
	    font-size: 2em;
	    margin: 0.67em 0;
	}

	input {
	    line-height: normal;
	}

	input[type="checkbox"], input[type="radio"] {
	    box-sizing: border-box;
	    padding: 0;
	}

	h1, h2, h3, h4, h5, h6 {
	    clear: both;
	}

	p {
	    margin-bottom: 1.5em;
	}

	*, *:before, *:after {
	    box-sizing: inherit;
	}

	.page-header {
	    padding: 2em 5%;
	    text-align: center;
	    color: #fff;
	    font-size: 1.5em;
	    background-size: cover;
	    min-height: 70vh;
	    font-weight: 700;
	}

	body.generating-kirki-build {
	    opacity: .3;
	}

	.form-wrapper {
	    width: 90%;
	    margin: auto;
	    display: flex;
	    flex-wrap: nowrap;
	    width: 100%;
	}

	@media all and (max-width: 800px) {
	    .form-wrapper {
	        flex-wrap: wrap;
	    }
	}

	.form-wrapper>div {
	    width: 100%;
	    padding: 1em 5%;
	}

	.form-wrapper label {
	    display: block;
	}

	.form-wrapper label input {
	    margin-right: 1em;
	}

	.form-wrapper>.field-types {}

	.form-wrapper>.modules {}

	.buttons-wrapper {
	    margin-top: 2em;
	}

	.buttons-wrapper a {
	    text-decoration: none;
	    color: #272822;
	    background-color: #A6E22E;
	    padding: 1em 2em;
	    border-radius: 3px;
	    display: block;
	    width: 100%;
	    text-align: center;
	    margin-bottom: 1em;
	}

	.buttons-wrapper #download-build {
	    background-color: #F92672;
	    display: none;
	    color: #fff;
	}

	body.kirki-build-complete #submit-me {
	    display: none;
	}

	body.kirki-build-complete #download-build {
	    display: block;
	}

	.info-wrapper-2 {
	    display: none;
	}

	body.kirki-build-complete .info-wrapper-1 {
	    display: none;
	}

	body.kirki-build-complete .info-wrapper-2 {
	    display: block;
	}

	h3 {
	    color: #FD971F
	}
	</style>

	<link href="https://fonts.googleapis.com/css?family=Rokkitt:300,400,700" rel="stylesheet">
	
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.3/underscore-min.js"></script>
	<script>
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
				},
			    ajaxurl = '<?php echo esc_url_raw( admin_url( 'admin-ajax.php' ) ); ?>';

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
	</script>
</body>
