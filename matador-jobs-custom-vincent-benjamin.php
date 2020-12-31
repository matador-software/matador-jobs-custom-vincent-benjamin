<?php
/**
 * Plugin Name: Matador Jobs Custom Extension for Vincent Benjamin Group
 * Plugin URI: http://matadorjobs.com/
 * Description: Custom Matador Jobs Pro extension for Vincent Benjamin Group.
 * Author: Jeremy Scott, Paul Bearne, Matador Software, LLC
 * Author URI: http://matadorjobs.com
 * Version: 1.0.0
 * Text Domain: matador-custom-vincent-benjamin
 * Domain Path: /languages
 *
 * Custom Matador Jobs Pro extension for Vincent Benjamin Group.
 *
 * Matador Jobs Custom Extension for Vincent Benjamin Group is free software:
 * you can redistribute it and/or modify it under the terms of the GNU General
 * Public License as published by the Free Software Foundation, either version
 * 3 of the License, or any later version.
 *
 * Matador Jobs Custom Extension for Vincent Benjamin Group is distributed in
 * the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the
 * implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See
 * the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Matador Jobs Board. If not, see <http://www.gnu.org/licenses/>.
 *
 * @author     Jeremy Scott, Paul Bearne
 * @version    1.0.0
 */

namespace matador\MatadorJobsVincentBenjamin;

/**
 * Starts the Plugin
 *
 * @since 1.0.0
 */
function run() {
	if ( file_exists( plugin_dir_path( __FILE__ ) . '/Extension.php' ) ) {
		include_once plugin_dir_path( __FILE__ ) . 'Extension.php';
		$run = new Extension();
		$run->instance();
	}
}

/**
 * Admin Notice if Can't Load
 *
 * If we can't load, lets tell our admins.
 *
 * @since 1.0.0
 */
function admin_notice() {
	printf( '<div class="notice notice-error is-dismissible"><p>%1$s</p></div>',
		esc_html__( 'The plugin Matador Jobs Custom Extension for Vincent Benjamin Group requires the Matador Jobs Pro plugin to be installed and active. Either activate the required plugin or deactivate the extension.', 'matador-custom-vincent-benjamin' )
	);
}

// Make sure we have access to is_plugin_active()
if ( ! function_exists( 'is_plugin_active' ) ) {
	require_once ABSPATH . 'wp-admin/includes/plugin.php';
}

// We need Matador to be activated, and if it is, load it.
if ( ! is_plugin_active( 'matador-jobs-pro/matador.php' ) ) {
	add_action( 'admin_notices', __NAMESPACE__ . '\admin_notice' );
} else {
	add_action( 'matador_initialized', __NAMESPACE__ . '\run' );
}
