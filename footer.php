<?php do_action( 'challenger_main_bottom' ); ?>
</section> <!-- .main -->
<?php get_sidebar( 'primary' ); ?>
<?php do_action( 'challenger_after_main' ); ?>

<footer id="site-footer" class="site-footer" role="contentinfo">
    <?php do_action( 'challenger_footer_top' ); ?>
    <div class="design-credit">
        <span>
            <?php
            $footer_text = sprintf( __( '<a href="%s">Challenger WordPress Theme</a> by Compete Themes.', 'challenger' ), 'https://www.competethemes.com/challenger/' );
            $footer_text = apply_filters( 'ct_challenger_footer_text', $footer_text );
            echo wp_kses_post( $footer_text );
            ?>
        </span>
    </div>
</footer>
</div><!-- .max-width -->
</div><!-- .overflow-container -->

<?php do_action( 'challenger_body_bottom' ); ?>

<?php wp_footer(); ?>

</body>
</html>