<?php
    /* Template Name: Faq */

    get_header();
    
    the_post();    
?>

<section id="page_header" class="section_bl">
    <?php get_sidebar("baner"); ?>
    <div class="container _sm">
        <h1 class="page_title section_title">Frequently asked <span class="cursive">Questions</span></h1>
    </div>
</section>


<?php
    $description = get_field("description");
    $des_text  = $description["text"];
    $des_image = $description["image"];
    
    if($des_text || $des_image){
?>
<section class="section_bl" id="faq_txt">
    <div class="container">
        <div class="wrap">
            <?php if($des_image){ ?>
            <div class="image_wrap">
                <img src="<?php echo $des_image["url"]; ?>" alt="<?php echo $des_image["alt"]; ?>">
            </div>
            <?php } ?>
            <div class="txt_wrap"><?php echo $des_text; ?></div>
        </div>
    </div>
</section>
<?php } ?>

<?php
    $faq = get_field("faq") ? get_field("faq") : array();
    
    if(count($faq) > 0){
?>
<section class="section_bl" id="faq">
    <div class="container">
        <?php 
            foreach($faq as $row){ 
                $title = $row["title"];
                $list  = $row["list"] ? $row["list"] : array();
        ?>
        <div class="faq_item">
            <div class="title"><?php echo $title; ?></div>
            <div class="list">
                <?php
                    foreach($list as $item){
                ?>
                <div class="item">
                    <div class="tit"><?php echo $item["question"]; ?></div>
                    <div class="txt"><?php echo $item["answer"]; ?></div>
                </div>
                <?php } ?>
            </div>
        </div>
        <?php } ?>
    </div>
</section>
<?php } ?>

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

