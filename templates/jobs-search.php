<?php
/**
 * Template: Jobs Search Form
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

use matador\Matador;
use matador\Job_Taxonomies;

/**
 * Defined before include:
 * @var array $fields
 * @var array $defaults
 * @var array $hidden
 * @var mixed $class
 */
?>

<?php do_action( 'matador_search_form_before' ); ?>

<div class="<?php matador_build_classes( 'matador-search-form-container', $class ); ?>">

	<?php do_action( 'matador_search_form_before_form' ); ?>

	<form role="search" method="get" class="matador-search-form"
		action="<?php echo esc_url( matador_get_the_jobs_link() ); ?>">

		<?php do_action( 'matador_search_form_before_fields' ); ?>

		<?php foreach ( $fields as $field ) : ?>

			<?php do_action( 'matador_search_form_before_field', $field ); ?>

			<?php
			// VB Customization START
			if ( in_array( $field, $hidden, true ) ) :

				if ( empty( $defaults[$field] ) ) {

					continue;
				}

				if ( in_array( $field, Job_Taxonomies::registered_taxonomies(), true ) ) {
					$taxonomy = Matador::variable( $field, 'job_taxonomies' );
					$key = $taxonomy['key'];
				} else {
					$key = $field;
				}

				echo '<input type="hidden" name="' . $key . '" value="' . $defaults[$field] . '" />';
				continue;
			endif;
			// VB Customization END
			?>

			<?php if ( 'keyword' === $field ) : ?>

				<?php
				// VB Customization START
				matador_get_template_part( 'jobs-search-field', 'keyword', [ 'default' => isset( $defaults['keyword'] ) ? $defaults['keyword'] : false ] );
				// VB Customization END
				?>

			<?php elseif ( 'reset' === $field ) : ?>

				<?php matador_get_template_part( 'jobs-search-field', 'reset' ); ?>

			<?php elseif ( in_array( $field, Job_Taxonomies::registered_taxonomies(), true ) ) : ?>

				<?php
				// VB Customization START
				matador_get_template_part( 'jobs-search-field', 'taxonomy', array( 'field' => $field ) );
				// VB Customization END
				?>

			<?php else : ?>

				<?php do_action( 'matador_search_form_field', $field ); ?>

			<?php endif; ?>

			<?php do_action( 'matador_search_form_after_field', $field ); ?>

		<?php endforeach; ?>

		<?php do_action( 'matador_search_form_before_submit' ); ?>

		<?php matador_get_template_part( 'jobs-search-field', 'submit' ); ?>

		<?php do_action( 'matador_search_form_after_fields' ); ?>

	</form>

	<?php do_action( 'matador_search_form_after_form' ); ?>

</div>

<?php do_action( 'matador_search_form_after' ); ?>
