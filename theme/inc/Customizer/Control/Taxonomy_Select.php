<?php namespace TrevorWP\Theme\Customizer\Control;

/**
 * Taxonomy Select Controller
 */
class Taxonomy_Select extends Abstract_Object_Select {
	public $taxonomy = 'category';
	public $parent   = null;

	/**
	 * @inheritDoc
	 */
	public function get_object_type(): string {
		return 'taxonomy';
	}

	/**
	 * @inheritDoc
	 */
	public function get_object_query_args(): array {
		return array(
			'number'   => 0,
			'taxonomy' => $this->taxonomy,
			'parent'   => $this->parent,
		);
	}

	/** @inheritDoc */
	public function json() {
		return array_merge(
			parent::json(),
			array(
				'taxonomy' => $this->taxonomy,
				'parent'   => $this->parent,
			)
		);
	}

	public function get_default_value(): object {
		$value = $this->value();
		$ids   = explode( ',', $value );
		$ids   = array_map( 'intval', $ids );
		$ids   = array_filter( $ids );

		$order_map = array_flip( $ids ); // Save the order

		if ( ! empty( $ids ) ) {
			$args = array_merge(
				$this->get_object_query_args(),
				array(
					'include'    => $ids,
					'hide_empty' => false,
				)
			);

			$terms = get_terms( $args );
		} else {
			$terms = array();
		}

		$out = new \stdClass();

		/** @var \WP_Term $term */
		foreach ( $terms as $term ) {
			$out->{$term->term_id} = array(
				'name'  => $term->name,
				'tax'   => $term->taxonomy,
				'order' => $order_map[ $term->term_id ] ?? - 1,
			);
		}

		return $out;
	}
}
