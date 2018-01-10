<?php

define ('GOOGLEMAPS', FALSE);

// Recommended plugins installer
require_once 'include/plugins/init.php';

// Custom admin area functions
require_once('include/admin-assets/admin-addons.php');

// Custom Posts Duplicator
require_once('include/plugins/duplicator.php');

function style_js()
{
    // Use script in current template
    /*if(basename(get_page_template()) == "tpl-contact.php") {
        wp_register_script( 'google-map', "//maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&language=en", '', null );
        wp_enqueue_script( 'google-map' );
    }*/
    
    if(defined('GOOGLEMAPS')) {
        wp_enqueue_script('googlemaps', '//maps.googleapis.com/maps/api/js?v=3.exp&language=en&key=AIzaSyAO77hGcvxmsvOn1RSjDFQMI4YUnW89MDo', false, null, false);
    }
    
    wp_enqueue_script('init', get_template_directory_uri() . '/js/init.js', array('jquery'), '1.0', true);
    /*    wp_enqueue_style('swiper.min', get_template_directory_uri() . '/style/swiper.min.css');*/
    wp_enqueue_style('style', get_template_directory_uri() . '/style/style.css');
    wp_enqueue_script('lib', get_template_directory_uri() . '/js/lib.js', array('jquery'), '1.0', true);

}
add_action('wp_enqueue_scripts', 'style_js');

//register acf_options
if( function_exists('acf_add_options_page') ) {
    acf_add_options_page(array(
        'page_title'    => 'Theme General Settings',
        'menu_title'    => 'Theme Settings',
        'menu_slug'     => 'theme-general-settings',
        'capability'    => 'edit_posts',
        'redirect'      => false
    ));
    acf_add_options_sub_page(array(
        'page_title'    => 'Theme Header Settings',
        'menu_title'    => 'Header',
        'parent_slug'   => 'theme-general-settings',
    ));
    acf_add_options_sub_page(array(
        'page_title'    => 'Theme Footer Settings',
        'menu_title'    => 'Footer',
        'parent_slug'   => 'theme-general-settings',
    ));
}

// Video from social networks
function my_deregister_scripts(){
    wp_deregister_script( 'wp-embed' );
}

//custom theme url
function theme($filepath = NULL){
    return preg_replace( '(https?://)', '//', get_stylesheet_directory_uri() . ($filepath?'/' . $filepath:'') );
}

//register menus
register_nav_menus(array(
    'main_menu' => 'Main menu',
));

//register sidebar
$reg_sidebars = array (
    'page_sidebar'     => 'Page Sidebar',
);
foreach ( $reg_sidebars as $id => $name ) {
    register_sidebar(
        array (
            'name'          => __( $name ),
            'id'            => $id,
            'before_widget' => '<div class="widget cfx %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<mark class="widget-title">',
            'after_title'   => '</mark>',
        )
    );
}

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

/* ===== New Body Classes ===== */
function wpa_body_classes( $classes ){
    if( is_page() ){
        global $post;
        $temp = get_page_template();
        if ( $temp != null ) {
            $path = pathinfo($temp);
            $tmp = $path['filename'] . "." . $path['extension'];
            $tn= str_replace(".php", "", $tmp);
            $classes[] = $tn;
        }
//        if (is_active_sidebar('sidebar')) {
//            $classes[] = 'with_sidebar';
//        }
        foreach($classes as $k => $v) {
            if(
                $v == 'page-template' ||
                $v == 'page-id-'.$post->ID ||
                $v == 'page-template-default' ||
                $v == 'woocommerce-page' ||
                ($temp != null?($v == 'page-template-'.$tn.'-php' || $v == 'page-template-'.$tn):'')) unset($classes[$k]);
        }
    }
    if( is_single() ){
        global $post;
        $f = get_post_format( $post->ID );
        foreach($classes as $k => $v) {
            if($v == 'postid-'.$post->ID || $v == 'single-format-'.(!$f?'standard':$f)) unset($classes[$k]);
        }
    }
    if ( is_multi_author() ) {
        $classes[] = 'group-blog';
    }
    global $is_lynx, $is_gecko, $is_IE, $is_opera, $is_NS4, $is_safari, $is_chrome, $is_iphone;
    $browser = $_SERVER[ 'HTTP_USER_AGENT' ];
    // Mac, PC ...or Linux
    if ( preg_match( "/Mac/", $browser ) ){
        $classes[] = 'macos';
    } elseif ( preg_match( "/Windows/", $browser ) ){
        $classes[] = 'windows';
    } elseif ( preg_match( "/Linux/", $browser ) ) {
        $classes[] = 'linux';
    } else {
        $classes[] = 'unknown-os';
    }
    // Checks browsers in this order: Chrome, Safari, Opera, MSIE, FF
    if ( preg_match( "/Edge/", $browser ) ) {
        $classes[] = 'edge';
    } elseif ( preg_match( "/Chrome/", $browser ) ) {
        $classes[] = 'chrome';
        preg_match( "/Chrome\/(\d.\d)/si", $browser, $matches);
        @$classesh_version = 'ch' . str_replace( '.', '-', $matches[1] );
        $classes[] = $classesh_version;
    } elseif ( preg_match( "/Safari/", $browser ) ) {
        $classes[] = 'safari';
        preg_match( "/Version\/(\d.\d)/si", $browser, $matches);
        $sf_version = 'sf' . str_replace( '.', '-', $matches[1] );
        $classes[] = $sf_version;
    } elseif ( preg_match( "/Opera/", $browser ) ) {
        $classes[] = 'opera';
        preg_match( "/Opera\/(\d.\d)/si", $browser, $matches);
        $op_version = 'op' . str_replace( '.', '-', $matches[1] );
        $classes[] = $op_version;
    } elseif ( preg_match( "/MSIE/", $browser ) ) {
        $classes[] = 'msie';
        if( preg_match( "/MSIE 6.0/", $browser ) ) {
            $classes[] = 'ie6';
        } elseif ( preg_match( "/MSIE 7.0/", $browser ) ){
            $classes[] = 'ie7';
        } elseif ( preg_match( "/MSIE 8.0/", $browser ) ){
            $classes[] = 'ie8';
        } elseif ( preg_match( "/MSIE 9.0/", $browser ) ){
            $classes[] = 'ie9';
        }
    } elseif ( preg_match( "/Firefox/", $browser ) && preg_match( "/Gecko/", $browser ) ) {
        $classes[] = 'firefox';
        preg_match( "/Firefox\/(\d)/si", $browser, $matches);
        $ff_version = 'ff' . str_replace( '.', '-', $matches[1] );
        $classes[] = $ff_version;
    } else {
        $classes[] = 'unknown-browser';
    }
    //qtranslate classes
    if(defined('QTX_VERSION')) {
        $classes[] = 'qtrans-' . qtranxf_getLanguage();
    }
    return $classes;
}
add_filter( 'body_class', 'wpa_body_classes' );

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

/* Activate ACF
   ========================================================================== */
function wpa__prelicense()
{
	if (function_exists('acf_pro_is_license_active') && !acf_pro_is_license_active()) {
		$args = array(
			'_nonce' => wp_create_nonce('activate_pro_licence'),
			'acf_license' => base64_encode('order_id=37918|type=personal|date=2014-08-21 15:02:59'),
			'acf_version' => acf_get_setting('version'),
			'wp_name' => get_bloginfo('name'),
			'wp_url' => home_url(),
			'wp_version' => get_bloginfo('version'),
			'wp_language' => get_bloginfo('language'),
			'wp_timezone' => get_option('timezone_string'),
		);
		$response = acf_pro_get_remote_response('activate-license', $args);
		$response = json_decode($response, true);
		if ($response['status'] == 1) {
			acf_pro_update_license($response['license']);
		}
	}
}
add_action( 'admin_init', 'wpa__prelicense', 99 );

/* ===== Custom SEO Title ===== */
function wpa_title(){
    global $post;
    if(!defined('WPSEO_VERSION')) {
        if(is_404()) {
            echo '404 Page not found - ';
        } elseif((is_single() || is_page()) && $post->post_parent) {
            $parent_title = get_the_title($post->post_parent);
            echo wp_title('-', true, 'right') . $parent_title.' - ';
        } elseif(class_exists('Woocommerce') && is_shop()) {
            echo get_the_title(SHOP_ID) . ' - ';
        } else {
            wp_title('-', true, 'right');
        }
        bloginfo('name');
    } else {
        wp_title();
    }
}

/* Update wp-scss setings
   ========================================================================== */
if (class_exists('Wp_Scss_Settings')) {
    $wpscss = get_option('wpscss_options');
    if (empty($wpscss['css_dir']) && empty($wpscss['scss_dir'])) {
        update_option('wpscss_options', array('css_dir' => '/style/', 'scss_dir' => '/style/', 'compiling_options' => 'scss_formatter_compressed'));
    }
}

// echo img
function image_src($id, $size = 'full', $background_image = false, $height = false) {
    if ($image = wp_get_attachment_image_src($id, $size, true)) {
        return $background_image ? 'background-image: url('.$image[0].');' . ($height?'height:'.$image[2].'px':'') : $image[0];
    }
}

// SVG to WP
function cc_mime_types($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'cc_mime_types');

function wpa_fix_svg_thumb() {
    echo '<style>td.media-icon img[src$=".svg"], img[src$=".svg"].attachment-post-thumbnail {width: 100% !important;height: auto !important}</style>';
}
add_action('admin_head', 'wpa_fix_svg_thumb');

/* Fonts */

function wpa_fontbase64($fonthash) {
	$font = get_stylesheet_directory() . '/fonts.css';
	$md5 = filemtime( $font );
	$md5_cached = get_transient('fonts64_md5');
	if( $md5_cached !== $md5 ) {
		set_transient( 'fonts64_md5', $md5, 168 * 3600 );
	}
	if($fonthash) {
		echo $md5_cached?$md5_cached:$md5;
	} else {
		$minfont = file_get_contents( $font );
		$minfont = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $minfont);
		$minfont = str_replace(array(': ',' : '), ':', $minfont);
		$minfont = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $minfont);
		$minfont = str_replace(';}','}', $minfont);
		$fontpack = array(
			'md5'      => $md5_cached,
			'value'    => $minfont
		);
		echo json_encode($fontpack);
		exit;
	}
}
add_action('wp_ajax_wpa_fontbase64', 'wpa_fontbase64');
add_action('wp_ajax_nopriv_wpa_fontbase64', 'wpa_fontbase64');

// Contact form 7 remove <p>&<br>
if(defined('WPCF7_VERSION')) {
    function maybe_reset_autop( $form ) {
        $form_instance = WPCF7_ContactForm::get_current();
        $manager = WPCF7_ShortcodeManager::get_instance();
        $form_meta = get_post_meta( $form_instance->id(), '_form', true );
        $form = $manager->do_shortcode( $form_meta );
        $form_instance->set_properties( array(
            'form' => $form
        ) );
        return $form;
    }
    add_filter( 'wpcf7_form_elements', 'maybe_reset_autop' );
}

// Google Maps
if(defined('GOOGLEMAPS')) {
    /* google map shortcode
        *** Using [googlemap id="somemapid" coordinates="1 ,1" zoom="17" height="500px" infobox="<p>Some Infobox Content</p>"]
        *** if need street view, please add 'streetview="true"';
        *** if you need satellite view in 45 angle add 'tilt="45"';
        
        <?php if ($contact_map = get_field('contact_map', 'option')) { ?>
		    <?php echo do_shortcode('[googlemap id="contact_map" height="750px" icon="" coordinates="' .$contact_map['lat']. ', ' .$contact_map['lng']. '"][/googlemap]'); ?>
	    <?php } ?>
        
    */
    function google_map_js($atts, $content) {
        extract(shortcode_atts(array(
            'id'                => 'map_canvas',
            'coordinates'       => '1, 1',
            'zoom'              => 15,
            'height'            => '350px',
            'zoomcontrol'       => 'false',
            'scrollwheel'       => 'false',
            'scalecontrol'      => 'false',
            'disabledefaultui'  => 'false',
            'infobox'           => '',
            'satellite'         => '',
            'tilt'              => '',
            'icon'              => theme().'/images/marker.png',
            'streetview'        => ''
        ), $atts));
        $mapid = str_replace('-','_',$id);

        $map = !$streetview?'<div class="googlemap" id="'.$id.'" '.($height?'style="height:'.$height.'"':'').'></div><script>
    var '.$mapid.';
    function initialize_'.$mapid.'() {
        var myLatlng = new google.maps.LatLng('.$coordinates.');
        var mapOptions = {
            '.($satellite?'mapTypeId: google.maps.MapTypeId.SATELLITE,':'').'
            zoom: '.$zoom.',
            center: myLatlng,
            zoomControl: '.$zoomcontrol.',
            scrollwheel: '.$scrollwheel.',
            scaleControl: '.$scalecontrol.',
            disableDefaultUI: '.$disabledefaultui.'
            '.( $content ? ',styles:'.$content : '' ).'
        };
        var '.$mapid.' = new google.maps.Map(document.getElementById("'.$id.'"), mapOptions);
        '.($tilt?$mapid.'.setTilt(45);':'').'
        var marker = new google.maps.Marker({
            position: myLatlng,
            map: '.$mapid.',
            '.($icon?'icon:"'.$icon.'",':'').'
            animation: google.maps.Animation.DROP
        });
        '.($infobox?'marker.info = new google.maps.InfoWindow({content: \''.javascript_escape($infobox).'\'});
        google.maps.event.addListener(marker, "click", function() {marker.info.open('.$mapid.', marker);});':'').'

        google.maps.event.addListener('.$mapid.', "center_changed", function() {
            window.setTimeout(function() {
                '.$mapid.'.panTo(marker.getPosition());
            }, 15000);
        });
    };
    google.maps.event.addDomListener(window, "load", initialize_'.$mapid.');
    </script>':do_streetView_map($id, $coordinates, $height, $streetview);
        return $map;
    }
    add_shortcode('googlemap', 'google_map_js');

    function do_streetView_map($id, $pos, $height, $streetview){
        return '<div class="googlemap" id="street_'.$id.'" '.($height?'style="height:'.$height.'"':'').'></div><script>
        function street_init_'.$id.'() {


        var geocoder =  new google.maps.Geocoder();
        geocoder.geocode( { "address": "'.$streetview.'" }, function(results, status) {
            var lookTo = results[0].geometry.location;
            if (status == google.maps.GeocoderStatus.OK) {
                  var panoOptions = {
                    position: lookTo,
                    panControl: false,
                    addressControl: false,
                    linksControl: false,
                    zoomControlOptions: false
                  };
                  var pano = new  google.maps.StreetViewPanorama(document.getElementById("street_'.$id.'"),panoOptions);
                  var service = new google.maps.StreetViewService;
                  service.getPanoramaByLocation(pano.getPosition(), 50, function(panoData) {
                    if (panoData != null) {
                      var panoCenter = panoData.location.latLng;
                      var heading = google.maps.geometry.spherical.computeHeading(panoCenter, lookTo);
                      var pov = pano.getPov();
                      pov.heading = heading;
                      pano.setPov(pov);
                      var marker = new google.maps.Marker({
                        map: pano,
                        position: lookTo
                      });
                    } else {
                      alert("Not Found");
                    }
                  });
            } else {
                alert("Could not find your address");
            }
        });
        }
        google.maps.event.addDomListener(window, "load", street_init_'.$id.');</script>';
    }
} //end GOOGLEMAPS


// ACF Repeater Styles
function acf_repeater_even() {
	$scheme = get_user_option( 'admin_color' );
	$color = '';
	if($scheme == 'fresh') {
		$color = '#0073aa';
	} else if($scheme == 'light') {
		$color = '#d64e07';
	} else if($scheme == 'blue') {
		$color = '#52accc';
	} else if($scheme == 'coffee') {
		$color = '#59524c';
	} else if($scheme == 'ectoplasm') {
		$color = '#523f6d';
	} else if($scheme == 'midnight') {
		$color = '#e14d43';
	} else if($scheme == 'ocean') {
		$color = '#738e96';
	} else if($scheme == 'sunrise') {
		$color = '#dd823b';
	}
	echo '<style>.acf-repeater > table > tbody > tr:nth-child(even) > td.order {color: #fff !important;background-color: '.$color.' !important; text-shadow: none}</style>';
}
add_action('admin_footer', 'acf_repeater_even');

// Shortcode button
function content_btn($atts,$content){
    extract(shortcode_atts(array(
        'text' => 'Read More',
        'link' => site_url(),
        'class' => false,
        'target' => false
    ), $atts ));
    return '<a href="' . $link . '" class="button'.($class?' '.$class:'').'" '.($target?'target="'.$target.'"':'').'>' . $text . '</a>';
}
add_shortcode("button", "content_btn");

// Update ACF Goole Map Key
function my_acf_init()
{
    acf_update_setting('google_api_key', 'AIzaSyAOfuigGs3rWzZsdcWmQTeGU82RGccVqfc');
}
add_action('acf/init', 'my_acf_init');


function soc(){ ?>
	<?php if ($social = get_field('social_links', 'option' )) { ?>
        <div class="soc">
			<?php foreach ($social as $s) { ?>
                <a class="fa fa-<?php echo $s['icon_name'] ?>" href="<?php echo $s['link']; ?>" target="_blank" aria-hidden="true"></a>
			<?php } ?>
        </div>
	<?php } ?>
<?php } ?>
