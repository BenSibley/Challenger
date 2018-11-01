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
			echo "<a href='" . $link . "' target='_blank'><img src='" . get_template_directory_uri() . "/assets/img/challenger-pro.gif' /></a>";
			// translators: placeholder is the name of the theme (Challenger)
			echo "<p class='bold'>" . sprintf( __('<a target="_blank" href="%1$s">%2$s Pro</a> makes advanced customization simple - and fun too!', 'challenger'), $link, wp_get_theme( get_template() ) ) . "</p>";
			// translators: placeholder is the name of the theme (Challenger)
			echo "<p>" . sprintf( esc_html_x('%s Pro adds the following features:', 'Challenger Pro adds the following features:', 'challenger'), wp_get_theme( get_template() ) ) . "</p>";
			echo "<ul>
					<li>" . esc_html__('8 new layouts', 'challenger') . "</li>
					<li>" . esc_html__('700+ fonts', 'challenger') . "</li>
					<li>" . esc_html__('Featured Videos', 'challenger') . "</li>
					<li>" . esc_html__('+ 4 more features', 'challenger') . "</li>
				  </ul>";
			// translators: placeholder is the name of the theme (Challenger)
			echo "<p class='button-wrapper'><a target=\"_blank\" class='challenger-pro-button' href='" . $link . "'>" . sprintf( esc_html_x('View %s Pro', 'View Challenger Pro', 'challenger'), wp_get_theme( get_template() ) ) . "</a></p>";
		}
	}

	class CT_Challenger_Control_Checkbox_Multiple extends WP_Customize_Control {

		public $type = 'checkbox-multiple';
		
    public function render_content() {
        if ( empty( $this->choices ) ) {
					return;
				}
				if ( !empty( $this->label ) ) : ?>
					<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
        <?php endif; ?>
        <?php if ( !empty( $this->description ) ) : ?>
					<span class="description customize-control-description"><?php echo $this->description; ?></span>
        <?php endif; ?>
        <?php $multi_values = !is_array( $this->value() ) ? explode( ',', $this->value() ) : $this->value(); ?>
        <ul>
					<?php foreach ( $this->choices as $value => $label ) : ?>
						<li>
							<label>
								<input type="checkbox" value="<?php echo esc_attr( $value ); ?>" <?php checked( in_array( $value, $multi_values ) ); ?> /> 
								<?php echo esc_html( $label ); ?>
							</label>
						</li>
					<?php endforeach; ?>
        </ul>
        <input type="hidden" <?php $this->link(); ?> value="<?php echo esc_attr( implode( ',', $multi_values ) ); ?>" />
    <?php }
	}

	/***** Challenger Pro Section *****/

	// don't add if Challenger Pro is active
	if ( !defined( 'CHALLENGER_PRO_FILE' ) ) {
		// section
		$wp_customize->add_section( 'ct_challenger_pro', array(
			// translators: placeholder is the name of the theme (Challenger)
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

	/***** Header *****/

	// section
	$wp_customize->add_section( 'challenger_header', array(
		'title'    => __( 'Header', 'challenger' ),
		'priority' => 7
	) );
	// setting
	$wp_customize->add_setting( 'header_box', array(
		'default'           => 'yes',
		'sanitize_callback' => 'ct_challenger_sanitize_yes_no_settings'
	) );
	// control
	$wp_customize->add_control( 'header_box', array(
		'label'    => __( 'Show the lead generation header?', 'challenger' ),
		'section'  => 'challenger_header',
		'settings' => 'header_box',
		'type'     => 'radio',
		'choices'  => array(
			'yes' => __( 'Yes', 'challenger' ),
			'no'  => __( 'No', 'challenger' )
		)
	) );
	// setting
	$wp_customize->add_setting( 'header_box_display', array(
		'default'           => array('homepage'),
		'sanitize_callback' => 'ct_challenger_sanitize_header_box_display'
	) );
	// control
	$wp_customize->add_control(
		new CT_Challenger_Control_Checkbox_Multiple( 
			$wp_customize, 'header_box_display', array(
			'label'    => __( 'Which pages should it display on?', 'challenger' ),
			'section'  => 'challenger_header',
			'settings' => 'header_box_display',
			'choices'  => array(
				'homepage' => __( 'Homepage', 'challenger' ),
				'blog'  	 => __( 'Blog', 'challenger' ),
				'posts'  	 => __( 'Posts', 'challenger' ),
				'pages'  	 => __( 'Pages', 'challenger' ),
				'archives' => __( 'Archives', 'challenger' ),
				'search'   => __( 'Search results', 'challenger' )
			) )
	) );
	// setting
	$wp_customize->add_setting( 'header_box_title', array(
		'default'           => __('Become a professional blogger with our FREE 5-day email course', 'challenger'),
		'sanitize_callback' => 'ct_challenger_sanitize_text',
		'transport'					=> 'postMessage'
	) );
	// control
	$wp_customize->add_control( 'header_box_title', array(
		'label'    => __( 'Title text', 'challenger' ),
		'section'  => 'challenger_header',
		'settings' => 'header_box_title',
		'type'     => 'textarea'
	) );
	// setting
	$wp_customize->add_setting( 'header_box_title_color', array(
		'default' 					=> '#fff',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'					=> 'postMessage'
	) );
	// control
	$wp_customize->add_control( new WP_Customize_Color_Control(
		$wp_customize, 'header_box_title_color', array(
			'label'    => __( 'Title text color', 'challenger' ),
			'section'  => 'challenger_header',
			'settings' => 'header_box_title_color'
		)
	) );
	// setting
	$wp_customize->add_setting( 'header_box_color', array(
		'default' => '#fff',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'					=> 'postMessage'
	) );
	// control
	$wp_customize->add_control( new WP_Customize_Color_Control(
		$wp_customize, 'header_box_color', array(
			'label'    => __( 'Site title, tagline, and menu color', 'challenger' ),
			'section'  => 'challenger_header',
			'settings' => 'header_box_color'
		)
	) );
	// setting
	$wp_customize->add_setting( 'header_box_button_text', array(
		'default'           => __('Signup Now', 'challenger'),
		'sanitize_callback' => 'ct_challenger_sanitize_text',
		'transport'					=> 'postMessage'
	) );
	// control
	$wp_customize->add_control( 'header_box_button_text', array(
		'label'    => __( 'Button text', 'challenger' ),
		'section'  => 'challenger_header',
		'settings' => 'header_box_button_text',
		'type'     => 'text'
	) );
	// setting
	$wp_customize->add_setting( 'header_box_button_url', array(
		'default'           => '#',
		'sanitize_callback' => 'ct_challenger_sanitize_text',
		'transport'					=> 'postMessage'
	) );
	// control
	$wp_customize->add_control( 'header_box_button_url', array(
		'label'    => __( 'Button URL', 'challenger' ),
		'section'  => 'challenger_header',
		'settings' => 'header_box_button_url',
		'type'     => 'text'
	) );
	// setting
	$wp_customize->add_setting( 'header_box_button_target', array(
		'default'           => 'no',
		'sanitize_callback' => 'ct_challenger_sanitize_yes_no_settings'
	) );
	// control
	$wp_customize->add_control( 'header_box_button_target', array(
		'label'    => __( 'Open the button link in a new tab?', 'challenger' ),
		'section'  => 'challenger_header',
		'settings' => 'header_box_button_target',
		'type'     => 'radio',
		'choices'  => array(
			'yes' => __( 'Yes', 'challenger' ),
			'no'  => __( 'No', 'challenger' )
		)
	) );
	// setting
	$wp_customize->add_setting( 'header_box_button_color', array(
		'default' 					=> '#fff',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'					=> 'postMessage'
	) );
	// control
	$wp_customize->add_control( new WP_Customize_Color_Control(
		$wp_customize, 'header_box_button_color', array(
			'label'    => __( 'Button text color', 'challenger' ),
			'section'  => 'challenger_header',
			'settings' => 'header_box_button_color'
		)
	) );
	// setting
	$wp_customize->add_setting( 'header_box_button_bg_color', array(
		'default' 					=> '#ff9900',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'					=> 'postMessage'
	) );
	// control
	$wp_customize->add_control( new WP_Customize_Color_Control(
		$wp_customize, 'header_box_button_bg_color', array(
			'label'    => __( 'Button background color', 'challenger' ),
			'section'  => 'challenger_header',
			'settings' => 'header_box_button_bg_color'
		)
	) );
	// setting
	$wp_customize->add_setting( 'header_box_overlay', array(
		'default' 					=> '#05b0e7',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'					=> 'postMessage'
	) );
	// control
	$wp_customize->add_control( new WP_Customize_Color_Control(
		$wp_customize, 'header_box_overlay', array(
			'label'    => __( 'Background overlay color', 'challenger' ),
			'section'  => 'challenger_header',
			'settings' => 'header_box_overlay'
		)
	) );
	// setting
	$wp_customize->add_setting( 'header_box_overlay_opacity', array(
		'default' 					=> 0.8,
		'sanitize_callback' => 'ct_challenger_sanitize_header_box_overlay_opacity',
		'transport'					=> 'postMessage'
	) );
	// control
	$wp_customize->add_control( 'header_box_overlay_opacity', array(
		'label'    => __( 'Overlay opacity', 'challenger' ),
		'section'  => 'challenger_header',
		'settings' => 'header_box_overlay_opacity',
		'type'     => 'range',
		'input_attrs' => array(
			'min'  => 0,
			'max'  => 1,
			'step' => 0.01
		)
	) );
	// setting
	$wp_customize->add_setting( 'header_box_image', array(
		'sanitize_callback' => 'esc_url_raw'
	) );
	// control
	$wp_customize->add_control( new WP_Customize_Image_Control(
		$wp_customize, 'header_box_image', array(
			'label'    		=> __( 'Background image', 'challenger' ),
			'description' => __( 'Use an image that is 2,000px wide for best results.', 'challenger' ),
			'section'  		=> 'challenger_header',
			'settings' 		=> 'header_box_image'
		)
	) );	
	// setting
	$wp_customize->add_setting( 'header_box_alt_logo', array(
		'sanitize_callback' => 'esc_url_raw'
	) );
	// control
	$wp_customize->add_control( new WP_Customize_Image_Control(
		$wp_customize, 'header_box_alt_logo', array(
			'label'    		=> __( 'Alternate logo', 'challenger' ),
			'description' => __( 'Upload a light variation of your logo to better match the background overlay.', 'challenger' ),
			'section'  		=> 'challenger_header',
			'settings' 		=> 'header_box_alt_logo'
		)
	) );	

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
		} else if ( $social_site == 'phone' ) {
			// setting
			$wp_customize->add_setting( $social_site, array(
				'sanitize_callback' => 'ct_challenger_sanitize_phone'
			) );
			// control
			$wp_customize->add_control( $social_site, array(
				'label'    => __( 'Phone', 'challenger' ),
				'section'     => 'ct_challenger_social_media_icons',
				'priority'    => $priority,
				'type'        => 'text'
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
			} elseif ( $social_site == 'stack-overflow' ) {
				$label = __('Stack Overflow', 'challenger');
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
					// translators: placeholder is a URL
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

	/***** Featured Image Size *****/

	// section
	$wp_customize->add_section( 'challenger_fi_size', array(
		'title'    => __( 'Featured Image Size', 'challenger' ),
		'priority' => 15
	) );
	// setting
	$wp_customize->add_setting( 'fi_size_type', array(
		'default'           => 'yes',
		'sanitize_callback' => 'ct_challenger_sanitize_fi_size_type'
	) );
	// control
	$wp_customize->add_control( 'fi_size_type', array(
		'label'    => __( 'Lock Featured Image aspect ratio?', 'challenger' ),
		'section'  => 'challenger_fi_size',
		'settings' => 'fi_size_type',
		'type'     => 'radio',
		'choices'  => array(
			'yes' => __( 'Yes, use the same aspect ratio for all Featured Images', 'challenger' ),
			'no'  => __( 'No, use the natural aspect ratio of each image', 'challenger' )
		)
	) );
	// setting
	$wp_customize->add_setting( 'fi_size', array(
		'default'           => '40',
		'sanitize_callback' => 'absint',
		'transport'					=> 'postMessage'
	) );
	// control
	$wp_customize->add_control( 'fi_size', array(
		'label'    => __( 'Featured Image Aspect Ratio', 'challenger' ),
		'section'  => 'challenger_fi_size',
		'settings' => 'fi_size',
		'type'     => 'range',
		'input_attrs' => array(
			'min'  => 15,
			'max'  => 80, 
			'step' => 1
		)
	) );

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
		'default'           => '35',
		'sanitize_callback' => 'absint'
	) );
	// control
	$wp_customize->add_control( 'excerpt_length', array(
		'label'    => __( 'Excerpt word count', 'challenger' ),
		'section'  => 'challenger_blog',
		'settings' => 'excerpt_length',
		'type'     => 'number'
	) );
	// Read More text - setting
	$wp_customize->add_setting( 'read_more_text', array(
		'default'           => __( 'Continue reading', 'challenger' ),
		'sanitize_callback' => 'ct_challenger_sanitize_text',
		'transport'					=> 'postMessage'
	) );
	// Read More text - control
	$wp_customize->add_control( 'read_more_text', array(
		'label'    => __( 'Read More button text', 'challenger' ),
		'section'  => 'challenger_blog',
		'settings' => 'read_more_text',
		'type'     => 'text'
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
	$wp_customize->add_setting( 'continue_reading', array(
		'default'           => 'yes',
		'sanitize_callback' => 'ct_challenger_sanitize_yes_no_settings'
	) );
	// control
	$wp_customize->add_control( 'continue_reading', array(
		'label'    => __( 'Show "Continue Reading" button after posts?', 'challenger' ),
		'section'  => 'challenger_show_hide',
		'settings' => 'continue_reading',
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

function ct_challenger_sanitize_header_box_display( $values ) {

	$multi_values = !is_array( $values ) ? explode( ',', $values ) : $values;

	return !empty( $multi_values ) ? array_map( 'sanitize_text_field', $multi_values ) : array();
}

function ct_challenger_sanitize_header_box_overlay_opacity( $input ) {
	if ( is_float( floatval( $input ) ) ) {
		return $input;
	} else {
		return 0.8;
	}
}

function ct_challenger_sanitize_fi_size_type( $input ) {

	$valid = array(
		'yes' => __( 'Yes, keep all Featured Images the same aspect ratio', 'challenger' ),
		'no'  => __( 'No, use the natural size of each image', 'challenger' )
	);

	return array_key_exists( $input, $valid ) ? $input : '';
}

function ct_challenger_sanitize_phone( $input ) {
	if ( $input != '' ) {
		return esc_url_raw( 'tel:' . $input, array( 'tel' ) );
	} else {
		return '';
	}
}