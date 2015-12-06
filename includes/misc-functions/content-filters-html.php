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
		echo '<div id="mp-stacks-navigation-container-' . $post_id . '" class="mp-stacks-navigation-container mp-stacks-navigation-alignment-' . $menu_alignment . '">';
			echo '<div class="mp-stacks-navigation-toggle-button-holder" style="display:none;"><a class="mp-stacks-navigation-toggle"></a></div>';
			wp_nav_menu( array( 'menu' => $the_menu_to_show, 'fallback_cb' => 'mp_stacks_navigation_fallback', 'container_class' => 'mp-stacks-navigation', ) );
		echo '</div>';
		
		//Echo the version that will be used in the side popout
		echo '<div id="mp-stacks-navigation-popout-holder-' . $post_id . '" class="mp-stacks-navigation-popout-holder mp-stacks-navigation-popout-holder-' . $open_from . ' mp-stacks-navigation-close">';
			wp_nav_menu( array( 'menu' => $the_menu_to_show, 'fallback_cb' => 'mp_stacks_navigation_fallback', 'container_class' => 'mp-stacks-navigation-popout', ) );
		echo '</div>';
		
	}
	
	//Enable Media Queries for JS
	 wp_enqueue_script( 'mp_stacks_navigation_enquire', plugins_url( '/js/enquire.min.js', dirname( __FILE__ ) ) );
	 
	 //MP Menu JS
	 //wp_enqueue_script( 'mp_stacks_navigation_js', plugins_url( '/js/mp-stacks-navigation.js', dirname( __FILE__ ) ), array( 'jquery', 'mp_stacks_navigation_enquire' ) );
	
	 
	 ob_start();
	 ?>
     jQuery(document).ready(function($){
	
        //Wrap body is holding div which can have values which are not processed on 'body' or 'html'
        if ( !$( '#mp-stacks-navigation-site-wrap' ).length ){
        	$( 'body' ).wrapInner('<div id="mp-stacks-navigation-site-wrap" />');
        }
            
        //Add menu holder to screen
        $( '#mp-stacks-navigation-popout-holder-<?php echo $post_id; ?>' ).prependTo( 'body' );
        
        //Items which should have the "open" or "close" class added to them
        var $items = $( '#mp-stacks-navigation-site-wrap' );
        
        //Open function
        var mp_stacks_navigation_open_<?php echo $post_id; ?> = function() {
    		
            $( '#mp-stacks-navigation-popout-holder-<?php echo $post_id; ?>' ).css( 'display', '' );
            
            var brick_position = $( '#mp-brick-<?php echo $post_id; ?>' ).offset().top;
            $( '#mp-stacks-navigation-popout-holder-<?php echo $post_id; ?>' ).css( 'top', brick_position );
            	
            //$( 'body' ).addClass( 'mobile-open' );
            setTimeout(function() {
                $items.removeClass('mp-stacks-navigation-close').addClass( 'mp-stacks-navigation-open-<?php echo $open_from; ?>' );
                $( '#mp-stacks-navigation-popout-holder-<?php echo $post_id; ?>' ).removeClass('mp-stacks-navigation-close').addClass( 'mp-stacks-navigation-open-<?php echo $open_from; ?>' );
            }, 300);
        }
        
        //Close function
        var mp_stacks_navigation_close_<?php echo $post_id; ?> = function() { 
            
            $items.removeClass('mp-stacks-navigation-open-<?php echo $open_from; ?>').addClass('mp-stacks-navigation-close');
            $( '#mp-stacks-navigation-popout-holder-<?php echo $post_id; ?>' ).addClass('mp-stacks-navigation-close').removeClass( 'mp-stacks-navigation-open-<?php echo $open_from; ?>' );
            
            //After the transition is done, remove the mp-stacks-navigation-close class because it breaks fixed menus because the properites conflict		
            setTimeout(function() {
                $items.removeClass( 'mp-stacks-navigation-close' );
                $( '#mp-stacks-navigation-popout-holder-<?php echo $post_id; ?>' ).css( 'display', 'none' );
            }, 300);
            
        }
        
        //When toggle switch is clicked
        $( '#mp-stacks-navigation-container-<?php echo $post_id; ?> .mp-stacks-navigation-toggle-button-holder .mp-stacks-navigation-toggle'  ).click(function(e){
            
            e.preventDefault();
    
            $items.hasClass( 'mp-stacks-navigation-open-<?php echo $open_from; ?>' ) ? $(mp_stacks_navigation_close_<?php echo $post_id; ?>) : $(mp_stacks_navigation_open_<?php echo $post_id; ?>);
        });
        
        //If the user clicks on the main site area, close the nav as well
        $(document).on('touchstart click', '#mp-stacks-navigation-site-wrap.mp-stacks-navigation-open-<?php echo $open_from; ?>', function(e){
                    
            $(mp_stacks_navigation_close_<?php echo $post_id; ?>);
            
        });
        
        //When the dropdown toggle is clicked in the sidebar popout nav:
        $( ' #mp-stacks-navigation-popout-holder-<?php echo $post_id; ?> .menu li.menu-item-has-children > a' ).on( 'click touchstart', function( event ){
            
            var flag = $(this).parent().attr( 'mp_stacks-subnavflag' );
            
            if ( !flag || flag == 'closed' ){
                event.preventDefault();
                $(this).parent().attr( 'mp_stacks-subnavflag', 'open' );
                $(this).parent().find( ' > .sub-menu' ).show();
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