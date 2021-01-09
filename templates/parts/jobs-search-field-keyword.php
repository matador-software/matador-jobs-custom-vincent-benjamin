<?php
/**
 * Template: Jobs Search Form Field Keyword
 *
 * Override of default template. Can be overridden in theme by copying it to wp-content/themes/{yourtheme}/matador/jobs-search.php
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
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<div class="matador-search-form-field-group matador-search-form-field-keyword">

	<label for="matador_s">
		<?php
		$label = apply_filters( 'matador_search_form_keyword_field_label_text', '' );

		$screen_reader_text = apply_filters(
			'matador_search_form_keyword_field_screen_reader_text',
			__( 'Key Word or Key Words', 'matador-jobs' )
		);
		?>

		<?php if ( ! $label ) : ?>

			<span class="matador-screen-reader-text">
				<?php echo esc_html( $screen_reader_text ); ?>
			</span>

		<?php endif; ?>

		<?php echo esc_html( $label ); ?>

	</label>

	<?php

	$placeholder = apply_filters( 'matador_search_form_keyword_field_placeholder', esc_html__( 'Search Jobs', 'matador-jobs' ) );

	$value = isset( $_REQUEST['matador_s'] ) ? esc_attr( $_REQUEST['matador_s'] ) : ''; // WPCS: CSRF ok.

	// VB CUSTOM
	if ( ! $value && $default ) {
		$value = $default;
	}
	// END VB CUSTOM
	?>

	<input type="text" id="matador_s" name="matador_s" value="<?php echo esc_attr( $value ); ?>"
		placeholder="<?php echo esc_attr( $placeholder ); ?>"/>

</div>
