<?php
    /* Template Name: Home */

    get_header();
    
    the_post();    
?>

<?php
    $slider = get_field("slider") ? get_field("slider") : array();
    
    if(count($slider) > 0){
?>

<section id="main" class="section_bl">
    <div class="wrap <?php if(count($slider) > 1){ echo "slider"; } ?>">
        <?php foreach($slider as $item){ ?>
        <div class="item">
            <div class="image_wrap">
                <?php
                    $baner_big = $item["baner_big"];
                    $baner_mob = $item["baner_mob"];

                    $baner_big_src = $baner_big ? $baner_big["sizes"]["bnr"] : get_template_directory_uri() . "/assets/img/page_des.jpg";
                    $baner_big_alt = $baner_big ? $baner_big["alt"] : "";

                    $baner_mob_src = $baner_mob ? $baner_mob["sizes"]["bnr_mob"] : get_template_directory_uri() . "/assets/img/page_mob.jpg";
                    $baner_mob_alt = $baner_mob ? $baner_mob["alt"] : "";
                ?>

                <img src="<?php echo $baner_big_src; ?>" alt="<?php echo $baner_big_alt; ?>" class="des">
                <img src="<?php echo $baner_mob_src; ?>" alt="<?php echo $baner_mob_alt; ?>" class="mob">
            </div>
            
            <div class="content_wrap">
                <div class="container">
                    <div class="content">
                        <div class="txt_wrap">
                            <div class="title"><?php echo $item["title"]; ?></div>

                            <div class="socials">
                                <ul>
                                    <?php if(getInstagramLink()){ ?>
                                    <li>
                                        <a href="<?php echo getInstagramLink(); ?>" target="_blank"><img src="<?php echo get_template_directory_uri() ?>/assets/img/ic_inst_white.png" alt=""></a>
                                    </li>
                                    <?php } ?>
                                    <?php if(getFacebook()){ ?>
                                    <li>
                                        <a href="<?php echo getFacebook(); ?>" target="_blank"><img src="<?php echo get_template_directory_uri() ?>/assets/img/ic_fb_white.png" alt=""></a>
                                    </li>
                                    <?php } ?>
                                    <?php if(getWa()){ ?>
                                    <li>
                                        <a href="<?php echo getWa(); ?>" target="_blank"><img src="<?php echo get_template_directory_uri() ?>/assets/img/ic_wa_white.png" alt=""></a>
                                    </li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>

                        <div class="btn_wrap">
                            <a href="<?php echo get_the_permalink(20) ?>" class="btn _bg_rouse">Quick online calculation</a>
                            <a href="#" class="btn _bg_rouse open_callform">Order an estimate now</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
</section>
<?php } ?>

<?php get_sidebar("advantages");  ?>

<?php
    $catalog = get_posts(array(
        'numberposts' => -1,
        'post_type'   => 'catalog',
        'order'       => 'ASC',
        'orderby'    => 'menu_order',
    ));
    if (count($catalog) > 0) {
?>

<section class="section_bl" id="catalog">
    <div class="container _sm">
        <h2 class="section_title">Catalog of  <span class="cursive">Cabinets</span></h2>
    </div>
        
        <?php 
            foreach ($catalog as $key => $item) { 
        
                $item_image   = get_field("image", $item->ID);
                $item_gallery = is_array(get_field("gallery", $item->ID)) ? get_field("gallery", $item->ID) : array();
                $item_tags    = get_field("tags", $item->ID);
                $item_color   = get_field("color", $item->ID);
        ?>
        <div class="wrap">
            <div class="container _sm">
                <?php if($item_tags){ ?>
                <div class="tags"><?php echo $item_tags; ?></div>
                <?php } ?>
                
                <div class="cont">
                    <div class="main_wrap">
                        <?php if($item_image){ ?>
                        <div class="image_wrap">
                            <a class="show_galley" href="#" data-index="0" data-gallery="catalog_<?php echo $item->ID; ?>"><img src="<?php echo $item_image["sizes"]["post"]; ?>" alt="<?php echo $item_image["alt"]; ?>"></a>
                        </div>
                        <?php } ?>
                        <div class="mobile_slider_wrap">
                            <?php if(count($item_gallery) > 0 || $item_image){ ?>
                            <div class="list">
                                <?php if($item_image){ ?>
                                <div class="item">
                                    <a class="show_galley" href="#" data-index="0" data-gallery="catalog_<?php echo $item->ID; ?>"><img src="<?php echo $item_image["sizes"]["post"]; ?>" alt="<?php echo $item_image["alt"]; ?>"></a>
                                </div>
                                <?php } ?>

                                <?php if(count($item_gallery) > 0){ ?>
                                <?php foreach($item_gallery as $img_key => $img){ ?>
                                <div class="item">
                                    <a class="show_galley" href="#" data-index="<?php echo $item_image ? $img_key + 1 : $img_key; ?>" data-gallery="catalog_<?php echo $item->ID; ?>"><img src="<?php echo $img["sizes"]["post"]; ?>" alt="<?php echo $img["alt"]; ?>"></a>
                                </div>
                                <?php } ?>
                                <?php } ?>
                            </div>
                            <?php } ?>
                        </div>
                        <div class="descr_wrap">
                            <div class="name"><span><?php echo get_the_title($item->ID); ?></span></div>

                            <?php if($item_color){ ?>
                            <div class="color"><?php echo $item_color; ?></div>
                            <?php } ?>

                            <?php $details = $item->post_content; ?>
                            <?php if($details){ ?>
                            <div class="link"><a data-fancybox href="#details_<?php echo $item->ID; ?>">MORE DETAILS</a></div>
                            
                            <div class="details txt_style" id="details_<?php echo $item->ID; ?>" style="display: none"><?php echo $details; ?></div>
                            
                            <?php } ?>
                        </div>
                    </div>
                    <?php 
                        if(count($item_gallery) > 0){ 
                    ?>
                    <div class="slider_wrap">
                        <div class="list">
                            <?php foreach($item_gallery as $img_key => $img){ ?>
                            <div class="item">
                                <a class="show_galley" data-index="<?php echo $item_image ? $img_key + 1 : $img_key; ?>" href="#" data-gallery="catalog_<?php echo $item->ID; ?>"><img src="<?php echo $img["sizes"]["post"]; ?>" alt="<?php echo $img["alt"]; ?>"></a>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                    
                    <script>
                        catalog["catalog_<?php echo $item->ID; ?>"] = [
                            <?php 
                                if($item_image){ ?> 
                                  {
                                    src: '<?php echo $item_image["sizes"]["large"]; ?>',
                                    thumb: "<?php echo $item_image["sizes"]["large"]; ?>"
                                },
                                  
                            <?php }
                                
                                foreach($item_gallery as $gall_item){ 
                                    $gall_link = $gall_item["sizes"]["large"];
                                    
                                    if($gall_link){
                            ?>
                                {
                                    src: '<?php echo $gall_link; ?>',
                                    thumb: "<?php echo $gall_link; ?>"
                                },
                                <?php } } ?>
                        ];
                    </script>
                    
                    <?php } ?>
                </div>
            </div>
        </div>
    <?php } ?>
</section>
<?php } ?>

<?php get_sidebar("consultform"); ?>

<section class="section_bl" id="reviews">
    <div class="container _sm">
        <h2 class="section_title">Reviews  <span class="cursive">About us</span></h2>
        
        <div class="wrap">
            <?php echo do_shortcode('[grw id=62]'); ?>
        </div>
    </div>
</section>

<?php
    $portfolio = get_posts(array(
        'numberposts' => -1,
        'post_type'   => 'portfolio',
        'meta_key' => 'in_home',
        'meta_value' => true,
    ));

    if (count($portfolio) > 0) {
        
        foreach ($portfolio as $item) { 
            $gallery = is_array(get_field("gallery", $item->ID)) ? get_field("gallery", $item->ID) : array();
        ?>
            
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
        <?php } ?>

<section class="section_bl" id="works">
    <div class="container">
        <h2 class="section_title">Examples <span class="cursive">of Works</span></h2>
        <div class="wrap">
            <div class="slider_wrap">
                <?php 
                    foreach ($portfolio as $item) { 
                       $image = get_field("image", $item->ID);

                        if($image){
                ?>
                <div class="item">
                    <div class="image_wrap">
                        <a href="#" class="show_galley" data-gallery="portfolio_<?php echo $item->ID; ?>">
                            <img src="<?php echo $image["sizes"]["post"] ?>" alt="<?php echo $image["alt"] ?>">
                        </a>
                    </div>
                    <div class="cont_wrap">
                        <div class="name">
                            <a href="#" class="show_galley" data-gallery="portfolio_<?php echo $item->ID; ?>"><?php echo get_the_title($item->ID); ?></a>
                        </div>
                        <div class="color"><?php echo get_field("color", $item->ID) ?></div>
                        <div class="link">
                            <a href="#" class="show_galley" data-gallery="portfolio_<?php echo $item->ID; ?>">MORE DETAILS</a>
                        </div>
                    </div>
                </div>
                <?php } ?>
                <?php } ?>
            </div>
        </div>
    </div>
</section>
<?php } ?>
    

<?php get_footer();

