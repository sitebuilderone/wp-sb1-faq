<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SB1_FAQ_HowTo_Meta_Boxes {

	const MAX_STEPS = 10;

	public static function add() {
		add_meta_box(
			'sb1-howto-meta',
			__( 'HowTo Details', 'wp-sb1-faq' ),
			array( __CLASS__, 'render' ),
			'howto',
			'normal',
			'high'
		);
	}

	public static function render( $post ) {
		wp_nonce_field( 'sb1_howto_meta_save', 'sb1_howto_meta_nonce' );

		$description = get_post_meta( $post->ID, '_sb1_howto_description', true );
		$total_time  = get_post_meta( $post->ID, '_sb1_howto_total_time', true );
		$supplies    = get_post_meta( $post->ID, '_sb1_howto_supplies', true );
		$steps       = get_post_meta( $post->ID, '_sb1_howto_steps', true );
		$steps       = is_array( $steps ) ? $steps : array();
		?>
		<div id="sb1-howto-meta">
			<div class="sb1-meta-field">
				<label for="sb1_howto_description"><?php esc_html_e( 'Description', 'wp-sb1-faq' ); ?></label>
				<textarea id="sb1_howto_description" name="sb1_howto_description" rows="3"><?php echo esc_textarea( $description ); ?></textarea>
				<p class="description"><?php esc_html_e( 'Short summary used in HowTo schema. If empty, the post excerpt is used.', 'wp-sb1-faq' ); ?></p>
			</div>

			<div class="sb1-meta-field">
				<label for="sb1_howto_total_time"><?php esc_html_e( 'Total Time', 'wp-sb1-faq' ); ?></label>
				<input id="sb1_howto_total_time" name="sb1_howto_total_time" type="text" value="<?php echo esc_attr( $total_time ); ?>" placeholder="PT10M" />
				<p class="description"><?php esc_html_e( 'Use ISO 8601 duration format, for example PT10M, PT1H, or PT1H30M.', 'wp-sb1-faq' ); ?></p>
			</div>

			<div class="sb1-meta-field">
				<label for="sb1_howto_supplies"><?php esc_html_e( 'Supplies', 'wp-sb1-faq' ); ?></label>
				<textarea id="sb1_howto_supplies" name="sb1_howto_supplies" rows="4"><?php echo esc_textarea( $supplies ); ?></textarea>
				<p class="description"><?php esc_html_e( 'Enter one supply per line.', 'wp-sb1-faq' ); ?></p>
			</div>

			<div class="sb1-meta-field">
				<h3><?php esc_html_e( 'Steps', 'wp-sb1-faq' ); ?></h3>
				<p class="description"><?php esc_html_e( 'Fill in as many steps as needed. Each step needs at least a name or instructions.', 'wp-sb1-faq' ); ?></p>

				<?php for ( $index = 0; $index < self::MAX_STEPS; $index++ ) : ?>
					<?php $step = isset( $steps[ $index ] ) && is_array( $steps[ $index ] ) ? $steps[ $index ] : array(); ?>
					<div class="sb1-howto-step">
						<h4><?php echo esc_html( sprintf( __( 'Step %d', 'wp-sb1-faq' ), $index + 1 ) ); ?></h4>

						<label for="sb1_howto_steps_<?php echo esc_attr( $index ); ?>_name"><?php esc_html_e( 'Step Name', 'wp-sb1-faq' ); ?></label>
						<input id="sb1_howto_steps_<?php echo esc_attr( $index ); ?>_name" name="sb1_howto_steps[<?php echo esc_attr( $index ); ?>][name]" type="text" value="<?php echo esc_attr( isset( $step['name'] ) ? $step['name'] : '' ); ?>" />

						<label for="sb1_howto_steps_<?php echo esc_attr( $index ); ?>_text"><?php esc_html_e( 'Instructions', 'wp-sb1-faq' ); ?></label>
						<textarea id="sb1_howto_steps_<?php echo esc_attr( $index ); ?>_text" name="sb1_howto_steps[<?php echo esc_attr( $index ); ?>][text]" rows="3"><?php echo esc_textarea( isset( $step['text'] ) ? $step['text'] : '' ); ?></textarea>

						<label for="sb1_howto_steps_<?php echo esc_attr( $index ); ?>_url"><?php esc_html_e( 'Step URL', 'wp-sb1-faq' ); ?></label>
						<input id="sb1_howto_steps_<?php echo esc_attr( $index ); ?>_url" name="sb1_howto_steps[<?php echo esc_attr( $index ); ?>][url]" type="url" value="<?php echo esc_url( isset( $step['url'] ) ? $step['url'] : '' ); ?>" />

						<label for="sb1_howto_steps_<?php echo esc_attr( $index ); ?>_image"><?php esc_html_e( 'Step Image URL', 'wp-sb1-faq' ); ?></label>
						<input id="sb1_howto_steps_<?php echo esc_attr( $index ); ?>_image" name="sb1_howto_steps[<?php echo esc_attr( $index ); ?>][image]" type="url" value="<?php echo esc_url( isset( $step['image'] ) ? $step['image'] : '' ); ?>" />
					</div>
				<?php endfor; ?>
			</div>
		</div>
		<?php
	}

	public static function save( $post_id ) {
		if ( ! isset( $_POST['sb1_howto_meta_nonce'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['sb1_howto_meta_nonce'], 'sb1_howto_meta_save' ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		update_post_meta( $post_id, '_sb1_howto_description', isset( $_POST['sb1_howto_description'] ) ? sanitize_textarea_field( $_POST['sb1_howto_description'] ) : '' );
		update_post_meta( $post_id, '_sb1_howto_total_time', isset( $_POST['sb1_howto_total_time'] ) ? sanitize_text_field( $_POST['sb1_howto_total_time'] ) : '' );
		update_post_meta( $post_id, '_sb1_howto_supplies', isset( $_POST['sb1_howto_supplies'] ) ? sanitize_textarea_field( $_POST['sb1_howto_supplies'] ) : '' );

		$steps = array();

		if ( isset( $_POST['sb1_howto_steps'] ) && is_array( $_POST['sb1_howto_steps'] ) ) {
			foreach ( array_slice( $_POST['sb1_howto_steps'], 0, self::MAX_STEPS ) as $step ) {
				if ( ! is_array( $step ) ) {
					continue;
				}

				$clean_step = array(
					'name'  => isset( $step['name'] ) ? sanitize_text_field( $step['name'] ) : '',
					'text'  => isset( $step['text'] ) ? sanitize_textarea_field( $step['text'] ) : '',
					'url'   => isset( $step['url'] ) ? esc_url_raw( $step['url'] ) : '',
					'image' => isset( $step['image'] ) ? esc_url_raw( $step['image'] ) : '',
				);

				if ( $clean_step['name'] || $clean_step['text'] ) {
					$steps[] = $clean_step;
				}
			}
		}

		if ( empty( $steps ) ) {
			delete_post_meta( $post_id, '_sb1_howto_steps' );
		} else {
			update_post_meta( $post_id, '_sb1_howto_steps', $steps );
		}
	}

	public static function enqueue_styles( $hook ) {
		global $post;

		if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
			return;
		}

		if ( ! $post || 'howto' !== $post->post_type ) {
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
