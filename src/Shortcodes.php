<?php
/**
 * Shortcodes
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

use matador\Helper;
use matador\Job_Taxonomies;
use matador\Template_Support;

if ( ! defined( 'WPINC' ) ) {
	die;
}

class Shortcodes {

	/**
	 * Constructor
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		add_filter( 'matador_locate_template_replace_default', [ __CLASS__, 'replace_templates' ], 10, 3 );
		add_shortcode( 'matador_custom_search', [ __CLASS__, 'matador_custom_search_shortcode' ] );
		add_filter( 'matador_search_form_args_after', [ __CLASS__, 'search_form_args' ] );
	}

	/**
	 * Search Form Shortcode (Custom)
	 *
	 * Retrieves a search form, fields and formatting based on arguments.
	 *
	 * @access public
	 *
	 * @since  2.0.0
	 *
	 * @param  array $atts
	 *
	 * @return string formatted html
	 */
	public static function matador_custom_search_shortcode( $atts = array() ) {
		$atts = shortcode_atts( array(
			'fields' => 'keyword',
			'hidden' => '',
			'class'  => null,
		), $atts );

		return Template_Support::search( $atts );
	}

	/**
	 * Search Form Args Filter Function
	 *
	 * Filter the custom search form values.
	 *
	 * @since 2.0.0
	 *
	 * @param array $args
	 *
	 * @return array
	 */
	public static function search_form_args( $args ) {

		if ( ! empty( $args['hidden'] ) ) {
			if ( is_string( $args['hidden'] ) ) {
				$args['hidden'] = Helper::comma_separated_string_to_escaped_array( $args['hidden'] );
			} else {
				$args['hidden'] = Helper::array_values_escaped( $args['hidden'] );
			}

			$args['temp_hidden'] = $args['hidden'];
			$args['hidden'] = [];

			foreach ( $args['temp_hidden'] as $unused => $default ) {
				if ( strpos( $default, ':' ) === false ) {
					continue;
				}
				$parts = explode( ':', $default );
				$parts = Helper::array_values_escaped( $parts );
				$args['hidden'][ $parts[0] ] = $parts[1];
			}
			unset( $args['temp_hidden'] );
		}

		return $args;
	}


	/**
	 * Replace Jobs-Search.php Template
	 *
	 * @since 2.0.0
	 *
	 * @param string $template     Name of found template.
	 * @param string $name         Template being searched for.
	 * @param string $subdirectory Subdirectory template should be found in.
	 *
	 * @return string Name of found template
	 */
	public static function replace_templates( $template, $name, $subdirectory ) {

		unset( $subdirectory );

		if ( 'jobs-search.php' === $name ) {
			if ( file_exists( trailingslashit( Extension::$directory ) . 'templates/' . $name ) ) {
				return trailingslashit( Extension::$directory ) . 'templates/' . $name;
			}
		}

		return $template;
	}
}
