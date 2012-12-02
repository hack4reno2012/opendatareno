<?php
/**
 * @package Rate_Me
 * @version 0.1
 */
/*
Plugin Name: Rate Me
Plugin URI: https://github.com/colinloretz/rateme
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
		$post_rating = $sum / $count;

	// update post meta with new post rating
	update_post_meta($postID, 'wprm_post_rating', $post_rating);
}

function print_rating($atts) {
	$the_rating = get_post_meta(get_the_ID(), 'wprm_post_rating', true);
	
	if($the_rating == '')
		echo $the_rating;
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


function rateme_css() {

	echo '
	<style type="text/css">
	.rating {
  unicode-bidi: bidi-override;
  direction: rtl;
  text-align: center;
}
.rating > span {
  display: inline-block;
  position: relative;
  width: 1.1em;
}
.rating > span:hover,
.rating > span:hover ~ span {
  color: transparent;
}
.rating:hover {
	cursor: pointer;
}
.rating > span:hover:before,
.rating > span:hover ~ span:before {
   content: "\2605";
   position: absolute;
   left: 0; 
   color: gold;
}

.selected:before {
	content: "\2605";
	position: absolute;
	left: 0; 
	color: gold;
}
	</style>
	';
}

add_action( 'wp_head', 'rateme_css' );

function rateme_js() {

	echo '
	<script>
$(document).ready(function() {
	
	$(".rating span").click(function(){
		$(this).removeClass("selected");
		$(this).siblings().removeClass("selected");
		
		var num = $(this).attr("class");
		if(num == "one") num = 1;
		if(num == "two") num = 2;
		if(num == "three") num = 3;
		if(num == "four") num = 4;
		if(num == "five") num = 5;
		$("input[name=wprm_rating]"").val(num);
		
		$(this).addClass("selected");
		$(this).nextAll("span").addClass("selected");
	});

});
</script>
	';
}

add_action( 'wp_head', 'rateme_js' );
?>