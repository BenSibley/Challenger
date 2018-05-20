<div <?php post_class(); ?>>
	<?php do_action( 'challenger_post_before' ); ?>
	<article>
		<div class='post-header'>
			<h1 class='post-title'><?php the_title(); ?></h1>
			<?php get_template_part( 'content/post-byline' ); ?>
		</div>
		<?php ct_challenger_featured_image(); ?>
		<div class="post-content">
			<?php get_sidebar( 'before-post' ); ?>
			<?php the_content(); ?>
			<?php wp_link_pages( array(
				'before' => '<p class="singular-pagination">' . esc_html__( 'Pages:', 'challenger' ),
				'after'  => '</p>',
			) ); ?>
			<?php do_action( 'challenger_post_after' ); ?>
			<?php get_template_part( 'content/author-box' ); ?>
		</div>
		<div class="post-meta">
			<?php get_template_part( 'content/post-categories' ); ?>
			<?php get_template_part( 'content/post-tags' ); ?>
		</div>
		<?php get_sidebar( 'after-post' ); ?>
	</article>
	<?php comments_template(); ?>
</div>