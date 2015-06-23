<?php
/*
Plugin Name: CC Feature Widget
Plugin URI: http://www.chriscernoch.com
Description: Creates a widget that lets the user specify an image url, link, and text and automatically formats it.
Version: 1.0
Author: Chris Cernoch
Author URI: http://www.chriscernoch.com
*/

/**
 * CC Feture Widget Class
 */
class CCFeatureWidget extends WP_Widget {
    /** constructor */
    function CCFeatureWidget() {
        
		
		$widget_options = array(
								'classname' => 'cc_feature_widget',
								'description' => 'Featured Image Boxes'
								);
		parent::WP_Widget('CCFeatureWidget', 'CCFeatureWidget', $widget_options);
    }

    /** @see WP_Widget::widget */
    function widget($args, $instance) {
        extract( $args );
        $title = apply_filters('widget_title', $instance['title']);
		$image = $instance['image'];
		$link = $instance['link'];
		$text = $instance['text'];
        ?>
              <?php echo $before_widget; ?>
			  <?php //checkt aht we have an image 
			  if($image){
			  ?>
			  <img src="<?php echo do_shortcode($image); ?>" alt="<?php echo $title; ?>" class="img" />
			  
			  <?php } ?>
              <div class="textwrapper">
              <?php
			  
		         if($title)
		          echo $before_title.$title.$after_title;
			    ?>
			  <div class="feature-text">
			         <?php echo $text; ?>
                     <?php if($link != "") { ?>
			         <a href="<?php echo do_shortcode($link); ?>" class="learn_more">Learn More</a>
                     <?php } ?>
		      </div><!--end feature-text-->
			  </div><!-- end textwrapper-->
              <?php echo $after_widget; ?>
        <?php
    }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {
	$instance = $old_instance;
	$instance['title'] = strip_tags($new_instance['title']);
	$instance['image'] = strip_tags($new_instance['image']);
	$instance['link'] = strip_tags($new_instance['link']);
	$instance['text'] = strip_tags($new_instance['text']);
        return $instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {
        $title = esc_attr($instance['title']);
		$image =   esc_attr($instance['image']);
		$link =   esc_attr($instance['link']);
		$text =   esc_attr($instance['text']);
        ?>
         <p>
          <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> 
          <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		  <label for="<?php echo $this->get_field_id('image'); ?>"><?php _e('Image URL:'); ?></label> 
		  <br /><span style="font-size: .8em">(The url of the image to use. <br  />Ex: http://mysite.com/image.jpg)</span><br />
		   <input class="widefat" id="<?php echo $this->get_field_id('image'); ?>" name="<?php echo $this->get_field_name('image'); ?>" type="text" value="<?php echo $image; ?>" />
		    <label for="<?php echo $this->get_field_id('link'); ?>"><?php _e('Link URL:'); ?></label> 
		  <br /><span style="font-size: .8em">(The url of the link to use. <br  />Ex: http://mysite.com/about-us/)</span><br />
		   <input class="widefat" id="<?php echo $this->get_field_id('link'); ?>" name="<?php echo $this->get_field_name('link'); ?>" type="text" value="<?php echo $link; ?>" />
		    <label for="<?php echo $this->get_field_id('text'); ?>"><?php _e('Text:'); ?></label> 
		  <br />
		   <textarea class="widefat" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>" ><?php echo $text; ?></textarea>
		  
        </p>
        <?php 
    }

} // class CCFeatureWidget

function CCFeatureWidget_init()
{
	register_widget('CCFeatureWidget');
}
add_action('widgets_init', 'CCFeatureWidget_init');