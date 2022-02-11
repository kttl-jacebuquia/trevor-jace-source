<?php
	$paginate_args = array(
		'next_text' => '&rsaquo;',
		'prev_text' => '&lsaquo;',
	);

	if ( ! empty( $args['total_pages'] ) ) {
		$paginate_args['total'] = $args['total_pages'];
	}
	?>
<div class="pagination number-pagination text-center">
	<?php echo paginate_links( $paginate_args ); ?>
</div>
