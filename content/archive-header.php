<?php
if ( ! is_archive() || get_theme_mod( 'archive_header' ) == 'no' ) {
	return;
}

$icon_class = 'folder-open';
$prefix = esc_html_x( 'Posts published in', 'Posts published in CATEGORY', 'challenger' );

if ( is_tag() ) {
	$icon_class = 'tag';
	$prefix = esc_html__( 'Posts tagged as', 'challenger' );
} elseif ( is_author() ) {
	$icon_class = 'user';
	$prefix = esc_html_x( 'Posts published by', 'Posts published by AUTHOR', 'challenger' );
} elseif ( is_date() ) {
	$icon_class = 'calendar';
	// Repeating default value to add new translator note - context may change word choice
	$prefix = esc_html_x( 'Posts published in', 'Posts published in MONTH', 'challenger' );
}
?>

<div class='archive-header'>
	<h1>
		<i class="fa fa-<?php echo esc_attr( $icon_class ); ?>"></i>
		<?php
		echo esc_html( $prefix ) . ' ';
		the_archive_title( '&ldquo;', '&rdquo;' );
		?>
	</h1>
	<?php if ( get_the_archive_description() != '' ) : ?>
		<p class="description">
			<?php the_archive_description(); ?>
		</p>
	<?php endif; ?>
</div>