<?php

    get_header();

    the_post();

    $portfolio_display = zeus_get_portfolio_display(get_the_ID());
    $image = $portfolio_display['image'];
    $gallery = $portfolio_display['gallery'];
?>

<section class="page_title">
    <div class="container _sm">
        <div class="breadcrumbs"><?php if (function_exists('yoast_breadcrumb')) { yoast_breadcrumb(); } ?></div>
        <h1><?php the_title() ?></h1>
    </div>
</section>

<?php if ($image) { ?>
<section class="section_bl" id="gallery">
    <script>
        var catalog = [];
        catalog["portfolio_<?php echo get_the_ID(); ?>"] = [
            <?php foreach ($gallery as $gall_item) { ?>
                {
                    src: '<?php echo $gall_item["sizes"]["large"]; ?>',
                    thumb: "<?php echo $gall_item["sizes"]["large"]; ?>"
                },
            <?php } ?>
        ];
    </script>

    <div class="container _sm">
        <div class="gallery_wrap">
            <div class="item _active">
                <a href="#" class="show_galley" data-gallery="portfolio_<?php echo get_the_ID(); ?>">
                    <img src="<?php echo $image["sizes"]["post"]; ?>" alt="<?php echo $image["alt"]; ?>">
                </a>
            </div>
        </div>
    </div>
</section>
<?php } ?>

<?php if (get_the_content()) { ?>
<section class="section_bl txt_style">
    <div class="container">
        <?php the_content(); ?>
    </div>
</section>
<?php } ?>

<?php get_footer();
