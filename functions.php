<?php

// Include the main generator class.
include_once 'kirki-generator.php';

add_action( 'wp_enqueue_scripts', function() {
	// wp_enqueue_style( 'kirki-generator', get_stylesheet_uri() );
});

define( 'KIRKI_GENERATOR_POPULARITY_THRESHOLD', 0.8 );
/**
 * Return the top 75% items in popularity.
 *
 * @since 1.0.0
 * @param string $context It can be "modules" or "fields".
 * @return array
 */
function kirki_generator_get_top_stats( $context = 'fields', $threshold = 0.75 ) {
	$stats = get_option( 'kirki_generator_stats', array() );
	
	$items   = $stats['options'][ $context ];
	$ordered = array();
	$max     = 0;
	foreach ( $items as $item ) {
		$top_item = kirki_generator_get_top_key( $items );
		unset( $items[ $top_item[0] ] );
		$ordered[ $top_item[0] ] = $top_item[1];
		if ( $top_item[1] > $max ) {
			$max = $top_item[1];
		}
	}
	if ( 0 < $max ) {
		foreach ( $ordered as $key => $value ) {
			if ( ( $value / $max ) < ( 1 - $threshold ) ) {
				unset( $ordered[ $key ] );
			}
		}
	}
	return $ordered;
}

/**
 * Get top item in array.
 *
 * @since 1.0.0
 * @param array $array The array.
 * @return int|string  The key.
 */
function kirki_generator_get_top_key( $array ) {
	$keys      = array_keys( $array );
	$values    = array_values( $array );
	$top_value = max( $values );
	$top_key   = array_search( $top_value, $values );
	return array(
		$keys[ $top_key ],
		$values[ $top_key ],
	);
}
kirki_generator_get_top_stats();
/**
 * Gets the field types.
 *
 * @since 1.0.0
 * @return array
 */
function kirki_generator_get_all_fields() {
	return array(
		'background'        => array(
			'label'         => esc_attr__( 'Background', 'kirki-generator' ),
		),
		'checkbox'          => array(
			'label'         => esc_attr__( 'Checkbox', 'kirki-generator' ),
			'wp-core'       => true,
		),
		'code'              => array(
			'label'         => esc_attr__( 'Code', 'kirki-generator' ),
			'dependencies'  => array(
				'scripts'   => array( 'codemirror' ),
			)
		),
		'color-palette'     => array(
			'label'         => esc_attr__( 'Color Palette', 'kirki-generator' ),
		),
		'color'             => array(
			'label'         => esc_attr__( 'Color', 'kirki-generator' ),
			'dependencies'  => array(
				'scripts'   => array( 'wp-color-picker-alpha' ),
			),
			'aliases'       => array( 'color-alpha' ),
		),
		'custom'            => array(
			'label'         => esc_attr__( 'Custom', 'kirki-generator' ),
		),
		'dashicons'         => array(
			'label'         => esc_attr__( 'Dashicons', 'kirki-generator' ),
		),
		'dimension'         => array(
			'label'         => esc_attr__( 'Dimension', 'kirki-generator' ),
		),
		'dimensions'        => array(
			'label'         => esc_attr__( 'Dimensions', 'kirki-generator' ),
		),
		'dropdown-pages'    => array(
			'label'         => esc_attr__( 'Dropdown Pages', 'kirki-generator' ),
			'wp-core'       => true,
		),
		'editor'            => array(
			'label'         => esc_attr__( 'Editor', 'kirki-generator' ),
		),
		'generic'           => array(
			'label'         => esc_attr__( 'Generic', 'kirki-generator' ),
			'aliases'       => array( 'kirki-generic' ),
			'hidden'        => true,
		),
		'group_title'       => array(
			'label'         => esc_attr__( 'Group Title', 'kirki-generator' ),
			'dependencies'  => array(
				'fields'    => array( 'custom' ),
			),
			'hidden'        => true,
		),
		'image'             => array(
			'label'         => esc_attr__( 'Image', 'kirki-generator' ),
			'wp-core'       => true,
		),
		'link'              => array(
			'label'         => esc_attr__( 'Link (URL)', 'kirki-generator' ),
			'dependencies'  => array(
				'fields'    => array( 'url', 'generic' ),
			),
			'hidden'        => true,
		),
		'multicheck'        => array(
			'label'         => esc_attr__( 'Multicheck', 'kirki-generator' ),
		),
		'multicolor'        => array(
			'label'         => esc_attr__( 'Multi-Color', 'kirki-generator' ),
			'dependencies'  => array(
				'scripts'   => array( 'wp-color-picker-alpha' ),
			),
		),
		'number'            => array(
			'label'         => esc_attr__( 'Number', 'kirki-generator' ),
		),
		'palette'           => array(
			'label'         => esc_attr__( 'Palette', 'kirki-generator' ),
			'dependencies'  => array(
				'fields'    => array( 'radio' ),
			),
		),
		'preset'            => array(
			'label'         => esc_attr__( 'Preset', 'kirki-generator' ),
			'dependencies'  => array(
				'scripts'   => array( 'select2' ),
				'fields'    => array( 'select' ),
			)
		),
		'radio-buttonset'   => array(
			'label'         => esc_attr__( 'Radio-Buttonset', 'kirki-generator' ),
			'dependencies'  => array(
				'fields'    => array( 'radio' ),
			),
		),
		'radio-image'       => array(
			'label'         => esc_attr__( 'Radio-Image', 'kirki-generator' ),
			'dependencies'  => array(
				'fields'    => array( 'radio' ),
			),
		),
		'radio'             => array(
			'label'         => esc_attr__( 'Radio', 'kirki-generator' ),
			'wp-core'       => true,
		),
		'repeater'          => array(
			'label'         => esc_attr__( 'Repeater', 'kirki-generator' ),
			'dependencies'  => array(
				'scripts'   => array( 'wp-color-picker-alpha', 'select2' ),
			),
		),
		'select'            => array(
			'label'         => esc_attr__( 'Select', 'kirki-generator' ),
			'dependencies'  => array(
				'scripts'   => array( 'select2' ),
			),
		),
		'slider'            => array(
			'label'         => esc_attr__( 'Slider', 'kirki-generator' ),
			'dependencies'  => array(
				'fields'    => array( 'number' ),
			),
		),
		'sortable'          => array(
			'label'         => esc_attr__( 'Sortable', 'kirki-generator' ),
		),
		'spacing'           => array(
			'label'         => esc_attr__( 'Spacing', 'kirki-generator' ),
			'dependencies'  => array(
				'fields'    => array( 'number', 'dimensions' ),
			),
		),
		'switch'            => array(
			'label'         => esc_attr__( 'Switch', 'kirki-generator' ),
			'dependencies'  => array(
				'fields'    => array( 'checkbox' ),
			),
		),
		'text'              => array(
			'label'         => esc_attr__( 'Text', 'kirki-generator' ),
			'dependencies'  => array(
				'fields'    => array( 'generic' ),
			),
			'wp-core'          => true,
		),
		'textarea'          => array(
			'label'         => esc_attr__( 'Textarea', 'kirki-generator' ),
			'dependencies'  => array(
				'fields'    => array( 'generic' ),
			),
			'wp-core'          => true,
		),
		'toggle'            => array(
			'label'         => esc_attr__( 'Toggle', 'kirki-generator' ),
			'dependencies'  => array(
				'fields'    => array( 'checkbox' ),
			),
		),
		'typography'        => array(
			'label'         => esc_attr__( 'Typography', 'kirki-generator' ),
			'dependencies'  => array(
				'scripts'   => array( 'select2' ),
			),
		),
		'upload'            => array(
			'label'         => esc_attr__( 'Upload', 'kirki-generator' ),
			'wp-core'       => true,
		),
		'url'               => array(
			'label'         => esc_attr__( 'URL', 'kirki-generator' ),
			'dependencies'  => array(
				'fields'    => array( 'generic' ),
			),
			'aliases'       => array( 'link' ),
		),
	);
}

/**
 * Gets all fields.
 *
 * @since 1.0.0
 * @return array
 */
function kirki_generator_get_all_modules() {
	return array(
		'collapsible'         => array(
			'label'           => esc_attr__( 'Collapsible Fields', 'kirki-generator' ),
		),
		'css'                 => array(
			'label'           => esc_attr__( 'CSS Output & Google Fonts', 'kirki-generator' ),
		),
		'custom-sections'     => array(
			'label'           => esc_attr__( 'Custom Sections', 'kirki-generator' ),
		),
		'customizer-branding' => array(
			'label'           => esc_attr__( 'Customizer Branding', 'kirki-generator' ),
		),
		'customizer-styling'  => array(
			'label'           => esc_attr__( 'Customizer Styling', 'kirki-generator' ),
		),
		'field-dependencies'  => array(
			'label'           => esc_attr__( 'Field Dependencies', 'kirki-generator' ),
		),
		'icons'               => array(
			'label'           => esc_attr__( 'Section & Panel Icons', 'kirki-generator' ),
		),
		'loading'             => array(
			'label'           => esc_attr__( 'Loading', 'kirki-generator' ),
		),
		'postmessage'         => array(
			'label'           => esc_attr__( 'postMessage', 'kirki-generator' ),
		),
		'reset'               => array(
			'label'           => esc_attr__( 'Reset', 'kirki-generator' ),
		),
		'selective-refresh'   => array(
			'label'           => esc_attr__( 'Selective Refresh', 'kirki-generator' ),
		),
		'tooltips'            => array(
			'label'           => esc_attr__( 'Tooltips', 'kirki-generator' ),
		),
	);
}
