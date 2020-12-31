<?php
/**
 * Matador Jobs Custom Extension for Vincent Benjamin Group
 *
 * The one class that powers the plugin and makes it extendable.
 *
 * @link        http://matadorjobs.com/
 * @since       1.0.0
 *
 * @package     Matador Jobs Vincent Benjamin
 * @subpackage  Core
 * @author      Jeremy Scott
 * @copyright   (c) 2020 Vincent Benjamin Group
 *
 * @license     https://opensource.org/licenses/GPL-3.0 GNU General Public License version 3
 */

namespace matador\MatadorJobsVincentBenjamin;

if ( ! defined( 'WPINC' ) ) {
	die;
}

use stdClass;
use matador\Matador;
use matador\Bullhorn_Import;

/**
 * Class Extension
 *
 * @final
 * @since 1.0.0
 */
final class Extension {

	/**
	 * Constant Version
	 *
	 * @since 1.0.0
	 *
	 * @var string VERSION
	 */
	const VERSION = '1.0.0';

	/**
	 * Variable Path
	 *
	 * @access public
	 * @static
	 * @since 1.0.0
	 *
	 * @var string $path
	 */
	public static $path;

	/**
	 * Variable Directory
	 *
	 * @access public
	 * @static
	 * @since 1.0.0
	 *
	 * @var string $directory
	 */
	public static $directory;

	/**
	 * Variable Plugin File
	 *
	 * @access public
	 * @static
	 * @since 1.0.0
	 *
	 * @var string $file
	 */
	public static $file;

	/**
	 * Variable Instance
	 *
	 * @access private
	 * @static
	 * @since 1.0.0
	 *
	 * @var Extension $instance
	 */
	private static $instance;

	/**
	 * Variable 'Group' Field Name
	 *
	 * The name of the Bullhorn customText field for Group
	 *
	 * @access private
	 * @static
	 * @since 1.0.0
	 *
	 * @var Extension $instance
	 */
	private static $bullhorn_group_field = 'customText4';

	/**
	 * Variable 'Region' Field Name
	 *
	 * The name of the Bullhorn customText field for Region
	 *
	 * @access private
	 * @static
	 * @since 1.0.0
	 *
	 * @var Extension $instance
	 */
	private static $bullhorn_region_field = 'customText2';

	/**
	 * Instance Builder
	 *
	 * Singleton pattern means we create only one instance of the class.
	 *
	 * @access public
	 * @static
	 * @since 1.0.0
	 *
	 * @return Extension
	 */
	public static function instance() {

		if ( ! ( isset( self::$instance ) ) && ! ( self::$instance instanceof Extension ) ) {

			self::$instance = new Extension();

			self::$instance->setup_properties();

			self::$instance->load();

		}
		return self::$instance;
	}

	/**
	 * Class Constructor
	 *
	 * @access public
	 * @static
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		// Silence is Golden
	}

	/**
	 * Throw error on object clone.
	 *
	 * Singleton design pattern means is that there is a single object,
	 * and therefore, we don't want or allow the object to be cloned.
	 *
	 * @access public
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, esc_html__( 'No can do! You may not clone an instance of the plugin.', 'matador-custom-vincent-benjamin' ), esc_attr( self::VERSION ) );
	}

	/**
	 * Disable unserializing of the class.
	 *
	 * Unserializing of the class is also forbidden in the singleton pattern.
	 *
	 * @access public
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, esc_html__( 'No can do! You may not unserialize an instance of the plugin.', 'matador-custom-vincent-benjamin' ), esc_attr( self::VERSION ) );
	}

	/**
	 * Setup Properties
	 *
	 * @access private
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function setup_properties() {
		self::$directory = plugin_dir_path( __FILE__ );
		self::$file      = self::$directory . 'matador-jobs-custom-vincent-benjamin.php';
		self::$path      = plugin_dir_url( self::$file );
	}

	/**
	 * Load Plugin
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 *
	 * @return void
	 */
	private function load() {
		add_filter( 'matador_bullhorn_import_fields', [ __CLASS__, 'add_import_fields' ] );
		add_filter( 'matador_variable_job_taxonomies', [ __CLASS__, 'add_taxonomy' ] );
		add_action( 'matador_bullhorn_import_save_job', [ __CLASS__, 'save_job_terms' ], 10, 3 );
		add_action( 'matador_bullhorn_after_import', [ __CLASS__, 'update_term_counts' ] );
	}

	/**
	 * Add Import Fields
	 *
	 * This is called by the 'matador_import_fields' to add fields to the job import
	 * function of the Bullhorn_Import::get_jobs() behavior so we can use the data
	 * later.
	 *
	 * @access public
	 * @static
	 * @since 1.0.0
	 *
	 * @param array $fields
	 *
	 * @return array
	 */
	public static function add_import_fields( $fields ) {
		$fields[ self::$bullhorn_group_field ]    = array(
			'type'   => 'text',
			'saveas' => 'core',
		);
		$fields[ self::$bullhorn_region_field ] = array(
			'type'   => 'text',
			'saveas' => 'core',
		);
		return $fields;
	}

	/**
	 * Add Taxonomy
	 *
	 * Uses the dynamic 'matador_variable_*' filter to define additional taxonomies for the
	 * taxonomy factory to register for us.
	 *
	 * @access public
	 * @static
	 * @since 1.0.0
	 *
	 * @param array $taxonomies
	 *
	 * @return array
	 */
	public static function add_taxonomy( $taxonomies ) {
		$taxonomies['group'] = array(
			'key'    => 'matador-groups',
			'single' => _x( 'group', 'Job Group Singular Name.', 'matador-custom-vincent-benjamin' ),
			'plural' => _x( 'groups', 'Job Groups Plural Name.', 'matador-custom-vincent-benjamin' ),
			'slug'   => Matador::setting( 'taxonomy_slug_group' ) ?: 'matador-groups',
			'args'   => array(
				'public'             => true,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'show_in_nav_menus'  => true,
				'show_tagcloud'      => true,
				'show_in_quick_edit' => true,
				'show_admin_column'  => true,
				'hierarchical'       => false,
			),
		);
		$taxonomies['region'] = array(
			'key'    => 'matador-regions',
			'single' => _x( 'region', 'Job Region Singular Name.', 'matador-custom-vincent-benjamin' ),
			'plural' => _x( 'regions', 'Job Regions Plural Name.', 'matador-custom-vincent-benjamin' ),
			'slug'   => Matador::setting( 'taxonomy_slug_region' ) ?: 'matador-regions',
			'args'   => array(
				'public'             => true,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'show_in_nav_menus'  => true,
				'show_tagcloud'      => true,
				'show_in_quick_edit' => true,
				'show_admin_column'  => true,
				'hierarchical'       => false,
			),
		);
		return $taxonomies;
	}

	/**
	 * Save Job Terms
	 *
	 * Uses collected data from a Bullhorn_Import::get_jobs call to add
	 * terms to the taxonomy.
	 *
	 * @access public
	 * @static
	 * @since 1.0.0
	 *
	 * @param stdClass $job
	 * @param integer $wpid
	 * @param Bullhorn_Import $bullhorn
	 *
	 * @return void
	 */
	public static function save_job_terms( $job, $wpid, $bullhorn ) {

		if ( ! is_object( $job ) || ! is_int( $wpid ) || ! is_object( $bullhorn ) ) {
			return;
		}

		if ( ! empty( $job->{ Extension::$bullhorn_group_field } ) ) {
			$taxonomy = Matador::variable( 'group', 'job_taxonomies' );
			wp_set_object_terms( $wpid, $job->{ Extension::$bullhorn_group_field }, $taxonomy['key'] );
		}

		if ( ! empty ( $job->{ Extension::$bullhorn_region_field } ) ) {
			$taxonomy = Matador::variable( 'region', 'job_taxonomies' );
			wp_set_object_terms( $wpid, $job->{ Extension::$bullhorn_region_field }, $taxonomy['key'] );
		}

		return;
	}
}
