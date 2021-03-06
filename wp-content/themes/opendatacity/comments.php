<?php
/**
 * @package WordPress
 * @subpackage Default_Theme
 */

// Do not delete these lines
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die (_e('Please do not load this page directly. Thanks!', 'BlueBubble'));

	if ( post_password_required() ) { ?>
		<p class="nocomments"><?php _e('This post is password protected. Enter the password to view comments.', 'BlueBubble') ?></p>
	<?php
		return;
	}
?>

<!-- You can start editing here. --> 

<?php if ( comments_open() ) : ?>

<div id="respond">

<h2><?php comment_form_title( 'Ratings/Comments'); ?> <span class = "avgRating">Average Rating: <?php echo do_shortcode('[showrating pid='.$post->id.']'); ?></span></h2>

<div class="cancel-comment-reply">
	<small><?php cancel_comment_reply_link(); ?></small>
</div>

<?php if ( get_option('comment_registration') && !is_user_logged_in() ) : ?>
<?php _e('<p>You must be <a href="<?php echo wp_login_url( get_permalink() ); ?>">logged in</a> to post a comment.</p>', 'BlueBubble') ?>
<?php else : ?>

<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">

<!--<p><small><strong>XHTML:</strong> You can use these tags: <code><?php echo allowed_tags(); ?></code></small></p>-->
                                                                                                   
<p><textarea name="comment" id="comment" cols="58" rows="10" tabindex="4"></textarea></p>

<p>
	<div style = "float: left; width: 150px;">
	<strong>Rate this data</strong>
	<div class="rating">
		<span class = "five">☆</span><span class = "four">☆</span><span class = "three">☆</span><span class = "two">☆</span><span class = "one">☆</span>
	</div>
	<input type = "hidden" name = "wprm_rating" />
	</div>
<input name="submit" type="submit" id="submit" tabindex="5" value="Submit" style = "float: right; margin-top: 0px; padding: 10px;"/><div style = "clear: both"></div>
<?php comment_id_fields(); ?>
</p>
<?php do_action('comment_form', $post->ID); ?>

</form>

<?php endif; // If registration required and not logged in ?>  

<?php if ( have_comments() ) : ?>

	<div class="navigation">
		<div class="alignleft"><?php previous_comments_link() ?></div>
		<div class="alignright"><?php next_comments_link() ?></div>
	</div>

	<div id="commentlist">
		<ol class="commentlist">
			<?php wp_list_comments("avatar_size=64&style=li"); ?>
		</ol>
	</div>

	<div class="navigation">
		<div class="alignleft"><?php previous_comments_link() ?></div>
		<div class="alignright"><?php next_comments_link() ?></div>
	</div>
 <?php else : // this is displayed if there are no comments so far ?>

	<?php if ( comments_open() ) : ?>
		<!-- If comments are open, but there are no comments. -->

	 <?php else : // comments are closed ?>
		<!-- If comments are closed. -->
		<p class="nocomments"><?php _e('Comments are closed.', 'BlueBubble') ?></p>

	<?php endif; ?>
<?php endif; ?>  
</div> 




<?php endif; // if you delete this the sky will fall on your head ?>
