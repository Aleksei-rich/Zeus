<div class="image_wrap">
    <?php
        $baner_big = get_field("baner_big");
        $baner_mob = get_field("baner_mob");

        $baner_big_src = $baner_big ? $baner_big["sizes"]["bnr"] : get_template_directory_uri() . "/assets/img/page_des.jpg";
        $baner_big_alt = $baner_big ? $baner_big["alt"] : "";

        $baner_mob_src = $baner_mob ? $baner_mob["sizes"]["bnr_mob"] : get_template_directory_uri() . "/assets/img/page_mob.jpg";
        $baner_mob_alt = $baner_mob ? $baner_mob["alt"] : "";
    ?>

    <img src="<?php echo $baner_big_src; ?>" alt="<?php echo $baner_big_alt; ?>" class="des">
    <img src="<?php echo $baner_mob_src; ?>" alt="<?php echo $baner_mob_alt; ?>" class="mob">
</div>