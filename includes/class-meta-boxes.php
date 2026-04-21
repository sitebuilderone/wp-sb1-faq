<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SB1_FAQ_Meta_Boxes {

	public static function add() {
		add_meta_box(
			'sb1-faq-meta',
			__( 'FAQ Details', 'wp-sb1-faq' ),
			array( __CLASS__, 'render' ),
			'faq',
			'normal',
			'high'
		);
	}

	public static function render( $post ) {
		wp_nonce_field( 'sb1_faq_meta_save', 'sb1_faq_meta_nonce' );

		$answer          = get_post_meta( $post->ID, '_sb1_faq_answer', true );
		$related_service = get_post_meta( $post->ID, '_sb1_faq_related_service', true );
		$services        = post_type_exists( 'service' ) ? get_posts( array(
			'post_type'      => 'service',
			'numberposts'    => -1,
			'orderby'        => 'title',
			'order'          => 'ASC',
			'post_status'    => 'publish',
		) ) : array();
		?>
		<div id="sb1-faq-meta">
			<div class="sb1-meta-field">
				<label for="sb1_faq_answer"><?php esc_html_e( 'Answer', 'wp-sb1-faq' ); ?></label>
				<textarea id="sb1_faq_answer" name="sb1_faq_answer" rows="4"><?php echo esc_textarea( $answer ); ?></textarea>
				<p class="description"><?php esc_html_e( 'Plain-text answer used in shortcode output and schema markup.', 'wp-sb1-faq' ); ?></p>
			</div>

			<div class="sb1-meta-field">
				<label for="sb1_faq_related_service"><?php esc_html_e( 'Related Service', 'wp-sb1-faq' ); ?></label>
				<?php if ( empty( $services ) ) : ?>
					<p class="description"><?php esc_html_e( 'No services found. Activate the WP SB1 Services plugin and add services to link them here.', 'wp-sb1-faq' ); ?></p>
				<?php else : ?>
					<select id="sb1_faq_related_service" name="sb1_faq_related_service">
						<option value=""><?php esc_html_e( '— Standalone (no service) —', 'wp-sb1-faq' ); ?></option>
						<?php foreach ( $services as $service ) : ?>
							<option value="<?php echo esc_attr( $service->ID ); ?>" <?php selected( $related_service, $service->ID ); ?>>
								<?php echo esc_html( $service->post_title ); ?>
							</option>
						<?php endforeach; ?>
					</select>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}

	public static function save( $post_id ) {
		if ( ! isset( $_POST['sb1_faq_meta_nonce'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['sb1_faq_meta_nonce'], 'sb1_faq_meta_save' ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		if ( isset( $_POST['sb1_faq_answer'] ) ) {
			update_post_meta( $post_id, '_sb1_faq_answer', sanitize_textarea_field( $_POST['sb1_faq_answer'] ) );
		}

		if ( isset( $_POST['sb1_faq_related_service'] ) ) {
			$service_id = absint( $_POST['sb1_faq_related_service'] );
			if ( $service_id ) {
				update_post_meta( $post_id, '_sb1_faq_related_service', $service_id );
			} else {
				delete_post_meta( $post_id, '_sb1_faq_related_service' );
			}
		}
	}

	public static function enqueue_styles( $hook ) {
		global $post;

		if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
			return;
		}

		if ( ! $post || 'faq' !== $post->post_type ) {
			return;
		}

		wp_enqueue_style(
			'sb1-faq-admin',
			SB1_FAQ_URL . 'assets/css/admin.css',
			array(),
			SB1_FAQ_VERSION
		);
	}
}
