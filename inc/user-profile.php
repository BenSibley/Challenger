<?php

//----------------------------------------------------------------------------------
//	Add social profile fields to user profile menu. Social icons are output in the author bio's after posts.
//----------------------------------------------------------------------------------
if ( ! function_exists( ( 'challenger_add_social_profile_settings' ) ) ) {
	function challenger_add_social_profile_settings( $user ) {

		$user_id = get_current_user_id();
		if ( ! current_user_can( 'edit_posts', $user_id ) ) {
			return false;
		}
		$social_sites = ct_challenger_social_array();
		?>
		<table class="form-table">
			<tr>
				<th>
					<h3><?php esc_html_e( 'Social Profiles', 'challenger' ); ?></h3>
				</th>
			</tr>
			<?php
			foreach ( $social_sites as $key => $social_site ) {

				$label = ucfirst( $key );

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
				?>
				<tr>
					<th>
						<?php if ( $key == 'email' ) : ?>
							<label
								for="<?php echo esc_attr( $key ); ?>-profile"><?php esc_html_e( 'Email Address', 'challenger' ); ?></label>
						<?php else : ?>
							<label
								for="<?php echo esc_attr( $key ); ?>-profile"><?php echo esc_html( $label ); ?></label>
						<?php endif; ?>
					</th>
					<td>
						<?php if ( $key == 'email' ) { ?>
							<input type='text' id='<?php echo esc_attr( $key ); ?>-profile' class='regular-text'
							       name='<?php echo esc_attr( $key ); ?>-profile'
							       value='<?php echo is_email( get_the_author_meta( $social_site, $user->ID ) ); ?>'/>
						<?php } elseif ( $key == 'skype' ) { ?>
							<input type='url' id='<?php echo esc_attr( $key ); ?>-profile' class='regular-text'
							       name='<?php echo esc_attr( $key ); ?>-profile'
							       value='<?php echo esc_url( get_the_author_meta( $social_site, $user->ID ), array(
								       'http',
								       'https',
								       'skype'
							       ) ); ?>'/>
						<?php } elseif ( $key == 'phone' ) { ?>
							<input type='url' id='<?php echo esc_attr( $key ); ?>-profile' class='regular-text'
						       name='<?php echo esc_attr( $key ); ?>-profile'
						       value='<?php echo esc_url( get_the_author_meta( $social_site, $user->ID ), array( 'tel' ) ); ?>'/>
						<?php } else { ?>
							<input type='url' id='<?php echo esc_attr( $key ); ?>-profile' class='regular-text'
							       name='<?php echo esc_attr( $key ); ?>-profile'
							       value='<?php echo esc_url( get_the_author_meta( $social_site, $user->ID ) ); ?>'/>
						<?php } ?>
					</td>
				</tr>
			<?php } ?>
		</table>
		<?php
	}
}
add_action( 'show_user_profile', 'challenger_add_social_profile_settings' );
add_action( 'edit_user_profile', 'challenger_add_social_profile_settings' );

//----------------------------------------------------------------------------------
//	Save the user's social profile links
//----------------------------------------------------------------------------------
if ( ! function_exists( ( 'challenger_save_social_profiles' ) ) ) {
	function challenger_save_social_profiles( $user_id ) {

		if ( ! current_user_can( 'edit_user', $user_id ) ) {
			return false;
		}

		$social_sites = ct_challenger_social_array();

		foreach ( $social_sites as $key => $social_site ) {
			if ( $key == 'email' ) {
				// if email, only accept 'mailto' protocol
				if ( isset( $_POST["$key-profile"] ) ) {
					update_user_meta( $user_id, $social_site, sanitize_email( wp_unslash( $_POST["$key-profile"] ) ) );
				}
			} elseif ( $key == 'skype' ) {
				// accept skype protocol
				if ( isset( $_POST["$key-profile"] ) ) {
					update_user_meta( $user_id, $social_site, esc_url_raw( wp_unslash( $_POST["$key-profile"] ), array(
						'http',
						'https',
						'skype'
					) ) );
				}
			} elseif ( $key == 'phone' ) {
				// if phone, only accept 'tel' protocol
				if ( isset( $_POST["$key-profile"] ) ) {
					if ( $_POST["$key-profile"] == '' ) {
						update_user_meta( $user_id, $social_site, '' );
					} else {
						update_user_meta( $user_id, $social_site, esc_url_raw( 'tel:' . $_POST["$key-profile"], array( 'tel' ) ) );
					}
				}
			} else {
				if ( isset( $_POST["$key-profile"] ) ) {
					update_user_meta( $user_id, $social_site, esc_url_raw( wp_unslash( $_POST["$key-profile"] ) ) );
				}
			}
		}
	}
}
add_action( 'personal_options_update', 'challenger_save_social_profiles' );
add_action( 'edit_user_profile_update', 'challenger_save_social_profiles' );