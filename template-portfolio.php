<?php
    /* Template Name: Portfolio */

    get_header();
    
    the_post();    
?>

<section id="page_header" class="section_bl">
    <?php get_sidebar("baner"); ?>
    <div class="container _sm">
        <h1 class="page_title section_title">Our <span class="cursive">Gallery</span></h1>
    </div>
</section>

<?php
    $categories = get_categories([
        'taxonomy'     => 'portfolio-category',
        'type'         => 'portfolio',
        'hierarchical' => false
    ]);
?>

<section class="section_bl" id="gallery">
    <?php
        if (count($categories) > 0) {
    ?>
    
    <div class="container _sm">
        <div class="tabs_wrap">
            <a href="#" data-slug="all" class="btn _sm _transparent _active">ALL  WORKS</a>
            <?php
                    $i = 0;
                    foreach ($categories as $cat) {
                        $i++;
                ?>
            <a href="#" data-slug="<?php echo $cat->slug ?>" class="btn _sm _transparent"><?php echo $cat->name; ?></a>
            <?php } ?>
        </div>
    </div>
    <?php } ?>
    
    <?php
        $portfolio = get_posts(array(
            'numberposts' => -1,
            'post_type'   => 'portfolio'
        ));
        
        if (count($portfolio) > 0) {
    ?>
    
    <script>
        var catalog = [];
    </script>
    
    <div class="container _sm">
        <div class="gallery_wrap">
            <?php 
                foreach ($portfolio as $item) { 
                    
                    $item_cats = wp_get_object_terms($item->ID, 'portfolio-category');
                    $class = "";
                    
                    if(count($item_cats) > 0){
                        foreach($item_cats as $cat){
                            $class .= " cat_".$cat->slug;
                        }
                    }
                    
                    $image = get_field("image", $item->ID);
                    $gallery = is_array(get_field("gallery", $item->ID)) ? get_field("gallery", $item->ID) : array();
                    
                    if($image){
            ?>
            <div class="item <?php echo $class; ?> _active">
                <a href="#" class="show_galley" data-gallery="portfolio_<?php echo $item->ID; ?>">
                    <img src="<?php echo $image["sizes"]["post"] ?>" alt="<?php echo $image["alt"] ?>">
                </a>
                
                <?php if(count($gallery) > 0){ ?>
                <div class="gallery_links">
                    <script>
                        catalog["portfolio_<?php echo $item->ID; ?>"] = [
                            <?php 
                                foreach($gallery as $gall_item){ 
                                    $gall_link = false;
                            
                                    if($gall_item['image']){
                                        $gall_link = $gall_item['image']["sizes"]["large"];
                                    }

                                    if($gall_item['youtube_link']){
                                        $gall_link = getYoutubeLink($gall_item['youtube_link']);
                                    }

                                    if($gall_link){
                            ?>
                                {
                                    src: '<?php echo $gall_link; ?>',
                                    <?php if($gall_item['image']){ ?>
                                    thumb: "<?php echo $gall_link; ?>",
                                    <?php }else if($gall_item['youtube_link']){ ?>
                                    thumb: "<?php echo getYoutubeThumbnail($gall_item['youtube_link']); ?>",
                                    <?php } ?>
                                },
                                <?php } } ?>
                        ];
                    </script>
                </div>
                <?php } ?>
                
            </div>
            <?php } ?>
            <?php } ?>
        </div>

        <div class="btn_wrap">
            <a href="<?php echo get_permalink(20) ?>" class="btn">Quick online calculation</a>
        </div>
    </div>
    <?php } ?>
</section>

<?php get_sidebar("consultform"); ?>

<?php if(get_the_content()){ ?>
<section class="section_bl txt_bl" >
    <div class="container">
        <?php the_content(); ?>
    </div>
</section>
<?php } ?>

<?php get_sidebar("advantages");  ?>

<?php get_footer();

