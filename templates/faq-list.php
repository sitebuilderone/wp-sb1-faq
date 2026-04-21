<?php
/**
 * Default template for the [sb1_faq] shortcode.
 *
 * Available variables:
 *   $faqs       WP_Query object
 *   $faq_posts  array of WP_Post objects
 *
 * Override by placing this file at {theme}/sb1-faq/faq-list.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="sb1-faq-list">
	<?php while ( $faqs->have_posts() ) : $faqs->the_post(); ?>
		<?php $answer = get_post_meta( get_the_ID(), '_sb1_faq_answer', true ); ?>
		<div class="sb1-faq-item">
			<h3 class="sb1-faq-question"><?php the_title(); ?></h3>
			<?php if ( $answer ) : ?>
				<div class="sb1-faq-answer"><?php echo wp_kses_post( wpautop( $answer ) ); ?></div>
			<?php endif; ?>
		</div>
	<?php endwhile; ?>
</div>
