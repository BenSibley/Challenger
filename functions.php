<?php

//----------------------------------------------------------------------------------
//	Include all required files
//----------------------------------------------------------------------------------
require_once( trailingslashit( get_template_directory() ) . 'theme-options.php' );
require_once( trailingslashit( get_template_directory() ) . 'inc/customizer.php' );
require_once( trailingslashit( get_template_directory() ) . 'inc/scripts.php' );
require_once( trailingslashit( get_template_directory() ) . 'inc/review.php' );

//----------------------------------------------------------------------------------
//	Include review request
//----------------------------------------------------------------------------------
require_once( trailingslashit( get_template_directory() ) . 'dnh/handler.php' );
new WP_Review_Me( array(
		'days_after' => 14,
		'type'       => 'theme',
		'slug'       => 'challenger',
		'message'    => __( 'Hey! Sorry to interrupt, but you\'ve been using Challenger for a little while now. If you\'re happy with this theme, could you take a minute to leave a review? <i>You won\'t see this notice again after closing it.</i>', 'challenger' )
	)
);

//----------------------------------------------------------------------------------
//	Set content width variable
//----------------------------------------------------------------------------------
if ( ! function_exists( ( 'ct_challenger_set_content_width' ) ) ) {
	function ct_challenger_set_content_width() {
		if ( ! isset( $content_width ) ) {
			$content_width = 780;
		}
	}
}
add_action( 'after_setup_theme', 'ct_challenger_set_content_width', 0 );

//----------------------------------------------------------------------------------
//	Add theme support for various features, register menus, load text domain
//----------------------------------------------------------------------------------
if ( ! function_exists( ( 'ct_challenger_theme_setup' ) ) ) {
	function ct_challenger_theme_setup() {

		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption'
		) );
		add_theme_support( 'infinite-scroll', array(
			'container' => 'loop-container',
			'footer'    => 'overflow-container',
			'render'    => 'ct_challenger_infinite_scroll_render'
		) );
		add_theme_support( 'custom-logo', array(
			'height'      => 60,
			'width'       => 240,
			'flex-height' => true,
			'flex-width'  => true
		) );

		register_nav_menus( array(
			'primary' => esc_html__( 'Primary', 'challenger' )
		) );

		load_theme_textdomain( 'challenger', get_template_directory() . '/languages' );
	}
}
add_action( 'after_setup_theme', 'ct_challenger_theme_setup' );

//----------------------------------------------------------------------------------
//	Register widget areas
//----------------------------------------------------------------------------------
if ( ! function_exists( ( 'ct_challenger_register_widget_areas' ) ) ) {
	function ct_challenger_register_widget_areas() {

		register_sidebar( array(
			'name'          => esc_html__( 'Primary Sidebar', 'challenger' ),
			'id'            => 'primary',
			'description'   => esc_html__( 'Widgets in this area will be shown in the sidebar next to the main post content', 'challenger' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>'
		) );
	}
}
add_action( 'widgets_init', 'ct_challenger_register_widget_areas' );

//----------------------------------------------------------------------------------
//	Customize the comment markup
//----------------------------------------------------------------------------------
if ( ! function_exists( ( 'ct_challenger_customize_comments' ) ) ) {
	function ct_challenger_customize_comments( $comment, $args, $depth ) { ?>
		<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			<div class="comment-author">
				<?php
				echo get_avatar( get_comment_author_email(), 36, '', get_comment_author() );
				?>
				<span class="author-name"><?php comment_author_link(); ?></span>
			</div>
			<div class="comment-content">
				<?php if ( $comment->comment_approved == '0' ) : ?>
					<em><?php esc_html_e( 'Your comment is awaiting moderation.', 'challenger' ) ?></em>
					<br/>
				<?php endif; ?>
				<?php comment_text(); ?>
			</div>
			<div class="comment-footer">
				<span class="comment-date"><?php comment_date(); ?></span>
				<?php comment_reply_link( array_merge( $args, array(
					'reply_text' => esc_html__( 'Reply', 'challenger' ),
					'depth'      => $depth,
					'max_depth'  => $args['max_depth'],
					'before'     => '<i class="fa fa-reply" aria-hidden="true"></i>'
				) ) ); ?>
				<?php edit_comment_link(
					esc_html__( 'Edit', 'challenger' ),
					'<i class="fa fa-pencil" aria-hidden="true"></i>'
				); ?>
			</div>
		</article>
		<?php
	}
}

//----------------------------------------------------------------------------------
//	Remove the allowed HTML text that shows below the comment form
//----------------------------------------------------------------------------------
if ( ! function_exists( 'ct_challenger_remove_comments_notes_after' ) ) {
	function ct_challenger_remove_comments_notes_after( $defaults ) {
		$defaults['comment_notes_after'] = '';
		return $defaults;
	}
}
add_action( 'comment_form_defaults', 'ct_challenger_remove_comments_notes_after' );

//----------------------------------------------------------------------------------
//	Filter the HTML for the 'read more' links
//----------------------------------------------------------------------------------
if ( ! function_exists( 'ct_challenger_filter_read_more_link' ) ) {
	function ct_challenger_filter_read_more_link( $custom = false ) {
		global $post;
		$ismore             = strpos( $post->post_content, '<!--more-->' );
		$read_more_text     = get_theme_mod( 'read_more_text' );
		$new_excerpt_length = get_theme_mod( 'excerpt_length' );
		$excerpt_more       = ( $new_excerpt_length === 0 ) ? '' : '&#8230;';
		$output = '';

		// add ellipsis for automatic excerpts
		if ( empty( $ismore ) && $custom !== true ) {
			$output .= $excerpt_more;
		}
		// Because i18n text cannot be stored in a variable
		if ( empty( $read_more_text ) ) {
			$output .= '<div class="more-link-wrapper"><a class="more-link" href="' . esc_url( get_permalink() ) . '">' . __( 'Continue Reading', 'challenger' ) . '<span class="screen-reader-text">' . esc_html( get_the_title() ) . '</span></a></div>';
		} else {
			$output .= '<div class="more-link-wrapper"><a class="more-link" href="' . esc_url( get_permalink() ) . '">' . esc_html( $read_more_text ) . '<span class="screen-reader-text">' . esc_html( get_the_title() ) . '</span></a></div>';
		}
		return $output;
	}
}
add_filter( 'the_content_more_link', 'ct_challenger_filter_read_more_link' ); // more tags
add_filter( 'excerpt_more', 'ct_challenger_filter_read_more_link', 10 ); // automatic excerpts

//----------------------------------------------------------------------------------
//	Filtering for custom excerpts
//----------------------------------------------------------------------------------
if ( ! function_exists( 'ct_challenger_filter_manual_excerpts' ) ) {
	function ct_challenger_filter_manual_excerpts( $excerpt ) {
		$excerpt_more = '';
		if ( has_excerpt() ) {
			$excerpt_more = ct_challenger_filter_read_more_link( true );
		}
		return $excerpt . $excerpt_more;
	}
}
add_filter( 'get_the_excerpt', 'ct_challenger_filter_manual_excerpts' );

//----------------------------------------------------------------------------------
//	Output the excerpt
//----------------------------------------------------------------------------------
if ( ! function_exists( 'ct_challenger_excerpt' ) ) {
	function ct_challenger_excerpt() {
		global $post;
		$show_full_post = get_theme_mod( 'full_post' );
		$ismore         = strpos( $post->post_content, '<!--more-->' );

		if ( $show_full_post === 'yes' || $ismore ) {
			the_content();
		} else {
			the_excerpt();
		}
	}
}

//----------------------------------------------------------------------------------
//	Change the excerpt length
//----------------------------------------------------------------------------------
if ( ! function_exists( 'ct_challenger_custom_excerpt_length' ) ) {
	function ct_challenger_custom_excerpt_length( $length ) {

		$new_excerpt_length = get_theme_mod( 'excerpt_length' );

		if ( ! empty( $new_excerpt_length ) && $new_excerpt_length != 25 ) {
			return $new_excerpt_length;
		} elseif ( $new_excerpt_length === 0 ) {
			return 0;
		} else {
			return 25;
		}
	}
}
add_filter( 'excerpt_length', 'ct_challenger_custom_excerpt_length', 99 );

//----------------------------------------------------------------------------------
//	Turn off scrolling to below the excerpt from clicking "more links"
//----------------------------------------------------------------------------------
if ( ! function_exists( 'ct_challenger_remove_more_link_scroll' ) ) {
	function ct_challenger_remove_more_link_scroll( $link ) {
		$link = preg_replace( '|#more-[0-9]+|', '', $link );
		return $link;
	}
}
add_filter( 'the_content_more_link', 'ct_challenger_remove_more_link_scroll' );

//----------------------------------------------------------------------------------
//	Output the Featured Image
//----------------------------------------------------------------------------------
if ( ! function_exists( 'ct_challenger_featured_image' ) ) {
	function ct_challenger_featured_image() {

		global $post;
		$featured_image = '';

		if ( has_post_thumbnail( $post->ID ) ) {

			if ( is_singular() ) {
				$featured_image = '<div class="featured-image">' . get_the_post_thumbnail( $post->ID, 'full' ) . '</div>';
			} else {
				$featured_image = '<div class="featured-image"><a href="' . esc_url( get_permalink() ) . '">' . esc_html( get_the_title() ) . get_the_post_thumbnail( $post->ID, 'full' ) . '</a></div>';
			}
		}

		$featured_image = apply_filters( 'ct_challenger_featured_image', $featured_image );

		if ( $featured_image ) {
			echo $featured_image;
		}
	}
}

//----------------------------------------------------------------------------------
//	Array of social media sites for icons
//----------------------------------------------------------------------------------
if ( ! function_exists( 'ct_challenger_social_array' ) ) {
	function ct_challenger_social_array() {

		$social_sites = array(
			'twitter'       => 'challenger_twitter_profile',
			'facebook'      => 'challenger_facebook_profile',
			'instagram'     => 'challenger_instagram_profile',
			'linkedin'      => 'challenger_linkedin_profile',
			'pinterest'     => 'challenger_pinterest_profile',
			'google-plus'   => 'challenger_googleplus_profile',
			'youtube'       => 'challenger_youtube_profile',
			'email'         => 'challenger_email_profile',
			'email-form'    => 'challenger_email_form_profile',
			'500px'         => 'challenger_500px_profile',
			'amazon'        => 'challenger_amazon_profile',
			'bandcamp'      => 'challenger_bandcamp_profile',
			'behance'       => 'challenger_behance_profile',
			'codepen'       => 'challenger_codepen_profile',
			'delicious'     => 'challenger_delicious_profile',
			'deviantart'    => 'challenger_deviantart_profile',
			'digg'          => 'challenger_digg_profile',
			'dribbble'      => 'challenger_dribbble_profile',
			'etsy'          => 'challenger_etsy_profile',
			'flickr'        => 'challenger_flickr_profile',
			'foursquare'    => 'challenger_foursquare_profile',
			'github'        => 'challenger_github_profile',
			'google-wallet' => 'challenger_google_wallet_profile',
			'hacker-news'   => 'challenger_hacker-news_profile',
			'meetup'        => 'challenger_meetup_profile',
			'paypal'        => 'challenger_paypal_profile',
			'podcast'       => 'challenger_podcast_profile',
			'quora'         => 'challenger_quora_profile',
			'qq'            => 'challenger_qq_profile',
			'ravelry'       => 'challenger_ravelry_profile',
			'reddit'        => 'challenger_reddit_profile',
			'rss'           => 'challenger_rss_profile',
			'skype'         => 'challenger_skype_profile',
			'slack'         => 'challenger_slack_profile',
			'slideshare'    => 'challenger_slideshare_profile',
			'snapchat'      => 'challenger_snapchat_profile',
			'soundcloud'    => 'challenger_soundcloud_profile',
			'spotify'       => 'challenger_spotify_profile',
			'steam'         => 'challenger_steam_profile',
			'stumbleupon'   => 'challenger_stumbleupon_profile',
			'telegram'      => 'challenger_telegram_profile',
			'tencent-weibo' => 'challenger_tencent_weibo_profile',
			'tumblr'        => 'challenger_tumblr_profile',
			'twitch'        => 'challenger_twitch_profile',
			'vimeo'         => 'challenger_vimeo_profile',
			'vine'          => 'challenger_vine_profile',
			'vk'            => 'challenger_vk_profile',
			'wechat'        => 'challenger_wechat_profile',
			'weibo'         => 'challenger_weibo_profile',
			'whatsapp'      => 'challenger_whatsapp_profile',
			'xing'          => 'challenger_xing_profile',
			'yahoo'         => 'challenger_yahoo_profile',
			'yelp'          => 'challenger_yelp_profile'
		);

		return apply_filters( 'ct_challenger_social_array_filter', $social_sites );
	}
}

//----------------------------------------------------------------------------------
//	Output the social media icons
//----------------------------------------------------------------------------------
if ( ! function_exists( 'ct_challenger_social_icons_output' ) ) {
	function ct_challenger_social_icons_output( $source = 'header' ) {

		$social_sites = ct_challenger_social_array();

		// store the site name and url
		foreach ( $social_sites as $social_site => $profile ) {

			if ( $source == 'header' ) {
				if ( strlen( get_theme_mod( $social_site ) ) > 0 ) {
					$active_sites[ $social_site ] = $social_site;
				}
			} elseif ( $source == 'author' ) {
				if ( strlen( get_the_author_meta( $profile ) ) > 0 ) {
					$active_sites[ $profile ] = $social_site;
				}
			}
		}

		if ( ! empty( $active_sites ) ) {

			echo "<ul class='social-media-icons'>";

			foreach ( $active_sites as $key => $active_site ) {

				if ( $active_site == 'email-form' ) {
					$class = 'fa fa-envelope-o';
				} else {
					$class = 'fa fa-' . $active_site;
				}

				echo '<li>';
				if ( $active_site == 'email' ) { ?>
					<a class="email" target="_blank"
					   href="mailto:<?php echo antispambot( is_email( get_theme_mod( $key ) ) ); ?>">
						<i class="fa fa-envelope" title="<?php esc_attr_e( 'email', 'challenger' ); ?>"></i>
					</a>
				<?php } elseif ( $active_site == 'skype' ) { ?>
					<a class="<?php echo esc_attr( $active_site ); ?>" target="_blank"
					   href="<?php echo esc_url( get_theme_mod( $key ), array( 'http', 'https', 'skype' ) ); ?>">
						<i class="<?php echo esc_attr( $class ); ?>"
						   title="<?php echo esc_attr( $active_site ); ?>"></i>
					</a>
				<?php } else { ?>
					<a class="<?php echo esc_attr( $active_site ); ?>" target="_blank"
					   href="<?php echo esc_url( get_theme_mod( $key ) ); ?>">
						<i class="<?php echo esc_attr( $class ); ?>"
						   title="<?php echo esc_attr( $active_site ); ?>"></i>
					</a>
					<?php
				}
				echo '</li>';
			}
			echo "</ul>";
		}
	}
}

/*
 * WP will apply the ".menu-primary-items" class & id to the containing <div> instead of <ul>
 * making styling difficult and confusing. Using this wrapper to add a unique class to make styling easier.
 */
if ( ! function_exists( ( 'ct_challenger_wp_page_menu' ) ) ) {
	function ct_challenger_wp_page_menu() {
		wp_page_menu( array(
				"menu_class" => "menu-unset",
				"depth"      => -1
			)
		);
	}
}

//----------------------------------------------------------------------------------
//	Label sticky posts
//----------------------------------------------------------------------------------
if ( ! function_exists( ( 'ct_challenger_sticky_post_marker' ) ) ) {
	function ct_challenger_sticky_post_marker() {

		if ( is_sticky() && ! is_archive() ) {
			echo '<div class="sticky-status"><span>' . esc_html__( "Featured", "challenger" ) . '</span></div>';
		}
	}
}
add_action( 'sticky_post_status', 'ct_challenger_sticky_post_marker' );

//----------------------------------------------------------------------------------
//	Reset the Customizer options added by Challenger
//----------------------------------------------------------------------------------
if ( ! function_exists( ( 'ct_challenger_reset_customizer_options' ) ) ) {
	function ct_challenger_reset_customizer_options() {

		if ( empty( $_POST['challenger_reset_customizer'] ) || 'challenger_reset_customizer_settings' !== $_POST['challenger_reset_customizer'] ) {
			return;
		}

		if ( ! wp_verify_nonce( wp_unslash( $_POST['challenger_reset_customizer_nonce'] ), 'challenger_reset_customizer_nonce' ) ) {
			return;
		}

		if ( ! current_user_can( 'edit_theme_options' ) ) {
			return;
		}

		$mods_array = array(
			'logo_upload',
			'search_bar',
			'full_post',
			'excerpt_length',
			'read_more_text',
			'full_width_post',
			'author_byline',
			'custom_css'
		);

		$social_sites = ct_challenger_social_array();

		// add social site settings to mods array
		foreach ( $social_sites as $social_site => $value ) {
			$mods_array[] = $social_site;
		}

		$mods_array = apply_filters( 'ct_challenger_mods_to_remove', $mods_array );

		foreach ( $mods_array as $theme_mod ) {
			remove_theme_mod( $theme_mod );
		}

		$redirect = admin_url( 'themes.php?page=challenger-options' );
		$redirect = add_query_arg( 'challenger_status', 'deleted', $redirect );

		// safely redirect
		wp_safe_redirect( $redirect );
		exit;
	}
}
add_action( 'admin_init', 'ct_challenger_reset_customizer_options' );

//----------------------------------------------------------------------------------
//	Admin notice that Customizer settings have been reset
//----------------------------------------------------------------------------------
if ( ! function_exists( ( 'ct_challenger_delete_settings_notice' ) ) ) {
	function ct_challenger_delete_settings_notice() {

		if ( isset( $_GET['challenger_status'] ) ) {
			?>
			<div class="updated">
				<p><?php esc_html_e( 'Customizer settings deleted', 'challenger' ); ?>.</p>
			</div>
			<?php
		}
	}
}
add_action( 'admin_notices', 'ct_challenger_delete_settings_notice' );

//----------------------------------------------------------------------------------
//	Update body classes for styling
//----------------------------------------------------------------------------------
if ( ! function_exists( ( 'ct_challenger_body_class' ) ) ) {
	function ct_challenger_body_class( $classes ) {

		global $post;
		$full_post = get_theme_mod( 'full_post' );

		if ( $full_post == 'yes' ) {
			$classes[] = 'full-post';
		}
		if ( get_bloginfo( 'description' ) ) {
			$classes[] = 'has-tagline';
		}

		return $classes;
	}
}
add_filter( 'body_class', 'ct_challenger_body_class' );

//----------------------------------------------------------------------------------
//	Add a shared class for post divs on archive and single pages
//----------------------------------------------------------------------------------
if ( ! function_exists( ( 'ct_challenger_post_class' ) ) ) {
	function ct_challenger_post_class( $classes ) {
		$classes[] = 'entry';
		return $classes;
	}
}
add_filter( 'post_class', 'ct_challenger_post_class' );

//----------------------------------------------------------------------------------
//	Output custom SVGs
//----------------------------------------------------------------------------------
if ( ! function_exists( ( 'ct_challenger_svg_output' ) ) ) {
	function ct_challenger_svg_output( $type ) {

		$svg = '';
		if ( $type == 'toggle-navigation' ) {
			$svg = '<svg width="36px" height="24px" viewBox="0 0 36 24" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
								<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
										<g transform="translate(-320.000000, -20.000000)" fill="#000000">
												<g transform="translate(320.000000, 20.000000)">
														<rect class="top" x="0" y="20" width="36" height="4" rx="2"></rect>
														<rect class="middle" x="5" y="10" width="26" height="4" rx="2"></rect>
														<rect class="bottom" x="0" y="0" width="36" height="4" rx="2"></rect>
												</g>
										</g>
								</g>
						</svg>';
		}

		return $svg;
	}
}

//----------------------------------------------------------------------------------
//	Output important meta elements in the <head>
//----------------------------------------------------------------------------------
if ( ! function_exists( ( 'ct_challenger_add_meta_elements' ) ) ) {
	function ct_challenger_add_meta_elements() {

		$meta_elements = '';

		$meta_elements .= sprintf( '<meta charset="%s" />' . "\n", esc_attr( get_bloginfo( 'charset' ) ) );
		$meta_elements .= '<meta name="viewport" content="width=device-width, initial-scale=1" />' . "\n";

		$theme    = wp_get_theme( get_template() );
		$template = sprintf( '<meta name="template" content="%s %s" />' . "\n", esc_attr( $theme->get( 'Name' ) ), esc_attr( $theme->get( 'Version' ) ) );
		$meta_elements .= $template;

		echo $meta_elements;
	}
}
add_action( 'wp_head', 'ct_challenger_add_meta_elements', 1 );

//----------------------------------------------------------------------------------
//	Get the right template part for Jetpack's infinite scroll feature
//----------------------------------------------------------------------------------
if ( ! function_exists( ( 'ct_challenger_infinite_scroll_render' ) ) ) {
	function ct_challenger_infinite_scroll_render() {
		while ( have_posts() ) {
			the_post();
			get_template_part( 'content', 'archive' );
		}
	}
}

//----------------------------------------------------------------------------------
//	Get the right template part from the loop
//  Routing templates this way to follow DRY coding patterns
//  (using index.php file only instead of duplicating loop in page.php, post.php, etc.)
//----------------------------------------------------------------------------------
if ( ! function_exists( 'ct_challenger_get_content_template' ) ) {
	function ct_challenger_get_content_template() {

		if ( is_home() || is_archive() ) {
			get_template_part( 'content-archive', get_post_type() );
		} else {
			get_template_part( 'content', get_post_type() );
		}
	}
}

//----------------------------------------------------------------------------------
//	Allowing Skype URIs to be used for the social icon
//----------------------------------------------------------------------------------
if ( ! function_exists( 'ct_challenger_allow_skype_protocol' ) ) {
	function ct_challenger_allow_skype_protocol( $protocols ) {
		$protocols[] = 'skype';

		return $protocols;
	}
}
add_filter( 'kses_allowed_protocols' , 'ct_challenger_allow_skype_protocol' );

//----------------------------------------------------------------------------------
//  Removing label that can't be edited with the_archive_title() 
//  e.g. "Category: Business" => "Business"
//----------------------------------------------------------------------------------

if ( ! function_exists( 'ct_challenger_modify_archive_titles' ) ) {
	function ct_challenger_modify_archive_titles( $title ) {

		if ( is_category() ) {
			$title = single_cat_title( '', false );
		} elseif ( is_tag() ) {
			$title = single_tag_title( '', false );
		} elseif ( is_author() ) {
			$title = get_the_author();
		} elseif ( is_month() ) {
			$title = single_month_title( ' ' );
		}
		// is_year() and is_day() neglected b/c there is no analogous function for retrieving the page title

		return $title;
	}
}
add_filter( 'get_the_archive_title', 'ct_challenger_modify_archive_titles' );

//----------------------------------------------------------------------------------
// Sanitize CSS and convert HTML character codes back into ">" character 
// so direct descendant CSS selectors work
//----------------------------------------------------------------------------------s
if ( ! function_exists( 'ct_challenger_sanitize_css' ) ) {
	function ct_challenger_sanitize_css( $css ) {
		$css = wp_kses( $css, '' );
		$css = str_replace( '&gt;', '>', $css );

		return $css;
	}
}