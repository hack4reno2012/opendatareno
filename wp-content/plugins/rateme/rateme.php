<?php
/**
 * @package Rate_Me
 * @version 0.1
 */
/*
Plugin Name: Rate Me
Plugin URI: n/a
Description: Post rating plugin built for Open Data Catalog
Author: Colin Loretz
Version: 0.1
Author URI: https://github.com/colinloretz
*/

function add_rating($comment_id) {

	// add rating to the comment
	add_comment_meta($comment_id, 'wprm_rating', $_POST['wprm_rating'], true);

	// calculate_post_rating
	calculate_post_rating($comment_id);
}


function calculate_post_rating($comment_id) {

	$comment = get_comment($comment_id);
	$postID = $comment->comment_post_ID;

   	// get all comments for this post
	$post_comments = get_approved_comments($postID);

	$sum = 0;
	$count = 0;

	// get the sum for all ratings for all comments
	foreach($post_comments as $comment) {
		$comment_rating = get_comment_meta($comment->comment_ID, 'wprm_rating', true);
		
		if($comment_rating != '')
		{
			$comment_rating = intval($comment_rating);
			$sum += $comment_rating;
			$count++;
		}
	}

	// calculate average rating for post
	if($count != 0)
	{
		$post_rating = $sum / $count;
		$post_rating = round($post_rating);
	}

	// update post meta with new post rating
	update_post_meta($postID, 'wprm_post_rating', $post_rating);
}

function print_rating($atts) {
	$the_rating = get_post_meta(get_the_ID(), 'wprm_post_rating', true);
	
	if($the_rating != '')
		echo $the_rating .' / 5';
	else
		echo "not rated";
}

add_shortcode('showrating', 'print_rating');

// Comment has been added, save the individual rating and calculate post rating
add_action('comment_post', 'add_rating');

// Comment has been deleted, recalculate the overall post rating
add_action('transition_comment_status', 'calculate_post_rating');
add_action('deleted_comment', 'calculate_post_rating');
add_action('trashed_comment', 'calculate_post_rating');
add_action('untrashed_comment', 'calculate_post_rating');

// Styles & Javascripts
function wprm_rateme_scripts() {
	wp_register_script('rateme_js', plugins_url('/assets/rateme.js', __FILE__), array('jquery'));
	wp_enqueue_script('rateme_js');
}
add_action('wp_enqueue_scripts', 'wprm_rateme_scripts');

function wprm_rateme_styles() {
	wp_register_style('rateme_css', plugins_url('/assets/rateme.css', __FILE__));
	wp_enqueue_style('rateme_css');
}
add_action('wp_enqueue_scripts', 'wprm_rateme_styles');

?>