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

use \Exception;

final class Extension {

	/**
	 * Constant Version
	 *
	 * @since 1.0.0
	 *
	 * @var string VERSION
	 */
	const VERSION = '2.0.0';

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

			try {
				spl_autoload_register( array( __CLASS__, 'auto_loader' ) );
			} catch ( Exception $error ) {
				_doing_it_wrong( __FUNCTION__, esc_html__( 'There was an error initializing the Autoloader. Contact the developer.', 'matador-custom-vincent-benjamin' ), esc_attr( self::VERSION ) );
			}

			add_action( 'plugins_loaded', array( self::$instance, 'textdomain' ) );

			add_action( 'plugins_loaded', array( self::$instance, 'load' ) );

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
	public function load() {
		new Taxonomies();
		new Shortcodes();
	}

	/**
	 * Auto Loader
	 *
	 * @since 2.0.0
	 *
	 * @param string $class
	 *
	 * @return void
	 */
	public static function auto_loader( $class ) {

		$prefix = __NAMESPACE__ . '\\';

		$length = strlen( $prefix );

		if ( 0 !== strncmp( $prefix, $class, $length ) ) {

			return;
		}

		if ( strncmp( $prefix, $class, $length ) === 0 ) {
			// get the relative class name
			$relative_class = substr( $class, $length );

			// base directory for the namespace prefix
			$base_dir = static::$directory . 'src/';

			// replace the namespace prefix with the base directory, replace namespace
			// separators with directory separators in the relative class name, append
			// with .php
			$file = $base_dir . str_replace( '\\', '/', $relative_class ) . '.php';

			// if the file exists, require it
			if ( file_exists( $file ) ) {
				require $file;
				return;
			}
		}
	}

	/**
	 * Plugin Textdomain
	 *
	 * @access public
	 *
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function textdomain() {
		load_plugin_textdomain( 'matador-custom-vincent-benjamin', false, static::$path . '/languages' );
	}
}
