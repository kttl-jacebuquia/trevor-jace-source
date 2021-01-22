<?php namespace TrevorWP\Admin;

use TrevorWP\CPT\Get_Involved\Get_Involved_Object;

class Taxonomy {
	const FIELD_NAME_TIER_NAME = 'tier-amt-name';
	const FIELD_NAME_TIER_VALUE = 'tier-amt-val';
	const FIELD_NAME_TIER_LOGO_SIZE = 'tier-logo-size';

	/**
	 * @see \TrevorWP\Util\Hooks::register_all()
	 */
	public static function register_hooks(): void {
		foreach (
				[
						Get_Involved_Object::TAXONOMY_PARTNER_TIER,
						Get_Involved_Object::TAXONOMY_GRANT_TIER
				] as $taxonomy
		) {
			# Add New Form
			add_action( "{$taxonomy}_add_form_fields", [
					self::class,
					'add_tier_fields'
			], 10, 0 );
			# Edit Form
			add_action( "{$taxonomy}_edit_form", [
					self::class,
					'add_tier_fields'
			], 10, 2 );

			# Save Action
			add_action( "saved_{$taxonomy}", [ self::class, 'save_tier_values' ], 10, 1 );
		}
	}

	/**
	 * Fires after the Add Term form fields.
	 *
	 * @link https://developer.wordpress.org/reference/hooks/taxonomy_add_form_fields/
	 * @link https://developer.wordpress.org/reference/hooks/taxonomy_edit_form/
	 */
	public static function add_tier_fields(): void {
		$screen = get_current_screen();

		$name      = $val = '';
		$logo_size = 'text';

		if ( $screen->parent_base == 'edit' ) {
			$term_id   = absint( @ $_GET['tag_ID'] );
			$name      = get_term_meta( $term_id, \TrevorWP\Meta\Taxonomy::KEY_PARTNER_TIER_NAME, true );
			$val       = get_term_meta( $term_id, \TrevorWP\Meta\Taxonomy::KEY_PARTNER_TIER_VALUE, true );
			$logo_size = \TrevorWP\Meta\Taxonomy::get_partner_tier_logo_size( $term_id );
		}

		?>
		<div class="form-field form-required">
			<label for="<?= self::FIELD_NAME_TIER_NAME ?>">Amount Name</label>
			<input name="<?= self::FIELD_NAME_TIER_NAME ?>"
				   id="<?= self::FIELD_NAME_TIER_NAME ?>"
				   type="text"
				   placeholder="$1M+"
				   value="<?= esc_attr( $name ) ?>"
				   aria-required="true"
				   required>
		</div>
		<div class="form-field form-required">
			<label for="<?= self::FIELD_NAME_TIER_VALUE ?>">Amount Value</label>
			<input name="<?= self::FIELD_NAME_TIER_VALUE ?>"
				   id="<?= self::FIELD_NAME_TIER_VALUE ?>"
				   type="number"
				   placeholder="1000000"
				   value="<?= esc_attr( $val ) ?>"
				   aria-required="true"
				   required>
			<p>Required for sorting. Bigger values appear on top.</p>
		</div>
		<div class="form-field form-required">
			<label for="<?= self::FIELD_NAME_TIER_LOGO_SIZE ?>">Logo Size</label>
			<select name="<?= self::FIELD_NAME_TIER_LOGO_SIZE ?>"
					class="widefat"
					id="<?= self::FIELD_NAME_TIER_LOGO_SIZE ?>">
				<?php foreach ( Get_Involved_Object::LOGO_SIZES as $key => $args ) { ?>
					<option value="<?= $key ?>" <?= selected( $logo_size, $key ) ?>><?= esc_html( $args['name'] ) ?></option>
				<?php } ?>
			</select>
		</div>
		<?php
	}

	/**
	 * Fires after a term in a specific taxonomy has been saved, and the term cache has been cleared.
	 *
	 * @param int $term_id
	 *
	 * @link https://developer.wordpress.org/reference/hooks/saved_taxonomy/
	 */
	public static function save_tier_values( int $term_id ): void {
		$name      = $_POST[ self::FIELD_NAME_TIER_NAME ] ?? '';
		$val       = $_POST[ self::FIELD_NAME_TIER_VALUE ] ?? '';
		$logo_size = $_POST[ self::FIELD_NAME_TIER_LOGO_SIZE ] ?? '';
		if ( ! array_key_exists( $logo_size, Get_Involved_Object::LOGO_SIZES ) ) {
			$logo_size = 'text';
		}

		update_term_meta( $term_id, \TrevorWP\Meta\Taxonomy::KEY_PARTNER_TIER_NAME, $name );
		update_term_meta( $term_id, \TrevorWP\Meta\Taxonomy::KEY_PARTNER_TIER_VALUE, $val );
		update_term_meta( $term_id, \TrevorWP\Meta\Taxonomy::KEY_PARTNER_TIER_LOGO_SIZE, $logo_size );
	}
}
