<?php
    /* Template Name: Calculation */

    get_header();
    
    the_post();    
?>

<section id="page_header" class="section_bl">
    <?php get_sidebar("baner"); ?>
    <div class="container _sm">
        <h1 class="page_title section_title">Оnline <span class="cursive">Calculation</span></h1>
    </div>
</section>

<section id="calculation" class="section_bl">
    <div class="container">
        <h2 class="section_title">Select by Scheme for Calculation</h2>
        <div class="descr">Set the dimensions in inches</div>

        <div class="wrap">
            <?php echo do_shortcode('[contact-form-7 id="b80ccd8" title="Calculation"]'); ?>
        </div>

    </div>
</section>

<?php if(get_the_content()){ ?>
<section class="section_bl txt_bl" >
    <div class="container">
        <?php the_content(); ?>
    </div>
</section>
<?php } ?>

<?php get_sidebar("advantages");  ?>

<?php get_footer();

