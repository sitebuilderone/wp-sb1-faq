<?php
/**
 * Default template for the [sb1_howto] shortcode.
 *
 * Available variables:
 *   $howtos       WP_Query object
 *   $howto_posts  array of WP_Post objects
 *
 * Override by placing this file at {theme}/sb1-faq/howto-list.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="sb1-howto-list">
	<?php while ( $howtos->have_posts() ) : $howtos->the_post(); ?>
		<?php
		$description = get_post_meta( get_the_ID(), '_sb1_howto_description', true );
		$total_time  = get_post_meta( get_the_ID(), '_sb1_howto_total_time', true );
		$supplies    = get_post_meta( get_the_ID(), '_sb1_howto_supplies', true );
		$steps       = get_post_meta( get_the_ID(), '_sb1_howto_steps', true );
		$steps       = is_array( $steps ) ? $steps : array();
		?>
		<article class="sb1-howto-item">
			<h3 class="sb1-howto-title"><?php the_title(); ?></h3>

			<?php if ( $description ) : ?>
				<div class="sb1-howto-description"><?php echo wp_kses_post( wpautop( $description ) ); ?></div>
			<?php endif; ?>

			<?php if ( $total_time ) : ?>
				<p class="sb1-howto-total-time">
					<strong><?php esc_html_e( 'Total time:', 'wp-sb1-faq' ); ?></strong>
					<?php echo esc_html( $total_time ); ?>
				</p>
			<?php endif; ?>

			<?php if ( $supplies ) : ?>
				<div class="sb1-howto-supplies">
					<h4><?php esc_html_e( 'Supplies', 'wp-sb1-faq' ); ?></h4>
					<ul>
						<?php foreach ( preg_split( '/\r\n|\r|\n/', $supplies ) as $supply ) : ?>
							<?php $supply = trim( $supply ); ?>
							<?php if ( $supply ) : ?>
								<li><?php echo esc_html( $supply ); ?></li>
							<?php endif; ?>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endif; ?>

			<?php if ( ! empty( $steps ) ) : ?>
				<ol class="sb1-howto-steps">
					<?php foreach ( $steps as $step ) : ?>
						<?php
						if ( ! is_array( $step ) ) {
							continue;
						}

						$step_url = ! empty( $step['url'] ) ? $step['url'] : '';
						?>
						<li class="sb1-howto-step">
							<?php if ( ! empty( $step['name'] ) ) : ?>
								<h4>
									<?php if ( $step_url ) : ?>
										<a href="<?php echo esc_url( $step_url ); ?>"><?php echo esc_html( $step['name'] ); ?></a>
									<?php else : ?>
										<?php echo esc_html( $step['name'] ); ?>
									<?php endif; ?>
								</h4>
							<?php endif; ?>

							<?php if ( ! empty( $step['text'] ) ) : ?>
								<div><?php echo wp_kses_post( wpautop( $step['text'] ) ); ?></div>
							<?php endif; ?>

							<?php if ( ! empty( $step['image'] ) ) : ?>
								<img src="<?php echo esc_url( $step['image'] ); ?>" alt="<?php echo esc_attr( ! empty( $step['name'] ) ? $step['name'] : get_the_title() ); ?>" />
							<?php endif; ?>
						</li>
					<?php endforeach; ?>
				</ol>
			<?php endif; ?>
		</article>
	<?php endwhile; ?>
</div>
