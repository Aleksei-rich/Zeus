<?php
    /* Template Name: About */

    get_header();
    
    the_post();    
?>

<section id="page_header" class="section_bl">
    <?php get_sidebar("baner"); ?>
    <div class="container _sm">
        <h1 class="page_title section_title">About <span class="cursive">Us</span></h1>
    </div>
</section>

<?php
    $managements = get_field("managements") ? get_field("managements") : array();
    
    if(count($managements) > 0){
?>
<section class="section_bl" id="managers">
    <div class="container">
        <h2 class="section_title _uppercase">Management and staff</h2>

        <div class="wrap">
            <?php foreach($managements as $item){ ?>
            <div class="item">
                <?php if($item["image"]){ ?>
                <div class="image_wrap">
                    <img src="<?php echo $item["image"]["sizes"]["manager"]; ?>" alt="<?php echo $item["image"]["alt"]; ?>">
                </div>
                <?php } ?>
                <?php if($item["name"]){ ?>
                <div class="name"><?php echo $item["name"]; ?></div>
                <?php } ?>
                <?php if($item["position"]){ ?>
                <div class="dolgnost"><?php echo $item["position"]; ?></div>
                <?php } ?>
            </div>
            <?php } ?>
        </div>
    </div>
</section>
    <?php } ?>

<?php
    $text_1 = get_field("text_1");
    $text_1_left = $text_1["text_1"];
    $text_1_right = $text_1["text_2"];
            
    $text_2 = get_field("text_2");
    
    $text_3 = get_field("text_3");
    $text_3_left   = $text_3["text_1"];
    $text_3_center = $text_3["text_2"];
    $text_3_right  = $text_3["text_3"];
    
    $text_4 = get_field("text_4");
?>
<section class="section_bl" id="about_txt">
    <div class="container">
        <div class="wrap">
            <div class="row_1">
                <div class="col"><?php echo $text_1_left; ?></div>
                <div class="col _bg _sm"><?php echo $text_1_right; ?></div>
            </div>
            <div class="row_2">
                <div class="col _uppercase"><?php echo $text_2; ?></div>
            </div>
            <div class="row_3">
                <div class="col _sm"><?php echo $text_3_left; ?></div>
                <div class="col _sm"><?php echo $text_3_center; ?></div>
                <div class="col _sm"><?php echo $text_3_right; ?></div>
            </div>
            <div class="row_4">
                <div class="col"><?php echo $text_4; ?></div>
            </div>
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

