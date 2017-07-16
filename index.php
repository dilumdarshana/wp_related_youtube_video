<?php
/*
Plugin Name: Lyrics15 Related Youtube Videos
Plugin URI: http://google.com/
Description: Pick related youtube video for the given post title. This uses youtube V3 APIs
Version: 1.0
Author: Dilum Darshana
Author URI: http://google.com
License: GPL
*/

$pluginPath = dirname( __FILE__ ) . DIRECTORY_SEPARATOR;


if( !class_exists( 'Lyrics15YoutubeVideos' ) ) {
  require_once $pluginPath. 'Lyrics15YoutubeVideos.php';
}

/**
 * The Plugin Object
 */
$Lyrics15YoutubeVideos = new Lyrics15YoutubeVideos($pluginPath);


?>
