<?php
/**
 * Close <main>, site footer, persistent mobile conversion bar, wp_footer().
 */
?>
</main>

<?php get_template_part( 'template-parts/footer/site-footer' ); ?>
<?php get_template_part( 'template-parts/mobile-conversion-bar' ); ?>

<?php wp_footer(); ?>
</body>
</html>
