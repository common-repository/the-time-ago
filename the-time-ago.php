<?php
/*
Plugin Name: The Time Ago
Plugin URI: http://wp-plugins.in/timeago-plugin
Description: One click convert date and time to "time ago" (e.g. 1 hour ago), easy to use, just one click! translation ready (to your language).
Version: 1.0.0
Author: Alobaidi
Author URI: http://wp-plugins.in
License: GPLv2 or later
*/

/*  Copyright 2015 Alobaidi (email: wp-plugins@outlook.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


defined( 'ABSPATH' ) or die( 'No script kiddies please!' );


// Add plugin meta links
function alobaidi_time_ago_plugin_row_meta( $links, $file ) {

	if ( strpos( $file, 'the-time-ago.php' ) !== false ) {
		
		$new_links = array(
						'<a href="http://wp-plugins.in/timeago-plugin" target="_blank">Explanation of Use</a>',
						'<a href="https://profiles.wordpress.org/alobaidi#content-plugins" target="_blank">More Plugins</a>',
						'<a href="http://j.mp/ET_WPTime_ref_pl" target="_blank">Elegant Themes</a>'
					);
		
		$links = array_merge( $links, $new_links );
		
	}
	
	return $links;
	
}
add_filter( 'plugin_row_meta', 'alobaidi_time_ago_plugin_row_meta', 10, 2 );


// Add settings page link in before activate/deactivate links.
function alobaidi_time_ago_plugin_action_links( $actions, $plugin_file ){
	
	static $plugin;

	if ( !isset($plugin) ){
		$plugin = plugin_basename(__FILE__);
	}
		
	if ($plugin == $plugin_file) {
		
		if ( is_ssl() ) {
			$settings_link = '<a href="'.admin_url( 'plugins.php?page=alobaidi_time_ago_settings', 'https' ).'">Settings</a>';
		}else{
			$settings_link = '<a href="'.admin_url( 'plugins.php?page=alobaidi_time_ago_settings', 'http' ).'">Settings</a>';
		}
		
		$settings = array($settings_link);
		
		$actions = array_merge($settings, $actions);
			
	}
	
	return $actions;
	
}
add_filter( 'plugin_action_links', 'alobaidi_time_ago_plugin_action_links', 10, 5 );


// Add settings page
include ( plugin_dir_path( __FILE__ ).'/settings.php' );


// Time ago function
function alobaidi_time_ago() {
	global $post;
	$from = get_post_time('U', false, $post->ID, false); // get post time
	$to = current_time('timestamp'); // get current time
	$diff = (int) abs( $to - $from );
	include ( plugin_dir_path( __FILE__ ).'/if_else.php' ); // some conditionals
	include ( plugin_dir_path( __FILE__ ).'/calculator.php' ); // time and date calculator
	return $since; // display time ago
}

if( !is_admin() ){
	add_filter('human_time_diff', 'alobaidi_time_ago'); // return alobaidi_time_ago() instead of human_time_diff()
	add_filter('get_the_date', 'alobaidi_time_ago'); // return alobaidi_time_ago() instead of get_the_date()
	add_filter('get_the_time', 'alobaidi_time_ago'); // return alobaidi_time_ago() instead of get_the_time()
	add_filter('the_time', 'alobaidi_time_ago'); // return alobaidi_time_ago() instead of th_time()
}


// Display time ago to comments date
if( get_option('wptta_comments') and !is_admin() ){	
	function alobaidi_time_ago_comments() {
		$from = get_comment_time('U'); // get comment time
		$to = current_time('timestamp'); // get current time
		$diff = (int) abs( $to - $from );
		include ( plugin_dir_path( __FILE__ ).'/if_else.php' ); // some conditionals
		include ( plugin_dir_path( __FILE__ ).'/calculator.php' ); // time and date calculator
		return $since; // display time ago
	}
	add_filter('get_comment_date', 'alobaidi_time_ago_comments'); // return alobaidi_time_ago() instead of get_comment_date()
}

?>