<?php
/*
Plugin Name: Berri Technorati Reactions on Dashboard
Plugin URI: http://www.berriart.com/technorati-reactions-dashboard-plugin/
Description: Shows on the Dashboard the Technorati Reactions of your blog. Widget for the main admin page.
Author: Alberto Varela
Version: 2.1
Author URI: http://www.berriart.com

== CHANGELOG V2.1 ==

Support for Wordpress 2.7
Show favicons
Show text of the link
Show total reactions

*/

/******************************************************************************

My plugin is released under the GNU General Public License (GPL)
http://www.gnu.org/licenses/gpl.txt

Copyright 2008-2009  Alberto Varela  (email : alberto@berriart.com)

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
The license is also available at http://www.gnu.org/copyleft/gpl.html

*********************************************************************************/
 
// Add actions and filters
add_action('wp_dashboard_setup', 'berriTechnoratiReactions_register_dashboard_widget');

// Dashboard Widget Function
function berriTechnoratiReactions_register_dashboard_widget() {
	wp_add_dashboard_widget('dashboard_berriTechnoratiReactions', __('Technorati Reactions', 'berriTechnoratiReactions'), 'dashboard_berriTechnoratiReactions');
}
 
// Print Dashboard Widget
function dashboard_berriTechnoratiReactions($sidebar_args) {
	global $wpdb;
	
	include_once(ABSPATH . WPINC . '/rss.php');
	$tech_rss_feed = "http://feeds.technorati.com/cosmos/rss/?url=". trailingslashit(get_option('home')) ."&partner=wordpress";
	
	echo "<style type=\"text/css\">";
	echo "#dashboard_berriTechnoratiReactions h4 {font-family:Georgia,\"Times New Roman\",\"Bitstream Charter\",Times,serif;font-size:1.3em;padding:0.2em 10px 0.2em 30px;} ";
	echo "#dashboard_berriTechnoratiReactions h4 span {color:#aaa;} ";
	echo "#dashboard_berriTechnoratiReactions #btrbutton {float:right;padding:0.2em 0 0 0.2em;} ";
	echo "#dashboard_berriTechnoratiReactions .btritem {border-top:1px solid #DFDFDF;margin:0 -10px;padding:1em 10px 1em 30px;} ";
	echo "#dashboard_berriTechnoratiReactions img {float:left;padding:1em 0 0 0; } ";
	echo "#dashboard_berriTechnoratiReactions blockquote {border:1px solid #ccc; color:#888; font-style:italic;padding:0.5em;margin:0.8em 0 0 0; } ";
	echo "</style>";
	
	$rss = fetch_rss($tech_rss_feed);
	$rss->items = array_slice($rss->items, 0, 6);
	$channel = $rss->channel;
	echo "<div id=\"btrbutton\"><a class=\"button\" href=\"http://www.technorati.com/search/" . trailingslashit(get_option('home')) . "\">View all</a></div>";
	echo "<img style=\"padding:0.2em 0 0 0\" src=\"http://technorati.com/favicon.ico\" /><h4>" . get_option('blogname') . " <span>/ {$channel['tapi']['result_inboundlinks']} blog reactions</span></h4>";
	foreach ($rss->items as $item ) {
		$parsed_url = parse_url(wp_filter_kses($item['link']));
		echo "<img src=\"http://{$parsed_url['host']}/favicon.ico\" />";
		echo "<div class=\"btritem\">";
		echo "<a href=" . wp_filter_kses($item['link']) . ">" . wptexturize(wp_specialchars($item['title'])) . "</a> - [" . $parsed_url['host'] . "]";
		echo "<blockquote>" . wptexturize(wp_specialchars($item['description'])) . "</blockquote>";
		echo "</div>";
	}

}


?>
