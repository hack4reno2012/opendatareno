<?php
/**
 * @package Twittar - Twitter Avatar for Wordpress
 * @author Ricardo Sousa - SmashingMagazine
 * @version 1.0
 */
/*
Plugin Name: Twittar 
Plugin URI: http://smashingmagazine.com
Description: This is a simple but yet powerful plugin for wordpress that lets you show your users Twitter avatar together with their comments in your website. This is done by matching their mail adress with their twitter account. Works only in public Twitter accounts. Depending of the version if the user has no avatar it will show a default picture that come along with this plugin or gravatar instead. 
Author: Ricardo Sousa for SmashingMagazine
Version: 1.0
Author URI: http://www.ricardojrsousa.com
*/



// Main Function that shows the avatars    
/*
- Options:
* size = The Picture size in px (both width and height)
* placeholder = The image you want to use if user has no Twitter Avatar or Gravatar
* border = specify the color of the border if you want to use a 2px one
* class = Top notch. Specify a class you want to add to each img that this plugin will generate. You can then style it in you own .css
* usegravatar = select 1 if yes or 0 if no. Select if you want to use gravatar pic if user has no twitter img or account
* rating = The necessary field for Gravatar
*/

function twittar ($size = "", $placeholder = "", $border = "", $class = "", $usegravatar = 0, $rating = "") {
  // Let us load comments info from wordpress
   global $comment; 
   
   // Will load the function that will mess with Twitter API (see bellow) It sends the comment author mail
   $result = showUser($comment->comment_author_email);  

   // After getting the responses from the function we will proceed

// In the case of we setup a border we will add a string with the border info   
if ($border != "") {
$stringadd = "border-width:2px; border-color:".$border."; border-style:solid;";
}     
 // lets handle the size. Like twitter has different default image sizes we will tell the plugin to get the one that 
 // can be better used with the set size
 if ($size == "") {
 $finalsize = "48px;";
 $suffix = "_normal";
 } else if ($size <= "60" && $size >= "34") {
 $finalsize = $size."px;";
 $suffix = "_normal";
 } else if ($size <= "35" && $size >= "0") {
 $finalsize = $size."px;";
 $suffix = "_mini";
 } else if ($size <= "90" && $size >= "61") {
 $finalsize = $size."px;";
 $suffix = "_bigger";
 } else if ($size >= "91") {
 $finalsize = $size."px;";
 $suffix = "";
 }
 
 // If User has no twitter    
if($result===false) {
    // In case we want our own image and not the default one lets tell it
    if ($placeholder != "") {
        $imagee = $placeholder;
    } else {
        // in the case we want the default image let's load it
        $imagee = get_option('siteurl')."/wp-content/plugins/twittar/default".$suffix.".png";
    }
    // If we want to use gravatar we will handle a couple of things
    if ($usegravatar == 1) {
             if ($size == "") {
                 // Size: Like gravatar default size is different from Twittar's one we will setup a new size when we 
                 // request the gravatar of the user
        $size = "48";
        }

   // Lets build the gravatar url :)
      $image = "http://www.gravatar.com/avatar.php?gravatar_id=".md5($comment->comment_author_email);
    if($rating && $rating != '')  {
        $image .= "&amp;rating=".$rating;
    }
    if($size && $size != '')     {
        $image .="&amp;size=".$size;
    }
            if($border && $border != '')     {
        $image .= "&amp;border=".$border;
            }

        $image .= "&amp;default=".urlencode($imagee);  // Not here that this image will be the defautl one or the placeholder if user has no gravatar as well
 
    }   else {
        // If we do not want to use gravatar the image we will load will be either the default one or the placeholder.
        // Difference: we dont request the gravatar first!
$image = $imagee;
    }
}else{

 // If user has twitter :)

 
$quaseimg = $result->profile_image_url;  // we get user image from array we built in the process function
// we need to make some calcs to get the image without _normal.png (we will add .png later and _normal deppending on the choosen size)

$wherestop = strrpos($quaseimg, "_");  
$lenght = strlen( $quaseimg  );
$takeof = $lenght-$wherestop;
$keepit = $lenght-4;
$fileextension = substr($quaseimg, $keepit, 4);
// Here we have a complete image url:
$image = substr($quaseimg, 0, -$takeof).$suffix.$fileextension; 

// Let's pay attention: If in the url we find static.twitter.com it means is the default img so lets go to our gravatar/default process   
if (strpos($quaseimg, "static.twitter.com") !== false) {
  // Same thing as we did before let's load either the placeholder (if set) or the default img
    if ($placeholder != "") {
        $imagee = $placeholder;
    } else {
        $imagee = get_option('siteurl')."/wp-content/plugins/twittar/default".$suffix.".png";
    }
    // if we want to use gravatar...
    if ($usegravatar == 1) {
        if ($size == "") {
            // ... we adjust the image size
        $size = "48";
        }
        // We build the url
        $image = "http://www.gravatar.com/avatar.php?gravatar_id=".md5($comment->comment_author_email);
    if($rating && $rating != '')
        $image .= "&amp;rating=".$rating;
    if($size && $size != '')
        $image .="&amp;size=".$size;

        $image .= "&amp;default=".urlencode($imagee);     // here we load the default/placeholder img if user has no gravatar
            if($border && $border != '')
        $image .= "&amp;border=".$border;

    }   else {
        // else we use the default img/placeholder
$image = $imagee;
    }          
 }   


} 
// Now let's build the img tag
 echo '<img src="'.$image.'" alt="'.$result->name.'" class="'.$class.'" title="'.$result->name.'" style="width:'.$finalsize.' height:'.$finalsize.' '.$stringadd.'" />';
      
 
}


// Function showUser 
/*
* $id = User email 
*/ 
    function showUser($id){
        // Will arrange the url we need to open @ twitter to get the photo info
        $request = 'http://twitter.com/users/show/show.xml?email='.urlencode($id).'';
    // Will open another function that will do all the hard work :)
        return process($request);
    } 
    
  // Function process
     function process($url,$postargs=false){
        // CURL will be used to open the URL and Retrieve the user ifo based on the email.
             $ch = curl_init($url);

        if($postargs !== false){
            curl_setopt ($ch, CURLOPT_POST, true);
            curl_setopt ($ch, CURLOPT_POSTFIELDS, $postargs);
        }
        
        
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_NOBODY, 0);
        curl_setopt($ch, CURLOPT_HEADER, 0);                   
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);           

        $response = curl_exec($ch);
        
        $responseInfo=curl_getinfo($ch);
        curl_close($ch);
        
        
        if(intval($responseInfo['http_code'])==200){
            if(class_exists('SimpleXMLElement')){
             // Will create a xml like SimpleXMLElement
                $xml = new SimpleXMLElement($response);
                return $xml;
            }else{
                return $response;    
            }
        }else{
            return false;
        }
    }  

?>