<?php
    
    
/**
 * Enqueue scripts and styles.
 */
function imtecseo_scripts() {
    wp_enqueue_style('styles-css', get_template_directory_uri() . '/assets/css/styles.css');

    wp_enqueue_script('scripts-js', get_template_directory_uri() . '/assets/js/scripts.js', array(), false, true);
    wp_enqueue_script('main-js', get_template_directory_uri() . '/assets/js/main.js', array(), false, true);
}

add_action('wp_enqueue_scripts', 'imtecseo_scripts');

//add_filter( 'wp_default_scripts', 'remove_jquery_migrate' );
//function remove_jquery_migrate( $scripts ) {
//
//	if ( empty( $scripts->registered['jquery'] ) || is_admin() ) {
//		return;
//	}
//
//	$deps = & $scripts->registered['jquery']->deps;
//
//	$deps = array_diff( $deps, [ 'jquery-migrate' ] );
//}
//
//// Отключаем jQuery WordPress
//function modify_jquery() {
//    if (!is_admin()) {
//    // Убираем подключенную старую версию библиотеки
//    wp_deregister_script('jquery');
//    }
//}
//add_action('init', 'modify_jquery');


register_nav_menus(array(
    'menu-1' => "Главное меню",
));

/*
 * Let WordPress manage the document title.
 * This theme does not use a hard-coded <title> tag in the document head,
 * WordPress will provide it for us.
 */
add_theme_support('title-tag');


/*
 *  Удаляем rel=prev rel=next
 */
add_filter( 'wpseo_next_rel_link', '__return_false' );
add_filter( 'wpseo_prev_rel_link', '__return_false' );


add_image_size('advantages', 200, 100, false);
add_image_size('manager', 280, 280, true);
add_image_size('bnr', 1920, 580, true);
add_image_size('bnr_mob', 768, 350, true);

add_image_size('post', 430, 320, true);

////////////////////////////////////////////////////////////

////Создаем новый тип записей - "Catalog""
add_action('init', 'catalog');

function catalog() {
    $labels = array(
        'name' => 'Catalog',
        'menu_name' => 'Catalog'
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => false,
        'exclude_from_search' => false,
        'hierarchical'  => false,
        'menu_position' => 5,
        'show_in_menu'  => true,
        'show_in_nav_menus' => true,
        'has_archive'   => false,
        'menu_icon'     => "dashicons-admin-page",
        'supports'      => array('title', 'page-attributes', 'editor')
    );
    register_post_type('catalog', $args);
}

////////////////////////////////////////////////////////////

// Создаем новую таксономию для Portfolio
add_action('init', 'create_portfolio_category');

function create_portfolio_category() {
    $labels = array(
        'name' => 'Categoryes',
        'menu_name' => __('Categoryes'),
    );

    register_taxonomy('portfolio-category', array('portfolio'), array(
        'labels'        => $labels,
        'hierarchical'  => true,
        'public'        => true,
        'show_admin_column' => true
    ));
}

////Создаем новый тип записей - "Portfolio""
add_action('init', 'portfolio');

function portfolio() {
    $labels = array(
        'name' => 'Portfolio',
        'menu_name' => 'Portfolio'
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => false,
        'exclude_from_search' => false,
        'hierarchical'  => false,
        'menu_position' => 5,
        'show_in_menu'  => true,
        'show_in_nav_menus' => true,
        'has_archive'   => false,
        'menu_icon'     => "dashicons-admin-page",
        'supports'      => array('title', 'page-attributes'),
        'taxonomies'    => array('portfolio-category'),
    );
    register_post_type('portfolio', $args);
}

////////////////////////////////////////////////////////////

function getYoutubeLink($link){
    preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $link, $youtubeMatch);
    if(isset($youtubeMatch[1])){
        return "https://www.youtube.com/embed/".$youtubeMatch[1];
    }
    return false;
}

function getYoutubeThumbnail($link) {
    preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $link, $youtubeMatch);
    if (isset($youtubeMatch[1])) {
        return 'https://img.youtube.com/vi/' . $youtubeMatch[1] . '/maxresdefault.jpg';
    }
    return false;
}

////////////////////////////////////////////////////////////

if (function_exists('acf_add_options_page')) {

    acf_add_options_page(array(
        'page_title' => 'Наполнение',
        'menu_title' => 'Наполнение',
        'icon_url' => 'dashicons-nametag'
    ));
}



////////////////////////////////////////////////////////////

// удаляет H2 из шаблона пагинации
add_filter('navigation_markup_template', 'my_navigation_template', 10, 2);

function my_navigation_template($template, $class) {
    /*
      Вид базового шаблона:
      <nav class="navigation %1$s" role="navigation">
      <h2 class="screen-reader-text">%2$s</h2>
      <div class="nav-links">%3$s</div>
      </nav>
     */

    return '
    <div class="pagination_content">
	<nav class="navigation %1$s" role="navigation">
		<div class="links">%3$s</div>
	</nav>    
	</div>    
	';
}

////////////////////////////////////////////////////////////
    
function shortText($str, $length) {
    
    if(!$str){ return false; }
    
    $str = strip_tags($str);
    $str = preg_replace('/\[.*?\]/is', '', $str);
    $str = mb_substr($str, 0, $length - 2);        //Обрезаем до заданной длины 
    $words = explode(" ", $str);                //Разбиваем по словам 
    array_splice($words, -1);                //Удаляем последнее слово 

    return implode(" ", $words) . '...';
}

////////////////////////////////////////////////////////////

function getPhone(){
    return get_field("phone", "options");
}
function getEmail(){
    return get_field("email", "options");
}
function getWorktime(){
    return get_field("worktime", "options");
}
function getFacebook(){
    return get_field("facebook", "options");
}
function getInstagram(){
    return get_field("instagram", "options");
}
function getInstagramLink(){
    return get_field("instagram", "options") ? 'https://www.instagram.com/'. get_field("instagram", "options") : false;
}
function getWa(){
    return get_field("phone", "options") ? 'https://api.whatsapp.com/send?phone='. preg_replace('/[^0-9]/', '', get_field("phone", "options")) : false;
}
