<?php
/*
Template Name: Contact
*/
?>
<?php 
//If the form is submitted
if(isset($_POST['submitted'])) {

	//Check to see if the honeypot captcha field was filled in
	if(trim($_POST['checking']) !== '') {
		$captchaError = true;
	} else {
	
		//Check to make sure that the name field is not empty
		if(trim($_POST['contactName']) === '') {
			$nameError = _e('You forgot to enter your name.');
			$hasError = true;
		} else {
			$name = trim($_POST['contactName']);
		}
		
		//Check to make sure sure that a valid email address is submitted
		if(trim($_POST['email']) === '')  {
			$emailError = 'You forgot to enter your email address.';
			$hasError = true;
		} else if (!eregi("^[A-Z0-9._%-]+@[A-Z0-9._%-]+\.[A-Z]{2,4}$", trim($_POST['email']))) {
			$emailError = _e('You entered an invalid email address.');
			$hasError = true;
		} else {
			$email = trim($_POST['email']);
		}
			
		//Check to make sure comments were entered	
		if(trim($_POST['comments']) === '') {
			$commentError = _e('You forgot to enter your message.');
			$hasError = true;
		} else {
			if(function_exists('stripslashes')) {
				$comments = stripslashes(trim($_POST['comments']));
			} else {
				$comments = trim($_POST['comments']);
			}
		}
			
		//If there is no error, send the email
		if(!isset($hasError)) {

			if ( get_option('bb_contact_email') ) {  $emailTo = get_option('bb_contact_email'); 
                }else{ $emailTo = get_option('admin_email'); } //change this with your email address
			$subject = 'Website email from '.$name;
			$sendCopy = trim($_POST['sendCopy']);
			$body = "Name: $name \n\nEmail: $email \n\nMessage: $comments";
			$headers = 'From: <'.$emailTo.'>' . "\r\n" . 'Reply-To: ' . $email;
			
			mail($emailTo, $subject, $body, $headers);

			if($sendCopy == true) {
				$subject = 'You emailed <'.$emailTo.'>';
				$headers = 'From: <'.$emailTo.'>';
				mail($email, $subject, $body, $headers);
			}

			$emailSent = true;

		}
	}
} ?>



<?php get_header(); ?>

<?php
//allows the theme to get info from the theme options page
global $options;
foreach ($options as $value) {
    if (get_settings( $value['id'] ) === FALSE) { $$value['id'] = $value['std']; } else { $$value['id'] = get_settings( $value['id'] ); }
}
?>

	<div id="content">          


<!-- jQuery GOES HERE (VALIDATION AND AJAX PROCESSING) -->

	<div id="main">
<?php if(isset($emailSent) && $emailSent == true) { ?>	

    <?php if ( get_option( 'bb_contact_msg' ) ) { ?>
	<div class="thanks">
    <p><?php _e(get_option( 'bb_contact_msg' )); ?></p> 
    </div>
    <?php }else{ ?>
    <div class="thanks">
    <p><?php _e('Thanks for sending an email.', 'BlueBubble') ?></p>
    </div>
	<?php } // End check for custom thank-you message ?>

<?php } else { ?>
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

			<div class="postsingle" id="post-<?php the_ID(); ?>">

				<h1><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a><?php if (get_option('bb_no_tweet') == '') { ?><a href="http://twitter.com/share" class="twitter-share-button" data-count="horizontal" data-via="<?php echo get_option('bb_twitter_name') ?>">Tweet</a>
<?php } // Check for Tweet Button Off ?></h1>

				<div class="entry">

					<?php the_content(); ?>

				</div>

			

		<?php endwhile; endif; ?><?php } ?>

<div id="contact-form">
		<form action="<?php the_permalink(); ?>" id="contactForm" method="post">
	
			<ol class="forms">
				<li><label for="contactName"><?php _e('Name', 'BlueBubble') ?></label>
					<input type="text" name="contactName" id="contactName" value="<?php if(isset($_POST['contactName'])) echo $_POST['contactName'];?>" class="requiredField" />
					<?php if($nameError != '') { ?>
						<span class="error"><?=$nameError;?></span> 
					<?php } ?>
				</li>
				
				<li><label for="email"><?php _e('Email', 'BlueBubble') ?></label>
					<input type="text" name="email" id="email" value="<?php if(isset($_POST['email']))  echo $_POST['email'];?>" class="requiredField email" />
					<?php if($emailError != '') { ?>
						<span class="error"><?=$emailError;?></span>
					<?php } ?>
				</li>
				
				<li class="textarea"><label for="commentsText"><?php _e('Message', 'BlueBubble') ?></label>
					<textarea name="comments" id="commentsText" rows="20" cols="30" class="requiredField"><?php if(isset($_POST['comments'])) { if(function_exists('stripslashes')) { echo stripslashes($_POST['comments']); } else { echo $_POST['comments']; } } ?></textarea>
					<?php if($commentError != '') { ?>
						<p class="error"><?=$commentError;?></p> 
					<?php } ?>
				</li>
				<li class="inline"><input type="checkbox" name="sendCopy" id="sendCopy" value="true"<?php if(isset($_POST['sendCopy']) && $_POST['sendCopy'] == true) echo ' checked="checked"'; ?> /> <label for="sendCopy"><?php _e('Send a copy of this email to yourself', 'BlueBubble') ?></label></li>
				<li class="screenReader"><label for="checking" class="screenReader">If you want to submit this form, do not enter anything in this field</label><input type="text" name="checking" id="checking" class="screenReader" value="<?php if(isset($_POST['checking']))  echo $_POST['checking'];?>" /></li>
				<li class="buttons"><input type="hidden" name="submitted" id="submitted" value="true" /><button type="submit"><?php _e('Send', 'BlueBubble') ?></button></li>
			</ol>
		</form>
	
    </div>
    </div>
    </div>
    </div>

<?php get_sidebar('standard'); ?>
<?php get_footer(); ?>

