<?php namespace TrevorWP\Util;

use TrevorWP\Main;
use WP_Post;
use TrevorWP\Block;
use \Solarium\QueryType\Update\Query\Document\Document as SolariumDocument;
use TrevorWP\Admin;
use TrevorWP\Jobs\GA_Results;
use TrevorWP\Jobs\Jobs;
use TrevorWP\Meta;
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

			# Header
			add_action( 'admin_head', [ self::class, 'admin_head' ], 10, 0 );

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

		# Solr Index
		add_filter( 'solr_build_document', [ self::class, 'solr_build_document' ], 10, 2 );

		# Resource Center
		CPT\RC\RC_Object::construct();

		# Post Meta
		Meta\Post::register_all();

		# Admin Blocks Data
		add_filter( 'trevor_editor_blocks_data', [ self::class, 'trevor_editor_blocks_data' ], 10, 1 );

		# Post Save for Blocks
		add_action( 'save_post', [ self::class, 'save_post_blocks' ], PHP_INT_MAX, 2 );

		# Wrap Singular Post Blocks
		add_filter( 'the_content', [ self::class, 'the_content' ], 8, 1 );
	}

	public static function highlight_search(): void {
		$term = (string) @$_GET['term'];

		$term_parts = explode( ' ', $term );
		$term_parts = array_map( function ( $term_part ) {
			return addslashes( rtrim( $term_part, '~' ) ) . '~';
		}, $term_parts );

		$term = implode( ' ', $term_parts );


		$client = \SolrPower_Api::get_instance()->get_solr();

		// get a select query instance
		$query = $client->createSelect();
		$query->setQuery( $term );
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
		$spellcheck->setCount( 10 );
		$spellcheck->setBuild( true );
		$spellcheck->setCollate( true );
		$spellcheck->setExtendedResults( true );
		$spellcheck->setCollateExtendedResults( true );
		$spellcheck->setMaxCollationEvaluations( 100 );
		$spellcheck->setMaxCollations( 2 );
		$spellcheck->setMaxCollationTries( 100 );
		$spellcheck->getOnlyMorePopular( false );

		// this executes the query and returns the result
		$resultset        = $client->select( $query );
		$spellcheckResult = $resultset->getSpellcheck();

		$response = [
				'correctlySpelled' => $spellcheckResult->getCorrectlySpelled(),
//				'suggestions'      => [],
				'collations'       => []
		];

//		foreach ( $spellcheckResult as $suggestion ) {
//			$response['suggestions'][] = [
//					'numFound'          => $suggestion->getNumFound(),
//					'startOffset'       => $suggestion->getStartOffset(),
//					'endOffset'         => $suggestion->getEndOffset(),
//					'originalFrequency' => $suggestion->getOriginalFrequency(),
//					'words'             => $suggestion->getWords()
//			];
//		}

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
		# Disable solr on rest api queries
		foreach ( array_merge( CPT\RC\RC_Object::$ALL_POST_TYPES, [ 'post', 'page' ] ) as $post_type ) {
			add_filter( "rest_{$post_type}_query", [ self::class, 'rest_post_type_query' ], 1, 1 );
		}
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
	 * Fires in head section for all admin pages.
	 *
	 * @link https://developer.wordpress.org/reference/hooks/admin_head/
	 */
	public static function admin_head(): void {
		$screen = get_current_screen();

		if ( $screen->is_block_editor && in_array( $screen->post_type, Tools::get_public_post_types() ) ) { ?>
			<script>
				Object.assign(window.TrevorWP.screen, {editorBlocksData: <?=json_encode( apply_filters( 'trevor_editor_blocks_data', [] ) )?>})
			</script>
			<?php
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

		foreach ( self::$admin_notices as $idx => $notice ) { ?>
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
	 * @link https://developer.wordpress.org/reference/hooks/admin_enqueue_scripts/
	 */
	public static function admin_enqueue_scripts( string $hook_suffix ): void {
		$post_types = array_fill_keys( array_merge( [ CPT\Post::POST_TYPE ], CPT\RC\RC_Object::$ALL_POST_TYPES ), [] );
		foreach ( $post_types as $post_type => &$pt_data ) {
			$obj              = get_post_type_object( $post_type );
			$pt_data['label'] = $obj->label;
		}

		$taxonomies = array_fill_keys( [
				'post_tag',
				'category',
				CPT\RC\RC_Object::TAXONOMY_CATEGORY,
				CPT\RC\RC_Object::TAXONOMY_TAG
		], [] );
		foreach ( $taxonomies as $taxonomy => &$tax_data ) {
			$obj               = get_taxonomy( $taxonomy );
			$tax_data['label'] = $obj->label;
		}

		$js_ns = [
				'screen'    => [
						'hook_suffix' => esc_js( $hook_suffix )
				],
				'utils'     => new \stdClass(),
				'adminApps' => new \stdClass(),
				'common'    => [
						'post_types' => $post_types,
						'taxonomies' => $taxonomies
				],
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
		if ( ! is_singular( GA_Results::POST_TYPES ) || ! ( $post = get_post() ) ) {
			return;
		}

		$env  = Tools::get_env();
		$wait = absint( get_option( Options\Google::KEY_GA_PAGE_VIEW_TO, Options\Google::DEFAULTS[ Options\Google::KEY_GA_PAGE_VIEW_TO ] ) ) * 1000;
		$args = [
				'event'          => 'post_event',
				'eventCategory'  => "view_post_{$env}",
				'eventAction'    => $post->post_type,
				'eventLabel'     => implode( '#', [ $post->ID, $post->post_name ] ),
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
		$rules = [ 'post' => [ 'post_tag', 'category' ] ]
				 + array_fill_keys( CPT\RC\RC_Object::$PUBLIC_POST_TYPES, [
						CPT\RC\RC_Object::TAXONOMY_CATEGORY,
						CPT\RC\RC_Object::TAXONOMY_TAG
				] );

		if ( ! array_key_exists( $post_type, $rules ) ) {
			return;
		}

		foreach ( $rules[ $post_type ] as $idx => $tax ) {
			wp_schedule_single_event( time() + ( $idx * 10 ), Jobs::NAME_UPDATE_TAXONOMY_RANKS, [ $tax, $post_type ] );
		}
	}

	/**
	 * @param SolariumDocument $doc Generated Solr document.
	 * @param WP_Post $post_info Original post object.
	 *
	 * @see \SolrPower_Sync::build_document()
	 */
	public static function solr_build_document( SolariumDocument $doc, WP_Post $post_info ): SolariumDocument {
		$doc->addField( 'post_title_t', $post_info->post_title );

		return $doc;
	}

	/**
	 * Filters the query arguments for a request.
	 *
	 * @param array $args
	 *
	 * @return array
	 *
	 * @link https://developer.wordpress.org/reference/hooks/rest_this-post_type_query/
	 */
	public static function rest_post_type_query( array $args ): array {
		$args['solr_integrate'] = false;
		Tools::disable_solr();

		return $args;
	}

	/**
	 * @param array $data
	 */
	public static function trevor_editor_blocks_data( array $data ): array {
		global $post;

		if ( ! $post ) {
			return $data;
		}

		# Post Taxonomies
		$data['catTax'] = Tools::get_post_category_tax( $post );
		$data['tagTax'] = Tools::get_post_category_tax( $post );

		return array_merge( $data, Meta\Post::get_editor_config( $post ) );
	}

	/**
	 * Fires once a post has been saved.
	 *
	 * @param int $post_id
	 * @param WP_Post $post
	 *
	 * @link https://developer.wordpress.org/reference/hooks/save_post/
	 */
	public static function save_post_blocks( int $post_id, WP_Post $post ): void {
		$blocks = parse_blocks( $post->post_content );

		/** @var Block\Post_Save_Handler[] $map */
		$map = [
				Block\Core_Heading::BLOCK_NAME => Block\Core_Heading::class,
		];

		$states = array_fill_keys( array_keys( $map ), [] );

		foreach ( $blocks as $block ) {
			if ( ! array_key_exists( $block_name = $block['blockName'], $map ) ) {
				continue;
			}

			call_user_func_array( [ $map[ $block_name ], 'save_post' ], [
					$block,
					$post,
					&$states[ $block_name ]
			] );
		}

		foreach ( $map as $block_name => $cls ) {
			call_user_func_array( [ $cls, 'save_post_finalize' ], [ $post, &$states[ $block_name ] ] );
		}
	}

	/**
	 * Wraps content blocks with the grid wrapper and leaves custom blocks to add their owns.
	 *
	 * @param string $content
	 *
	 * @return string Rendered content.
	 *
	 * @link https://developer.wordpress.org/reference/hooks/the_content/
	 */
	public static function the_content( string $content ): string {
		if ( is_singular( Tools::get_public_post_types() ) || is_page() && in_the_loop() && is_main_query() ) {
			/**
			 * Disables wpautop for this post. Otherwise it replaces new lines with p element.
			 * @see do_blocks()
			 */
			$priority = has_filter( 'the_content', 'wpautop' );
			if ( false !== $priority && doing_filter( 'the_content' ) && has_blocks( $content ) ) {
				remove_filter( 'the_content', 'wpautop', $priority );
				add_filter( 'the_content', '_restore_wpautop_hook', $priority + 1 );
			}
		}

		return $content;
	}

}
