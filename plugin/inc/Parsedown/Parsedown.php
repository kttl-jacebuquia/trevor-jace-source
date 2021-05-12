<?php namespace TrevorWP\Parsedown;

use Parsedown as RealParsedown;
use TrevorWP\CPT\RC\RC_Object;

class Parsedown extends RealParsedown {
	/**
	 * @param array $Element
	 *
	 * @return string
	 */
	protected function element( array $Element ) {
		if ( is_array( $Element ) ) {

			if ( is_string( $Element['name'] ) ) {

				switch ( $Element['name'] ) {
					case 'a':
						$this->_processA( $Element );
						break;

				}
			}
		}

		return parent::element( $Element );
	}

	/**
	 * Processes the A element.
	 *
	 * @param array $Element
	 */
	private function _processA( array &$Element ) {
		$url       = $Element['attributes']['href'];
		$url_parts = parse_url( $url );
		$scheme    = (string) @$url_parts['scheme'];

		if ( $scheme === 'wp' ) {
			$this->_process_wp_protocol( $Element, $url_parts );
		}
	}

	/**
	 * Processes the WP:// protocol.
	 *
	 * @param array $Element
	 * @param array $url_parts
	 */
	private function _process_wp_protocol( array &$Element, array $url_parts ) {
		$host  = (string) @$url_parts['host'];
		$path  = (string) @$url_parts['path'];
		$query = (string) @$url_parts['query'];

		$forced_args = array(
			'posts_per_page' => 1,
		);

		$href = &$Element['attributes']['href'];

		if ( post_type_exists( $host ) ) {
			$forced_args['post_type'] = $host;

			$q = new \WP_Query( array_merge( wp_parse_args( $query ), $forced_args ) );

			if ( ! empty( $q->posts ) ) {
				$href = get_permalink( reset( $q->posts ) );
			}
		} elseif ( $host == 'rc' ) {
			if ( $path == '/search/' ) {
				$href = home_url( RC_Object::PERMALINK_BASE ) . ( empty( $query ) ? '' : "?$query" );
			}
		}
	}
}
