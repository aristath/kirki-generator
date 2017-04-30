<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width" />
	<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

	<?php $field_types = kirki_generator_get_all_fields(); ?>
	<?php $modules     = kirki_generator_get_all_modules(); ?>

	<header>
		Create custom builds for the Kirki WordPress plugin.
	</header>

	<div class="form-wrapper">
		
		<div class="info">
			<?php esc_attr_e( 'Select the fields and modules you want to include in your project. Initial selections reflect the most used options and are updated automatically.', 'kirki-generator' ); ?>
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
	
	<?php wp_footer(); ?>
</body>
