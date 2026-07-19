<?php
    /* Template Name: Contacts */

    get_header();
    
    the_post();
?>

<section id="page_header" class="section_bl">
    <?php get_sidebar("baner"); ?>
    <div class="container _sm">
        <h1 class="page_title section_title">Contact <span class="cursive">Us</span></h1>
    </div>
</section>


<section class="section_bl" id="contacts">
    <div class="container">
        <div class="name">Zeus cabinets <br> 
            and countertops <br>
            in Florida</div>

        <div class="wrap">
            <div class="col">
                <?php if(getWorktime()){ ?>
                <div class="item">
                    <div class="ic"><img src="<?php echo get_template_directory_uri() ?>/assets/img/ic_blue_time.png" alt=""></div>
                    <div class="val"><?php echo getWorktime(); ?></div>
                </div>
                <?php } ?>
            </div>
            <div class="col">
                <?php if(getPhone()){ ?>
                <div class="item">
                    <div class="ic"><img src="<?php echo get_template_directory_uri() ?>/assets/img/ic_blue_phone.png" alt=""></div>
                    <div class="val"><a href="tel:<?php echo getPhone(); ?>"><?php echo getPhone(); ?></a></div>
                </div>
                <?php } ?>
                <?php if(getEmail()){ ?>
                <div class="item">
                    <div class="ic"><img src="<?php echo get_template_directory_uri() ?>/assets/img/ic_blue_mail.png" alt=""></div>
                    <div class="val _email"><a href="mailto:<?php echo getEmail(); ?>"><?php echo getEmail(); ?></a></div>
                </div>
                <?php } ?>
            </div>
            <div class="col">
                <?php if(getInstagramLink()){ ?>
                <div class="item">
                    <div class="ic"><img src="<?php echo get_template_directory_uri() ?>/assets/img/ic_blue_insta.png" alt=""></div>
                    <div class="val"><a href="<?php echo getInstagramLink(); ?>" target="_blank"><?php echo getInstagram(); ?></a></div>
                </div>
                <?php } ?>
                <?php if(getFacebook()){ ?>
                <div class="item">
                    <div class="ic"><img src="<?php echo get_template_directory_uri() ?>/assets/img/ic_blue_fb.png" alt=""></div>
                    <div class="val"><a href="<?php echo getFacebook(); ?>" class="_sm" target="_blank"><?php echo getFacebook(); ?></a></div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</section>

<section class="section_bl" id="map">
    <div class="container">
        <h2 class="section_title _uppercase">WE ON THE MAP</h2>
    </div>

    <div id="map_container">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2585.86274945895!2d-81.58132205896044!3d28.449753345408773!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x88e780dd67a2a6b1%3A0x9a8504baacb2022!2zNzc0MiBCcm9maWVsZCBBdmUsIFdpbmRlcm1lcmUsIEZMIDM0Nzg2LCDQodCo0JA!5e0!3m2!1sru!2sru!4v1740042641860!5m2!1sru!2sru" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>    
    </div>
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

