<?php namespace TrevorWP\Jobs;

use TrevorWP\Exception\Internal;
use TrevorWP\Meta\Post;
use TrevorWP\Options;
use TrevorWP\Util\Google_API;
use TrevorWP\Util\Log;

class GA_Results {
	const POST_STATS_REPORT_ITEM_COUNT = 1;
	const POST_STATS_REPORT_NEXT_PAGE_COOLING = 20; // seconds; API is restricted to a maximum of 10 requests per second per user.

	public static function update_post_stats() {
		$has_token = Google_API::has_token();

		if ( ! $has_token ) {
			Log::warning( 'Updating post stats job could not be completed because of missing access token.' );

			return false;
		}

		self::get_the_post_stats_report_page();
	}

	/**
	 * @param null $page_token
	 *
	 * @return int
	 * @throws Internal
	 * @throws \Google_Exception
	 */
	public static function get_the_post_stats_report_page( $page_token = null ): int {
		$view_id = get_option( Options\Google::KEY_GA_VIEW_ID );
		if ( empty( $view_id ) ) {
			Log::warning( 'GA View Id is missing.' );

			return 0;
		}

		$client = Google_API::get_client( true );

		// Create an authorized analytics service object.
		$analytics = new \Google_Service_AnalyticsReporting( $client );

		// Create the DateRange object.
		$dateRange7 = new \Google_Service_AnalyticsReporting_DateRange();
		$dateRange7->setStartDate( "7daysAgo" );
		$dateRange7->setEndDate( "today" );

		$dateRange30 = new \Google_Service_AnalyticsReporting_DateRange();
		$dateRange30->setStartDate( "36daysAgo" );
		$dateRange30->setEndDate( "8daysAgo" );

		// Create the Metrics object.
		$sessions = new \Google_Service_AnalyticsReporting_Metric();
		$sessions->setExpression( "ga:uniqueEvents" );
		$sessions->setAlias( "sessions" );

		//Create the Dimensions object.
		$browser = new \Google_Service_AnalyticsReporting_Dimension();
		$browser->setName( "ga:eventLabel" );

		// Ordering
		$ordering = new \Google_Service_AnalyticsReporting_OrderBy();
		$ordering->setFieldName( "ga:uniqueEvents" );
		$ordering->setOrderType( "VALUE" );
		$ordering->setSortOrder( "DESCENDING" );

		# Filters
		$filters = new \Google_Service_AnalyticsReporting_DimensionFilterClause();
		$filters->setOperator( 'AND' );

		// Event Category
		$filter_cat = new \Google_Service_AnalyticsReporting_DimensionFilter();
		$filter_cat->setDimensionName( 'ga:eventCategory' );
		$filter_cat->setOperator( 'EXACT' );
		$filter_cat->setExpressions( 'view_post_dev' );

		// Filter out "(not set)" values
		$filter_not_set = new \Google_Service_AnalyticsReporting_DimensionFilter();
		$filter_not_set->setDimensionName( 'ga:eventLabel' );
		$filter_not_set->setOperator( 'REGEXP' );
		$filter_not_set->setExpressions( '\d+#.*' );

		$filters->setFilters( [ $filter_cat, $filter_not_set ] );

		// Create the ReportRequest object.
		$request = new \Google_Service_AnalyticsReporting_ReportRequest();
		$request->setViewId( $view_id );
		$request->setDateRanges( [ $dateRange7, $dateRange30 ] );
		$request->setDimensions( array( $browser ) );
		$request->setMetrics( array( $sessions ) );
		$request->setOrderBys( $ordering );
		$request->setDimensionFilterClauses( [ $filters ] );
		$request->pageSize  = self::POST_STATS_REPORT_ITEM_COUNT;
		$request->pageToken = $page_token;

		$body = new \Google_Service_AnalyticsReporting_GetReportsRequest();
		$body->setReportRequests( array( $request ) );

		try {
			$reports = $analytics->reports->batchGet( $body );
		} catch ( \Exception $e ) {
			if ( $e instanceof \Google_Service_Exception && $e->getCode() == 401 ) {
				throw new Internal( 'Google Authentication is failed.', compact( 'page_token' ), $e->getMessage(), $e->getCode(), $e );
			} else {
				throw new Internal( 'GA report fetching failed.', compact( 'page_token' ), $e->getMessage(), $e->getCode(), $e );
			}
		}

		if ( count( $reports ) != 1 || empty( $reports[0] ) ) {
			throw new Internal( 'GA post views reports should have only one report.', [ 'count' => count( $reports ) ] );
		}

		/** @var  \Google_Service_AnalyticsReporting_Report $report */
		$report = $reports[0];

		return self::_process_the_post_stats_report( $report );
	}

	/**
	 * @param \Google_Service_AnalyticsReporting_Report $report
	 * @param string $post_type
	 * @param string $post_status
	 *
	 * @return int Processed post count.
	 */
	protected static function _process_the_post_stats_report(
		\Google_Service_AnalyticsReporting_Report $report,
		string $post_type = 'post',
		string $post_status = 'publish'
	): int {
		$processed = 0;
		$rows      = $report->getData()->getRows();
		for ( $rowIndex = 0; $rowIndex < count( $rows ); $rowIndex ++ ) {
			/** @var \Google_Service_AnalyticsReporting_ReportRow $row */
			$row = $rows[ $rowIndex ];

			$dimensions    = $row->getDimensions();
			$id__post_name = $dimensions[0];
			$parts         = explode( '#', $id__post_name );
			if ( count( $parts ) < 2 ) {
				Log::notice( 'Malformed GA event label format on GA post views report.', compact( 'parts' ) );
				continue;
			}

			$id        = (int) $parts[0];
			$post_name = implode( '#', array_slice( $parts, 1 ) ); // fallback

			# Try to match with slug first
			$get_posts = get_posts( [
				'name'        => $post_name,
				'post_type'   => $post_type,
				'post_status' => $post_status,
				'numberposts' => 1
			] );

			# Nothing matched with the slug
			if ( empty( $get_posts ) ) {
				# Try to match with ID
				$post = get_post( $id );
				if ( $post && $post->post_type == $post_type && $post->post_status == $post_status ) {
					Log::notice( 'Post matched with ID instead of slug.', compact( 'post_name', 'id' ) );
				} else {
					Log::notice( 'Could not found post the post with slug or id.', compact( 'post_name', 'id' ) );
					continue;
				}
			} else {
				# Matched post with the slug
				$post = $get_posts[0];

				# Compare its ID
				if ( $id != $post->ID ) {
					Log::warning( 'ID & slug not matched on found post.', compact( 'id', 'post_name' ) );
				}
			}

			/** @var \Google_Service_AnalyticsReporting_DateRangeValues[] $metrics */
			$metrics        = $row->getMetrics();
			$shorter_period = (int) $metrics[0]->getValues()[0];
			$longer_period  = (int) $metrics[1]->getValues()[0];

			update_post_meta( $post->ID, Post::KEY_VIEW_COUNT_SHORT, $shorter_period );
			update_post_meta( $post->ID, Post::KEY_VIEW_COUNT_LONG, $longer_period );
			$processed ++;
		}

		if ( $page_token = $report->getNextPageToken() ) {
			// Schedule the next page for processing
			wp_schedule_single_event(
				time() + self::POST_STATS_REPORT_NEXT_PAGE_COOLING,
				Jobs::NAME_PROCESS_POST_STATS_PAGE,
				[ $page_token ]
			);
		} else {
			// Report finished, schedule the job for post rank calculation
			wp_schedule_single_event(
				time(),
				Jobs::NAME_UPDATE_POST_RANKS,
				[ $post_type ]
			);
		}

		return $processed;
	}
}
