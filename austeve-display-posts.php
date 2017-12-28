<?php

/*
Plugin Name: AUSteve Display Posts Shortcode
Plugin URI: https://github.com/australiansteve/wp-plugin-austeve-display-posts
Description: Display post content or archives with a simple shortcode 
Version: 1.0.0
Author: AustralianSteve
Author URI: http://australiansteve.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/


class AUSteve_Display_Posts {

	function __construct() {

		add_shortcode( 'austeve-display-posts', array($this, 'shortcode_output'));

		add_action( 'wp_enqueue_scripts', array($this, 'austeve_posts_enqueue_style') );

		add_action( 'wp_enqueue_scripts', array($this, 'austeve_posts_enqueue_script') );
	}


	function austeve_posts_enqueue_style() {
		wp_enqueue_style( 'jquery-ui-css', 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css');
		wp_enqueue_style( 'austeve-posts', plugin_dir_url( __FILE__ ). 'style.css' , '' , '1.0'); 
	}

	function austeve_posts_enqueue_script() {
		wp_enqueue_script( 'austeve-posts', plugin_dir_url( __FILE__ ). 'js/display-posts.js' , array( 'jquery-ui-accordion', 'jquery' ) , '1.0'); 
	}

	function shortcode_output($atts, $content)
	{
	    $atts = shortcode_atts( array(
	    	'post_type' => 'post',
	        'category_name' => '',
	        'tax_name' => '',
	        'tax_term' => '',
	        'tax_field' => 'slug',
	        'format' => 'single',
	        'posts_per_page' => 5,
	        'id' => '',
	        'show_pagination' => 'false',
	        'container_class' => '',
	        'post_class' => '',
	    ), $atts );
	    
	    extract( $atts );

	    $args = array(
	        'post_type' => $post_type,
	        'p' => $id,
	        'post_status' => array('publish'),
	        'posts_per_page' => $posts_per_page,
	        'paged'	=> get_query_var('paged') ? get_query_var('paged') : 1,
	    );


	    if (!empty($category_name) && $post_type == 'post')
	    {
	    	//Only good for post categories
	    	$args['category_name'] = $category_name;	
	    }

	    if (!empty($tax_name) && !empty($tax_term)) 
		{
			$args['tax_query'] = array(
		        array(
		            'taxonomy' => $tax_name,
		            'field' => $tax_field,
		            'terms' => $tax_term,
		        )
		    );
		}	

	    error_log("Display posts query: ". print_r($args, true));
	    query_posts( $args );
	    
	    $format == 'archive' ? '' : $format;
	    error_log("Format: ". print_r($format, true));
		
		ob_start();
	    if ( have_posts() ):

			if (!empty($container_class))
				echo "<div class='".$container_class."'>";;
	    	
		    while ( have_posts() ) :
		        the_post();

			    echo "<div id='".$post_type."-".get_the_id()."' class='austeve-display-posts ".$post_class."'>";

				if ($post_type == 'page') :
		    		the_content();
		    	else:
					get_template_part( 'template-parts/content', $format ); 
				endif;
				
				echo "</div>";

		    endwhile;

	    	if (!empty($container_class))
	    		echo "</div>";

		    if ($show_pagination == 'true'):
			    ?>
				    <!-- pagination -->
				    <div class='row pagination'>
					    <div class='small-6 columns page-link newer-posts'>
							<?php previous_posts_link( "<< Newer posts" ); ?>
					    </div>
					    <div class='small-6 columns text-right page-link older-posts'>
							<?php next_posts_link( "Older posts >>"); ?>
					    </div>
				    </div>
			    <?php
			endif;

	    	
		else:
	    	echo "<div id='no-posts'>No posts found</div>";
		endif;
		wp_reset_query();

    	return ob_get_clean();
	}

}

// Display posts!
$austeveDisplayPosts = new AUSteve_Display_Posts();

?>
