<?php
header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time() + 3600));
header('Content-Type: text/html; charset=utf-8');
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header('X-UA-Compatible: IE=Edge,chrome=1');
/*
// HTML Compress
function ob_html_compress($buf){
return preg_replace(array('/<!--(?>(?!\[).)(.*)(?>(?!\]).)-->/Uis','/[[:blank:]]+/'),array('',' '),str_replace(array("\n","\r","\t"),'',$buf));
}
ob_start('ob_html_compress');
*/
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
    <title><?php wpa_title(); ?></title>
    <meta name="HandheldFriendly" content="True"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="theme-color" content="#6aa35b"><!--add color-->
    <meta name="format-detection" content="telephone=no">
    <link rel="shortcut icon" type="image/vnd.microsoft.icon" href="<?php echo theme('images/favicon.png'); ?>" sizes="16x16 32x32 48x48">
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo theme('images/favicon.png'); ?>" sizes="16x16">
    <link rel="icon" type="image/x-icon" href="<?php echo theme('images/favicon.png'); ?>" sizes="16x16">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>  data-hash="<?php wpa_fontbase64(true); ?>" data-a="<?php echo admin_url('admin-ajax.php'); ?>">
<main>
    <header>
        <div class="wrap">
            <a id="logo" href="<?php echo get_option('home') ?>">
                <img src="<?php echo theme('images/logo.png'); ?>" alt="logo">
            </a>

            <a href="tel:+1<?php echo preg_replace('/[^0-9]/', '', get_field("phone","option")); ?>" id="phone"><?php the_field("phone","option"); ?></a>

            <div id="menuOpen"></div>

            <div id="mainMenu">
                <?php wp_nav_menu(array('container' => false, 'items_wrap' => '<ul id="%1$s">%3$s</ul>', 'theme_location'  => 'main_menu')); ?>
            </div>
        </div>
    </header>
