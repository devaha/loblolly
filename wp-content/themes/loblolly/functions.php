<?php

// Add Search bar to nav area
add_action( 'woo_nav_inside', 'woo_custom_add_searchform', 10 );
 
function woo_custom_add_searchform () {
    echo '<div id="header-search" class="header-search fr">' . "\n";
    get_template_part( 'search', 'form' );
    echo '</div><!--/#header-search .header-search fr-->' . "\n";
} // End woo_custom_add_searchform()


/**** Add widget areas to home page  *****/

// Register widget area

if ( function_exists('register_sidebar') ) {
   register_sidebar( array(
		'name' => __( 'Home Callouts', 'loblolly' ),
		'id' => 'home-callouts',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => "</div>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
}

function show_home_callouts() {
	
	if(is_active_sidebar('home-callouts') && is_front_page()) {
		echo '<div id="home-callouts">';
		  dynamic_sidebar('home-callouts');
		  
		echo '<div class="clearer"></div><!-- .clearer-->
		  </div><!-- #home-callouts -->
		 ';
	}
}
 
 
add_action( 'woo_loop_before', 'show_home_callouts', 1 );

/*-----------------------------------------------------------------------------------*/
/* Woo Slider Business */
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'woo_slider_biz' ) ) {
	function woo_slider_biz( $args = null ) {
		
		global $woo_options, $post;
		
		// This is where our output will be added.
		$html = '';
		
		// Default slider settings. 
		$defaults = array(
							'id' => 'loopedSlider', 
							'echo' => true, 
							'excerpt_length' => '15', 
							'pagination' => false, 
							'width' => '940', 
							'height' => '350', 
							'order' => 'ASC', 
							'posts_per_page' => '5', 
							'slide_page' => 'all', 
							'use_slide_page' => false
						 );
						 
		// Setup the "Slide Page", if one is set.
		if ( isset( $post->ID ) ) {
			$slide_page = 'all';
			$stored_slide_page = get_post_meta( $post->ID, '_slide-page', true );
			
			if ( $stored_slide_page != '' && $stored_slide_page != 'all' ) {
				$slide_page = $stored_slide_page;
				$defaults['use_slide_page'] = true; // Instruct the slider to apply the necessary conditional.
				$defaults['slide_page'] = $slide_page;
			}
		}
		
		// Setup height of slider.
		$height = $woo_options['woo_slider_biz_height'];
		if ( $height != '' ) { $defaults['height'] = $height; }
	
		// Setup width of slider and images.
		$layout = $woo_options['woo_layout'];
		if ( !$layout )
			$layout = get_option( 'woo_layout' ); 
		$layout_width = get_option('woo_layout_width');
	
		// Setup the number of posts to show.
		$posts_per_page = $woo_options['woo_slider_biz_number'];
		if ( $posts_per_page != '' ) { $defaults['posts_per_page'] = $posts_per_page; }

		// Setup the order of posts.
		$post_order = $woo_options['woo_slider_biz_order'];
		if ( $post_order != '' ) { $defaults['order'] = $post_order; }
		
		// Setup the excerpt length.
		if ( isset($woo_options['woo_slider_biz_excerpt_length']) ) {
			$excerpt_length = $woo_options['woo_slider_biz_excerpt_length'];
			if ( $excerpt_length != '' ) { $defaults['excerpt_length'] = $excerpt_length; }
		} 
		
		$width = intval( $layout_width );
		
		if ( $width > 0 && $args['width'] == '' ) { $defaults['width'] = $width; }
	
		// Merge the arguments with defaults.
		$args = wp_parse_args( $args, $defaults );
		
		if ( ( ( isset($args['width']) ) && ( ( $args['width'] <= 0 ) || ( $args['width'] == '')  ) ) || ( !isset($args['width']) ) ) {	$args['width'] = '100'; }
		if ( ( isset($args['height']) ) && ( $args['height'] <= 0 )  ) { $args['height'] = '100'; }
		
		// Allow child themes/plugins to filter these arguments.
		$args = apply_filters( 'woo_biz_slider_args', $args );
	
		// Setup slider page id's
		$query_args = array(
							'post_type' => 'slide', 
							'order' => $args['order'], 
							'orderby' => 'date', 
							'posts_per_page' => $args['posts_per_page']
						);
		
		if ( $args['use_slide_page'] == true ) {
		
			$query_args['tax_query'] = array( array(
								'taxonomy' => 'slide-page',
								'field' => 'slug',
								'terms' => $args['slide_page']
							) );
		
		}
		
		$slide_query = new WP_Query( $query_args );
		
		if ( ( ! $slide_query->have_posts() ) ) {
			echo '<p class="note">' . __( 'Please add some slider posts using the Slide custom post type.', 'woothemes' ) . '</p>';
			return;
		}
		
		if ( ( $slide_query->found_posts < 1 ) ) {
			echo '<p class="note">' . __( 'Please note that this slider requires 2 or more slides in order to function. Please assign another slide.', 'woothemes' ) . '</p>';
			return;
		}
			
		// Setup the slider CSS class.
		$slider_css = '';
		
		if ( @$woo_options['woo_slider_pagination'] == 'true' ) {
			$slider_css = ' class="has-pagination"';
		}

	// Begin setting up HTML output.
	
	if ( @$woo_options['woo_slider_autoheight'] != 'true' ) {
		 $html .= '<div id="' . $args['id'] . '"' . $slider_css . ' style="height:' . $args['height'] . 'px">' . "\n";
	     $html .= '<div class="container" style="height:' . $args['height'] . 'px">' . "\n";
    } else {
		 $html .= '<div id="' . $args['id'] . '"' . $slider_css . ' style="height:auto;">' . "\n";
	     $html .= '<div class="container" style="height:auto;">' . "\n";
	}

	     
	     if ( @$woo_options['woo_slider_autoheight'] != 'true' )
	         $html .= '<div class="slides" style="height:' . $args['height'] . 'px">' . "\n";
	     else
	         $html .= '<div class="slides">' . "\n";
	                
	       	 if ( $slide_query->have_posts() ) { $count = 0; while ( $slide_query->have_posts() ) { $slide_query->the_post(); global $post; $count++;
			   
	
				$styles = 'width: ' . $args['width'] . 'px;';
				if ( $count >= 2 ) { $styles .= ' display:none;'; }
	
	            $html .= '<div id="slide-' . $count . '" class="slide" style="' . $styles . '">' . "\n";                
	
				$type = get_post_meta( $post->ID, 'image', true );
				
				if ( $type ) {
					$url = get_post_meta( $post->ID, 'url', true );
					
					if ( $url ) {
						$html .= '<a href="' . $url . '" title="' . the_title_attribute( array( 'echo' => 0 ) ) . '">' . woo_image('width=' . $args['width'] . '&height=' . $args['height'] . '&link=img&return=true') . '</a>' . "\n";
					} else {
						$html .= woo_image( 'width=' . $args['width'] . '&height=' . $args['height'] . '&link=img&return=true' );
					}
					
					$html .= '<div class="content">' . "\n";                 
	                
	                if ( $woo_options['woo_slider_biz_title'] == 'true' ) {
	                	if ( $url ) {
		                	$html .= '<div class="title"><h2 class="title"><a href="' . $url . '">' . get_the_title( $post->ID ) . '</a></h2></div>' . "\n";
	                	
	                	} else { 
	                		$html .= '<div class="title"><h2 class="title">' . get_the_title( $post->ID ) . '</h2></div>' . "\n";
	                
	                	}
	                }
	                	$content = get_the_content($post->ID);
						$content = do_shortcode($content);

						if($count == 1)
	                	   {$excerpt .= '<div class="excerpt">' . wpautop( $content ) . '</div>' . "\n";}
						   
	                    $html .= '</div>' . "\n";
	                    
	                } else {
	                
	                	$content = get_the_content($post->ID);
						$content = do_shortcode($content);

	                	$html .= '<div class="entry">' . wpautop( $content ) . '</div>' . "\n";                       
	               
	               }
	
	            $html .= '</div>' . "\n";  
	
	        } // End WHILE Loop
	        
	       } // End IF Statement
	        
	        $html .= '</div><!-- /.slides -->' . "\n";
	    $html .= '</div><!-- /.container --> ' . "\n";  
		
		$html .= $excerpt;
	    
	    $html .= '<a href="#" class="previous"><img src="' . get_stylesheet_directory_uri() . '/images/btn-prev-slider.png" alt="Previous Slide" /></a>' . "\n";
	    $html .= '<a href="#" class="next"><img src="' . get_stylesheet_directory_uri() . '/images/btn-next-slider.png" alt="Next Slide" /></a>' . "\n";        
	    
	
	$html .= '</div><!-- /#' . $args['id'] . ' -->' . "\n";
	
		if ( $args['echo'] ) {
			echo $html;
		}
		
		return $html;
	
	} // End woo_slider_biz()
}


//function to determine if a page has children
function has_children($child_of = null)
{
        if(is_null($child_of)) {
                global $post;
                $child_of = ($post->post_parent != '0') ? $post->post_parent : $post->ID;
        }
        return (wp_list_pages("child_of=$child_of&echo=0")) ? true : false;
}


//Add Javascript for equal hieghts (and any other js we might need)
function register_scripts() {
	wp_enqueue_script('jquery');
	$url = get_stylesheet_directory_uri().'/js/jQuery.equalHeights.js';
 	wp_enqueue_script('equalHeights', $url, 'jquery', '1.0');

}

add_action('wp_head', 'register_scripts');



?>