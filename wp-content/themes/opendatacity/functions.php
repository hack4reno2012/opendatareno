<?php

function odc_linkify_urls( $content ) {
	$content = preg_replace( "#(https?|ftp)://[^\s<]+[^\s.,><)\];'\"!?]#", '<a href="\\0">\\0</a>', $content );
	return $content;
}

function odc_register_post_types() {
	// nominations post type

	$labels = array(
		'name' => 'Nominations',
		'singular_name' => 'Nomination', 
		'add_new' => 'Add New',
		'add_new_item' => 'Add New Nomination',
		'edit_item' => 'Edit Nomination',
		'new_item' => 'New Nomination',
		'all_items' => 'All Nominations', 
		'view_item' => 'View Nominations', 
		'search_items' => 'Search Nominations', 
		'not_found' => 'No nominations found', 
		'not_found_in_trash' => 'No nominations found in Trash',
		'parent_item_colon' => '',
		'menu_name' => 'Nominations',
	);
	$args = array(
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true, 
		'show_in_menu' => true, 
		'query_var' => true,
		'rewrite' => array( 'slug' => 'nomination' ),
		'capability_type' => 'post',
		'has_archive' => true, 
		'hierarchical' => false,
		'menu_position' => null,
		'supports' => array( 'title', 'editor', 'author', 'comments', 'custom-fields' ),
	); 
	register_post_type( 'nomination', $args );
}
add_action( 'init', 'odc_register_post_types' );

/*
* Stuff below is from the blue bubble theme and should be ruthlessly decommissioned!
*/

// Make theme available for translation
// Translations can be filed in the /languages/ directory
load_theme_textdomain( 'BlueBubble', TEMPLATEPATH . '/languages' );  
  

function get_custom_field($key, $echo = FALSE) {
	global $post;
	$custom_field = get_post_meta($post->ID, $key, true);
	if ($echo == FALSE) return $custom_field;
	echo $custom_field;
}
                        
/* VOTING */
function ShowPostByVotes($query) {

	global $wpdb, $voteiu_databasetable;
	$upperlimit = get_option('voteiu_limit');
	if ($upperlimit == '') { $upperlimit = 100; } $lowerlimit = 0;

	$votesarray = array();

        $pageposts = $wpdb->get_results($query, OBJECT);

	$query_limited = $query." LIMIT ".$lowerlimit.", ".$upperlimit;

	$posttablecontents = mysql_query( $query_limited );

	while ($row = mysql_fetch_array($posttablecontents)) {
		$post_id = $row['ID'];
		$vote_array = GetVotes($post_id, "array");
		array_push($votesarray, array(GetVotes($post_id)));
	}
	array_multisort($votesarray, SORT_DESC, $pageposts);
	$output = $pageposts;
	return $output;
}


/*Sidebar Widget*/

if ( function_exists('register_sidebar') )
    register_sidebar(array(
    	'name' => 'Sidebar',
        'before_widget' => '<div id="widget">',
        'after_widget' => '</div>',
        'before_title' => '<h2>',
        'after_title' => '</h2>',
    ));


// New and changed widgets in BlueBubble
require_once('includes/bb-widgets.php');


/* Add Post Image Theme Support */
add_theme_support( 'post-thumbnails' );
add_image_size( 'portfolio-thumb', 310, 150, true );
add_image_size( 'portfolio-big', 657, 318, true );


/* Enable Custom Background WP 3.0 Feature */
add_custom_background();

/* Making Menus Compatible with Wordpress 3.0 */
add_theme_support('menus');
add_action( 'init', 'register_my_menus' );

function register_my_menus() {
	register_nav_menus(
		array(
			'first-menu' => __( 'main', 'Main Menu' ),
			'second-menu' => __( 'footer', 'Footer Menu' ),
			'third-menu' => __( '404', '404 Menu' ),
			'fourth-menu' => __( 'Top', 'Top Menu' ),
		)
	);
}

?>
<?php if ( get_option('bb_custom_login') ) {
/* Custom Login Screen */
function custom_login() { 
echo '<link rel="stylesheet" type="text/css" href="'.get_bloginfo('template_directory').'/css/custom-login/custom-login.css" />'; 
}
add_action('login_head', 'custom_login');
}  /* End of Custom Login Screen */ 


/* Limit Title Characters */
function short_title($before = '', $after = '', $echo = true, $length = false) {
$title = get_the_title();

if ( $length && is_numeric($length) && strlen($title) >= $length) {
$title = substr( $title, 0, $length ); // Trim title to 40 characters
$lastSpace = strrpos($title, ' '); // Locate the last space in the title
$title = substr($title, 0, $lastSpace); // Trim the portion of the last word off
}

if ( strlen($title)> 0 ) {

$title = apply_filters('short_title', $before . $title . $after, $before, $after);

if ( $echo )

echo $title;

else

return $title;

}

}


/* Remove Wordpress Ver. Number from HTML - For Security Reasons */

function bb_remove_version() {
return '';
}
add_filter('the_generator', 'bb_remove_version');



/* Add Custom User Profile Fields*/

	// Add Custom User Contact Fields and remove Default Fields
	function add_contactmethod( $contactmethods ) {

		// Add Telephone
	    $contactmethods['phone'] = (__('Phone', 'BlueBubble'));
		
		// Add Facebook
	    $contactmethods['facebook'] = 'Facebook';
		
	    // Add Twitter
	    $contactmethods['twitter'] = 'Twitter';
		
		// Add Skype
	    $contactmethods['skype'] = 'Skype';
	 
	    // Remove User Contact Fields
	    unset($contactmethods['yim']);
	    unset($contactmethods['aim']);
		unset($contactmethods['jabber']);
		
	    return $contactmethods;
	}
	add_filter('user_contactmethods','add_contactmethod',10,1);



/*Custom Write Panel*/

$meta_boxes =
	array(
		"image" => array(
			"name" => "post_image",
			"type" => "text",
			"std" => "",
			"title" => _("Image"),
			"description" => __("Using the \"<em>Add an Image</em>\" button, upload an image and paste the URL here.", 'BlueBubble'))
	);


function meta_boxes() {
	global $post, $meta_boxes;
	
	echo'
		<table class="widefat" cellspacing="0" id="inactive-plugins-table">
		
			<tbody class="plugins">';
	
			foreach($meta_boxes as $meta_box) {
				$meta_box_value = get_post_meta($post->ID, $pre.'_value', true);
				
				if($meta_box_value == "")
					$meta_box_value = $meta_box['std'];
				
				echo'<tr>
						<td width="100" align="center">';		
							echo'<input type="hidden" name="'.$meta_box['name'].'_noncename" id="'.$meta_box['name'].'_noncename" value="'.wp_create_nonce( plugin_basename(__FILE__) ).'" />';
							echo'<h2>'.$meta_box['title'].'</h2>';
				echo'	</td>
						<td>';
							echo'<input type="text" name="'.$meta_box['name'].'_value" value="'.get_post_meta($post->ID, $meta_box['name'].'_value', true).'" size="100" /><br />';
							echo'<p><label for="'.$meta_box['name'].'_value">'.$meta_box['description'].' </label></p>';
				echo'	</td>
					</tr>';
			}
	
	echo'
			</tbody>
		</table>';		
}


/* Shortcodes */
	function alert($atts, $content = null) {
	    return '<div class="alert">'.$content.'</div></br>';}
    add_shortcode('alert', 'alert');
	function dload($atts, $content = null) {
	    return '<div class="dload">'.$content.'</div></br>';}
    add_shortcode('dload', 'dload');
	function info($atts, $content = null) {
	    return '<div class="info">'.$content.'</div></br>';}
    add_shortcode('info', 'info');
	function idea($atts, $content = null) {
	    return '<div class="idea">'.$content.'</div></br>';}
    add_shortcode('idea', 'idea');

function gbutton($atts, $content = null) {
	extract(shortcode_atts(array(
		"url" => ''
	), $atts));
	return '<a class="gbutton" href="'.$url.'">'.$content.'</a>';
}
add_shortcode('gbutton', 'gbutton');


function bbutton($atts, $content = null) {
	extract(shortcode_atts(array(
		"url" => ''
	), $atts));
	return '<a class="bbutton" href="'.$url.'">'.$content.'</a>';
}
add_shortcode('bbutton', 'bbutton');


/* Adds Shortcodes to Wordpress Editor */
add_action('init', 'add_button');

function add_button() {
   if ( current_user_can('edit_posts') &&  current_user_can('edit_pages') )
   {
     add_filter('mce_external_plugins', 'add_plugin');
     add_filter('mce_buttons_3', 'register_button');
   }
}

function register_button($buttons) {
   array_push($buttons, "alert", "dload", "info", "idea", "|", "gbutton", "bbutton");
   return $buttons;
}

function add_plugin($plugin_array) {
   $plugin_array['alert'] = get_bloginfo('template_url').'/scripts/customcodes.js';
   $plugin_array['dload'] = get_bloginfo('template_url').'/scripts/customcodes.js';
   $plugin_array['info'] = get_bloginfo('template_url').'/scripts/customcodes.js';
   $plugin_array['idea'] = get_bloginfo('template_url').'/scripts/customcodes.js';
   $plugin_array['gbutton'] = get_bloginfo('template_url').'/scripts/customcodes.js';
   $plugin_array['bbutton'] = get_bloginfo('template_url').'/scripts/customcodes.js';
   return $plugin_array;
}


/* Allows Shortcodes in Sidebars */
add_filter('widget_text', 'do_shortcode');


/*Start of Theme Options*/

$themename = "BlueBubble";
$shortname = "bb";

$categories = get_categories('hide_empty=0&orderby=name');
$wp_cats = array();
foreach ($categories as $category_list ) {
       $wp_cats[$category_list->cat_ID] = $category_list->cat_name;
}
array_unshift($wp_cats,_("Choose a category")); 


$options = array (

array( "name" => $themename." Options",
	"type" => "title"),

array( "name" => __("General", 'BlueBubble'),
	"type" => "section"),
array( "type" => "open"),
	
array( "name" => __("Color Scheme", 'BlueBubble'),
	"desc" => __("Choose a color scheme for the website.", 'BlueBubble'),
	"id" => $shortname."_color_scheme",
	"type" => "select",
	"options" => array( __("light gray (default)", 'BlueBubble'), __("white", 'BlueBubble'), __("lime", 'BlueBubble'), __("forest", 'BlueBubble'), __("red", 'BlueBubble'), __("blue", 'BlueBubble'), __("coffee", 'BlueBubble'), __("black", 'BlueBubble')),
	"std" =>__("light gray (default)", 'BlueBubble')),

array( "name" => __("Feedburner URL", 'BlueBubble'),
	"desc" => __("Feedburner is a Google service that takes care of your RSS feed. Paste your Feedburner URL here to let readers see it in your website", 'BlueBubble'),
	"id" => $shortname."_feedburner",
	"type" => "text",
	"std" => get_bloginfo('rss2_url')),

array( "name" => __("Footer copyright text", 'BlueBubble'),
	"desc" => __("Enter text used in the left side of the footer. It can be HTML.", 'BlueBubble'),
	"id" => $shortname."_footer_text",
	"type" => "textarea",
	"std" => ""),
	
array( "name" => __("Google Analytics Code", 'BlueBubble'),
	"desc" => __("You can put your Google Analytics code or code from another tracking service here if you want.  It is automatically added to the footer. Just paste your code, without the &lt;script&gt;&lt;/script&gt; tags.", 'BlueBubble'),
	"id" => $shortname."_ga_code",
	"type" => "textarea",
	"std" => ""),
			
array( "name" => __("Custom CSS", 'BlueBubble'),
	"desc" => __("Place here any custom CSS you might need. (Note: This overrides any other stylesheets)", 'BlueBubble'),
	"id" => $shortname."_custom_css",
	"type" => "textarea",
	"std" => ""),

array( "type" => "close"),

array( "name" => __("Horizontal Top Menu", 'BlueBubble'),
	"desc" => __("Check if you want a horizontal menu on the top-right of your website, to the right of the logo.", 'BlueBubble'),
	"id" => $shortname."_top_menu",
	"type" => "checkbox",
	"std" => ""),
	
array( "name" => __("Legacy Menu Style", 'BlueBubble'),
	"desc" => __("Check if you want to <strong>turn on</strong> the old BlueBubble navigation menu style, used before BlueBubble 3.0", 'BlueBubble'),
	"id" => $shortname."_old_menus",
	"type" => "checkbox",
	"std" => ""),
	
array( "name" => __("Sidebar Login", 'BlueBubble'),
	"desc" => __("Check if you want to <strong>turn on</strong> a login form in the sidebar", 'BlueBubble'),
	"id" => $shortname."_login_menu",
	"type" => "checkbox",
	"std" => ""),

array( "name" => __("Custom Login", 'BlueBubble'),
	"desc" => __("Check if you want to <strong>turn on</strong> a custom login screen. (replaces default Wordpress login screen.)", 'BlueBubble'),
	"id" => $shortname."_custom_login",
	"type" => "checkbox",
	"std" => ""),
		
array( "name" => __("Sidebar On Right", 'BlueBubble'),
	"desc" => __("Check if you want the sidebar on the <strong>right</strong> side. By default, the sidebar appears on the left.", 'BlueBubble'),
	"id" => $shortname."_right_sidebar",
	"type" => "checkbox",
	"std" => ""),

array( "name" => __("Logo", 'BlueBubble'),
	"desc" => __("Enter the full path to your logo.<br /><strong>Ideal size is 192 x 77.</strong>", 'BlueBubble'),
	"id" => $shortname."_logo",
	"type" => "text",
	"std" => get_bloginfo('template_directory') ."/images/logo.png"),		

array( "name" => __("Custom Favicon", 'BlueBubble'),
	"desc" => __("A favicon is the 16x16 pixel icon that appears in the address bar of most browsers and represents your site; Upload a favicon to Wordpress, then paste the URL to the image that you want to use. (Note: Image should be in .ico format)", 'BlueBubble'),
	"id" => $shortname."_favicon",
	"type" => "text",
	"std" => get_bloginfo('url') ."/images/favicon.ico"),

array( "name" => __("Portfolio Category", 'BlueBubble'),
	"desc" => __("Enter the name of the Portfolio category. (you must create categories before they will show up in the list.)", 'BlueBubble'),
	"id" => $shortname."_portfolio_cat",
	"type" => "select",
	"options" => $wp_cats,
	"std" => __("Choose a category for your portfolio.", 'BlueBubble')),

array( "name" => __("Portfolio Items Per Page", 'BlueBubble'),
	"desc" => __("How many portfolio items do you want to show on each page? (default is 6)", 'BlueBubble'),
	"id" => $shortname."_portfolio_num",
	"type" => "text",
	"std" => ""),

array( "name" => __("Comments disable?", 'BlueBubble'),
	"desc" => __("Check if you want to disable comments on portfolio items.", 'BlueBubble'),
	"id" => $shortname."_comments",
	"type" => "checkbox",
	"std" => ""),

array( "name" => __("Turn Off Lightbox?", 'BlueBubble'),
	"desc" => __("Check if you want to <strong>turn off</strong> the Colorbox popup that appears when clicking an image on your portfolio page. (if turned off, clicking the image will take you to the single portfolio page with the larger image)", 'BlueBubble'),
	"id" => $shortname."_no_colorbox",
	"type" => "checkbox",
	"std" => ""),

array( "name" => __("Blog Parent Category", 'BlueBubble'),
	"desc" => __("Enter the name of the Portfolio category. (you must create categories before they will show up in the list.)", 'BlueBubble'),
	"id" => $shortname."_blog_cat",
	"type" => "select",
	"options" => $wp_cats,
	"std" => __("Choose a category for your blog.", 'BlueBubble')),

array( "name" => __("Oldest Posts First?", 'BlueBubble'),
	"desc" => __("BlueBubble 3.0 normally displays posts from newest to oldest. Check if you want to show oldest posts first. (Note: This will only change blog posts, not portfolio posts order)", 'BlueBubble'),
	"id" => $shortname."_post_order",
	"type" => "checkbox",
	"std" => ""),

array( "name" => __("Show Date Updated?", 'BlueBubble'),
	"desc" => __("Check this if you would like the <strong>last date updated</strong> to appear on Blog posts.", 'BlueBubble'),
	"id" => $shortname."_last_updated",
	"type" => "checkbox",
	"std" => ""),
		
array( "name" => __("Show Twitter Stream?", 'BlueBubble'),
	"desc" => __("Check if you want to show Twitter your stream. It will appear in the left sidebar above <strong>Other ways to reach me: </strong> (you must indicate your Twitter username in the next field)", 'BlueBubble'),
	"id" => $shortname."_twitter",
	"type" => "checkbox",
	"std" => ""),

array( "name" => __("Twitter Username", 'BlueBubble'),
	"desc" => __("Enter your Twitter username. In addition to the Twitter stream, your username will be used with the <strong>Tweet Button</strong> located on most pages. (you must check the box in the field above for your Twitter stream to show)", 'BlueBubble'),
	"id" => $shortname."_twitter_name",
	"type" => "text",
	"std" => ""),

array( "name" => __("Number of Twitter Feeds", 'BlueBubble'),
	"desc" => __("How many Twitter entries do you want to show? (default is 2; more than 5 is not recommended)", 'BlueBubble'),
	"id" => $shortname."_twitter_num",
	"type" => "text",
	"std" => ""),
	
array( "name" => __("Hide Tweet Buttons?", 'BlueBubble'),
	"desc" => __("Check if you want to hide the Twitter <strong>Tweet</strong> buttons that appear on posts and pages. (The buttons appear by default)", 'BlueBubble'),
	"id" => $shortname."_no_tweet",
	"type" => "checkbox",
	"std" => ""),
	
array( "name" => __("Contact Form Email Address", 'BlueBubble'),
	"desc" => __("Where do you want the emails from the contact form to arrive? Place that email address here. (if no email address is entered, email will automatically be sent to the administrator email address)", 'BlueBubble'),
	"id" => $shortname."_contact_email",
	"type" => "text",
	"std" => ""),	
	
array( "name" => __("Show Social Sites Section", 'BlueBubble'),
	"desc" => __("Check if you want to show links to sites such as Facebook, Twitter, etc. It will appear in the left sidebar with the header <strong>Other ways to reach me: </strong>", 'BlueBubble'),
	"id" => $shortname."_social",
	"type" => "checkbox",
	"std" => ""),


array( "name" => __("Facebook Social Link", 'BlueBubble'),
	"desc" => __("If you would like a link to your Facebook page, paste the URL here. (<strong>Note:</strong> Enter complete URL, like: <strong>http://www.twitter.com/username</strong>)", 'BlueBubble'),
	"id" => $shortname."_soc_fb",
	"type" => "text",
	"std" => ""),
	
array( "name" => __("Twitter Social Link", 'BlueBubble'),
	"desc" => __("If you would like a link to your Twitter page, paste the URL here. (<strong>Note:</strong> Enter complete URL, like: <strong>http://www.twitter.com/username</strong>)", 'BlueBubble'),
	"id" => $shortname."_soc_tw",
	"type" => "text",
	"std" => ""),
	
array( "name" => __("LinkedIn Social Link", 'BlueBubble'),
	"desc" => __("If you would like a link to your LinkedIn page, paste the URL here. (<strong>Note:</strong> Enter complete URL, like: <strong>http://www.twitter.com/username</strong>)", 'BlueBubble'),
	"id" => $shortname."_soc_lnk",
	"type" => "text",
	"std" => ""),

array( "name" => __("Behance Social Link", 'BlueBubble'),
	"desc" => __("If you would like a link to your Behance page, paste the URL here. (<strong>Note:</strong> Enter complete URL, like: <strong>http://www.twitter.com/username</strong>)", 'BlueBubble'),
	"id" => $shortname."_soc_beh",
	"type" => "text",
	"std" => ""),
	
array( "name" => __("Delicious Social Link", 'BlueBubble'),
	"desc" => __("If you would like a link to your Delicious page, paste the URL here. (<strong>Note:</strong> Enter complete URL, like: <strong>http://www.twitter.com/username</strong>)", 'BlueBubble'),
	"id" => $shortname."_soc_de",
	"type" => "text",
	"std" => ""),
		
array( "name" => __("Digg Social Link", 'BlueBubble'),
	"desc" => __("If you would like a link to your Digg page, paste the URL here. (<strong>Note:</strong> Enter complete URL, like: <strong>http://www.twitter.com/username</strong>)", 'BlueBubble'),
	"id" => $shortname."_soc_dg",
	"type" => "text",
	"std" => ""),
	
array( "name" => __("DeviantArt Social Link", 'BlueBubble'),
	"desc" => __("If you would like a link to your DeviantArt page, paste the URL here. (<strong>Note:</strong> Enter complete URL, like: <strong>http://www.twitter.com/username</strong>)", 'BlueBubble'),
	"id" => $shortname."_soc_dva",
	"type" => "text",
	"std" => ""),
	
array( "name" => __("MySpace Social Link", 'BlueBubble'),
	"desc" => __("If you would like a link to your MySpace page, paste the URL here. (<strong>Note:</strong> Enter complete URL, like: <strong>http://www.twitter.com/username</strong>)", 'BlueBubble'),
	"id" => $shortname."_soc_ms",
	"type" => "text",
	"std" => ""),
	
array( "name" => __("Evernote Social Link", 'BlueBubble'),
	"desc" => __("If you would like a link to your Evernote page, paste the URL here. (<strong>Note:</strong> Enter complete URL, like: <strong>http://www.twitter.com/username</strong>)", 'BlueBubble'),
	"id" => $shortname."_soc_ev",
	"type" => "text",
	"std" => ""),

array( "name" => __("Flickr Social Link", 'BlueBubble'),
	"desc" => __("If you would like a link to your Flickr page, paste the URL here. (<strong>Note:</strong> Enter complete URL, like: <strong>http://www.twitter.com/username</strong>)", 'BlueBubble'),
	"id" => $shortname."_soc_fl",
	"type" => "text",
	"std" => ""),
	
array( "name" => __("Netvibes Social Link", 'BlueBubble'),
	"desc" => __("If you would like a link to your Netvibes page, paste the URL here. (<strong>Note:</strong> Enter complete URL, like: <strong>http://www.twitter.com/username</strong>)", 'BlueBubble'),
	"id" => $shortname."_soc_nv",
	"type" => "text",
	"std" => ""),
	
array( "name" => __("Orkut Social Link", 'BlueBubble'),
	"desc" => __("If you would like a link to your Orkut page, paste the URL here. (<strong>Note:</strong> Enter complete URL, like: <strong>http://www.twitter.com/username</strong>)", 'BlueBubble'),
	"id" => $shortname."_soc_or",
	"type" => "text",
	"std" => ""),

array( "name" => __("Reddit Social Link", 'BlueBubble'),
	"desc" => __("If you would like a link to your Reddit page, paste the URL here. (<strong>Note:</strong> Enter complete URL, like: <strong>http://www.twitter.com/username</strong>)", 'BlueBubble'),
	"id" => $shortname."_soc_re",
	"type" => "text",
	"std" => ""),
	
array( "name" => __("ShareThis Social Link", 'BlueBubble'),
	"desc" => __("If you would like a link to your ShareThis page, paste the URL here. (<strong>Note:</strong> Enter complete URL, like: <strong>http://www.twitter.com/username</strong>)", 'BlueBubble'),
	"id" => $shortname."_soc_sh",
	"type" => "text",
	"std" => ""),
		
array( "name" => __("StumbleUpon Social Link", 'BlueBubble'),
	"desc" => __("If you would like a link to your StumbleUpon page, paste the URL here. (<strong>Note:</strong> Enter complete URL, like: <strong>http://www.twitter.com/username</strong>)", 'BlueBubble'),
	"id" => $shortname."_soc_su",
	"type" => "text",
	"std" => ""),

array( "name" => __("Technorati Social Link", 'BlueBubble'),
	"desc" => __("If you would like a link to your Technorati page, paste the URL here. (<strong>Note:</strong> Enter complete URL, like: <strong>http://www.twitter.com/username</strong>)", 'BlueBubble'),
	"id" => $shortname."_soc_te",
	"type" => "text",
	"std" => ""),

array( "name" => __("Tumblr Social Link", 'BlueBubble'),
	"desc" => __("If you would like a link to your Tumblr page, paste the URL here. (<strong>Note:</strong> Enter complete URL, like: <strong>http://www.twitter.com/username</strong>)", 'BlueBubble'),
	"id" => $shortname."_soc_tu",
	"type" => "text",
	"std" => ""),
	
array( "name" =>(__("Search Engine Optimization", 'BlueBubble')),
	"type" => "section"),
array( "type" => "open"),

array( "name" => __("Meta Tag: Description", 'BlueBubble'),
	"desc" => __("The meta tag <strong>description</strong> is found in the header of your webpages and is used by search engines (i.e. Google) to rank and describe your site. Write an overall brief description of your site here.", 'BlueBubble'),
	"id" => $shortname."_seo_description",
	"type" => "textarea",
	"std" => ""),

array( "name" => __("Meta Tag: Keywords", 'BlueBubble'),
	"desc" => __("The meta tag <strong>keywords</strong> is found in the header of your webpages and is used by search engines (i.e. Google) to rank and describe your site. List here the keywords that describe your site. <strong>(Example: Blue,Bubble,portfolio,theme)</strong>", 'BlueBubble'),
	"id" => $shortname."_seo_keywords",
	"type" => "textarea",
	"std" => ""),	

array( "type" => "close")
 
);


function mytheme_add_admin() {
 global $themename, $shortname, $options, $theme_update, $changelog_output;
 if ( $_GET['page'] == basename(__FILE__) ) {
 	if ( 'save' == $_REQUEST['action'] ) {
 		foreach ($options as $value) {
		update_option( $value['id'], $_REQUEST[ $value['id'] ] ); }
 foreach ($options as $value) {
	if( isset( $_REQUEST[ $value['id'] ] ) ) { update_option( $value['id'], $_REQUEST[ $value['id'] ]  ); } else { delete_option( $value['id'] ); } }
 	header("Location: admin.php?page=functions.php&saved=true");
die;
 } 
else if( 'reset' == $_REQUEST['action'] ) {
 	foreach ($options as $value) {
		delete_option( $value['id'] ); }
 	header("Location: admin.php?page=functions.php&reset=true");
die;
 
}
}
 

 
// New Update Attempt

	
	$theme_data = get_theme_data(TEMPLATEPATH . '/style.css');
	
	$theme_update['current_version'] = $theme_data['Version'];
	

if (function_exists('curl_init')) {
   // initialize a new curl resource
   $ch = curl_init();

   // set the url to fetch
   curl_setopt($ch, CURLOPT_URL, 'http://bluebubble.dosmundoscafe.com/version.txt');

   // don't give me the headers just the content
   curl_setopt($ch, CURLOPT_HEADER, 0);

   // return the value instead of printing the response to browser
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

   // use a user agent to mimic a browser
   curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.5) Gecko/20041107 Firefox/1.0');

   $changelog = curl_exec($ch);

   // remember to always close the session and free all resources
   curl_close($ch);
} else {
   $changelog = file_get_contents('http://bluebubble.dosmundoscafe.com/version.txt');
} 

	
	preg_match_all('/<version>(.+?)<\/version>/is', $changelog, $matches);
	preg_match_all('/<description>(.+?)<\/description>/is', $changelog, $desc);
	preg_match_all('/<date>(.+?)<\/date>/is', $changelog, $date);
	
	$changelog_count = count($matches[0]);
	
	$i = 0;
	foreach($matches[0] as $val){
		$changelog_output .= '<h3>' . $val . '</h3><p>' . $date[0][$i] . '</p><p>' . $desc[0][$i] . '</p>';
		$i++;
	}
	
	if(preg_match('/<item>.*?<version>(.+?)<\/version>.*?<\/item>/is', $changelog, $matches))
		$theme_update['new_version'] = esc_html($matches[1]);
	
	if(preg_match('/<item>.*?<description>(.+?)<\/description>.*?<\/item>/is', $changelog, $matches))
		$theme_update['desc'] = $matches[1];
	
	if($theme_update['current_version'] < $theme_update['new_version'])
		$opts = "<span class='update-plugins count-1'><span class='update-count'>1</span></span>";

}


function bb_theme_updates(){
	global $themename, $shortname, $options, $theme_update, $changelog_output;
?>
	<div class="wrap">
    <div class="icon32" id="icon-options-general"><br></div>
    <h2><?php echo $themename; ?> <?php echo __('Updates'); ?></h2>
<?php    
	if($theme_update['current_version'] < $theme_update['new_version']){
		echo '<div id="message" class="updated fade">
			<p><strong>There is a new version of BlueBubble available</strong>. You have version ' . $theme_update['current_version'] . ' installed.  Please update to <strong>' . $theme_update['new_version'] . '</strong>.</p>
			</div>
			<p>Click here to download to your computer: (will not install automatically)</p>
			<p><a class="button" href="http://algo.dosmundoscafe.com/en/descargar/" target="_blank">Download BlueBubble ' . $theme_update['new_version'] . '</a> RAR&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class="button" href="http://algo.dosmundoscafe.com/en/descargar/" target="_blank">Download BlueBubble ' . $theme_update['new_version'] . '</a> ZIP</p>
			<p><strong>Note:</strong> If you made any changes to the theme files, they will be lost. Make sure to save your changes before installing the new version.</p>
			<p>The options you set in the BlueBubble Theme Options panel, your menus and your Custom Headers will not be lost.</p>
			<p><img src="' . get_bloginfo('template_directory') . '/screenshot.png"></p>
			<h2>Changelog</h2>' . $changelog_output
		;
	}
	else{
?>
    <h3><?php echo __('You have the latest version of the <?php echo $themename; ?> Wordpress theme.'); ?></h3>
    <p><?php echo __('You can always check the official websites for the latest updates:</p> <p><a class="button" href="http://algo.dosmundoscafe.com/descargar" target="_blank">imaginalgo</a> or <a class="button" href="http://www.flexible7.com/" target="_blank">Flexible7</a></p> <p>For help and support please visit the BlueBubble demo site:</p> <p><a class="button" href="http://bluebubble.dosmundoscafe.com/" target="_blank">BlueBubble Website</a>'); ?></p>
    </div>
<?php
	}
}

 
function mytheme_add_init() {

$file_dir=get_bloginfo('template_directory');
wp_enqueue_style("functions", $file_dir."/includes/bb-functions.css", false, "1.0", "all");
wp_enqueue_script("rm_script", $file_dir."/includes/rm_script.js", false, "1.0");

}
function mytheme_admin() {
 
global $themename, $shortname, $options;
$i=0;
 
if ( $_REQUEST['saved'] ) echo '<div id="message" class="updated fade"><p><strong>'.$themename. (__(' settings saved.</strong></p></div>', 'BlueBubble'));
if ( $_REQUEST['reset'] ) echo '<div id="message" class="updated fade"><p><strong>'.$themename. (__(' settings reset.</strong></p></div>', 'BlueBubble'));
 
?>

<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_url'); ?>/includes/functions.css" media="screen" />

<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/scripts/jquery.min.js"></script>
<script type="text/javascript" charset="utf-8">
	$(function () {
    	var tabContainers = $('div.tabs > div');
        tabContainers.hide().filter(':one').show();
        $('div.tabs ul.tabNavigation a').click(function () {
        	tabContainers.hide();
            tabContainers.filter(this.hash).show();
            $('div.tabs ul.tabNavigation a').removeClass('selected');
            $(this).addClass('selected');
             return false;
        }).filter(':first').click();
	});
</script>

<div class="wrap">


<div class="admintopbar">
<span class="left">
</span>
<span class="right">
<h3>BlueBubble v3.4</h3>
<h5>Free Minimal and Elegant Wordpress Theme</h5>
</span>
</div>


	<form method="post" class="theme-options">
    <div class="options-wrap">
		<div class="tabs">
            <ul class="tabNavigation">
                <li><a href="#one"><?php echo __("General", 'BlueBubble'); ?></a></li>
                <li><a href="#two"><?php echo __("Layout", 'BlueBubble'); ?></a></li>
                <li><a href="#three"><?php echo __("Logo and Icon", 'BlueBubble'); ?></a></li>
                <li><a href="#four"><?php echo __("Portfolio", 'BlueBubble'); ?></a></li>
                <li><a href="#five"><?php echo __("Blog", 'BlueBubble'); ?></a></li>
                <li><a href="#six"><?php echo __("Twitter", 'BlueBubble'); ?></a></li>
                <li><a href="#seven"><?php echo __("Contact Form", 'BlueBubble'); ?></a></li>
                <li><a href="#eight"><?php echo __("Social Icons", 'BlueBubble'); ?></a></li>
                <li><a href="#nine"><?php echo __("SEO", 'BlueBubble'); ?></a></li>
                <li><a href="#last"><?php echo __("Info", 'BlueBubble'); ?></a></li>
            </ul> 
      
        	<div id="one">
            <div id="tab-title"><h3><?php echo __("General", 'BlueBubble'); ?></h3><span class="submit"><input name="save<?php echo $i; ?>" type="submit" value="Save changes" /></span></div>
            <div class="title-line"></div>
			<?php 
            foreach ($options as $value) {    
                switch ( $value['type'] ) {	
                case "open":
                ?>
                  <div width="500px">
                    <?php 
                    break;	
                    case "close":
                    ?>		
                    </div>
                <?php } // end switch ?>  
        
                <?php  // Text Fields for Section
                switch ( $value['id'] ) {					
				case $shortname."_color_scheme":			
                ?>

 <div class="row">
  <div class="feature-name"><?php echo $value['name']; ?></div>
     <div class="feature">
        <select name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>">			

	
        <?php foreach ($value['options'] as $option) { ?>                 <option
             <?php if ( get_settings( $value['id'] ) == $option) { echo ' selected="selected"'; } 	

				
            elseif ($option == $value['std']) { echo ' selected="selected"'; } ?>>			

	    <?php echo $option; ?>                        
          </option>
          <?php } //end for ?>                    
        </select>
     </div><!--end feature-->
       <div class="description">
          <?php echo $value['desc']; ?>
       </div><!--end description-->
 <div class="line"></div>
</div><!--end row-->

                <?php 
                break;		
                case $shortname."_feedburner":
				?>

<div class="row">
   <div class="feature-name"><?php echo $value['name']; ?></div>
      <div class="feature">
         <input class="text" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" value="<?php if ( get_settings( $value['id'] ) != "") { echo stripslashes(get_settings( $value['id'] )); } else { echo $value['std']; } ?>" />
       </div><!--end feature-->
           <div class="description">
              <?php echo $value['desc']; ?>
           </div><!--end description-->
    <div class="line"></div>  
</div><!--end row-->

                <?php 
                break;		
                case $shortname."_footer_text":
				case $shortname."_ga_code":
				case $shortname."_custom_css":
				?>

<div class="row">
  <div class="feature-name"><?php echo $value['name']; ?></div>
     <div class="feature">
         <textarea rows="5" cols="22" class="textarea" name="<?php echo $value['id']; ?>" id="<?php 

echo $value['id']; ?>"><?php if ( get_settings( $value['id'] ) != "") { echo stripslashes(get_settings( $value

['id'] )); } else { echo $value['std']; } ?></textarea>
     </div><!--end feature-->
         <div class="description">
             <?php echo $value['desc']; ?>
          </div><!--end description-->
  <div class="line"></div>  
</div><!--end row-->

                <?php 
                break;
                } // end switch
            } // end for loop ?>         
		</div><!--end one-->

        		<div id="two">
                <div id="tab-title"><h3><?php echo __("Layout", 'BlueBubble'); ?></h3><span class="submit"><input name="save<?php echo $i; ?>" type="submit" value="Save changes" /></span></div>
                <div class="title-line"></div>
		<?php 
        foreach ($options as $value) {    
            switch ( $value['type'] ) {	
            case "open":
            ?>
                <div width="500px"> 
                <?php 
                break;	
                case "close":
                ?>		
                </div>
            <?php } // end switch ?>  
        
            <?php 
            switch ( $value['id'] ) {
			case $shortname."_top_menu":				
            case $shortname."_old_menus":
			case $shortname."_login_menu":
			case $shortname."_custom_login":
			case $shortname."_right_sidebar":			
            ?>        

                    <div class="row">
                        <div class="feature-name"><?php echo $value['name']; ?></div>
                        <div class="feature">
	                        <?php if(get_option($value['id'])){ $checked = "checked=\"checked\""; }else{ $checked = "";} ?>
                            <input type="checkbox" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" value="true" <?php echo $checked; ?> />
                        </div><!--end feature-->
                        <div class="description">
                            <?php echo $value['desc']; ?>
                        </div><!--end description-->
                        <div class="line"></div>  
                    </div><!--end row-->
 
            <?php 
            break;		
            } // end switch
        } // end for loop ?>         
    </div><!--end two-->
        
        <div id="three">
        <div id="tab-title"><h3><?php echo __("Logo and Icon", 'BlueBubble'); ?></h3><span class="submit"><input name="save<?php echo $i; ?>" type="submit" value="Save changes" /></span></div>
        <div class="title-line"></div>
			<?php 
			foreach ($options as $value) {    
				switch ( $value['type'] ) {	
				case "open":
			?>
        		<div width="500px"> 
				<?php 
				break;	
				case "close":
				?>		
        		</div>
        	<?php } // end switch ?>        
        	<?php 
			switch ( $value['id'] ) {			
			case $shortname."_logo":
			case $shortname."_favicon":		
			?>        
<div class="row">
   <div class="feature-name"><?php echo $value['name']; ?></div>
      <div class="feature">
         <input class="text" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" value="<?php if ( get_settings( $value['id'] ) != "") { echo get_settings( $value['id'] ); } else { echo $value['std']; } ?>" />
       </div><!--end feature-->
           <div class="description">
              <?php echo $value['desc']; ?>
           </div><!--end description-->
    <div class="line"></div>  
</div><!--end row-->
				<?php break; ?>        
        	<?php } // end switch ?>
			<?php } // end for loop ?>
		</div><!--end three-->
    	
        <div id="four"> 
        <div id="tab-title"><h3><?php echo __("Portfolio", 'BlueBubble'); ?></h3><span class="submit"><input name="save<?php echo $i; ?>" type="submit" value="Save changes" /></span></div>
        <div class="title-line"></div>          
        <?php 
		foreach ($options as $value) {    
			switch ( $value['type'] ) {	
			case "open":
			?>
        		<div width="500px"> 
				<?php 
				break;	
				case "close":
				?>		
        		</div>
        	<?php } // end switch ?>
        
        	<?php 
			switch ( $value['id'] ) {		
			case $shortname."_portfolio_cat":
			?>
                <div class="row">
                    <div class="feature-name"><?php echo $value['name']; ?></div>
                    <div class="feature">
                        <select name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>">				
                        <?php foreach ($value['options'] as $option) { ?>                
                            <option
                            <?php if ( get_settings( $value['id'] ) == $option) { 					
                                echo ' selected="selected"'; } 					
                                elseif ($option == $value['std']) { 						
                                    echo ' selected="selected"'; } ?>>						
                                <?php echo $option; ?>                        
                            </option>
                        <?php } ?>                    
                        </select>
                    </div><!--end feature-->
                    <div class="description">
                        <?php echo $value['desc']; ?>
                    </div><!--end description-->
                    <div class="line"></div> 
                </div><!--end row-->
    
                <?php break;			
                case $shortname."_portfolio_num":
                ?>
                
                <div class="row">
                    <div class="feature-name"><?php echo $value['name']; ?></div>
                    <div class="feature">
                        <input class="text" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" value="<?php if ( get_settings( $value['id'] ) != "") { echo get_settings( $value['id'] ); } else { echo $value['std']; } ?>" />
                    </div><!--end feature-->
                    <div class="description">
                        <?php echo $value['desc']; ?>
                    </div><!--end description-->   
                    <div class="line"></div>              
                </div><!--end row-->
           
                <?php break;
				case $shortname."_comments":			
                case $shortname."_no_colorbox":
                ?>
                
                    <div class="row">
                        <div class="feature-name"><?php echo $value['name']; ?></div>
                        <div class="feature">
	                        <?php if(get_option($value['id'])){ $checked = "checked=\"checked\""; }else{ $checked = "";} ?>
                            <input type="checkbox" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" value="true" <?php echo $checked; ?> />
                        </div><!--end feature-->
                        <div class="description">
                            <?php echo $value['desc']; ?>
                        </div><!--end description-->
                        <div class="line"></div>  
                    </div><!--end row-->
    
                <?php 
                break;				
                } // end switch 
            } // end for loop ?>       
 		</div><!--end four-->
        
		<div id="five">
        <div id="tab-title"><h3><?php echo __("Blog", 'BlueBubble'); ?></h3><span class="submit"><input name="save<?php echo $i; ?>" type="submit" value="Save changes" /></span></div>
        <div class="title-line"></div>
		<?php 
        foreach ($options as $value) {    
            switch ( $value['type'] ) {	
            case "open":
            ?>
                <div width="500px"> 
                <?php 
                break;	
                case "close":
                ?>		
                </div>
            <?php } // end switch ?>  
        
            <?php 
            switch ( $value['id'] ) {				
            case $shortname."_blog_cat":			
            ?>        
                <div class="row">
                    <div class="feature-name"><?php echo $value['name']; ?></div>
                    <div class="feature">
                        <select name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>">				
                        <?php foreach ($value['options'] as $option) { ?>                
                            <option
                            <?php if ( get_settings( $value['id'] ) == $option) { 					
                                echo ' selected="selected"'; } 					
                                elseif ($option == $value['std']) { 						
                                    echo ' selected="selected"'; } ?>>						
                                <?php echo $option; ?>                        
                            </option>
                        <?php } ?>                    
                        </select>
                    </div><!--end feature-->
                    <div class="description">
                        <?php echo $value['desc']; ?>
                    </div><!--end description-->
                    <div class="line"></div> 
                </div><!--end row-->

    
                <?php break;			
                case $shortname."_post_order":
				case $shortname."_last_updated":
                ?>
                
                    <div class="row">
                        <div class="feature-name"><?php echo $value['name']; ?></div>
                        <div class="feature">
	                        <?php if(get_option($value['id'])){ $checked = "checked=\"checked\""; }else{ $checked = "";} ?>
                            <input type="checkbox" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" value="true" <?php echo $checked; ?> />
                        </div><!--end feature-->
                        <div class="description">
                            <?php echo $value['desc']; ?>
                        </div><!--end description-->
                        <div class="line"></div>  
                    </div><!--end row-->
 
            <?php 
            break;		
            } // end switch
        } // end for loop ?>         
    </div><!--end five-->
    
    		<div id="six">
            <div id="tab-title"><h3><?php echo __("Twitter", 'BlueBubble'); ?></h3><span class="submit"><input name="save<?php echo $i; ?>" type="submit" value="Save changes" /></span></div>
            <div class="title-line"></div>
		<?php 
        foreach ($options as $value) {    
            switch ( $value['type'] ) {	
            case "open":
            ?>
                <div width="500px"> 
                <?php 
                break;	
                case "close":
                ?>		
                </div>
            <?php } // end switch ?>  
        
            <?php 
            switch ( $value['id'] ) {				
            case $shortname."_twitter":	
			case $shortname."_no_tweet":		
            ?>        

                    <div class="row">
                        <div class="feature-name"><?php echo $value['name']; ?></div>
                        <div class="feature">
	                        <?php if(get_option($value['id'])){ $checked = "checked=\"checked\""; }else{ $checked = "";} ?>
                            <input type="checkbox" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" value="true" <?php echo $checked; ?> />
                        </div><!--end feature-->
                        <div class="description">
                            <?php echo $value['desc']; ?>
                        </div><!--end description-->
                        <div class="line"></div>  
                    </div><!--end row-->

    
                <?php break;			
                case $shortname."_twitter_name":
				case $shortname."_twitter_num":
                ?>
                 
                <div class="row">
                    <div class="feature-name"><?php echo $value['name']; ?></div>
                    <div class="feature">
                        <input class="text" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" value="<?php if ( get_settings( $value['id'] ) != "") { echo get_settings( $value['id'] ); } else { echo $value['std']; } ?>" />
                    </div><!--end feature-->
                    <div class="description">
                        <?php echo $value['desc']; ?>
                    </div><!--end description-->   
                    <div class="line"></div>              
                </div><!--end row-->
 
            <?php 
            break;		
            } // end switch
        } // end for loop ?>         
    </div><!--end six-->
    
        		<div id="seven">
                <div id="tab-title"><h3><?php echo __("Contact Form", 'BlueBubble'); ?></h3><span class="submit"><input name="save<?php echo $i; ?>" type="submit" value="Save changes" /></span></div>
                <div class="title-line"></div>
		<?php 
        foreach ($options as $value) {    
            switch ( $value['type'] ) {	
            case "open":
            ?>
                <div width="500px"> 
                <?php 
                break;	
                case "close":
                ?>		
                </div>
            <?php } // end switch ?>  
        
            <?php 
            switch ( $value['id'] ) {				
            case $shortname."_contact_email":			
            ?>        

<div class="row">
   <div class="feature-name"><?php echo $value['name']; ?></div>
      <div class="feature">
         <input class="text" name="<?php echo $value['id']; ?>" id="<?php 

echo $value['id']; ?>" type="<?php echo $value['type']; ?>" value="<?php if 

( get_settings( $value['id'] ) != "") { echo get_settings( $value['id'] ); 

} else { echo $value['std']; } ?>" />
       </div><!--end feature-->
           <div class="description">
              <?php echo $value['desc']; ?>
           </div><!--end description-->
    <div class="line"></div>  
</div><!--end row-->
 
            <?php 
            break;		
            } // end switch
        } // end for loop ?>         
    </div><!--end seven-->
    
        		<div id="eight">
                <div id="tab-title"><h3><?php echo __("Social Icons", 'BlueBubble'); ?></h3><span class="submit"><input name="save<?php echo $i; ?>" type="submit" value="Save changes" /></span></div>
                <div class="title-line"></div>
		<?php 
        foreach ($options as $value) {    
            switch ( $value['type'] ) {	
            case "open":
            ?>
                <div width="500px"> 
                <?php 
                break;	
                case "close":
                ?>		
                </div>
            <?php } // end switch ?>  
        
            <?php 
            switch ( $value['id'] ) {				
            case $shortname."_social":			
            ?>        

                    <div class="row">
                        <div class="feature-name"><?php echo $value['name']; ?></div>
                        <div class="feature">
	                        <?php if(get_option($value['id'])){ $checked = "checked=\"checked\""; }else{ $checked = "";} ?>
                            <input type="checkbox" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" value="true" <?php echo $checked; ?> />
                        </div><!--end feature-->
                        <div class="description">
                            <?php echo $value['desc']; ?>
                        </div><!--end description-->
                        <div class="line"></div>  
                    </div><!--end row-->

    
                <?php break;			
                case $shortname."_soc_fb":
				case $shortname."_soc_tw":
				case $shortname."_soc_lnk":
				case $shortname."_soc_de":
				case $shortname."_soc_dg":
				case $shortname."_soc_dva":
				case $shortname."_soc_ms":
				case $shortname."_soc_ev":
				case $shortname."_soc_fl":
				case $shortname."_soc_nv":
				case $shortname."_soc_or":
				case $shortname."_soc_re":
				case $shortname."_soc_sh":
				case $shortname."_soc_su":
				case $shortname."_soc_te":
				case $shortname."_soc_tu":
                ?>
                 
                <div class="row">
                    <div class="feature-name"><?php echo $value['name']; ?></div>
                    <div class="feature">
                        <input class="text" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" value="<?php if ( get_settings( $value['id'] ) != "") { echo get_settings( $value['id'] ); } else { echo $value['std']; } ?>" />
                    </div><!--end feature-->
                    <div class="description">
                        <?php echo $value['desc']; ?>
                    </div><!--end description-->   
                    <div class="line"></div>              
                </div><!--end row-->      

 
            <?php 
            break;		
            } // end switch
        } // end for loop ?>         
    </div><!--end eight-->
    
        		<div id="nine">
                <div id="tab-title"><h3><?php echo __("SEO", 'BlueBubble'); ?></h3><span class="submit"><input name="save<?php echo $i; ?>" type="submit" value="Save changes" /></span></div>
                <div class="title-line"></div>
		<?php 
        foreach ($options as $value) {    
            switch ( $value['type'] ) {	
            case "open":
            ?>
                <div width="500px"> 
                <?php 
                break;	
                case "close":
                ?>		
                </div>
            <?php } // end switch ?>  
        
            <?php 
            switch ( $value['id'] ) {				
            case $shortname."_seo_description":	
			case $shortname."_seo_keywords":		
            ?>        

<div class="row">
  <div class="feature-name"><?php echo $value['name']; ?></div>
     <div class="feature">
         <textarea rows="5" cols="22" class="textarea" name="<?php echo 

$value['id']; ?>" id="<?php echo $value['id']; ?>"><?php if ( get_settings( 

$value['id'] ) != "") { echo get_settings( $value['id'] ); } else { echo 

$value['std']; } ?></textarea>
     </div><!--end feature-->
         <div class="description">
             <?php echo $value['desc']; ?>
          </div><!--end description-->
  <div class="line"></div>  
</div><!--end row-->
 
            <?php 
            break;		
            } // end switch
        } // end for loop ?>         
    </div><!--end nine-->

        		<div id="last">
                <div id="tab-title"><h3><?php echo __("Info", 'BlueBubble'); ?></h3></div> 
                <div class="title-line"></div> 
		<?php 
        foreach ($options as $value) {    
            switch ( $value['type'] ) {	
            case "open":
            ?>
                <div width="500px">
                <?php 
                break;	
                case "close":
                ?>	
                </div>
            <?php } // end switch ?>  
        
            <?php 
            switch ( $value['id'] ) {				
            case $shortname."_seo_description":		
            ?> 
        <?php echo __('<h3>About BlueBubble</h3>'); ?>  
        <?php echo __('<p>BlueBubble is a minimalist and elegant Wordpress portfolio theme originally designed by Thomas Veit, with additions, modifications, and further designs by Mike Walsh beginning with BlueBubble 3.0</p>'); ?>
        <?php echo __('<p>A full list of credits and thanks can be found at the end of the documents provided with the theme.</p>'); ?>
        <?php echo __('<p>You can read the latest news about the BlueBubble Wordpress theme at one of the following sites:</p>'); ?>
        <p><a class="button" href="http://algo.dosmundoscafe.com/descargar" target="_blank">imaginalgo</a> (English & Espa&ntilde;ol)</p>
        <p><a class="button" href="http://bluebubble.dosmundoscafe.com/" target="_blank">BlueBubble Website</a></p>
        <p><a class="button" href="http://www.flexible7.com/" target="_blank">Flexible7</a></p>
        <br />
        <?php echo __('<p><strong>Thank you for choosing BlueBubble!</strong></p>'); ?>
            <?php 
            break;		
            } // end switch
        } // end for loop ?>      
    </div><!--end last-->  
           
    </div><!--end tabs-->
</div>
<div class="adminbotbar">


<span class="credits"><div style="font-size:9px;"><?php _e('Social Media Icons:', 'BlueBubble') ?> <a href="http://www.komodomedia.com/blog/2009/06/social-network-icon-pack/" target="_blank">Komodo Media</a></div></span>



<div class="save">
<li class="submit">
<input name="save<?php echo $i; ?>" class="submit" type="submit" value="<?php _e('Save Changes', 'BlueBubble') ?>" />
<input type="hidden" name="action" value="save" /></li>
</div>


</div>

</div><!-- end ADMINBOTBAR -->

 
 </div> 
 </div>

<?php
}
?>
<?php
add_action('admin_init', 'mytheme_add_init');
add_action('admin_menu', 'mytheme_add_admin');
?>
