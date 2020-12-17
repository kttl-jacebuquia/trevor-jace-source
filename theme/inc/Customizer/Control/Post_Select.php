<?php namespace TrevorWP\Theme\Customizer\Control;

/**
 * Post Select Controller
 */
class Post_Select extends Abstract_Object_Select {
	/**
	 * @var string|string[]
	 */
	public $post_type = 'post';

	/**
	 * @var array
	 */
	public $taxonomy = [];

	/**
	 * @inheritDoc
	 */
	public function get_object_type(): string {
		return 'post';
	}

	/**
	 * @inheritDoc
	 */
	public function get_object_query_args(): array {
		$tax_query = [
			'relation' => 'OR'
		];

		# Taxonomy filter
		if ( ! empty( $this->taxonomy ) && is_array( $this->taxonomy ) ) {
			foreach ( $this->taxonomy as $taxonomy => $terms ) {
				$tax_query[] = [
					'taxonomy' => $taxonomy,
					'terms'    => $terms,
					'operator' => 'IN'
				];
			}
		}

		return [
			'post_type'   => $this->post_type,
			'numberposts' => - 1,
			'tax_query'   => $tax_query
		];
	}

	/** @inheritDoc */
	public function json() {
		return array_merge( parent::json(), [
			'post_type' => $this->post_type,
			'taxonomy'  => $this->taxonomy,
		] );
	}

	/** @inheritDoc */
	public function get_default_value(): object {
		$value = $this->value();
		$ids   = explode( ',', $value );
		$ids   = array_map( 'intval', $ids );
		$ids   = array_filter( $ids );

		$order_map = array_flip( $ids ); // Save the order

		if ( ! empty( $ids ) ) {
			$args = array_merge( $this->get_object_query_args(), [
				'post__in' => $ids
			] );

			$posts = get_posts( $args );
		} else {
			$posts = [];
		}

		$out = new \stdClass();

		foreach ( $posts as $post ) {
			$out->{$post->ID} = [
				'name'  => $post->post_title,
				'pt'    => $post->post_type,
				'order' => $order_map[ $post->ID ] ?? - 1
			];
		}

		return $out;
	}
}
