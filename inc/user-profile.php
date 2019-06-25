<?php

//----------------------------------------------------------------------------------
//	Add social profile fields to user profile menu. 
//----------------------------------------------------------------------------------
if ( ! function_exists( ( 'ct_challenger_add_author_social_profiles' ) ) ) {
	function ct_challenger_add_author_social_profiles( $user ) {

    // Only available for user's who can create posts
		$user_id = get_current_user_id();
		if ( ! current_user_can( 'edit_posts', $user_id ) ) {
			return false;
    }
    // Get social icons data
		$social_sites = ct_challenger_social_array();
		?>
		<table class="form-table">
			<tr>
				<th>
					<h3><?php esc_html_e( 'Social Profiles', 'challenger'  ); ?></h3>
				</th>
			</tr>
			<?php
			foreach ( $social_sites as $key => $social_site ) {
        $label = ct_challenger_social_icon_labels( $social_site ); 
        $type = 'url';
        if ( $key == 'email' ) {
          $value = is_email( get_the_author_meta( $social_site, $user->ID ) );
          $type = 'text';
        } elseif ( $key == 'skype' ) {
          $value = esc_url( get_the_author_meta( $social_site, $user->ID ), array(
            'http',
            'https',
            'skype'
          ) );
        } elseif ( $key == 'phone' ) {
          $value = esc_url( get_the_author_meta( $social_site, $user->ID ), array( 'tel' ) );
        } else {
          $value = esc_url( get_the_author_meta( $social_site, $user->ID ) );
        }
        ?>
				<tr>
					<th>
            <label for="<?php echo esc_attr( $key ); ?>-profile"><?php echo esc_html( $label ); ?></label>
					</th>
					<td>
						<input type='<?php echo esc_attr( $type ); ?>' id='<?php echo esc_attr( $key ); ?>-profile' class='regular-text'
              name='<?php echo esc_attr( $key ); ?>-profile' value='<?php echo $value; ?>'/>
					</td>
				</tr>
			<?php } ?>
		</table>
		<?php
	}
}
add_action( 'show_user_profile', 'ct_challenger_add_author_social_profiles' );
add_action( 'edit_user_profile', 'ct_challenger_add_author_social_profiles' );

//----------------------------------------------------------------------------------
//	Save the user's social profile links
//----------------------------------------------------------------------------------
if ( ! function_exists( ( 'ct_challenger_save_social_profiles' ) ) ) {
	function ct_challenger_save_social_profiles( $user_id ) {

		if ( ! current_user_can( 'edit_user', $user_id ) ) {
			return false;
		}
    // Get social icon data
		$social_sites = ct_challenger_social_array();

    // Loop through icons and save any with URLs entered
		foreach ( $social_sites as $key => $social_site ) {
      if ( isset( $_POST["$key-profile"] ) ) {
        // if email, only accept 'mailto' protocol
        if ( $key == 'email' ) {
          update_user_meta( $user_id, $social_site, sanitize_email( wp_unslash( $_POST["$key-profile"] ) ) );
        } // accept skype protocol
        elseif ( $key == 'skype' ) {
          update_user_meta( $user_id, $social_site, esc_url_raw( wp_unslash( $_POST["$key-profile"] ), array(
            'http',
            'https',
            'skype'
          ) ) );
        } // if phone, only accept 'tel' protocol 
        elseif ( $key == 'phone' ) {
          if ( $_POST["$key-profile"] == '' ) {
            update_user_meta( $user_id, $social_site, '' );
          } else {
            update_user_meta( $user_id, $social_site, esc_url_raw( 'tel:' . $_POST["$key-profile"], array( 'tel' ) ) );
          }
        } else {
          update_user_meta( $user_id, $social_site, esc_url_raw( wp_unslash( $_POST["$key-profile"] ) ) );
        }
      }
		}
	}
}
add_action( 'personal_options_update', 'ct_challenger_save_social_profiles' );
add_action( 'edit_user_profile_update', 'ct_challenger_save_social_profiles' );