<?
/* twitter-rss-to-html.php

Twitter-RSS-to-HTML for PHP by Rogers Cadenhead

Version 1.1
Web: http://www.cadenhead.org/workbench/twitter-rss-to-html
   
Copyright (C) 2008 Rogers Cadenhead

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
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA. */

// a PHP script to display Twitter updates from a user's RSS feed on a web page

require_once("magpie/rss_fetch.inc");

// set up script variables
// the user's RSS feed on Twitter
$remote_url = "http://twitter.com/statuses/user_timeline/PCCToday.rss";
// the number of tweets to display (or -1 to display all)
$tweets_to_display = 1;
// the page where the tweet(s) will be displayed
$page = "http://apps.facebook.com/pccnews/";
// the user's Twitter username
$username = "PCCToday";

$rss = fetch_rss($remote_url);

// start buffering output
ob_start();

$count = 1;
$now = time();
foreach ($rss->items as $item) {
	// limit the number of tweets displayed
	if ($tweets_to_display > 0) {
		if ($count > $tweets_to_display) {
			continue;
		}
	}
	$tweet = $item['description'];
	// ignore tweets linking back to page (if it is defined)
	if ($page != "") {
		if (!strpos($tweet, $page) === false) {
			continue;
		}
	}
	// figure out how recently the tweet was posted
	$when = ($now - strtotime($item['pubdate']));
	$posted = "";
	if ($when < 60) {
		$posted = $when . " Seconds Ago";
	}
	if (($posted == "") & ($when < 3600)) {
		$posted = "about " . (floor($when / 60)) . " Minutes Ago";
	}
	if (($posted == "") & ($when < 7200)) {
		$posted = "about 1 hour ago";
	}
	if (($posted == "") & ($when < 86400)) {
		$posted = "about " . (floor($when / 3600)) . " Hours Ago";
	}
	if (($posted == "") & ($when < 172800)) {
		$posted = "about 1 day ago";
	}
	if ($posted == "") {
		$posted = (floor($when / 86400)) . " Days Ago";
	}
	// filter the user's username out of tweets
	$tweet = str_replace($username . ": ", "", $tweet);
	// turn URLs into hyperlinks
	$tweet = preg_replace("/(http:\/\/)(.*?)\/([\w\.\/\&\=\?\-\,\:\;\#\_\~\%\+]*)/", "<a href=\"\\0\">{LINK}</a>", $tweet);
	// link to users in replies
	$tweet = preg_replace("(@([a-zA-Z0-9\_]+))", "<a href=\"http://www.twitter.com/\\1\">\\0</a>", $tweet);
	// add the time posted
	// $tweet = $tweet . "<br \/><span class=\"tweetwhen\">[ " . $posted . " ]</span>";
	echo ("" . $tweet);
	$count++;
}
	

// display output
ob_end_flush();
?>
