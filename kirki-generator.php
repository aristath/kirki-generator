<?php
/**
 * Plugin Name:   Kirki Generator
 * Plugin URI:    http://kirki.org
 * Description:   Generates customized Kirki packages.
 * Author:        Aristeides Stathopoulos
 * Author URI:    http://aristeides.com
 * Version:       1.0.0
 * Text Domain:   kirki-generator
 *
 * GitHub Plugin URI: aristath/kirki-generator
 * GitHub Plugin URI: https://github.com/aristath/kirki-generator
 *
 * @package     Kirki Generator
 * @category    Core
 * @author      Aristeides Stathopoulos
 * @copyright   Copyright (c) 2016, Aristeides Stathopoulos
 * @license     http://opensource.org/licenses/https://opensource.org/licenses/MIT
 * @since       1.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The main Generator class.
 *
 * @since 1.0.0
 */
class Kirki_Generator {

	/**
	 * The path to the Kirki plugin.
	 *
	 * @access protected
	 * @since 1.0.0
	 * @var string
	 */
	protected $kirki_path;

	/**
	 * Constructor.
	 * Adds all hooks.
	 *
	 * @access public
	 * @since 1.0.0
	 */
	public function __construct() {

		include_once 'functions.php';

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		add_action( 'wp_ajax_kirki_generator_submit_form', array( $this, 'ajax_form_submission' ) );
		add_action( 'wp_ajax_nopriv_kirki_generator_submit_form', array( $this, 'ajax_form_submission' ) );

		$this->maintenance();
	}

	/**
	 * Enqueue all scripts & styles.
	 *
	 * @access public
	 * @since 1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( 'kirki-generator', get_template_directory_uri() . '/script.js', array( 'wp-util', 'underscore', 'backbone' ) );
		wp_localize_script( 'kirki-generator', 'ajaxurl', admin_url( 'admin-ajax.php' ) );

	}

	/**
	 * The ajax form handler.
	 *
	 * @access public
	 * @since 1.0.0
	 */
	function ajax_form_submission() {
		$groups = array(
			'fields'  => array(),
			'modules' => array(),
			'core'    => true,
		);

		foreach ( $groups as $group => $selected ) {
			if ( is_array( $selected ) ) {
				if ( isset( $_POST[ $group ] ) ) {
					$selections = (array) $_POST[ $group ];
					foreach ( $selections as $selection => $status ) {
						$status = (bool) ( true === $status || 'true' === $status || 1 === $status || '1' === $status );
						if ( true === $status && ! in_array( $selection, $groups[ $group ] ) ) {
							$groups[ $group ][] = $selection;
						}
					}
				}
			}
		}
		// Groups may have dependencies, so make sure they are all included.
		$groups['fields'] = $this->get_fields( $groups['fields'] );

		echo json_encode( array(
			'statistics'  => $this->update_statistics( $groups ),
			'downloadURL' => $this->build_zip( $this->build_paths_array( $groups ) ),
		) );
		wp_die();
	}

	/**
	 * Get the fields that we have to include.
	 *
	 * @access protected
	 * @since 1.0.0
	 * @param array $fields The array of fields we want to include.
	 * @return array        Array of fields - including dependencies & aliases.
	 */
	protected function get_fields( $fields ) {

		$all_fields = kirki_generator_get_all_fields();

		foreach ( $fields as $field ) {
			if ( isset( $all_fields[ $field ] ) && isset( $all_fields[ $field ]['dependencies'] ) && isset( $all_fields[ $field ]['dependencies']['fields'] ) ) {
				foreach ( $all_fields[ $field ]['dependencies']['fields'] as $dependency ) {
					$fields[] = $dependency;
				}
			}
			if ( isset( $all_fields[ $field ] ) && isset( $all_fields[ $field ]['aliases'] ) ) {
				foreach ( $all_fields[ $field ]['aliases'] as $alias ) {
					$fields[] = $alias;
				}
			}
		}

		return array_unique( $fields );
	}

	protected function build_paths_array( $groups ) {

		$folders = array(
			'core',
			'lib',
		);
		$files = array(
			'autoloader.php',
			'kirki.php',
			'LICENCE',
			'readme.txt',
			'settings/class-kirki-settings-default-setting.php',
		);

		$all_fields = kirki_generator_get_all_fields();

		foreach ( $groups as $group => $args ) {

			// Add fields.
			if ( 'fields' === $group ) {
				foreach ( $args as $field ) {

					// Add the control.
					$folders[] = "controls/{$field}";

					// Add the fields.
					$files[] = "field/class-kirki-field-{$field}.php";

					// Add settings classes
					if ( 'repeater' === $field ) {
						$files[] = "settings/class-kirki-settings-{$field}-setting.php";
					}

					// Add script dependencies.
					if ( isset( $all_fields[ $field ] ) && isset( $all_fields[ $field ]['dependencies'] ) && isset( $all_fields[ $field ]['dependencies']['scripts'] ) ) {
						foreach ( $all_fields[ $field ]['dependencies']['scripts'] as $script ) {
							$folders[] = "assets/vendor/{$script}";
						}
					}
				}
			}

			// Add modules.
			if ( 'modules' === $group ) {
				foreach ( $args as $module ) {
					$folders[] = "modules/{$module}";
				}
			}
		}

		return array(
			'folders' => $folders,
			'files'   => $files,
		);
	}

	protected function build_zip( $include_paths ) {

		$kirki_path = Kirki::$path;

		$id  = md5( json_encode( $include_paths ) );
		$zip = new ZipArchive();

		$upload_dir = wp_upload_dir();
		$dir_path   = "{$upload_dir['basedir']}/kirki-custom-builds/";
		$zip_path   = "{$upload_dir['basedir']}/kirki-custom-builds/{$id}.zip";
		$zip_url    = trailingslashit( $upload_dir['baseurl'] ) . "kirki-custom-builds/{$id}.zip";

		if ( file_exists( $zip_path ) ) {
			return $zip_url;
		}

		if ( ! file_exists( $dir_path ) ) {
			wp_mkdir_p( $dir_path );
		}

		if ( true !== $zip->open( $zip_path, ZipArchive::CREATE ) ) {
			return false;
		}

		foreach ( $include_paths as $context => $paths ) {
			if ( 'folders' === $context ) {
				foreach ( $paths as $folder_path ) {
					$all_paths = $this->get_dir_contents( wp_normalize_path( Kirki::$path . '/' . $folder_path ) );
					if ( is_array( $all_paths ) ) {
						foreach ( $all_paths as $system_path ) {
							if ( file_exists( $system_path ) && ! is_dir( $system_path ) ) {
								$found_file_path = 'kirki/' . str_replace( Kirki::$path . '/', '', $system_path );
								$zip->addFile( $system_path, $found_file_path );
							}
						}
					}
				}
			}
			if ( 'files' === $context ) {
				foreach ( $paths as $file_path ) {
					$system_path = wp_normalize_path( Kirki::$path . '/' . $file_path );
					if ( file_exists( $system_path ) ) {
						$zip->addFile( $system_path, 'kirki/' . $file_path );
					}
				}
			}
		}

		$zip->close();

		if ( file_exists( $zip_path ) ) {
			return $zip_url;
		}
		return false;

	}

	/**
	 * Recursively scan a directory and retrieve all files as an array.
	 *
	 * @access protected
	 * @since 1.0.0
	 * @return array
	 */
	protected function get_dir_contents( $dir, &$results = array() ) {
		if ( ! file_exists( $dir ) || ! is_dir( $dir ) ) {
			return;
		}
		$files = scandir( $dir );

		foreach ( $files as $key => $value ) {
			$path = realpath( "{$dir}/{$value}" );
			if ( ! is_dir( $path ) ) {
				$results[] = $path;
			} elseif( '.' !== $value && '..' !== $value ) {
				$this->get_dir_contents( $path, $results );
				$results[] = $path;
			}
		}
		return $results;
	}

	/**
	 * Send a JSON response back to an Ajax request.
	 *
	 * @since 1.0.0
	 * @param mixed $response    Variable (usually an array or object) to encode as JSON,
	 * @param int   $status_code The HTTP status code to output.
	 */
	protected function send_json( $response, $status_code = null ) {
		@header( 'Content-Type: application/json; charset=' . get_option( 'blog_charset' ) );
		if ( null !== $status_code ) {
			status_header( $status_code );
		}
		echo wp_json_encode( $response );
	}

	/**
	 * Maintenance task: Delete all files 24 hours after they have been created.
	 *
	 * @access protected
	 * @since 1.0.0
	 */
	protected function maintenance() {

		if ( false === get_transient( 'kirki_generator_maintenance' ) ) {
			global $wp_filesystem;
			if ( empty( $wp_filesystem ) ) {
				require_once( ABSPATH . '/wp-admin/includes/file.php' );
				WP_Filesystem();
			}
			$upload_dir = wp_upload_dir();
			$wp_filesystem->delete( "{$upload_dir['basedir']}/kirki-custom-builds/", true, 'd' );

			set_transient( 'kirki_generator_maintenance', true, DAY_IN_SECONDS );
		}
	}

	/**
	 * Update settings with counts for every used feature.
	 *
	 * @access protected
	 * @since 1.0.0
	 * @param array $groups The groups we're using.
	 */
	protected function update_statistics( $groups ) {
		
		$stats = get_option( 'kirki_generator_stats', array() );
		if ( ! isset( $stats['builds'] ) ) {
			$stats['builds'] = 0;
		}

		// Increase the number of packages built by 1.
		$stats['builds'] += 1;

		if ( isset( $groups['fields'] ) ) {
			if ( ! isset( $stats['options']['fields'] ) ) {
				$stats['options']['fields'] = array();
			}
			foreach ( $groups['fields'] as $field ) {
				if ( ! isset( $stats['options']['fields'][ $field ] ) ) {
					$stats['options']['fields'][ $field ] = 0;
				}
				$stats['options']['fields'][ $field ] += 1;
			}
		}

		if ( isset( $groups['modules'] ) ) {
			if ( ! isset( $stats['options']['modules'] ) ) {
				$stats['options']['modules'] = array();
			}
			foreach ( $groups['modules'] as $module ) {
				if ( ! isset( $stats['options']['modules'][ $module ] ) ) {
					$stats['options']['modules'][ $module ] = 0;
				}
				$stats['options']['modules'][ $module ] += 1;
			}
		}
		
		update_option( 'kirki_generator_stats', $stats );

		return $stats;
	}
}

$kirki_generator = new Kirki_Generator();