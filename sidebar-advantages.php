<?php
    $advantages = is_array(get_field("advantages", "options")) ? get_field("advantages", "options") : array();
    
    if(count($advantages) > 0){
?>

<section class="section_bl" id="advantages">
    <div class="container">
        <h2 class="section_title">Our <span class="cursive">Advantages</span></h2>
        <div class="wrap">
            <?php foreach($advantages as $item){ ?>
            <div class="item">
                <?php 
                    $image = $item["image"];
                    
                    if($image){
                ?>
                <div class="image_wrap">
                    <img src="<?php echo $image["sizes"]["advantages"]; ?>" alt="<?php echo $image["alt"]; ?>">
                </div>
                <?php } ?>
                <div class="tit"><?php echo $item["title"]; ?></div>
                <div class="txt"><?php echo $item["text"]; ?></div>
            </div>
            <?php } ?>
        </div>
    </div>
</section>
    <?php }