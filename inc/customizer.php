<?php

/* Add customizer panels, sections, settings, and controls */
add_action( 'customize_register', 'ct_challenger_add_customizer_content' );

function ct_challenger_add_customizer_content( $wp_customize ) {

	/***** Reorder default sections *****/

	$wp_customize->get_section( 'title_tagline' )->priority = 2;

	// check if exists in case user has no pages
	if ( is_object( $wp_customize->get_section( 'static_front_page' ) ) ) {
		$wp_customize->get_section( 'static_front_page' )->priority = 5;
	}

	/***** Add PostMessage Support *****/

	$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

	/***** Custom Controls *****/

	class ct_challenger_pro_ad extends WP_Customize_Control {
		public function render_content() {
			$link = 'https://www.competethemes.com/challenger-pro/';
			echo "<a href='" . $link . "' target='_blank'><img src='" . get_template_directory_uri() . "/assets/images/challenger-pro.gif' /></a>";
			echo "<p class='bold'>" . sprintf( __('<a target="_blank" href="%1$s">%2$s Pro</a> makes advanced customization simple - and fun too!', 'challenger'), $link, wp_get_theme( get_template() ) ) . "</p>";
			echo "<p>" . sprintf( esc_html_x('%s Pro adds the following features:', 'Challenger Pro adds the following features:', 'challenger'), wp_get_theme( get_template() ) ) . "</p>";
			echo "<ul>
					<li>" . esc_html__('6 new layouts', 'challenger') . "</li>
					<li>" . esc_html__('4 post templates', 'challenger') . "</li>
					<li>" . esc_html__('61 advanced color controls', 'challenger') . "</li>
					<li>" . esc_html__('+ 5 more features', 'challenger') . "</li>
				  </ul>";
			// translators: placeholder is "Challenger"
			echo "<p class='button-wrapper'><a target=\"_blank\" class='challenger-pro-button' href='" . $link . "'>" . sprintf( esc_html_x('View %s Pro', 'View Challenger Pro', 'challenger'), wp_get_theme( get_template() ) ) . "</a></p>";
		}
	}

	/***** Challenger Pro Section *****/

	// don't add if Challenger Pro is active
	if ( !defined( 'challenger_PRO_FILE' ) ) {
		// section
		$wp_customize->add_section( 'ct_challenger_pro', array(
			'title'    => sprintf( __( '%s Pro', 'challenger' ), wp_get_theme( get_template() ) ),
			'priority' => 1
		) );
		// setting
		$wp_customize->add_setting( 'challenger_pro', array(
			'sanitize_callback' => 'absint'
		) );
		// control
		$wp_customize->add_control( new ct_challenger_pro_ad(
			$wp_customize, 'challenger_pro', array(
				'section'  => 'ct_challenger_pro',
				'settings' => 'challenger_pro'
			)
		) );
	}

	/***** Social Media Icons *****/

	// get the social sites array
	$social_sites = ct_challenger_social_array();

	// set a priority used to order the social sites
	$priority = 5;

	// section
	$wp_customize->add_section( 'ct_challenger_social_media_icons', array(
		'title'       => __( 'Social Media Icons', 'challenger' ),
		'priority'    => 10,
		'description' => __( 'Add the URL for each of your social profiles.', 'challenger' )
	) );

	// create a setting and control for each social site
	foreach ( $social_sites as $social_site => $value ) {
		// if email icon
		if ( $social_site == 'email' ) {
			// setting
			$wp_customize->add_setting( $social_site, array(
				'sanitize_callback' => 'ct_challenger_sanitize_email'
			) );
			// control
			$wp_customize->add_control( $social_site, array(
				'label'    => __( 'Email Address', 'challenger' ),
				'section'  => 'ct_challenger_social_media_icons',
				'priority' => $priority
			) );
		} else {

			$label = ucfirst( $social_site );

			if ( $social_site == 'google-plus' ) {
				$label = __('Google Plus', 'challenger');
			} elseif ( $social_site == 'rss' ) {
				$label = __('RSS', 'challenger');
			} elseif ( $social_site == 'soundcloud' ) {
				$label = __('SoundCloud', 'challenger');
			} elseif ( $social_site == 'slideshare' ) {
				$label = __('SlideShare', 'challenger');
			} elseif ( $social_site == 'codepen' ) {
				$label = __('CodePen', 'challenger');
			} elseif ( $social_site == 'stumbleupon' ) {
				$label = __('StumbleUpon', 'challenger');
			} elseif ( $social_site == 'deviantart' ) {
				$label = __('DeviantArt', 'challenger');
			} elseif ( $social_site == 'hacker-news' ) {
				$label = __('Hacker News', 'challenger');
			} elseif ( $social_site == 'whatsapp' ) {
				$label = __('WhatsApp', 'challenger');
			} elseif ( $social_site == 'qq' ) {
				$label = __('QQ', 'challenger');
			} elseif ( $social_site == 'vk' ) {
				$label = __('VK', 'challenger');
			} elseif ( $social_site == 'wechat' ) {
				$label = __('WeChat', 'challenger');
			} elseif ( $social_site == 'tencent-weibo' ) {
				$label = __('Tencent Weibo', 'challenger');
			} elseif ( $social_site == 'paypal' ) {
				$label = __('PayPal', 'challenger');
			} elseif ( $social_site == 'email-form' ) {
				$label = __('Contact Form', 'challenger');
			} elseif ( $social_site == 'google-wallet' ) {
				$label = __('Google Wallet', 'challenger');
			} elseif ( $social_site == 'ok-ru' ) {
				$label = __('OK.ru', 'challenger');
			}

			if ( $social_site == 'skype' ) {
				// setting
				$wp_customize->add_setting( $social_site, array(
					'sanitize_callback' => 'ct_challenger_sanitize_skype'
				) );
				// control
				$wp_customize->add_control( $social_site, array(
					'type'        => 'url',
					'label'       => $label,
					'description' => sprintf( __( 'Accepts Skype link protocol (<a href="%s" target="_blank">learn more</a>)', 'challenger' ), 'https://www.competethemes.com/blog/skype-links-wordpress/' ),
					'section'     => 'ct_challenger_social_media_icons',
					'priority'    => $priority
				) );
			} else {
				// setting
				$wp_customize->add_setting( $social_site, array(
					'sanitize_callback' => 'esc_url_raw'
				) );
				// control
				$wp_customize->add_control( $social_site, array(
					'type'     => 'url',
					'label'    => $label,
					'section'  => 'ct_challenger_social_media_icons',
					'priority' => $priority
				) );
			}
		}
		// increment the priority for next site
		$priority = $priority + 5;
	}

	/***** Blog *****/

	// section
	$wp_customize->add_section( 'challenger_blog', array(
		'title'    => __( 'Blog', 'challenger' ),
		'priority' => 20
	) );
	// setting
	$wp_customize->add_setting( 'full_post', array(
		'default'           => 'no',
		'sanitize_callback' => 'ct_challenger_sanitize_yes_no_settings'
	) );
	// control
	$wp_customize->add_control( 'full_post', array(
		'label'    => __( 'Show full posts on blog?', 'challenger' ),
		'section'  => 'challenger_blog',
		'settings' => 'full_post',
		'type'     => 'radio',
		'choices'  => array(
			'yes' => __( 'Yes', 'challenger' ),
			'no'  => __( 'No', 'challenger' )
		)
	) );
	// setting
	$wp_customize->add_setting( 'comment_link', array(
		'default'           => 'yes',
		'sanitize_callback' => 'ct_challenger_sanitize_yes_no_settings'
	) );
	// control
	$wp_customize->add_control( 'comment_link', array(
		'label'    => __( 'Show link to comments after each post?', 'challenger' ),
		'section'  => 'challenger_blog',
		'settings' => 'comment_link',
		'type'     => 'radio',
		'choices'  => array(
			'yes' => __( 'Yes', 'challenger' ),
			'no'  => __( 'No', 'challenger' )
		)
	) );
	// setting
	$wp_customize->add_setting( 'author_link', array(
		'default'           => 'yes',
		'sanitize_callback' => 'ct_challenger_sanitize_yes_no_settings'
	) );
	// control
	$wp_customize->add_control( 'author_link', array(
		'label'    => __( 'Link author name to post archive?', 'challenger' ),
		'section'  => 'challenger_blog',
		'settings' => 'author_link',
		'type'     => 'radio',
		'choices'  => array(
			'yes' => __( 'Yes', 'challenger' ),
			'no'  => __( 'No', 'challenger' )
		)
	) );
	// setting
	$wp_customize->add_setting( 'excerpt_length', array(
		'default'           => '45',
		'sanitize_callback' => 'absint'
	) );
	// control
	$wp_customize->add_control( 'excerpt_length', array(
		'label'    => __( 'Excerpt word count', 'challenger' ),
		'section'  => 'challenger_blog',
		'settings' => 'excerpt_length',
		'type'     => 'number'
	) );

	/***** Show/Hide *****/

	// section
	$wp_customize->add_section( 'challenger_show_hide', array(
		'title'    => __( 'Show/Hide Elements', 'challenger' ),
		'priority' => 25
	) );
	// setting
	$wp_customize->add_setting( 'post_byline_avatar', array(
		'default'           => 'yes',
		'sanitize_callback' => 'ct_challenger_sanitize_yes_no_settings'
	) );
	// control
	$wp_customize->add_control( 'post_byline_avatar', array(
		'label'    => __( 'Show author avatar in post byline?', 'challenger' ),
		'section'  => 'challenger_show_hide',
		'settings' => 'post_byline_avatar',
		'type'     => 'radio',
		'choices'  => array(
			'yes' => __( 'Yes', 'challenger' ),
			'no'  => __( 'No', 'challenger' )
		)
	) );
	// setting
	$wp_customize->add_setting( 'post_byline_author', array(
		'default'           => 'yes',
		'sanitize_callback' => 'ct_challenger_sanitize_yes_no_settings'
	) );
	// control
	$wp_customize->add_control( 'post_byline_author', array(
		'label'    => __( 'Show author name in post byline?', 'challenger' ),
		'section'  => 'challenger_show_hide',
		'settings' => 'post_byline_author',
		'type'     => 'radio',
		'choices'  => array(
			'yes' => __( 'Yes', 'challenger' ),
			'no'  => __( 'No', 'challenger' )
		)
	) );
	// setting
	$wp_customize->add_setting( 'post_byline_date', array(
		'default'           => 'yes',
		'sanitize_callback' => 'ct_challenger_sanitize_yes_no_settings'
	) );
	// control
	$wp_customize->add_control( 'post_byline_date', array(
		'label'    => __( 'Show date in post byline?', 'challenger' ),
		'section'  => 'challenger_show_hide',
		'settings' => 'post_byline_date',
		'type'     => 'radio',
		'choices'  => array(
			'yes' => __( 'Yes', 'challenger' ),
			'no'  => __( 'No', 'challenger' )
		)
	) );
	// setting
	$wp_customize->add_setting( 'author_box', array(
		'default'           => 'yes',
		'sanitize_callback' => 'ct_challenger_sanitize_yes_no_settings'
	) );
	// control
	$wp_customize->add_control( 'author_box', array(
		'label'    => __( 'Show author box after posts?', 'challenger' ),
		'section'  => 'challenger_show_hide',
		'settings' => 'author_box',
		'type'     => 'radio',
		'choices'  => array(
			'yes' => __( 'Yes', 'challenger' ),
			'no'  => __( 'No', 'challenger' )
		)
	) );
	// setting
	$wp_customize->add_setting( 'post_categories', array(
		'default'           => 'yes',
		'sanitize_callback' => 'ct_challenger_sanitize_yes_no_settings'
	) );
	// control
	$wp_customize->add_control( 'post_categories', array(
		'label'    => __( 'Show categories after the post?', 'challenger' ),
		'section'  => 'challenger_show_hide',
		'settings' => 'post_categories',
		'type'     => 'radio',
		'choices'  => array(
			'yes' => __( 'Yes', 'challenger' ),
			'no'  => __( 'No', 'challenger' )
		)
	) );
	// setting
	$wp_customize->add_setting( 'post_tags', array(
		'default'           => 'yes',
		'sanitize_callback' => 'ct_challenger_sanitize_yes_no_settings'
	) );
	// control
	$wp_customize->add_control( 'post_tags', array(
		'label'    => __( 'Show tags after the post?', 'challenger' ),
		'section'  => 'challenger_show_hide',
		'settings' => 'post_tags',
		'type'     => 'radio',
		'choices'  => array(
			'yes' => __( 'Yes', 'challenger' ),
			'no'  => __( 'No', 'challenger' )
		)
	) );
	// setting
	$wp_customize->add_setting( 'archive_header', array(
		'default'           => 'yes',
		'sanitize_callback' => 'ct_challenger_sanitize_yes_no_settings'
	) );
	// control
	$wp_customize->add_control( 'archive_header', array(
		'label'    => __( 'Show archive page titles?', 'challenger' ),
		'section'  => 'challenger_show_hide',
		'settings' => 'archive_header',
		'type'     => 'radio',
		'choices'  => array(
			'yes' => __( 'Yes', 'challenger' ),
			'no'  => __( 'No', 'challenger' )
		)
	) );
}
/***** Custom Sanitization Functions *****/

function ct_challenger_sanitize_email( $input ) {
	return sanitize_email( $input );
}

// sanitize yes/no settings
function ct_challenger_sanitize_yes_no_settings( $input ) {

	$valid = array(
		'yes' => __( 'Yes', 'challenger' ),
		'no'  => __( 'No', 'challenger' )
	);

	return array_key_exists( $input, $valid ) ? $input : '';
}

function ct_challenger_sanitize_text( $input ) {
	return wp_kses_post( force_balance_tags( $input ) );
}

function ct_challenger_sanitize_skype( $input ) {
	return esc_url_raw( $input, array( 'http', 'https', 'skype' ) );
}