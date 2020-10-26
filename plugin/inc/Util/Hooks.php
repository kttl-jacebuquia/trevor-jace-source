<?php namespace TrevorWP\Util;

use \Solarium\QueryType\Update\Query\Document\Document as SolariumDocument;
use TrevorWP\Admin;
use TrevorWP\Jobs\Jobs;
use TrevorWP\Ranks;
use TrevorWP\CPT;
use TrevorWP\Options;

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
		add_action( 'wp_ajax_highlight-search-test', [ self::class, 'highlight_search' ], 10, 0 );
		add_action( 'wp_ajax_nopriv_highlight-search-test', [ self::class, 'highlight_search' ], 10, 0 );

		# Custom Hooks
		add_action( 'trevor_post_ranks_updated', [ self::class, 'trevor_post_ranks_updated' ], 10, 1 );

		# Post Links
		add_filter( 'post_type_link', [ self::class, 'post_type_link' ], PHP_INT_MAX, 2 );
		add_filter( 'post_type_archive_link', [ self::class, 'post_type_archive_link' ], PHP_INT_MAX, 2 );

		# Solr Index
		add_filter( 'solr_build_document', [ self::class, 'solr_build_document' ], 10, 2 );
	}

	public static function highlight_search(): void {
		$term = @$_GET['term'];


		$client = \SolrPower_Api::get_instance()->get_solr();

		// get a select query instance
		$query = $client->createSelect();
		$query->setQuery( $aa = '"' . addslashes( $term ) . '"~2' );
		$query->setFields( [ 'ID', 'post_title' ] );

		// get highlighting component and apply settings
		$hl = $query->getHighlighting();
		$hl->setFields( [ 'post_title_t', 'post_content' ] );
		$hl->setSimplePrefix( '<b>' );
		$hl->setSimplePostfix( '</b>' );

		// this executes the query and returns the result
		$resultset    = $client->select( $query );
		$highlighting = $resultset->getHighlighting();

		$results = [];
		/** @var \Solarium\QueryType\Select\Result\Document $document */
		foreach ( $resultset->getDocuments() as $document ) {
			$fields = $document->getFields();
			$id     = absint( $fields['ID'] );
			if ( ! $id ) {
				continue;
			}

			if ( $highlight = $highlighting->getResult( $id ) ) {
				$highlight = $highlight->getFields();
				$highlight = array_map( 'reset', $highlight );
			} else {
				$highlight = [];
			}

			$results[] = [
					'url'     => get_permalink( $id ),
					'title'   => empty( $highlight['post_title_t'] ) ? $fields['post_title'] : $highlight['post_title_t'],
					'content' => $highlight['post_content'] ?? '',
			];
		}

		$response = [
				'error'   => false,
				'results' => $results
		];

		wp_send_json( $response );
	}

	public static function autocomplete_test(): void {
		$term = @$_GET['term'];

		$client = \SolrPower_Api::get_instance()->get_solr();

		// get a select query instance
		$query = $client->createSelect();
		$query->setRows( 0 );

		// add spellcheck settings
		$spellcheck = $query->getSpellcheck();
		$spellcheck->setQuery( $term );
		$spellcheck->setCount( 20 );
		$spellcheck->setBuild( true );
		$spellcheck->setCollate( true );
		$spellcheck->setExtendedResults( true );
		$spellcheck->setCollateExtendedResults( true );
		$spellcheck->setMaxCollationEvaluations( 100 );
		$spellcheck->setMaxCollations( 5 );
		$spellcheck->setMaxCollationTries( 100 );
		$spellcheck->getOnlyMorePopular( true );

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
		# Post Types
		CPT\Support::init();
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

		$env  = Tools::get_env();
		$wait = absint( get_option( Options\Google::KEY_GA_PAGE_VIEW_TO, Options\Google::DEFAULTS[ Options\Google::KEY_GA_PAGE_VIEW_TO ] ) ) * 1000;

		$event_label = implode( '#', [ $post->ID, $post->post_name ] );
		$args        = [
				'event'          => 'post_event',
				'eventCategory'  => "view_post_{$env}",
				'eventAction'    => "view_{$post->post_type}",
				'eventLabel'     => $event_label,
				'nonInteraction' => true,
		];
		?>
		<script>
			jQuery(function () {
				setTimeout(function () {
					window.dataLayer && window.dataLayer.push(<?= json_encode( $args )?>);
				}, <?=$wait?>);
			});
		</script>
		<?php
	}

	/**
	 * @param string $post_type
	 *
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

	/**
	 * Filters the permalink for a post of a custom post type.
	 *
	 * @param string $post_link The post's permalink.
	 * @param \WP_Post $post The post in question.
	 *
	 * @return string
	 *
	 * @link https://developer.wordpress.org/reference/hooks/post_type_link/
	 */

	public static function post_type_link( string $post_link, \WP_Post $post ): string {
		switch ( $post->post_type ) {
			case CPT\Support::POST_TYPE:
				return home_url( "support/{$post->ID}-{$post->post_name}" );
			default:
				return $post_link;
		}
	}

	/**
	 * Filters the post type archive permalink.
	 *
	 * @param string $link
	 * @param string $post_type
	 *
	 * @return string
	 *
	 * @link https://developer.wordpress.org/reference/hooks/post_type_archive_link/
	 */
	public static function post_type_archive_link( string $link, string $post_type ): string {
		switch ( $post_type ) {
			case CPT\Support::POST_TYPE:
				return home_url( CPT\Support::PERMALINK_BASE . "/" );
			default:
				return $link;
		}
	}

	/**
	 * @param SolariumDocument $doc Generated Solr document.
	 * @param \WP_Post $post_info Original post object.
	 *
	 * @see \SolrPower_Sync::build_document()
	 */
	public static function solr_build_document( SolariumDocument $doc, \WP_Post $post_info ): SolariumDocument {
		$doc->addField( 'post_title_t', $post_info->post_title );

		return $doc;
	}
}
