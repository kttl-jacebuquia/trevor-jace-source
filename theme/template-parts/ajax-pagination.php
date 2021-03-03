<?php
$current = ( (int) ( $wp_query->get( 'paged' ) ?: 1 ) );
$total   = $wp_query->max_num_pages ?? 1;
$links   = paginate_links( [
	'total'     => $total,
	'current'   => $current,
	'show_all'  => false,
	'end_size'  => 0,
	'mid_size'  => 0,
	'prev_next' => true,
	'type'      => 'array',
	'next_text' => 'Load More'
] );
?>

<div class="ajax-pagination">
	<?php if ( $current < $total ) { ?>
		<div class="next-wrap text-center">
			<?= array_pop( $links ); /* Last one is the next link */ ?>
		</div>
	<?php } ?>
	<div class="pagination-wrap">
		<?php if ( ! empty( $links ) ) {
			echo implode( "\n", $links );
		} ?>
	</div>
</div>
