<?php
/**
 * This file contains the function which hooks to a brick's CSS content output
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
 * This function hooks to the brick css output. If it is supposed to be an 'navigation', then it will add the css for the navigation to the brick's css
 *
 * @access   public
 * @since    1.0.0
 * @return   void
 */
function mp_stacks_brick_content_output_css_navigation( $css_output, $post_id, $first_content_type, $second_content_type ){

	if ( $first_content_type != 'navigation' && $second_content_type != 'navigation' ){
		return $css_output;
	}

	//Enqueue navigation CSS
	wp_enqueue_style( 'mp_stacks_navigation_css', plugins_url( 'css/mp-stacks-navigation.css', dirname( __FILE__ ) ), MP_STACKS_NAVIGATION_VERSION );

	//Font Styling
	$menu_font_size = mp_core_get_post_meta( $post_id, 'mp_stacks_navigation_item_font_size', 20 );
	$menu_font_color = mp_core_get_post_meta( $post_id, 'mp_stacks_navigation_item_font_color', '#fff' );

	//Font Styling when the mouse is over.
	$menu_font_color_when_mouse_over = mp_core_get_post_meta( $post_id, 'mp_stacks_navigation_item_font_color_hover', '#fff' );

	//Spacing between items
	$horizontal_spacing = mp_core_get_post_meta( $post_id, 'mp_stacks_navigation_item_horizontal_spacing', 20 );

	//Toggle button color
	$toggle_button_color = mp_core_get_post_meta( $post_id, 'mp_stacks_navigation_mobile_toggle_button_color', '#fff' );
	$toggle_button_hover_color = mp_core_get_post_meta( $post_id, 'mp_stacks_navigation_mobile_toggle_button_color_hover', '#fff' );

	//CSS for the navigation.
	$css_navigation_output =
	'#mp-stacks-navigation-container-' . $post_id . ' .menu-item{' .
		mp_core_css_line( 'padding-right', $horizontal_spacing, 'px' ) .
		mp_core_css_line( 'font-size', $menu_font_size, 'px' ) .
		mp_core_css_line( 'color', $menu_font_color ) .
	'}
	#mp-stacks-navigation-container-' . $post_id . ' .menu-item a{' .
		mp_core_css_line( 'color', $menu_font_color ) .
	'}
	#mp-stacks-navigation-container-' . $post_id . ' .menu-item a:hover{' .
		mp_core_css_line( 'color', $menu_font_color_when_mouse_over ) .
	'}
	#mp-stacks-navigation-popout-holder-' . $post_id . '{' .
		mp_core_css_line( 'font-size', $menu_font_size, 'px' ) .
	'}
	#mp-stacks-navigation-container-' . $post_id . ' .mp-stacks-navigation-toggle{' .
		mp_core_css_line( 'color', $toggle_button_color ) .
	'}
	#mp-stacks-navigation-container-' . $post_id . ' .mp-stacks-navigation-toggle:hover{' .
		mp_core_css_line( 'color', $toggle_button_hover_color ) .
	'}';

	//Mobile Styles
	
	//Toggle button color
	$mp_stacks_navigation_mobile_font_color = mp_core_get_post_meta( $post_id, 'mp_stacks_navigation_mobile_font_color', '#000' );
	$mp_stacks_navigation_mobile_font_size = mp_core_get_post_meta( $post_id, 'mp_stacks_navigation_mobile_font_size', '20' );
	$mp_stacks_navigation_mobile_bg_color = mp_core_get_post_meta( $post_id, 'mp_stacks_navigation_mobile_bg_color', '#fff' );
	$mp_stacks_navigation_mobile_bg_color_hover = mp_core_get_post_meta( $post_id, 'mp_stacks_navigation_mobile_bg_color_hover', '#F8F8F8' );
	
	$css_navigation_output .= '#mp-stacks-navigation-popout-holder-' . $post_id . ' .mp-stacks-navigation-popout{' .
		mp_core_css_line( 'color', $mp_stacks_navigation_mobile_font_color ) .
		mp_core_css_line( 'font-size', $mp_stacks_navigation_mobile_font_size, 'px' ) .
		mp_core_css_line( 'background-color', $mp_stacks_navigation_mobile_bg_color ) .
	'}' .
	'#mp-stacks-navigation-popout-holder-' . $post_id . ' .mp-stacks-navigation-popout li a{' .
		mp_core_css_line( 'color', $mp_stacks_navigation_mobile_font_color ) .
		mp_core_css_line( 'background-color', $mp_stacks_navigation_mobile_bg_color ) .
		mp_core_css_line( 'font-size', $mp_stacks_navigation_mobile_font_size, 'px' ) .
		mp_core_css_line( 'border-bottom', '1px solid ' . $mp_stacks_navigation_mobile_font_color ) .
	'}' . 
	'#mp-stacks-navigation-popout-holder-' . $post_id . ' .mp-stacks-navigation-popout li a:hover{' .
		mp_core_css_line( 'background-color', $mp_stacks_navigation_mobile_bg_color_hover ) .
	'}';
	

	return $css_navigation_output . $css_output;

}
add_filter('mp_brick_additional_css', 'mp_stacks_brick_content_output_css_navigation', 10, 4);
