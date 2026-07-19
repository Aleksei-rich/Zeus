
<footer>
    <div class="container">
        <div class="wrap">
            <div class="col">
                <div class="logo_wrap">
                    <img src="<?php echo get_template_directory_uri() ?>/assets/img/logo_footer.png" alt="">
                </div>
                <div class="descr">Zeus cabinets and countertops in Florida</div>
                <?php if(getWorktime()){ ?>
                <div class="worktime"><?php echo getWorktime(); ?></div>
                <?php } ?>
            </div>
            <div class="col center">
                <div class="menu_wrap">
                    <?php wp_nav_menu(['menu' => 'Главное']); ?>
                </div>
                <div class="locations">
                    <div class="tit">OUR LOCATIONS:</div>
                    <div class="txt">
                        <?php echo get_field("our_locations", "options"); ?>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="mob_over">
                    <?php if(getPhone()){ ?>
                    <div class="phone_wrap">
                        <a href="tel:<?php echo getPhone(); ?>"><?php echo getPhone(); ?></a>
                    </div>
                    <?php } ?>

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

                <?php if(getEmail()){ ?>
                <div class="email">
                    <a href="mailto:<?php echo getEmail(); ?>"><?php echo getEmail(); ?></a>
                </div>
                <?php } ?>

                <div class="copy">
                    © 2024. Zeus business LLC. <a href="<?php echo get_permalink(3); ?>">Privacy Policy</a>
                </div>
            </div>
        </div>
    </div>
</footer>

<div id="call_form" class="popup_form">
    <div class="form_bg"></div>
    <div class="form_content_wrap">
        <div class="form_wrap">
            <a href="#" class="close close_call"><i></i></a>
            <div class="form_tit">Call order</div>
            <div class="form_content">
                <?php echo do_shortcode('[contact-form-7 id="247fe76" title="Call order"]'); ?>
            </div>
        </div>
    </div>
</div>

<?php wp_footer(); ?>

</body>
</html>