</div> <!-- END #container -->
<div id="footer">
<div id="footer-content">
<div class="copyright"><p><?php echo stripslashes(get_option('bb_footer_text')); ?></p></div>
<div class="footer-nav"><?php wp_nav_menu( array( 'sort_column' => 'menu_order', 'container_class' => 'footer-nav', 'theme_location' => 'second-menu' ) ); ?></div>
</div>
</div>
<?php if ( get_option( 'bb_twitter' ) ) { ?>
<script src="http://twitter.com/javascripts/blogger.js"></script>  
<script src="http://twitter.com/statuses/user_timeline/<?php echo get_option('bb_twitter_name'); ?>.json?callback=twitterCallback2&count=<?php if ( get_option('bb_twitter_num') ) { ?><?php echo get_option('bb_twitter_num'); ?>"> 
<?php }else{ ?><?php echo ('2') ?>
<?php } // End check for Twitter Count Number ?>"></script><?php } // End check for Twitter ?> 

<?php if ( get_option('bb_ga_code') ) { ?><!-- START Google Analytics Code -->
<script><?php echo stripslashes(get_option('bb_ga_code')); ?></script>
<!-- END Google Analytics Code --> 
<?php } // End check for Google Analytics ?>

</body>
</html>