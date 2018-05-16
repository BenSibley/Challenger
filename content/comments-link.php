<span class="comments-link">
	<i class="fa fa-comment" title="<?php esc_attr_e( 'comment icon', 'challenger' ); ?>"></i>
	<?php
	if ( ! comments_open() && get_comments_number() < 1 ) :
		comments_number( esc_html__( 'Comments closed', 'challenger' ), esc_html__( '1 Comment', 'challenger' ), esc_html__( '% Comments', 'challenger' ) );
	else :
		echo '<a href="' . esc_url( get_comments_link() ) . '">';
		comments_number( esc_html__( 'Leave a Comment', 'challenger' ), esc_html__( '1 Comment', 'challenger' ), esc_html__( '% Comments', 'challenger' ) );
		echo '</a>';
	endif;
	?>
</span>