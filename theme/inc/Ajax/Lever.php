<?php

namespace TrevorWP\Theme\Ajax;

/**
 * Lever API
 */
class Lever {


	public static function construct() {
		/**
		 * Ajax for Lever
		 */
		add_action( 'wp_ajax_nopriv_lever', array( self::class, 'lever' ) );
		add_action( 'wp_ajax_lever', array( self::class, 'lever' ) );
	}

	/**
	 * Retrives cached jobs data from the transient
	 */
	public static function lever() {
		$data = self::get_jobs_data();

		wp_die(
			json_encode(
				array(
					'status' => 'SUCCESS',
					'data'   => $data,
				)
			),
			200
		);
	}

	/**
	 * Gets jobs data. Includes extracted filter items (locations and departments).
	 */
	public static function get_jobs_data() {
		// Test
		// $url = 'https://api.lever.co/v0/postings/leverdemo?mode=json&limit=30';

		// Trevor Lever
		$url = 'https://api.lever.co/v0/postings/thetrevorproject?mode=json';

		$response_json = static::curl( $url, array(), array(), array(), 'GET' );

		list(
			$jobs,
			$locations,
			$departments,
			$total_jobs,
		) = static::extract_jobs_data( $response_json );

		return compact(
			'jobs',
			'locations',
			'departments',
			'total_jobs',
		);
	}

	/**
	 * Extracts necessary jobs data from a raw json response from Lever.
	 */
	public static function extract_jobs_data( $lever_response_json ) {
		$data = json_decode( $lever_response_json, true );

		$jobs        = array();
		$locations   = array();
		$departments = array();

		foreach ( $data as $job ) {
			// Extract job data
			$job_data = array(
				'applyUrl'         => $job['applyUrl'],
				'id'               => $job['id'],
				'descriptionPlain' => $job['descriptionPlain'],
				'title'            => $job['text'],
				'createdAt'        => $job['createdAt'],
			);

			$department = $job['categories']['department'];
			$location   = $job['categories']['location'];

			// Append location if not yet saved
			if ( ! empty( $location ) ) {
				$job_data['location'] = $location;

				if ( ! in_array( $location, $locations, true ) ) {
					$locations[] = $location;
				}
			}

			// Append department if not yet saved
			if ( ! empty( $department ) ) {
				$job_data['department'] = $department;

				if ( ! in_array( $department, $departments, true ) ) {
					$departments[] = $department;
				}
			}

			// Append commitment if not yet saved
			if ( ! empty( $job['categories']['commitment'] ) ) {
				$job_data['commitment'] = $job['categories']['commitment'];
			}

			// Append job data
			$jobs[] = $job_data;
		}

		return array(
			$jobs,
			$locations,
			$departments,
			count( $jobs ),
		);
	}

	/**
	 * Perform a curl
	 *
	 * @param string $url
	 * @param array $headers
	 * @param array $payload
	 * @param array $settings
	 * @param boolean $is_ssl
	 *
	 * @return string $response
	 */
	public static function curl( $url, $headers, $payload, $settings, $method = 'POST', $is_ssl = false ) {
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $url );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers );
		if ( $is_ssl ) {
			curl_setopt( $ch, CURLOPT_SSLKEY, $settings['key_file'] );
			curl_setopt( $ch, CURLOPT_SSLCERT, $settings['pem_file'] );
		}
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		if ( 'POST' === $method ) {
			curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
			curl_setopt( $ch, CURLOPT_POST, 1 );
		}
		$response = curl_exec( $ch );
		curl_close( $ch );

		return $response;
	}
}
