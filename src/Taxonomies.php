<?php
/**
 * Taxonomies
 *
 * @link        http://matadorjobs.com/
 * @since       2.0.0
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

use \stdClass;
use matador\Bullhorn_Import;
use matador\Matador;

class Taxonomies {

	/**
	 * Variable 'Group' Field Name
	 *
	 * The name of the Bullhorn customText field for Group
	 *
	 * @access private
	 * @static
	 * @since 1.0.0, 2.0.0 from class Extension to class Taxonomies
	 *
	 * @var string $instance
	 */
	private static $bullhorn_group_field = 'customText4';

	/**
	 * Variable 'Region' Field Name
	 *
	 * The name of the Bullhorn customText field for Region
	 *
	 * @access private
	 * @static
	 * @since 1.0.0, 2.0.0 from class Extension to class Taxonomies
	 *
	 * @var string $instance
	 */
	private static $bullhorn_region_field = 'customText2';

	/**
	 * Constructor
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	public function __construct() {
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
	 * @since 1.0.0, 2.0.0 from class Extension to class Taxonomies
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
	 * @since 1.0.0, 2.0.0 from class Extension to class Taxonomies
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
	 * @since 1.0.0, 2.0.0 from class Extension to class Taxonomies
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

		if ( ! empty( $job->{ self::$bullhorn_group_field } ) ) {
			$taxonomy = Matador::variable( 'group', 'job_taxonomies' );
			wp_set_object_terms( $wpid, $job->{ self::$bullhorn_group_field }, $taxonomy['key'] );
		}

		if ( ! empty ( $job->{ self::$bullhorn_region_field } ) ) {
			$taxonomy = Matador::variable( 'region', 'job_taxonomies' );
			wp_set_object_terms( $wpid, $job->{ self::$bullhorn_region_field }, $taxonomy['key'] );
		}

		return;
	}
}
