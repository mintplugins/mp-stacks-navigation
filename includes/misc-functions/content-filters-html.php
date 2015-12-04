<?php 
/**
 * This file contains the function which hooks to a brick's HTML content output
 *
 * @since 1.0.0
 *
 * @package    MP Stacks Navigation
 * @subpackage Functions
 *
 * @copyright  Copyright (c) 2015, Mint Plugins
 * @license    http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @author     Philip Johnston
 */

/**
 * This function hooks to the brick output. If it is supposed to be a 'navigation', then it will output the navigation
 *
 * @access   public
 * @since    1.0.0
 * @return   void
 */
function mp_stacks_brick_content_output_navigation($default_content_output, $mp_stacks_content_type, $post_id){
	
	//If this stack content type isn't set to be an navigation	
	if ($mp_stacks_content_type != 'navigation'){
		return $default_content_output; 	
	}
	
	$content_output = NULL;
	
	$the_menu_to_show = mp_core_get_post_meta( $post_id, 'mp_stacks_navigation_menu' );
	//$menu_alignment = mp_core_get_post_meta( $post_id, 'mp_stacks_navigation_alignment', 'horizontal' );
	$menu_alignment = 'horizontal';
	
	$mp_stacks_navigation_mobile_switchout_width = mp_core_get_post_meta( $post_id, 'mp_stacks_navigation_mobile_switchout_width', 600 );
	$open_from = mp_core_get_post_meta( $post_id, 'mp_stacks_navigation_mobile_right_or_left', 'right' );
	
	ob_start(); 
	
	//If there is a menu to show
	if (!empty( $the_menu_to_show ) ){
		
		//Echo the version actually shown in the Brick
		echo '<div class="mp-stacks-navigation-toggle-button-holder" style="display:none;"><a class="mp-stacks-navigation-toggle"></a></div>';
		echo '<div id="mp-stacks-navigation-container-' . $post_id . '" class="mp-stacks-navigation-container mp-stacks-navigation-alignment-' . $menu_alignment . '">';
			wp_nav_menu( array( 'menu' => $the_menu_to_show, 'fallback_cb' => 'mp_stacks_navigation_fallback', 'container_class' => 'mp-stacks-navigation', ) );
		echo '</div>';
		
		//Echo the version that will be used in the side popout
		echo '<div class="mp-stacks-navigation-popout-holder mp-stacks-navigation-popout-holder-' . $open_from . '" style="display:none;">';
			wp_nav_menu( array( 'menu' => $the_menu_to_show, 'fallback_cb' => 'mp_stacks_navigation_fallback', 'container_class' => 'mp-stacks-navigation-popout', ) );
		echo '</div>';
		
	}
	
	//Enable Media Queries for JS
	 wp_enqueue_script( 'mp_stacks_navigation_enquire', plugins_url( '/js/enquire.min.js', dirname( __FILE__ ) ) );
	 
	 //MP Menu JS
	 wp_enqueue_script( 'mp_stacks_navigation_js', plugins_url( '/js/mp-stacks-navigation.js', dirname( __FILE__ ) ), array( 'jquery', 'mp_stacks_navigation_enquire' ) );
	 wp_localize_script( 'mp_stacks_navigation_js', 'mp_stacks_navigation_vars', array(
 	 	'change_at_screen_width' => $mp_stacks_navigation_mobile_switchout_width,
		'open_from' => $open_from
     ));
	
	//Return
	return ob_get_clean();
	
}
add_filter('mp_stacks_brick_content_output', 'mp_stacks_brick_content_output_navigation', 10, 3);

/**
 * This function will show if there is no menu set for this Brick's Navigation.
 *
 * @access   public
 * @since    1.0.0
 * @return   void
 */
function mp_stacks_navigation_fallback(){
	echo __( 'Edit this Brick to set a menu to show here', 'mp_stacks_navigation' );	
}