<?php

    get_header();
    
    the_post();
?>

<section class="page_title">
    <div class="container _sm">
        <div class="breadcrumbs"><?php if (function_exists('yoast_breadcrumb')) { yoast_breadcrumb(); } ?></div>
        <h1><?php the_title() ?></h1>
    </div>
</section>

<section class="section_bl txt_style">
    <div class="container">
        <?php the_content(); ?>
    </div>
</section>

<?php get_footer();

