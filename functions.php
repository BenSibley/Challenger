<?php

//----------------------------------------------------------------------------------
//	Include all required files
//----------------------------------------------------------------------------------
require_once( trailingslashit( get_template_directory() ) . 'theme-options.php' );
require_once( trailingslashit( get_template_directory() ) . 'inc/customizer.php' );
require_once( trailingslashit( get_template_directory() ) . 'inc/scripts.php' );
require_once( trailingslashit( get_template_directory() ) . 'inc/review.php' );
require_once( trailingslashit( get_template_directory() ) . 'inc/user-profile.php' );

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
			'name'          => esc_html__( 'After Post Content', 'challenger' ),
			'id'            => 'after-post',
			'description'   => esc_html__( 'Widgets in this area will be shown on post pages after the content.', 'challenger' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<div class="widget-title">',
			'after_title'   => '</div>'
		) );
		register_sidebar( array(
			'name'          => esc_html__( 'After Page Content', 'challenger' ),
			'id'            => 'after-page',
			'description'   => esc_html__( 'Widgets in this area will be shown on pages after the content.', 'challenger' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<div class="widget-title">',
			'after_title'   => '</div>'
		) );
		register_sidebar( array(
			'name'          => esc_html__( 'Before Post Content', 'challenger' ),
			'id'            => 'before-post',
			'description'   => esc_html__( 'Widgets in this area will be shown on post pages before the content.', 'challenger' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<div class="widget-title">',
			'after_title'   => '</div>'
		) );
		register_sidebar( array(
			'name'          => esc_html__( 'Before Page Content', 'challenger' ),
			'id'            => 'before-page',
			'description'   => esc_html__( 'Widgets in this area will be shown on pages before the content.', 'challenger' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<div class="widget-title">',
			'after_title'   => '</div>'
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
					<p class="awaiting-moderation">
						<?php esc_html_e( 'Your comment is awaiting moderation.', 'challenger' ) ?>
					</p>
				<?php endif; ?>
				<?php comment_text(); ?>
			</div>
			<div class="comment-footer">
				<?php comment_reply_link( array_merge( $args, array(
					'reply_text' => esc_html__( 'Reply', 'challenger' ),
					'depth'      => $depth,
					'max_depth'  => $args['max_depth']
				) ) ); ?>
				<?php edit_comment_link(
					esc_html__( 'Edit', 'challenger' )
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
		if ( get_theme_mod( 'continue_reading' ) == 'no' ) {
			return $output;
		} elseif ( empty( $read_more_text ) ) {
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
			if ( get_theme_mod('comment_link') != 'no' ) {
				echo '<div class="comment-link">';
				echo '<i class="fas fa-comment"></i><a href="'. esc_url( get_permalink() ) .'#respond">'. esc_html( __('Comment on this post', 'challenger' ) ) .'</a>';
				echo '</div>';
			}
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

		if ( ! empty( $new_excerpt_length ) && $new_excerpt_length != 35 ) {
			return $new_excerpt_length;
		} elseif ( $new_excerpt_length === 0 ) {
			return 0;
		} else {
			return 35;
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
			echo wp_kses( $featured_image, array(
				'div' => array(
					'class' => array()
				),
				'a'   => array(
					'href' => array()
				),
				'img' => array(
					'src' 	 => array(),
					'srcset' => array(),
					'alt' 	 => array(),
					'id' 		 => array(),
					'class'  => array(),
					'height' => array(),
					'width'  => array(),
					'sizes'  => array()
				),
				// for Featured Videos in Challenger Pro
				'iframe' => array(
					'src' => array(),
					'id' => array(),
					'title' => array(),
					'frameborder' => array(),
					'allow' => array(),
					'allowfullscreen' => array(),
					'webkitallowfullscreen' => array(),
					'mozallowfullscreen' => array()
				)
			) );
		}
	}
}

//----------------------------------------------------------------------------------
//	Array of social media sites for icons
//----------------------------------------------------------------------------------
if ( ! function_exists( 'ct_challenger_social_array' ) ) {
	function ct_challenger_social_array() {

		$social_sites = array(
			'twitter'       => 'ct_challenger_twitter_profile',
			'facebook'      => 'ct_challenger_facebook_profile',
			'instagram'     => 'ct_challenger_instagram_profile',
			'linkedin'      => 'ct_challenger_linkedin_profile',
			'pinterest'     => 'ct_challenger_pinterest_profile',
			'youtube'       => 'ct_challenger_youtube_profile',
			'rss'           => 'ct_challenger_rss_profile',
			'email'         => 'ct_challenger_email_profile',
			'phone'         => 'ct_challenger_phone_profile',
			'email-form'    => 'ct_challenger_email_form_profile',
			'amazon'        => 'ct_challenger_amazon_profile',
			'bandcamp'      => 'ct_challenger_bandcamp_profile',
			'behance'       => 'ct_challenger_behance_profile',
			'bitbucket'     => 'ct_challenger_bitbucket_profile',
			'codepen'       => 'ct_challenger_codepen_profile',
			'delicious'     => 'ct_challenger_delicious_profile',
			'deviantart'    => 'ct_challenger_deviantart_profile',
			'digg'          => 'ct_challenger_digg_profile',
			'discord'       => 'ct_challenger_discord_profile',
			'dribbble'      => 'ct_challenger_dribbble_profile',
			'etsy'          => 'ct_challenger_etsy_profile',
			'flickr'        => 'ct_challenger_flickr_profile',
			'foursquare'    => 'ct_challenger_foursquare_profile',
			'github'        => 'ct_challenger_github_profile',
			'google-plus'   => 'ct_challenger_googleplus_profile',
			'google-wallet' => 'ct_challenger_google_wallet_profile',
			'hacker-news'   => 'ct_challenger_hacker-news_profile',
			'meetup'        => 'ct_challenger_meetup_profile',
			'ok-ru'         => 'ct_challenger_ok_ru_profile',
			'paypal'        => 'ct_challenger_paypal_profile',
			'podcast'       => 'ct_challenger_podcast_profile',
			'quora'         => 'ct_challenger_quora_profile',
			'qq'            => 'ct_challenger_qq_profile',
			'ravelry'       => 'ct_challenger_ravelry_profile',
			'reddit'        => 'ct_challenger_reddit_profile',
			'skype'         => 'ct_challenger_skype_profile',
			'slack'         => 'ct_challenger_slack_profile',
			'slideshare'    => 'ct_challenger_slideshare_profile',
			'snapchat'      => 'ct_challenger_snapchat_profile',
			'soundcloud'    => 'ct_challenger_soundcloud_profile',
			'spotify'       => 'ct_challenger_spotify_profile',
			'stack-overflow' => 'ct_challenger_stack_overflow_profile',
			'steam'         => 'ct_challenger_steam_profile',
			'stumbleupon'   => 'ct_challenger_stumbleupon_profile',
			'telegram'      => 'ct_challenger_telegram_profile',
			'tencent-weibo' => 'ct_challenger_tencent_weibo_profile',
			'tumblr'        => 'ct_challenger_tumblr_profile',
			'twitch'        => 'ct_challenger_twitch_profile',
			'vimeo'         => 'ct_challenger_vimeo_profile',
			'vine'          => 'ct_challenger_vine_profile',
			'vk'            => 'ct_challenger_vk_profile',
			'wechat'        => 'ct_challenger_wechat_profile',
			'weibo'         => 'ct_challenger_weibo_profile',
			'whatsapp'      => 'ct_challenger_whatsapp_profile',
			'xing'          => 'ct_challenger_xing_profile',
			'yahoo'         => 'ct_challenger_yahoo_profile',
			'yelp'          => 'ct_challenger_yelp_profile',
			'500px'         => 'ct_challenger_500px_profile'
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

				if ( $active_site == 'rss' ) {
					$class = 'fas fa-rss';
				} elseif ( $active_site == 'email-form' ) {
					$class = 'far fa-envelope';
				} elseif ( $active_site == 'podcast' ) {
					$class = 'fas fa-podcast';
				} elseif ( $active_site == 'ok-ru' ) {
					$class = 'fab fa-odnoklassniki';
				} elseif ( $active_site == 'wechat' ) {
					$class = 'fab fa-weixin';
				} elseif ( $active_site == 'phone' ) {
					$class = 'fas fa-phone';
				} else {
					$class = 'fab fa-' . $active_site;
				}
				if ( $source == 'header' ) {
					$url = get_theme_mod( $key );
				} elseif ( $source == 'author' ) {
					$url = get_the_author_meta( $key );
				}

				echo '<li>';
				if ( $active_site == 'email' ) { ?>
					<a class="email" target="_blank"
					   href="mailto:<?php echo antispambot( is_email( $url ) ); ?>">
						<i class="fas fa-envelope" title="<?php esc_attr_e( 'email', 'challenger' ); ?>"></i>
					</a>
				<?php } elseif ( $active_site == 'skype' ) { ?>
					<a class="<?php echo esc_attr( $active_site ); ?>" target="_blank"
					   href="<?php echo esc_url( $url, array( 'http', 'https', 'skype' ) ); ?>">
						<i class="<?php echo esc_attr( $class ); ?>"
						   title="<?php echo esc_attr( $active_site ); ?>"></i>
					</a>
				<?php } elseif ( $active_site == 'phone' ) { ?>
					<a class="<?php echo esc_attr( $active_site ); ?>" target="_blank"
							href="<?php echo esc_url( get_theme_mod( $active_site ), array( 'tel' ) ); ?>">
						<i class="<?php echo esc_attr( $class ); ?>"></i>
						<span class="screen-reader-text"><?php echo esc_html( $active_site );  ?></span>
					</a>
				<?php } else { ?>
					<a class="<?php echo esc_attr( $active_site ); ?>" target="_blank"
					   href="<?php echo esc_url( $url ); ?>">
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
			echo '<div class="sticky-status"><span>' . esc_html__( "Featured Post", "challenger" ) . '</span></div>';
		}
	}
}
add_action( 'challenger_sticky_post_status', 'ct_challenger_sticky_post_marker' );

//----------------------------------------------------------------------------------
//	Reset the Customizer options added by Challenger
//----------------------------------------------------------------------------------
if ( ! function_exists( ( 'ct_challenger_reset_customizer_options' ) ) ) {
	function ct_challenger_reset_customizer_options() {

		if ( !isset( $_POST['challenger_reset_customizer'] ) || 'challenger_reset_customizer_settings' !== $_POST['challenger_reset_customizer'] ) {
			return;
		}

		if ( ! wp_verify_nonce( wp_unslash( $_POST['challenger_reset_customizer_nonce'] ), 'challenger_reset_customizer_nonce' ) ) {
			return;
		}

		if ( ! current_user_can( 'edit_theme_options' ) ) {
			return;
		}

		$mods_array = array(
			'header_box',
			'header_box_display',
			'header_box_title',
			'header_box_title_color',
			'header_box_color',
			'header_box_button_text',
			'header_box_button_url',
			'header_box_button_target',
			'header_box_button_color',
			'header_box_button_bg_color',
			'header_box_overlay',
			'header_box_overlay_opacity',
			'header_box_image',
			'header_box_alt_logo',
			'fi_size_type',
			'fi_size',
			'full_post',
			'comment_link',
			'author_link',
			'excerpt_length',
			'read_more_text',
			'post_byline_avatar',
			'post_byline_author',
			'post_byline_date',
			'continue_reading',
			'author_box',
			'post_categories',
			'post_tags',
			'archive_header'
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
		if ( get_theme_mod( 'header_box' ) != 'no' && ct_challenger_header_box_output_rules() == true ) {
			$classes[] = 'has-header-box';
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

		echo wp_kses( $meta_elements, array(
			'meta' => array(
				'charset' 	 		=> array(),
				'name' 					=> array(),
				'content' 	 		=> array(),
				'initial-scale' => array()
			)
		) );
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
//----------------------------------------------------------------------------------
if ( ! function_exists( 'ct_challenger_sanitize_css' ) ) {
	function ct_challenger_sanitize_css( $css ) {
		$css = wp_kses( $css, '' );
		$css = str_replace( '&gt;', '>', $css );

		return $css;
	}
}

//----------------------------------------------------------------------------------
// Header box styles
//----------------------------------------------------------------------------------
function ct_challenger_output_header_styles() {
	if ( get_theme_mod( 'header_box' ) == 'no' ) return; 
	if ( ct_challenger_header_box_output_rules() == false ) return;

	$css = '';
	$header_box_image = get_theme_mod( 'header_box_image' ) ? get_theme_mod( 'header_box_image' ) : trailingslashit( get_template_directory_uri() ) . 'assets/img/header.jpg';
	$overlay_color = get_theme_mod( 'header_box_overlay' ) ? get_theme_mod( 'header_box_overlay' ) : '#05b0e7';
	$overlay_opacity = get_theme_mod( 'header_box_overlay_opacity' );
	if ( (string) $overlay_opacity === '0' ) {
		$overlay_opacity = 0;
	} else {
		$overlay_opacity = !empty( $overlay_opacity ) ? $overlay_opacity : '0.8';
	}
	$button_color = get_theme_mod( 'header_box_button_color' ) ? get_theme_mod( 'header_box_button_color' ) : '#fff';
	$button_bg_color = get_theme_mod( 'header_box_button_bg_color' ) ? get_theme_mod( 'header_box_button_bg_color' ) : '#ff9900';
	$title_color = get_theme_mod( 'header_box_title_color' ) ? get_theme_mod( 'header_box_title_color' ) : '#fff';
	$color = get_theme_mod( 'header_box_color' ) ? get_theme_mod( 'header_box_color' ) : '#fff';

	// Don't add the background image if the opacity is 1 unless in Customizer preview 
	if ( is_customize_preview() || $overlay_opacity != 1 ) {
		$css .= '.site-header { background-image: url("'. esc_url( $header_box_image ) .'"); }';
	} 

	$css .= ".site-header .overlay { 
		background: $overlay_color;
		opacity: $overlay_opacity;
	}";
	$css .= ".header-box .button { 
		color: $button_color;
		background: $button_bg_color;
	}";
	$css .= ".header-box .title { color: $title_color; }";
	$css .= ".site-title a, .tagline { color: $color; }";
	$css .= ".has-header-box .toggle-navigation svg g { fill: $color; }";
	$css .= "@media all and (min-width: 800px) {
		.social-media-icons a, #menu-primary a { color: $color; }
		.social-media-icons a, .social-media-icons a:hover { border-color: $color; }
	}";

	if ( !empty( $css ) ) {
		$css = ct_challenger_sanitize_css($css);
		wp_add_inline_style( 'ct-challenger-style', $css );
	}
}
add_action( 'wp_enqueue_scripts', 'ct_challenger_output_header_styles', 99 );

function ct_challenger_output_fi_styles() {

	$css = '';
	$fi_size_type = get_theme_mod( 'fi_size_type' );

	if ( $fi_size_type == 'no' ) {
		$css .= ".featured-image { 
			padding-bottom: 0; 
			height: auto;
		}";
		$css .= ".featured-image > a, .featured-image > a > img, .featured-image > img { 
			position: static;
		}";
	} else {
		$fi_size = get_theme_mod( 'fi_size' );
		if ( !empty($fi_size) && $fi_size != 40 ) {
			$css .= ".featured-image { padding-bottom: $fi_size%; }";
		}
	}

	if ( !empty( $css ) ) {
		$css = ct_challenger_sanitize_css($css);
		wp_add_inline_style( 'ct-challenger-style', $css );
	}
}
add_action( 'wp_enqueue_scripts', 'ct_challenger_output_fi_styles', 99 );

function ct_challenger_header_box_output_rules() {
	$display = get_theme_mod( 'header_box_display' ) ? get_theme_mod( 'header_box_display' ) : array('homepage');
	$output = false;

	if ( is_front_page() && in_array( 'homepage', $display) ) {
		$output = true;
	}
	if ( is_home() && in_array( 'blog', $display) ) {
		$output = true;
	}
	if ( is_singular('post') && in_array( 'posts', $display) ) {
		$output = true;
	}
	if ( is_singular('page') && !is_front_page() && in_array( 'pages', $display) ) {
		$output = true;
	}
	if ( is_archive() && in_array( 'archives', $display) ) {
		$output = true;
	}
	if ( is_search() && in_array( 'search', $display) ) {
		$output = true;
	}

	return $output;
}

//----------------------------------------------------------------------------------
// Allows site title to display in Customize preview when logo is removed
//----------------------------------------------------------------------------------
function ct_challenger_logo_refresh($wp_customize) {
  $wp_customize->get_setting( 'custom_logo' )->transport = 'refresh';
}
add_action( 'customize_register', 'ct_challenger_logo_refresh', 20 );