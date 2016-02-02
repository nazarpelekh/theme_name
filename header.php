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
    <title><?php seo_title(); ?></title>
    <meta name="HandheldFriendly" content="True"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <link rel="shortcut icon" href="<?php theme(); ?>/favicon.ico" type="image/x-icon"/>
    <meta name="theme-color" content="#6aa35b">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<main>
    <header>
        <div class="wrap">
            <a id="logo" href="<?php echo get_option('home') ?>">
                <img src="<?php theme(); ?>/images/logo.png" alt="logo">
            </a>

            <a href="tel:+1<?php echo preg_replace('/[^0-9]/', '', get_field("phone","option")); ?>" id="phone"><?php the_field("phone","option"); ?></a>

            <div id="menuOpen">Menu</div>
            <nav id="mainMenu">
                <?php wp_nav_menu(array('container' => false, 'items_wrap' => '<ul id="%1$s">%3$s</ul>', 'theme_location'  => 'main_menu')); ?>
            </nav>
        </div>
    </header>
