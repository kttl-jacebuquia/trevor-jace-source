<?php namespace TrevorWP\Util;

use TrevorWP\Admin;
use TrevorWP\Jobs\Jobs;
use TrevorWP\Ranks;

class Hooks {
	/**
	 * Print buffer for admin_footer hook.
	 *
	 * @var string[]
	 */
	static public $admin_footer_buffer = [];

	/**
	 * Print buffer for wp_footer hook.
	 *
	 * @var array
	 */
	static public $frontend_footer_buffer = [];

	/**
	 * Print buffer for admin_notices hook.
	 *
	 * @var array
	 */
	static public $admin_notices = [];

	/**
	 * List of admin page controllers.
	 *
	 * @var string[]
	 */
	static protected $_admin_page_controllers = [
			Admin\Classy::class,
			Admin\Google::class
	];

	/**
	 * Registers all hooks
	 */
	public static function register_all() {
		# Init
		add_action( 'init', [ self::class, 'init' ] );

		# Print footer
		add_action( 'wp_footer', [ self::class, 'wp_footer' ] );

		# Register & Enqueue Scripts
		add_action( 'wp_enqueue_scripts', [ StaticFiles::class, 'register_frontend' ], 10, 0 );

		# Prints GA Post Events Tracker
		add_action( 'wp_footer', [ self::class, 'print_post_event_tracker' ], PHP_INT_MAX, 0 );

		# Google OAuth Return
		add_action( 'wp_ajax_trevor-g-return', [ Google_API::class, 'handle_return_page' ], 10, 0 );

		# Recurring Job Hooks
		add_action( 'init', [ Jobs::class, 'register_hooks' ], 10, 0 );

		if ( is_admin() ) {
			# Scripts
			add_action( 'admin_enqueue_scripts', [ self::class, 'admin_enqueue_scripts' ], 1, 1 );
			add_action( 'admin_enqueue_scripts', [ StaticFiles::class, 'register_admin' ], 10, 1 );

			# Footer
			add_action( 'admin_footer', [ self::class, 'admin_footer' ], 10, 0 );

			# Admin Notices
			add_action( 'admin_notices', [ self::class, 'admin_notices' ] );

			# Admin Pages
			foreach ( self::$_admin_page_controllers as $admin_page_cls ) {
				add_action( 'admin_menu', [ $admin_page_cls, 'register' ], 10, 0 );
			}

			# Admin Post Columns
			Admin\Post_Rank_Column::register_hooks();
		}

		add_action( 'wp_ajax_autocomplete-test', [ self::class, 'autocomplete_test' ], 10, 0 );
		add_action( 'wp_ajax_nopriv_autocomplete-test', [ self::class, 'autocomplete_test' ], 10, 0 );

		# Custom Hooks
		add_action( 'trevor_post_ranks_updated', [ self::class, 'trevor_post_ranks_updated' ], 10, 1 );
	}

	public static function autocomplete_test() {
		$term = @$_GET['term'];

		$client = \SolrPower_Api::get_instance()->get_solr();

		// get a select query instance
		$query = $client->createSelect();
		$query->setRows( 0 );

		// add spellcheck settings
		$spellcheck = $query->getSpellcheck();
		$spellcheck->setQuery( $term );
		$spellcheck->setCount( 10 );
		$spellcheck->setBuild( true );
		$spellcheck->setCollate( true );
		$spellcheck->setExtendedResults( true );
		$spellcheck->setCollateExtendedResults( true );
		$spellcheck->setMaxCollationEvaluations( 20 );
		$spellcheck->setMaxCollations( 20 );
		$spellcheck->setMaxCollationTries( 5 );
//		$spellcheck->getOnlyMorePopular(true);

		// this executes the query and returns the result
		$resultset        = $client->select( $query );
		$spellcheckResult = $resultset->getSpellcheck();

		$response = [
				'correctlySpelled' => $spellcheckResult->getCorrectlySpelled(),
				'suggestions'      => [],
				'collations'       => []
		];

		foreach ( $spellcheckResult as $suggestion ) {
			$response['suggestions'][] = [
					'numFound'          => $suggestion->getNumFound(),
					'startOffset'       => $suggestion->getStartOffset(),
					'endOffset'         => $suggestion->getEndOffset(),
					'originalFrequency' => $suggestion->getOriginalFrequency(),
					'words'             => $suggestion->getWords()
			];
		}

		$collations = $spellcheckResult->getCollations();
		foreach ( $collations as $collation ) {
			$response['collations'][] = [
					'query'       => $collation->getQuery(),
					'hits'        => $collation->getHits(),
					'corrections' => $collation->getCorrections()
			];
		}

		wp_send_json( $response );
	}

	/**
	 * Fires after WordPress has finished loading but before any headers are sent.
	 *
	 * @link https://developer.wordpress.org/reference/hooks/init/
	 */
	public static function init(): void {
	}

	/**
	 * Prints admin screen notices.
	 *
	 * @link https://developer.wordpress.org/reference/hooks/admin_notices/
	 */
	public static function admin_notices(): void {
		foreach ( self::$admin_notices as $idx => $notice ) {
			?>
			<div class="notice <?= esc_attr( $notice['class'] ) ?> is-dismissible">
				<p><?= $notice['msg'] ?></p>
			</div>
			<?php
			unset( self::$admin_notices[ $idx ] );
		}
	}

	/**
	 * Print scripts or data before the closing body tag on the front end.
	 *
	 * @link https://developer.wordpress.org/reference/hooks/wp_footer/
	 */
	static public function wp_footer(): void {
		# Print buffer
		foreach ( self::$frontend_footer_buffer as $line ) {
			echo $line;
		}
	}

	/**
	 * Print scripts or data before the default footer scripts.
	 *
	 * @link https://developer.wordpress.org/reference/hooks/admin_footer/
	 */
	static public function admin_footer(): void {
		# Print buffer
		foreach ( self::$admin_footer_buffer as $line ) {
			echo $line;
		}

		foreach ( self::$admin_notices as $idx => $notice ) {
			?>
			<script>
				jQuery(function () {
					TrevorWP.utils.notices.add("<?=esc_js( $notice['msg'] );?>", {class: "<?=esc_js( $notice['class'] );?>"})
				});
			</script>
			<?php
		}
	}

	/**
	 * Enqueue scripts for all admin pages.
	 *
	 * @param string $hook_suffix
	 *
	 * https://developer.wordpress.org/reference/hooks/admin_enqueue_scripts/
	 */
	public static function admin_enqueue_scripts( string $hook_suffix ): void {
		$js_ns = [
				'screen'    => [
						'hook_suffix' => esc_js( $hook_suffix )
				],
				'common'    => new \stdClass(),
				'utils'     => new \stdClass(),
				'adminApps' => new \stdClass(),
		];
		?>
		<script>window.TrevorWP = <?= json_encode( $js_ns )?>;</script>
		<?php
	}

	/**
	 * Prints the post tracking code.
	 *
	 * @link https://developer.wordpress.org/reference/hooks/wp_footer/
	 */
	public static function print_post_event_tracker(): void {
		if ( ! is_single() || ! ( $post = get_post() ) ) {
			return;
		}

		$env = Tools::get_env();

		$event_label = implode( '#', [ $post->ID, $post->post_name ] );
		$args        = [
				'event'          => 'post_event',
				'eventCategory'  => "view_post_{$env}",
				'eventAction'    => 'view_post',
				'eventLabel'     => $event_label,
				'nonInteraction' => true,
		];
		?>
		<script>
			window.dataLayer && window.dataLayer.push(<?= json_encode( $args )?>);
		</script>
		<?php
	}

	/**
	 * @param string $post_type
	 * @see Ranks\Post::update_ranks()
	 */
	public static function trevor_post_ranks_updated( string $post_type ): void {
		switch ( $post_type ) {
			case 'post':
				wp_schedule_single_event( time(), Jobs::NAME_UPDATE_TAXONOMY_RANKS, [ 'post_tag', $post_type ] );
				wp_schedule_single_event( time() + 60, Jobs::NAME_UPDATE_TAXONOMY_RANKS, [ 'category', $post_type ] );
				break;
		}

	}
}
