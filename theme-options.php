<?php

function ct_challenger_register_theme_page() {
	// translators: placeholder is the name of the theme (Challenger)
	add_theme_page( sprintf( esc_html__( '%s Dashboard', 'challenger' ), wp_get_theme( get_template() ) ), wp_get_theme( get_template() ), 'edit_theme_options', 'challenger-options', 'ct_challenger_options_content', 'ct_challenger_options_content' );
}
add_action( 'admin_menu', 'ct_challenger_register_theme_page' );

function ct_challenger_options_content() {

	$customizer_url = add_query_arg(
		array(
			'url'    => get_home_url(),
			'return' => add_query_arg( 'page', 'challenger-options', admin_url( 'themes.php' ) )
		),
		admin_url( 'customize.php' )
	);
	$support_url = 'https://www.competethemes.com/documentation/challenger-support-center/';
	?>
	<div id="challenger-dashboard-wrap" class="wrap">
		<h2><?php 
			// translators: placeholder is the name of the theme (Challenger)
			printf( esc_html__( '%s Dashboard', 'challenger' ), wp_get_theme( get_template() ) ); ?>
		</h2>
		<?php do_action( 'ct_challenger_theme_options_before' ); ?>
		<div class="content-boxes">
			<div class="content content-support">
				<h3><?php esc_html_e( 'Get Started', 'challenger' ); ?></h3>
				<p><?php 
					// translators: placeholder is the name of the theme (Challenger)
					printf( esc_html__( 'Not sure where to start? The %1$s Support Center is filled with tutorials that will take you step-by-step through every feature in %1$s.', 'challenger' ), wp_get_theme( get_template() ) ); ?></p>
				<p>
					<a target="_blank" class="button-primary"
					   href="https://www.competethemes.com/documentation/challenger-support-center/"><?php esc_html_e( 'Visit Support Center', 'challenger' ); ?></a>
				</p>
			</div>
			<?php if ( !defined( 'CHALLENGER_PRO_FILE' ) ) : ?>
				<div class="content content-premium-upgrade">
					<h3><?php printf( esc_html__( 'Challenger Pro', 'challenger' ), wp_get_theme( get_template() ) ); ?></h3>
					<p><?php 
						// translators: placeholder is the name of the theme (Challenger)
						printf( esc_html__( 'Download the %s Pro plugin and unlock 8 new layouts, 700+ of fonts, Featured Videos, and more.', 'challenger' ), wp_get_theme( get_template() ) ); ?>
					</p>
					<p>
						<a target="_blank" class="button-primary"
						   href="https://www.competethemes.com/challenger-pro/"><?php esc_html_e( 'See Full Feature List', 'challenger' ); ?></a>
					</p>
				</div>
			<?php endif; ?>
			<div class="content content-review">
				<h3><?php esc_html_e( 'Leave a Review', 'challenger' ); ?></h3>
				<p><?php 
					// translators: placeholder is the name of the theme (Challenger)
					printf( esc_html__( 'Help others find %s by leaving a review on wordpress.org.', 'challenger' ), wp_get_theme( get_template() ) ); ?>
				</p>
				<a target="_blank" class="button-primary" href="https://wordpress.org/support/theme/challenger/reviews/"><?php esc_html_e( 'Leave a Review', 'challenger' ); ?></a>
			</div>
			<div class="content content-delete-settings">
				<h3><?php esc_html_e( 'Reset Customizer Settings', 'challenger' ); ?></h3>
				<p>
					<?php // translators: placeholder is the name of the theme (Challenger)
					printf( __( '<strong>Warning:</strong> Clicking this button will erase the %2$s theme\'s current settings in the <a href="%1$s">Customizer</a>.', 'challenger' ), esc_url( $customizer_url ), wp_get_theme( get_template() ) ); ?>
				</p>
				<form method="post">
					<input type="hidden" name="challenger_reset_customizer" value="challenger_reset_customizer_settings"/>
					<p>
						<?php wp_nonce_field( 'challenger_reset_customizer_nonce', 'challenger_reset_customizer_nonce' ); ?>
						<?php submit_button( esc_html__( 'Reset Customizer Settings', 'challenger' ), 'delete', 'delete', false ); ?>
					</p>
				</form>
			</div>
		</div>
		<?php do_action( 'ct_challenger_theme_options_after' ); ?>
	</div>
<?php }