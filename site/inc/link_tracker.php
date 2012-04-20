<?php

// Here's some general backdrop.  There is very much reason 
// to track visits to urls either on your app, or send in notifications/e-mails from your app 
// And, there isn't a system for easily tracking clicks to links.  
//
// This class is an attempt to solve this problem.  
// The track_ functions are used to add parameters to a url in order to 
// track the number of visits to it.  These functions will add a GET event parameter 
// that will specify an event handler method (which must defined as a part of this class
// or in a class that extends this one).
//
// The get parameters the track_ functions add all are prepended with t_ to prevent 
// clashes w/ what whatever get parameters you might be using and urlencoded for you
// protection ;)
//
// You will notice further down in this file, the t_ functions.  These are the event handlers.
// You may define your own if you ever decide to extend this class.  These event handlers get
// called whenever those links you modified are clicked.  In addition, they are passed the params
// associative array that you passed in way back when you modified the link.  
//  
// One important note here is that the params array is now no longer to be trusted, as it 
// had a chance to be out in the wild.  t_ functions will normally just increment some
// stat variable like notification_return_visit, though you can also do other fancy things
// like save the sender_id if you so please, whatever you want.
//
// Also, in order for your t_ functions to work, you must insert a $link_tracker->track_handler() 
// call whereever you want your event handlers to be called.  This maybe at the top 
// of your app code and/or in a separate track.php file and is accessed via a callback url...
//
// Alrighty kids, hopefully this will be going in the wiki someday, until then, enjoy.
//
// Word of warning, once a link is out there that uses a certain event handler,
// that thing won't be tracked unless the event handler exists...
//
// One last thing:
// Make a generic link track function that increments a certain labeled for a link
// You can generate this from a simple form
//
// Don't redirect on your post add page otherwise you won't be able to track new adds...
//
// Add timestamp to urls to see how long it takes for people to respond to link clicks
//
// 
// @author Wayne Mak
//
class Link_Tracker {
	var $track_url;

	// If you want to use track_handler function, you need to specify a track url
	// passed into the constructor here, this track url will be used
	// by the track function, when your urls are modified (unless you decide not 
	// to use a url that will redirect)
	function __construct($t = '') {
		$this->track_url = $t;
	}

	function set_track_url($t) {
		$this->track_url = $t;
	}

	function get_track_url() {
		return $this->track_url;
	}

	/**
	 * This function modifies a URL so that it can be tracked.  
	 *
	 * An example modification:
	 * $url = 'http://google.com';
	 * $event_handler = 'url_visit';
	 * $params = 'array('label' => 'google_visit');
	 * $redirect = true;
	 *
	 * $new_url: 
	 * http://mytrackurl.com/?t_event=url_visit&t_label=google_visit&t_target=http://google.com
	 *
	 * $url 	 	- Self explanatory
	 * $event_handler 	- The name of a method (string) that belongs to 
	 * 			  the link_track class, that will serve as an 
	 * 			  event handler when the link is clicked.
	 * $params 		- An associative array of data you'd like passed to event_handler
	 * $redirect		- False will just append event parameters to the current url
	 */ 
	function track($url, $event_handler, $params = array(), $redirect = true) {
		if (empty($event_handler)) {
			return;
		}
		if ($redirect == true) {
			$query_str = '?t_event=' . urlencode($event_handler) . '&';
			if (!empty($params)) {
				foreach($params as $pname => $pvalue) {
					$query_str .= 't_' . urlencode($pname) . 
						      '=' . urlencode($pvalue) . '&';
				}
			}
			$tracked_link = $this->track_url . $query_str . 't_target=' . urlencode($url);
		}
		else {
			if (strpos($url, '?') == false) {
				$sep = '?';
			}
			else {
				$sep = '&';
			}
			$query_str = $sep . 't_event=' . urlencode($event_handler);
			foreach($params as $pname => $pvalue) {
				$query_str .= '&t_' . urlencode($pname) . 
					      '=' . urlencode($pvalue);
			}
			$tracked_link = $url . $query_str;
		}
		return $tracked_link;
	}

	function handle_event() {
		// Retrieve params
		$params = array();
		foreach($_GET as $pname => $pvalue) {
			if (substr($pname, 0, 2) == "t_") {
				$params[urldecode(substr($pname, 2))] = urldecode($pvalue);
			}
		}

		// Retrieve the target url
		$target_url = urldecode($_GET['t_target']);

		// Retrieve referrer url
		$referrer_url = $_SERVER['HTTP REFERRER'];

		// Retrieve name of event handler
		$event_handler = urldecode($_GET['t_event']);

		// Necessary for preventing evil user from setting methods him/herself
		$valid_methods = get_class_methods($this);

		// Add $_GET['ref'] when FB implements this in full...
		if (!empty($event_handler) && in_array('t_' . $event_handler, $valid_methods)) {
			$event_handler = 't_' . $event_handler;
			$this->{$event_handler}($params, $target_url, $referrer_url);
		}

		// Redirect to the target
		if (isset($_GET['t_target'])) {
			header('Location: ' . $target_url);
		}
	}
}

?>
