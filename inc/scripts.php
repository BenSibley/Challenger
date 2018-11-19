<?php
// Front-end scripts
function ct_challenger_load_scripts_styles() {

	wp_enqueue_style( 'ct-challenger-google-fonts', '//fonts.googleapis.com/css?family=Poppins:300,300i,700' );
	wp_enqueue_script( 'ct-challenger-js', get_template_directory_uri() . '/js/build/production.min.js', array( 'jquery' ), '', true );
	wp_localize_script( 'ct-challenger-js', 'objectL10n', array(
		'openMenu'       => esc_html__( 'open menu', 'challenger' ),
		'closeMenu'      => esc_html__( 'close menu', 'challenger' ),
		'openChildMenu'  => esc_html__( 'open dropdown menu', 'challenger' ),
		'closeChildMenu' => esc_html__( 'close dropdown menu', 'challenger' )
	) );
	wp_enqueue_style( 'ct-challenger-font-awesome', get_template_directory_uri() . '/assets/font-awesome/css/all.min.css' );
	wp_enqueue_style( 'ct-challenger-style', get_stylesheet_uri() );

	// enqueue comment-reply script only on posts & pages with comments open ( included in WP core )
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'ct_challenger_load_scripts_styles' );

// Back-end scripts
function ct_challenger_enqueue_admin_styles( $hook ) {

	if ( $hook == 'appearance_page_challenger-options' ) {
		wp_enqueue_style( 'ct-challenger-admin-styles', get_template_directory_uri() . '/styles/admin.min.css' );
	}
	if ( $hook == 'post.php' || $hook == 'post-new.php' ) {

		$font_args = array(
			'family' => urlencode( 'Poppins:300,300i,700' ),
			'subset' => urlencode( 'latin,latin-ext' )
		);
		$fonts_url = add_query_arg( $font_args, '//fonts.googleapis.com/css' );
	
		wp_enqueue_style( 'ct-challenger-google-fonts', $fonts_url );
	}
}
add_action( 'admin_enqueue_scripts', 'ct_challenger_enqueue_admin_styles' );

// Customizer scripts
function ct_challenger_enqueue_customizer_scripts() {
	wp_enqueue_style( 'ct-challenger-customizer-styles', get_template_directory_uri() . '/styles/customizer.min.css' );
	wp_enqueue_script( 'ct-challenger-customizer-js', get_template_directory_uri() . '/js/build/customizer.min.js', array( 'jquery' ), '', true );
}
add_action( 'customize_controls_enqueue_scripts', 'ct_challenger_enqueue_customizer_scripts' );

/*
 * Script for live updating with customizer options. Has to be loaded separately on customize_preview_init hook
 * transport => postMessage
 */
function ct_challenger_enqueue_customizer_post_message_scripts() {
	wp_enqueue_script( 'ct-challenger-customizer-post-message-js', get_template_directory_uri() . '/js/build/postMessage.min.js', array( 'jquery' ), '', true );

}
add_action( 'customize_preview_init', 'ct_challenger_enqueue_customizer_post_message_scripts' );