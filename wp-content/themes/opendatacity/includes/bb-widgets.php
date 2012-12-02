<?php 
/**
 * Original Archives Widget - Changed to place post count within links
 *
 * Credit to: http://skratchdot.com/2009/09/getting-wordpress-post-counts-to-show-up-inside-anchor-tag/
 *
 * @since 2.8.0
 */
class BB_Archives extends WP_Widget {

		function BB_Archives() {
		$widget_ops = array('classname' => 'widget_archive', 'description' => __( 'A monthly archive of your blog&#8217;s posts') );
		$this->WP_Widget('mytheme_archives', __('BB Archives'), $widget_ops);
	}

	function widget( $args, $instance ) {
		global $wpdb, $wp_locale;
		extract($args);
		$title = apply_filters('widget_title', empty($instance['title']) ? __('Archives') : $instance['title']);
 
		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;
 ?>
		<ul>
		<?php 
			//filters
			$where = apply_filters('getarchives_where', "WHERE post_type = 'post' AND post_status = 'publish'", $r );
			$join = apply_filters('getarchives_join', "", $r);
 
			$query = "SELECT YEAR(post_date) AS `year`, MONTH(post_date) AS `month`, count(ID) as posts FROM $wpdb->posts $join $where GROUP BY YEAR(post_date), MONTH(post_date) ORDER BY post_date DESC $limit";
			$key = md5($query);
			$cache = wp_cache_get( 'wp_get_archives' , 'general');
			if ( !isset( $cache[ $key ] ) ) {
				$arcresults = $wpdb->get_results($query);
				$cache[ $key ] = $arcresults;
				wp_cache_add( 'wp_get_archives', $cache, 'general' );
			} else {
				$arcresults = $cache[ $key ];
			}
			if ( $arcresults ) {
				$afterafter = $after;
				foreach ( (array) $arcresults as $arcresult ) {
					$url = get_month_link( $arcresult->year, $arcresult->month );
					$text = sprintf(__('%1$s %2$d'), $wp_locale->get_month($arcresult->month), $arcresult->year);
					$text .= '&nbsp;('.$arcresult->posts.')' . $afterafter;
					$output .= get_archives_link($url, $text, $format, '<li>', '</li>');
				
				}
			?>
		</ul>
<?php
		}
 
			echo '<ul>' . $output . '</ul>';
		    echo $after_widget;
	}
 
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
 
		return $instance;
	}
 
	function form( $instance ) {
		//Defaults
		$instance = wp_parse_args( (array) $instance, array( 'title' => '') );
		$title = esc_attr( $instance['title'] );
?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
<?php
	}
}

function mytheme_widget_init() {
	// unregister some default widgets
	unregister_widget('WP_Widget_Archives');
 
	// register my own widgets
	register_widget('BB_Archives');
}
 
add_action('widgets_init', 'mytheme_widget_init');