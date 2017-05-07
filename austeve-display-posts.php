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
		wp_enqueue_style( 'austeve-faqs', plugin_dir_url( __FILE__ ). 'style.css' , '' , '1.0'); 
	}

	function austeve_posts_enqueue_script() {
		wp_enqueue_script( 'austeve-faqs-js', plugin_dir_url( __FILE__ ). 'js/faqs.js' , array( 'jquery-ui-accordion', 'jquery' ) , '1.0'); 
	}

	function shortcode_output($atts, $content)
	{
	    $atts = shortcode_atts( array(
	    	'post_type' => 'post',
	        'category' => '',
	        'format' => 'single',
	    ), $atts );
	    
	    extract( $atts );

	    $args = array(
	        'post_type' => $post_type,
	        'post_status' => array('publish'),
	        'posts_per_page' => -1,
	        'paged'         => false,

	    );

	    if ($category != '' && $post_type == 'post')
	    {
	    	//Only good for post categories
	    	$args['category_name'] = $category;
	    }

	    error_log("Display posts query: ". print_r($args, true));
	    query_posts( $args );
	    
	    $format == 'archive' ? '' : $format;
	    error_log("Format: ". print_r($format, true));
		
		ob_start();
	    if ( have_posts() ):
	    	?>
		    <div id='<?php echo $post_type; ?>' class='austeve-display-posts'>
		    <?php
		    while ( have_posts() ) :
		        the_post();
?>
				<?php get_template_part( 'components/content', $format ); ?>
<?php
		    endwhile;
		    ?>
		    </div>
		    <?php
		else:
			    	echo "<div id='faqs'>No posts found</div>";
		endif;
		wp_reset_query();

    	return ob_get_clean();
	}

}

// Display posts!
$austeveDisplayPosts = new AUSteve_Display_Posts();

?>