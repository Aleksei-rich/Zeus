<?php

    get_header();
    
    the_post();
?>

<section id="page_header" class="section_bl">
    <?php // get_sidebar("baner"); ?>
    <div class="container _sm">
        <h1 class="page_title section_title"><?php the_title(); ?></h1>
    </div>
</section>


<section class="section_bl txt_style">
    <div class="container">
        
        <?php the_content(); ?>
        
    </div>
</section>

<?php get_footer();

