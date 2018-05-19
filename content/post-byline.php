<?php
$author_display = get_theme_mod( 'post_byline_author' );
$date_display   = get_theme_mod( 'post_byline_date' );

if ( $author_display == 'no' && $date_display == 'no' ) {
	return;
}

$author = get_the_author();
// add compatibility when used in header before loop
if ( empty( $author ) ) {
	global $post;
	$author = get_the_author_meta( 'display_name', $post->post_author );
}
$date = date_i18n( get_option( 'date_format' ), strtotime( get_the_date() ) );

echo '<div class="post-byline">';
if ( $author_display == 'no' ) {
	echo $date;
} elseif ( $date_display == 'no' ) {
	echo get_avatar( get_the_author_meta( 'ID' ), 36, '', get_the_author() );
	echo $author;
} else {
	echo get_avatar( get_the_author_meta( 'ID' ), 36, '', get_the_author() );
	echo $author . ' - ' . $date;
}
echo '</div>';
