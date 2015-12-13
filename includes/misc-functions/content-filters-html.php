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

	global $mp_stacks_navigation_current_brick_id;

	$content_output = NULL;

	$the_menu_to_show = mp_core_get_post_meta( $post_id, 'mp_stacks_navigation_menu' );
	//$menu_alignment = mp_core_get_post_meta( $post_id, 'mp_stacks_navigation_alignment', 'horizontal' );
	$menu_alignment = 'horizontal';

	$mp_stacks_navigation_mobile_switchout_width = mp_core_get_post_meta( $post_id, 'mp_stacks_navigation_mobile_switchout_width', 600 );

	ob_start();

	//If there is a menu to show
	if (!empty( $the_menu_to_show ) ){

		//Echo the version actually shown in the Brick
		echo '<div id="mp-stacks-navigation-container-' . $post_id . '" class="mp-stacks-navigation-container mp-stacks-navigation-alignment-' . $menu_alignment . '">';
			echo '<div class="mp-stacks-navigation-toggle-button-holder" style="display:none;"><a class="mp-stacks-navigation-toggle"></a></div>';
			wp_nav_menu( array( 'menu' => $the_menu_to_show, 'fallback_cb' => 'mp_stacks_navigation_fallback', 'container_class' => 'mp-stacks-navigation', 'mp_stacks_brick_id' => $post_id ) );
		echo '</div>';

		//Echo the version that will be used in the side popout
		echo '<div id="mp-stacks-navigation-popout-holder-' . $post_id . '" class="mp-stacks-navigation-popout-holder mp-stacks-navigation-close">';
			wp_nav_menu( array( 'menu' => $the_menu_to_show, 'fallback_cb' => 'mp_stacks_navigation_fallback', 'container_class' => 'mp-stacks-navigation-popout' ) );
		echo '</div>';

	}

	//Enable Media Queries for JS
	 wp_enqueue_script( 'mp_stacks_navigation_enquire', plugins_url( '/js/enquire.min.js', dirname( __FILE__ ) ) );

	 ob_start();
	 ?>
     jQuery(document).ready(function($){

         //When an item with a sub menu is hovered, make the brick grow to be the same size so none is cut off (bricks are overflow:hidden)
         $( '.mp-stacks-navigation > ul > .menu-item-has-children' ).on( 'mouseenter', function(){

            var this_brick_id = $(this).find( '> a').attr( 'mp_stacks_brick_id' );

            //The navigation area's current height
            var nav_current_height = parseInt( $('#mp-brick-' + this_brick_id + ' .mp-stacks-navigation').css( 'height' ) );

            //This submenu's height
            var this_submenu_height = parseInt( $(this).find( '.sub-menu').css( 'height' ) );

            //This nav button's height
            var this_button_height = parseInt( $(this).css('height' ) );

            var total_height = this_button_height + this_submenu_height + 'px';

            $('#mp-brick-' + this_brick_id + ' .mp-stacks-navigation').css( 'min-height', total_height );


         }).on( 'mouseleave', function(){

            var this_brick_id = $(this).find( '> a').attr( 'mp_stacks_brick_id' );

            $('#mp-brick-' + this_brick_id + ' .mp-stacks-navigation').css( 'min-height', '' );

         });

         //When an item with a second level (or later) sub menu is hovered
         $( '.mp-stacks-navigation > ul > .menu-item-has-children > ul .menu-item-has-children' ).on( 'mouseenter', function(){

            var this_brick_id = $(this).find( '> a').attr( 'mp_stacks_brick_id' );

            //The navigation area's current height
            var nav_current_height = parseInt( $('#mp-brick-' + this_brick_id + ' .mp-stacks-navigation').css( 'height' ) );

            //This submenu's height
            var this_submenu_height = parseInt( $(this).find( '.sub-menu').css( 'height' ) );

            //This nav button's height
            var this_button_height = parseInt( $(this).css('height' ) );

            var total_height = this_button_height + nav_current_height + this_submenu_height + 'px';

            $('#mp-brick-' + this_brick_id + ' .mp-stacks-navigation').css( 'min-height', total_height );


         });

        //Mobile Open function
        var mp_stacks_navigation_open_<?php echo $post_id; ?> = function() {

              $( '#mp-stacks-navigation-popout-holder-<?php echo $post_id; ?>' ).removeClass('mp-stacks-navigation-close').addClass( 'mp-stacks-navigation-open' );

							$( '#mp-stacks-navigation-popout-holder-<?php echo $post_id; ?>' ).css( 'max-height', $( '#mp-stacks-navigation-popout-holder-<?php echo $post_id; ?> .mp-stacks-navigation-popout' ).css( 'height' ) );

        }

        //Close function
        var mp_stacks_navigation_close_<?php echo $post_id; ?> = function() {

            $( '#mp-stacks-navigation-popout-holder-<?php echo $post_id; ?>' ).addClass('mp-stacks-navigation-close').removeClass( 'mp-stacks-navigation-open' );

						$( '#mp-stacks-navigation-popout-holder-<?php echo $post_id; ?>' ).css( 'max-height', '' );

        }

        //When toggle switch is clicked
        $( '#mp-stacks-navigation-container-<?php echo $post_id; ?> .mp-stacks-navigation-toggle-button-holder .mp-stacks-navigation-toggle'  ).click(function(e){

            e.preventDefault();

						$( '#mp-stacks-navigation-popout-holder-<?php echo $post_id; ?>' ).hasClass( 'mp-stacks-navigation-open' ) ? $(mp_stacks_navigation_close_<?php echo $post_id; ?>) : $(mp_stacks_navigation_open_<?php echo $post_id; ?>);

        });

        //When a dropdown is clicked in the mobile popout nav:
        $( ' #mp-stacks-navigation-popout-holder-<?php echo $post_id; ?> .menu li.menu-item-has-children > a' ).on( 'click touchstart', function( event ){

            var flag = $(this).parent().attr( 'mp_stacks-subnavflag' );

            if ( !flag || flag == 'closed' ){
                event.preventDefault();
                $(this).parent().attr( 'mp_stacks-subnavflag', 'open' );
                $(this).parent().find( ' > .sub-menu' ).show();

								$(mp_stacks_navigation_open_<?php echo $post_id; ?>);
            }
            else{
                $(this).parent().attr( 'mp_stacks-subnavflag', 'closed' );
                $(this).parent().find( ' > .sub-menu' ).hide();
            }

        });

        //Get the width at which we should switch to the popout button
        var screen_width = <?php echo $mp_stacks_navigation_mobile_switchout_width; ?>

        //When the screen is smaller than the defined number of pixels
        enquire.register("screen and (max-width:"+screen_width+"px)", {

                // If supplied, triggered when a media query matches.
                match : function() {

                    //Hide the menu in the brick
                    $( '#mp-stacks-navigation-container-<?php echo $post_id; ?> .mp-stacks-navigation' ).css( 'display', 'none' );
                    //Show the toggle button in the brick
                    $( '#mp-stacks-navigation-container-<?php echo $post_id; ?> .mp-stacks-navigation-toggle-button-holder' ).css( 'display', '' );
                },

                // If supplied, triggered when the media query transitions
                // *from a matched state to an unmatched state*.
                unmatch : function() {

                    //Show the menu in the brick
                    $( '#mp-stacks-navigation-container-<?php echo $post_id; ?> .mp-stacks-navigation' ).css( 'display', '' );
                    //Hide the toggle button in the brick
                    $( '#mp-stacks-navigation-container-<?php echo $post_id; ?> .mp-stacks-navigation-toggle-button-holder' ).css( 'display', 'none' );

                }

        });

    });
    <?php

	 //Pull in the existing MP Stacks inline js string which is output the Footer.
	 global $mp_stacks_footer_inline_js;
	 $mp_stacks_footer_inline_js[ 'mp-stacks-navigation-' . $post_id ] = ob_get_clean();

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

/**
 * Filter Function which returns class name for a brick
 *
 * @since    1.0.0
 * @link     http://mintplugins.com/doc/
 * @param    string $classes See link for description.
 * @param    string $post_id See link for description.
 * @return   void
 */
function mp_stacks_navigation_brick_class( $classes, $post_id ){

	//First Media Type
	$mp_stacks_first_content_type = get_post_meta($post_id, 'brick_first_content_type', true);

	//Second Media Type
	$mp_stacks_second_content_type = get_post_meta($post_id, 'brick_second_content_type', true);

	//If navigation is on for this brick
	if ( $mp_stacks_first_content_type == 'navigation' || $mp_stacks_second_content_type == 'navigation' ){

		//Add the navigation class name to the brick
		$classes .= ' mp-brick-navigation';

	}

	//Return CSS Output
	return $classes;

}
add_filter( 'mp_stacks_brick_class', 'mp_stacks_navigation_brick_class', 10, 2);

function add_specific_menu_atts( $atts, $item, $args ) {

	//If this menu is being output by the mp stacks navigation addon
	if ( isset( $args->mp_stacks_brick_id ) ){
		//Add the Brick Id to each menu item
		$atts['mp_stacks_brick_id'] = $args->mp_stacks_brick_id;
	}

    return $atts;
}
add_filter( 'nav_menu_link_attributes', 'add_specific_menu_atts', 10, 3 );
