<?php

// Recommended plugins installer
require_once 'include/plugins/init.php';

// Custom admin area functions
//require_once('include/admin-assets/admin-addons.php');

function style_js()
{
    // Use script in current template
    /*if(basename(get_page_template()) == "tpl-contact.php") {
        wp_register_script( 'google-map', "//maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&language=en", '', null );
        wp_enqueue_script( 'google-map' );
    }*/
    wp_enqueue_script('init', get_template_directory_uri() . '/js/init.js', array('jquery'), '1.0', true);
    wp_enqueue_script('lib', get_template_directory_uri() . '/js/lib.js', array('jquery'), '1.0', true);
    wp_enqueue_style('style', get_template_directory_uri() . '/style/style.css');
/*    wp_enqueue_style('swiper.min', get_template_directory_uri() . '/style/swiper.min.css');*/
}
add_action('wp_enqueue_scripts', 'style_js');

// Video from social networks
function my_deregister_scripts(){
    wp_deregister_script( 'wp-embed' );
}

//custom theme url
function theme(){
    return bloginfo('stylesheet_directory');
}

//register menus
register_nav_menus(array(
    'main_menu' => 'Main menu',
));

//register sidebar
$bar = array(
    'name'          => __( $name ),
    'id'            => $id,
    'description'   => 'Sidebar for news section',
    'before_widget' => '<div class="widget %2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<div class="widgettitle">',
    'after_title'   => '</div>'
);
register_sidebar($bar);

if(function_exists('acf_add_options_page') ) {
    acf_add_options_page(array(
        'page_title'    => 'Theme General Settings',
        'menu_title'    => 'Theme Settings',
        'menu_slug'     => 'theme-general-settings',
        'capability'    => 'edit_posts',
        'redirect'      => false
    ));
}

//Thumbnails theme support
add_theme_support( 'post-thumbnails' );

//images sizes
//add_image_size( 'image_name', 'x', 'y', true );

//clear wp_head
remove_action('wp_head', 'feed_links_extra', 3);
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'index_rel_link');
remove_action('wp_head', 'parent_post_rel_link', 10, 0);
remove_action('wp_head', 'start_post_rel_link', 10, 0);
remove_action('wp_head', 'wp_shortlink_wp_head' );
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head' );
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'rel_canonical');
//remove_action('wp_head', 'qtrans_header', 10, 0);
add_action('widgets_init', 'my_remove_recent_comments_style');

/* BEGIN: Theme config section*/
define ('HOME_PAGE_ID', get_option('page_on_front'));
define ('BLOG_ID', get_option('page_for_posts'));
/* END: Theme config section*/

//Correct Error in admin panel
function my_remove_recent_comments_style() {
    global $wp_widget_factory;
    remove_action('wp_head', array($wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style'));
}

//Body class
function new_body_classes( $classes ){
    if( is_page() ){
        $temp = get_page_template();
        if ( $temp != null ) {
            $path = pathinfo($temp);
            $tmp = $path['filename'] . "." . $path['extension'];
            $tn= str_replace(".php", "", $tmp);
            $classes[] = $tn;
        }
        global $post;
        $classes[] = 'page-'.get_post($post)->post_name;
        if (is_active_sidebar('sidebar')) {
            $classes[] = 'with_sidebar';
        }
    }
    if(is_page() && !is_front_page() || is_single()) {$classes[] = 'static-page';}
    global $is_lynx, $is_gecko, $is_IE, $is_opera, $is_NS4, $is_safari, $is_chrome, $is_iphone;
    if($is_lynx) $classes[] = 'lynx';elseif($is_gecko) $classes[] = 'gecko';elseif($is_opera) $classes[] = 'opera';elseif($is_NS4) $classes[] = 'ns4';elseif($is_safari) $classes[] = 'safari';elseif($is_chrome) $classes[] = 'chrome';elseif($is_IE) $classes[] = 'ie';else $classes[] = 'unknown';if($is_iphone) $classes[] = 'iphone';
    return $classes;
}
add_filter( 'body_class', 'new_body_classes' );

//remove ID in menu list
add_filter('nav_menu_item_id', 'clear_nav_menu_item_id', 10, 3);
function clear_nav_menu_item_id($id, $item, $args) {
    return "";
}

//get content of the post
function get_post_content($pid = 0)
{
    if (empty($pid))
        $pid = get_the_ID();
    $my_query = new WP_Query(array(
        'p' => $pid,
        'post_type' => 'any'
    ));
    if ($my_query->have_posts()) {
        while ($my_query->have_posts()) {
            $my_query->the_post();
            $content = str_replace(']]>', ']]&gt;', apply_filters('the_content', get_the_content()));
        }
        wp_reset_query();
        return $content;
    } else {
        return false;
    }
}

//custom SEO title
function seo_title(){
    global $post;
    if($post->post_parent) {
        $parent_title = get_the_title($post->post_parent);
        echo wp_title('-', true, 'right') . $parent_title.' - ';
    } else {
        wp_title('-', true, 'right');
    } bloginfo('name');
}

/* Update wp-scss setings
   ========================================================================== */
if (class_exists('Wp_Scss_Settings')) {
    $wpscss = get_option('wpscss_options');
    if (empty($wpscss['css_dir']) && empty($wpscss['scss_dir'])) {
        update_option('wpscss_options', array('css_dir' => '/style/', 'scss_dir' => '/style/', 'compiling_options' => 'scss_formatter_compressed'));
    }
}

function soc(){ ?>
    <div class="soc">
        <?php if ($twit = get_field("twit","option")) { ?>
            <a class="icon-twitter" href="<?php echo $twit; ?>" target="_blank"></a>
        <?php } ?>
        <?php if ($face = get_field("face","option")) { ?>
            <a class="icon-facebook" href="<?php echo $face; ?>" target="_blank"></a>
        <?php } ?>
        <?php if ($google = get_field("google","option")) { ?>
            <a class="icon-google-plus" href="<?php echo $google; ?>" target="_blank"></a>
        <?php } ?>
    </div>
<?php }

function image_src($id, $size = 'full', $background_image = false, $height = false) {
    if ($image = wp_get_attachment_image_src($id, $size, true)) {
        return $background_image ? 'background-image: url('.$image[0].');' . ($height?'height:'.$image[2].'px':'') : $image[0];
    }
}

?>
