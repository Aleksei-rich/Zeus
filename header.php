<!doctype html>
<html <?php language_attributes(); ?>>

    <head>
        <meta charset="<?php bloginfo('charset'); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">
        <?php wp_head(); ?>
        

        <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
        <link rel="manifest" href="/site.webmanifest">
        <meta name="msapplication-TileColor" content="#da532c">
        <meta name="theme-color" content="#ffffff">

        
    </head>


    <body <?php body_class(); ?>>

    <aside id="mobile_sidebar">
        <a class="close_sidebar close_mob_menu"></a>
        <div class="sidebar_content">
            <div class="menu_wrap">
                <?php wp_nav_menu(['menu' => 'Главное']); ?>
            </div>

            <?php if(getPhone()){ ?>
            <div class="phone_wrap"><a href="tel:<?php echo getPhone(); ?>"><?php echo getPhone(); ?></a></div>
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
    </aside>
        
    <header>
        <div class="container">
            <div class="header_content">
                <div class="logo_wrap"><a href="<?php echo home_url(); ?>"><img src="https://zeuscabinetsflorida.com/wp-content/uploads/2026/07/logo.png" alt="logo"></a></div>
                <div class="center">
                    <div class="descr">
                        <div class="big">Kitchen Cabinets in Florida</div>
                        <div class="small">Family Business</div>
                    </div>
                    <div class="menu_wrap">
                        <?php wp_nav_menu(['menu' => 'Главное']); ?>
                    </div>
                </div>
                <div class="right">
                    <?php if(getPhone()){ ?>
                    <div class="phone_wrap"><a href="tel:<?php echo getPhone(); ?>"><?php echo getPhone(); ?></a></div>
                    <?php } ?>
                    
                    <div class="call_wrap"><a href="#" class="open_callform">Call order</a></div>
                    <div class="socials">
                        <ul>
                            <?php if(getInstagramLink()){ ?>
                            <li>
                                <a href="<?php echo getInstagramLink(); ?>" target="_blank"><img src="<?php echo get_template_directory_uri() ?>/assets/img/ic_inst.png" alt=""></a>
                            </li>
                            <?php } ?>
                            <?php if(getFacebook()){ ?>
                            <li>
                                <a href="<?php echo getFacebook(); ?>" target="_blank"><img src="<?php echo get_template_directory_uri() ?>/assets/img/ic_fb.png" alt=""></a>
                            </li>
                            <?php } ?>
                            <?php if(getWa()){ ?>
                            <li>
                                <a href="<?php echo getWa(); ?>" target="_blank"><img src="<?php echo get_template_directory_uri() ?>/assets/img/ic_wa.png" alt=""></a>
                            </li>
                            <?php } ?>
                        </ul>
                    </div>
                    <div class="mob_menu_btn">
                        <a href="#" class="open_mob_menu"><span></span><span></span><span></span></a>
                    </div>
                </div>
            </div>
        </div>
    </header>




