<?php namespace TrevorWP\Theme\Helper;

use TrevorWP\Theme\Ajax\ADP;

/**
 * Careers Option Page
 */
class Careers {

	/**
	 * @var array
	 */


	public function __construct() {}

	/**
	 * @return string|null
	 */

	public function render_option_page(): void {
		$refresh = ! empty( $_POST['jobs_refresh'] );

		// If refreshing
		if ( $refresh ) {
			// Refetch ADP jobs data
			ADP::adp_refresh();
		}

		$jobs = ADP::get_jobs();

		ob_start(); ?>
			<div class="wrap acf-settings-wrap careers-options">
				<h1>Careers</h1>
				<?php if ( $refresh ) : ?>
					<h2>List Updated.</h2>
				<?php endif; ?>
				<form id="post" method="POST" name="post">
					<div id="acf-form-data">
						<div id="poststuff" class="poststuff">
							<div id="post-body" class="metabox-holder columns-2">

								<div id="postbox-container-2" class="postbox-container">

									<div id="normal-sortables" class="meta-box-sortables ui-sortable">
										<div id="acf-trvr-quick-exit" class="postbox acf-postbox">
											<div class="postbox-header">
												<h2 class="hndle ui-sortable-handle">Current Openings</h2>
											</div>
											<div class="inside acf-fields">
												<div class="careers-options__overview">
													<div class="block">
														Total: <strong><?php echo count( $jobs ) ?? 0; ?></strong>
													</div>
													<div class="careers-options__action-wrap">
														Hit Refresh to update list (Might take a while):
														<button name="jobs_refresh" type="submit" class="careers-options__action" value="true">Refresh</button>
													</div>
												</div>
												<hr />
												<table class="careers-options__table">
													<thead>
														<th>ID</th>
														<th>Job Name</th>
														<th>Department</th>
													</thead>
													<tbody>
														<?php if ( empty( $jobs ) ) : ?>
															<tr>
																<td colspan="3">
																	<p><strong>No data.</strong></p>
																</td>
															</tr>
														<?php endif; ?>
														<?php foreach ( $jobs as $job ) : ?>
															<tr>
																<td>
																	<?php echo $job['itemID']; ?>
																</td>
																<td>
																	<?php echo $job['job']['jobTitle']; ?>
																</td>
																<td>
																	<?php echo static::get_job_department( $job ); ?>
																</td>
															</tr>
														<?php endforeach; ?>
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>

							</div>

							<br class="clear">

						</div>

				</form>
			</div>
		<?php
		echo ob_get_clean();
	}

	protected static function get_job_department( $job ): string {
		$org_unit   = $job['organizationalUnits'];
		$department = array();

		foreach ( $org_unit as $v ) {
			if ( 'Department' === $v['typeCode']['codeValue'] ) {
				$department = ! empty( $v['nameCode']['longName'] ) ? $v['nameCode']['longName'] : $v['nameCode']['shortName'];
			}
		}

		return $department;
	}
}
