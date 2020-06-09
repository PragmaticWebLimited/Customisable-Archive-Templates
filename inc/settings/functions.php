<?php
/**
 * "CAT" settings.
 */

declare( strict_types = 1 );

namespace Pragmatic\Customisable_Archive_Templates\Settings;

/**
 * Add our options to the taxonomy term screens.
 */
function add_term_options() : void {

	foreach ( get_supported_taxonomies() as $taxonomy ) {
		\add_action( $taxonomy . '_edit_form', __NAMESPACE__ . '\edit_term_fields', 10, 2 );
	}
}

/**
 * Display our options when editing a term.
 *
 * Believe it or not, the terms edit screen uses a table for layout.
 * For v1 for this plugin, please do forgive us. :)
 *
 * @param WP_Term $tag      The term object.
 * @param string  $tax_slug The taxonomy slug.
 */
function edit_term_fields( $tag, $tax_slug ) : void {
	$taxonomy     = \get_taxonomy( $tax_slug );
	$use_template = get_if_term_has_template_enabled( (int) $tag->term_id );

	\wp_nonce_field( 'pragcat-' . $tax_slug, 'pragcat-nonce' );
	?>

	<h2><?php \esc_html_e( 'Archive Template', 'customisable-archive-templates' ); ?></h2>

	<table class="form-table pragcat-term-meta-fields">
		<tbody>
			<tr class="form-field">
				<th scope="row"><?php \esc_html_e( 'Use Template', 'customisable-archive-templates' ); ?></th>
				<td>
					<fieldset>
						<legend class="screen-reader-text">
							<span><?php \esc_html_e( 'Use Template', 'customisable-archive-templates' ); ?></span>
						</legend>

						<label for="pragcat_use_template">
							<input name="pragcat[use-template]" type="checkbox" id="pragcat_use_template" value="1" <?php \checked( $use_template ); ?>>
							<?php
							printf(
								/* translators: 1 = taxonomy term, singular label. */
								\esc_html__( 'Use a custom template for this %s', 'customisable-archive-templates' ),
								\esc_html( strtolower( $taxonomy->labels->singular_name ) )
							);
							?>
						</label>
					</fieldset>
				</td>
			</tr>
		</tbody>
	</table>

	<?php
}

/**
 * After a tax term's settings are saved, store the CAT options.
 *
 * @param int    $term_id  Term ID.
 * @param int    $tt_id    Term taxonomy ID.
 * @param string $taxonomy Taxonomy slug.
 */
function save_term_fields( $term_id, $tt_id, $taxonomy ) : void {

	if ( ! isset( $_POST['taxonomy'] ) || defined( '\DOING_AUTOSAVE' ) && \DOING_AUTOSAVE ) {
		return;
	}

	$object = \get_taxonomy( $taxonomy );
	if ( empty( $object->cap->edit_terms ) || ! \current_user_can( $object->cap->edit_terms ) ) {
		return;
	}

	if ( ! isset( $_POST['pragcat-nonce'] ) ) {
		return;
	}

	if ( ! \wp_verify_nonce(
			\sanitize_text_field( \wp_unslash( $_POST['pragcat-nonce'] ) ),
			'pragcat-' . $taxonomy
		) ) {
		return;
	}

	if ( ! is_taxonomy_supported( $taxonomy ) ) {
		return;
	}

	if ( ! empty( $_POST['pragcat'] ) && ! empty( $_POST['pragcat']['use-template'] ) ) {
		\update_term_meta( $term_id, 'pragcat-use-template', true );
	} else {
		\delete_term_meta( $term_id, 'pragcat-use-template' );
	}
}

/**
 * Does the specified term have its "use template" option enabled, or not?
 *
 * @param string $term_id The term ID.
 *
 * @return boolean True if yes, false if no.
 */
function get_if_term_has_template_enabled( int $term_id ) : bool {
	return (bool) \get_term_meta( $term_id, 'pragcat-use-template', true );
}

/**
 * Get taxonomies to use CAT with.
 *
 * By default, all taxonomies visible in wp-admin are used.
 *
 * @return string[] Taxonomies names to use CAT with.
 */
function get_supported_taxonomies() : array {

	return \apply_filters(
		'cat/get_supported_taxonomies',
		\get_taxonomies( [ 'show_ui' => true, ] )
	);
}

/**
 * Has the specified taxonomy been set to use CAT?
 *
 * @param string $taxonomy Taxonomy slug (name).
 *
 * @return boolean True if yes, false if no.
 */
function is_taxonomy_supported( string $taxonomy ) : bool {

	return in_array( $taxonomy, get_supported_taxonomies(), true );
}
