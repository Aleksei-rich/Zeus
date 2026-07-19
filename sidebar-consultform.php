<section class="section_bl" id="consultform">
    <div class="container _sm">
        <div class="wrap">
            <div class="cont">
                <h2 class="section_title _align_left">Fill out an  form</h2>
                <div class="txt">
                    <p>Contact us and you will get a quick estimate 
                        for new cabinets and countertops with no wait 
                        for measurements!
                    </p>
                    <p>
                        You can reach us via Whatsapp
                        your name,  phone number and email
                    </p>
                </div>
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
            <div class="form_wrap">
                <?php echo do_shortcode('[contact-form-7 id="40b3c8d" title="Consult form"]'); ?>
            </div>
        </div>
    </div>
</section>