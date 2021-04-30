<?php namespace TrevorWP\Theme\Helper;

use TrevorWP\CPT;
use TrevorWP\Util\Tools;
use \TrevorWP\Meta;
use \TrevorWP\Theme\Single_Page;
use TrevorWP\Theme\ACF\Field_Group;
use TrevorWP\Theme\ACF\Util\Field_Val_Getter;
use TrevorWP\Theme\ACF\Field_Group\Financial_Report;

class Tile {
	/**
	 * @param \WP_Post $post
	 * @param int $key
	 * @param array $options
	 *
	 * @return string
	 */
	public static function post( \WP_Post $post, $key, array $options = [] ): string {
		$attachment_id = '';
		if ( CPT\Donate\Partner_Prod::POST_TYPE === $post->post_type ) {
			$attachment_id = Meta\Post::get_item_img_id( $post->ID );
		} else if ( CPT\Donate\Prod_Partner::POST_TYPE === $post->post_type ) {
			$attachment_id = Meta\Post::get_store_img_id( $post->ID );
		}

		if ( ! empty( $attachment_id ) ) {
			$img = wp_get_attachment_image( $attachment_id, 'medium', false, [
					'class' => implode( " ", [
							'object-center',
							'object-cover',
							'tile-image',
					] ),
			] );
		}


		$data = [
				'title'     => $post->post_title,
				'desc'      => $post->post_excerpt,
				'img'       => $img,
				'cta_txt'   => 'Read More',
				'cta_url'   => get_permalink( $post ),
		];

		if ( $post->post_type == CPT\Get_Involved\Bill::POST_TYPE ) {
			$data['title_top'] = \TrevorWP\Meta\Post::get_bill_id( $post->ID );
		}

		if ( $post->post_type == CPT\Donate\Partner_Prod::POST_TYPE ) {
			$data['title_top'] = get_the_title( Meta\Post::get_partner_id( $post->ID ) );
		}

		if ( $post->post_type == CPT\Donate\Prod_Partner::POST_TYPE ) {
			$data['title_top'] = '';
		}

		if ( $post->post_type == CPT\Donate\Prod_Partner::POST_TYPE || $post->post_type == CPT\Donate\Partner_Prod::POST_TYPE ) {
			$data['cta_txt'] = 'Check It Out';
			$data['cta_url'] = Meta\Post::get_store_url( $post->ID ) | Meta\Post::get_item_url( $post->ID );
			$options['class'][] = 'product-card';
			$options['card_type'] = 'product';
		}

		if ( in_array( $post->post_type, [ CPT\Get_Involved\Bill::POST_TYPE, CPT\Get_Involved\Letter::POST_TYPE ] ) ) {
			$id            = uniqid( 'post-' );
			$options['id'] = $id;
			$options['class'][] = 'bill-letter-card';

			add_action( 'wp_footer', function () use ( $id, $post ) {
				echo ( new \TrevorWP\Theme\Helper\Modal( CPT\Get_Involved\Bill::render_modal( $post ), [
						'target' => "#{$id} a",
						'id'     => "{$id}-content"
				] ) )->render();
				?>
				<script>jQuery(function () {
						trevorWP.features.sharingMore(
								document.querySelector('#<?= $id ?>-content .post-share-more-btn'),
								document.querySelector('#<?= $id ?>-content .post-share-more-content'),
								{appendTo: document.querySelector('#<?= $id ?>-content')}
						);
					})</script>
				<?php
			}, 10, 0 );
		}

		if ( $post->post_type === CPT\Research::POST_TYPE ) {
			$data['cta_txt'] = 'Learn More';
		}
		return self::custom( $data, $key, $options );
	}

	/**
	 * @param array $data
	 * @param int $key
	 * @param array $options
	 *
	 * @return string
	 */
	public static function accordion( array $data, int $key, array $options = [] ): string {
		$options = array_merge( array_fill_keys( [
				'class'
		], null ), $options );

		# class
		$cls = [ 'tile-accordion-item', 'relative' ];
		if ( ! empty( $options['class'] ) ) {
			$cls = array_merge( $cls, $options['class'] );
		}

		ob_start();
		?>
		<div class="js-accordion <?= implode( ' ', $cls ) ?>">
			<h2 class="accordion-header" id="heading-<?= $key ?>">
				<button class="accordion-button text-px20 leading-px26 py-8 px-7 w-full text-left border-b border-blue_green border-opacity-20 flex justify-between items-center font-semibold md:pt-9 md:mb-px6 md:border-0 md:pb-0 md:text-px24 md:leading-px28 lg:text-px26 lg:leading-px36"
						type="button" aria-expanded="false" aria-controls="collapse-<?= $key ?>">
					<?= $data['title'] ?>
				</button>
			</h2>
			<div id="collapse-<?= $key ?>" class="accordion-collapse collapse" aria-labelledby="heading-<?= $key ?>">
				<div class="accordion-body px-7 pt-5 pb-9 bg-gray-light md:bg-transparent md:pb-36 lg:pt-0">
					<p class="text-px18 leading-px28 mb-6 md:text-px16 md:leading-px24 lg:text-px18 lg:leading-px26">
						<?= $data['desc'] ?>
					</p>
					<div class="tile-cta-wrap">
						<a href="<?= @$data['cta_url'] ?>"
						   class="tile-cta font-bold text-px18 leading-px28 border-b-2 border-teal-dark lg:text-px20">
							<span><?= $data['cta_txt'] ?></span>
						</a>
					</div>
				</div>
			</div>
		</div>
		<?php return ob_get_clean();
	}

	/**
	 * @param array $data
	 * @param int $key
	 * @param array $options
	 *
	 * @return string
	 */
	public static function custom( array $data, int $key, array $options = [] ): string {
		if ( $options['accordion'] ) {
			return self::accordion( $data, $key, $options );
		}

		$options = array_merge( array_fill_keys( [
				'class',
				'attr',
				'card_type',
				'hidden',
		], null ), $options );

		# class
		$cls = [ 'tile', 'relative' ];
		if ( ! empty( $options['class'] ) ) {
			$cls = array_merge( $cls, $options['class'] );
		}

		if ( $options['hidden'] ) {
			$cls[] = 'hidden';
		}

		$attr          = (array) $options['attr'];
		$attr['class'] = implode( ' ', $cls );
		if ( ! empty( $options['id'] ) ) {
			$attr['id'] = $options['id'];
		}

		ob_start();
		?>
		<div <?= Tools::flat_attr( $attr ) ?>>
			<?php if ( in_array( 'clickable-card', $cls ) ) { ?>
				<a href="<?= @$data['cta_url'] ?>" class="card-link">&nbsp;</a>
			<?php } ?>

			<?php if ( $options['card_type'] === 'product' && ! empty ( $data['img'] ) ) {?>
				<?= $data['img'] ?>
			<?php } ?>
			<div class="tile-inner">
				<?php if ( ! empty ( $data['img'] ) && $options['card_type'] !== 'product' ) { ?>
					<?= $data['img'] ?>
				<?php } ?>
				<?php if ( ! empty( $data['title_top'] ) ) { ?>
					<div class="tile-title-top"><?= $data['title_top'] ?></div>
				<?php } ?>
				<div class="tile-title"><?= $data['title'] ?></div>
				<?php if ( ! empty( $data['desc'] ) ) { ?>
					<div class="tile-desc"><?= $data['desc'] ?></div>
				<?php } ?>

				<?php if ( ! empty( $data['cta_txt'] ) ) { ?>
					<div class="tile-cta-wrap">
						<a href="<?= @$data['cta_url'] ?>" class="tile-cta stretched-link">
							<span><?= $data['cta_txt'] ?></span>
						</a>
					</div>
				<?php } ?>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * @param \WP_Post $post
	 * @param int $key
	 * @param array $options
	 *
	 * @return string
	 */
	public static function staff ( \WP_Post $post, int $key, array $options = [] ) :string {
		$_class = [ 'tile', 'staff', 'relative', ];
		$post = get_post( $post );
		$name = get_the_title( $post );
		$val = new Field_Val_Getter( Field_Group\Team_Member::class, $post );
		$pronoun = $val->get( Field_Group\Team_Member::FIELD_PRONOUN );
		$group_terms = get_the_terms( $post, CPT\Team::TAXONOMY_GROUP );
		$group = array_pop( $group_terms )->name;
		$thumbnail_variants = [
			self::_get_thumb_var( Thumbnail::TYPE_VERTICAL ),
			self::_get_thumb_var( Thumbnail::TYPE_HORIZONTAL ),
			self::_get_thumb_var( Thumbnail::TYPE_SQUARE ),
		];

		$thumbnail = Thumbnail::post( $post, ...$thumbnail_variants );
		$is_placeholder_thumbnail = false;

		$options = array_merge( array_fill_keys( [
			'placeholder_image',
			'class',
			'hidden',
		], [] ), $options );

		if ( empty( $thumbnail ) ) {
			$_class[] = 'placeholder-thumbnail';
			$placeholder_img_id = $options[ 'placeholder_image' ];
			$thumbnail = wp_get_attachment_image( $placeholder_img_id );
			$is_placeholder_thumbnail = true;
		} else {
			$_class[] = 'with-thumbnail';
		}

		// Merge all HTML classes
		$_class = array_merge( $_class, $options[ 'class' ] );

		if ( $options['hidden'] ) {
			$_class[] = 'hidden';
		}

		/**
		 * @todo: use AJAX
		 */
		$id = \uniqid( 'team-member-' );
		add_action( 'wp_footer', function () use ( $id, $post, $thumbnail, $is_placeholder_thumbnail ) {

			echo ( new \TrevorWP\Theme\Helper\Modal( CPT\Team::render_modal( $post,  compact( 'thumbnail', 'is_placeholder_thumbnail', ) ), [
					'target' => "#{$id} a",
					'id'     => "{$id}-content",
					'class'  => [ 'team' ],
			] ) )->render();
		}, 10, 0 );

		$attr          = (array) $options['attr'];
		$attr['class'] = implode( ' ', $_class );
		$attr['id'] = $id;

		$name_class = [];
		if ( strtolower( $group ) === "founder" ) {
			$name_class = explode( " ", "text-px22 leading-px28 tracking-em005 md:text-px20 md:leading-px30 xl:text-px22 xl:leading-px32" );
		} else {
			$name_class = explode( " ", "text-px18 leading-px26 tracking-em005 xl:text-px22 xl:leading-px32" );
		}

		ob_start();
	?>
		<article <?php echo Tools::flat_attr( $attr ); ?>>
			<a href="<?php echo get_permalink( $post )  ?>">
				<div class="post-thumbnail-wrap bg-gray-light">
					<?php echo $thumbnail; ?>
				</div>
				<div class="information bg-white text-teal-dark px-4 xl:px-6 pt-4 xl:pt-6 pb-px24">
					<p class="information__name font-semibold <?php echo implode( " ", $name_class ); ?> <?php echo ( strtolower( $group ) === 'founder' ) ? 'text-center' : '' ?>">
						<?php echo esc_html( $name ); ?>
					</p>
					<?php if ( ! empty ( $group ) && strtolower( $group ) !== 'founder' || ! empty ( $pronoun ) ) { ?>
						<div class="information__details text-px14 leading-px18 xl:text-px16 xl:leading-px22 mt-px10">
							<?php if ( ! empty( $group ) && strtolower( $group ) !== 'founder' ) { ?>
								<span class="information__group font-medium pr-px12"><?php echo esc_html( $group ); ?></span>
							<?php } ?>
							<?php if ( ! empty( $pronoun ) ) { ?>
								<span class="information__pronoun font-normal pl-px12"><?php echo esc_html( $pronoun ); ?></span>
							<?php } ?>
						</div>
					<?php } ?>
				</div>
			</a>
		</article>
	<?php
		return ob_get_clean();
	}

	/**
	 * @param \WP_Post $post
	 * @param int $key
	 * @param array $options
	 *
	 * @return string
	 */
	public static function financial_report ( \WP_Post $post, int $key, array $options = [] ) :string {
		$options = array_merge( array_fill_keys( [
				'class',
				'attr',
				'card_type',
				'hidden',
		], null ), $options );

		# class
		$cls = [ 'tile', 'financial-report-tile', 'relative' ];
		if ( ! empty( $options['class'] ) ) {
			$cls = array_merge( $cls, $options['class'] );
		}

		$reports = Financial_Report::get_reports($post);

		$attr          = (array) $options['attr'];
		$attr['class'] = implode( ' ', $cls );

		ob_start();
		?>
		<div <?= Tools::flat_attr( $attr ) ?>>
			<div class="tile-inner">
				<div class="tile-title"><?= $post->post_title ?></div>
				<?php if ( ! empty( $reports ) ) : ?>
					<?php foreach ( $reports as $report ): ?>
						<div class="tile-cta-wrap">
							<a href="<?= $report['url'] ?>" class="tile-cta">
								<span><?= $report['title'] ?></span>
							</a>
						</div>
					<?php endforeach; ?>
				<?php endif; ?>

			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * @param string $type
	 *
	 * @return array
	 */
	protected static function _get_thumb_var( string $type ): array {
		return Thumbnail::variant(
				Thumbnail::SCREEN_SM,
				$type,
				Thumbnail::SIZE_MD,
				[ 'class' => 'post-header-bg' ]
		);
	}
}
