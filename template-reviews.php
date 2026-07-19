<?php
    /* Template Name: Reviews */

    get_header();
    
    the_post();    
?>

<section id="page_header" class="section_bl">
    <?php get_sidebar("baner"); ?>
    <div class="container _sm">
        <h1 class="page_title section_title">Feedback <span class="cursive">about us</span></h1>
    </div>
</section>

<div class="section_bl" id="reviews">
    <div class="container _sm">
        <div class="wrap">
            <?php echo do_shortcode('[grw id=62]'); ?>
        </div>
    </div>
</div>

<?php if(get_the_content()){ ?>
<section class="section_bl txt_bl" >
    <div class="container">
        <?php the_content(); ?>
    </div>
</section>
<?php } ?>

<?php get_sidebar("advantages");  ?>

<?php get_footer();

