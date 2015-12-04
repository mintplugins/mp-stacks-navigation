jQuery(document).ready(function($){
	
	//Wrap body is holding div which can have values which are not processed on 'body' or 'html'
	$( 'body' ).wrapInner('<div id="mp-stacks-navigation-site-wrap" />');
		
	//Add menu holder to screen
	$( '.mp-stacks-navigation-popout-holder' ).prependTo( 'body' );
	
	//Items which should have the "open" or "close" class added to them
	var $items = $( '#mp-stacks-navigation-site-wrap' );
	
	//Open function
	var mp_stacks_navigation_open = function() {

		//$( 'body' ).addClass( 'mobile-open' );
		setTimeout(function() {
			$items.removeClass('mp-stacks-navigation-close').addClass('mp-stacks-navigation-open-' + mp_stacks_navigation_vars.open_from );
			$( '.mp-stacks-navigation-popout-holder' ).css( 'display', '' );
		}, 300);
	}
	
	//Close function
	var mp_stacks_navigation_close = function() { 
		
		$items.removeClass('mp-stacks-navigation-open-' + mp_stacks_navigation_vars.open_from).addClass('mp-stacks-navigation-close');
		
		//After the transition is done, remove the mp-stacks-navigation-close class because it breaks fixed menus because the properites conflict		
		setTimeout(function() {
			$items.removeClass( 'mp-stacks-navigation-close' );
			$( '.mp-stacks-navigation-popout-holder' ).css( 'display', 'none' );
		}, 300);
		
	}
	
	//When toggle switch is clicked
  	$( '.mp-stacks-navigation-toggle, .mp-stacks-navigation-close-button' ).click(function(e){
		
		e.preventDefault();

		$items.hasClass( 'mp-stacks-navigation-open-' + mp_stacks_navigation_vars.open_from ) ? $(mp_stacks_navigation_close) : $(mp_stacks_navigation_open);
	});
	
	//If the user clicks on the main site area, close the nav as well
	$(document).on('touchstart click', '#mp-stacks-navigation-site-wrap.mp-stacks-navigation-open-' + mp_stacks_navigation_vars.open_from, function(e){
				
		$(mp_stacks_navigation_close);
		
	});
	
	//When the dropdown toggle is clicked in the sidebar popout nav:
	$( '.mp-stacks-navigation-popout-holder .menu li.menu-item-has-children > a' ).on( 'click touchstart', function( event ){
		
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
	var screen_width = mp_stacks_navigation_vars.change_at_screen_width;
	
	//When the screen is smaller than the defined number of pixels
	enquire.register("screen and (max-width:"+screen_width+"px)", {
						
			// If supplied, triggered when a media query matches.
			match : function() {
				
				//Hide the menu in the brick
				$( '.mp-stacks-navigation-container' ).css( 'display', 'none' );
				//Show the toggle button in the brick
				$( '.mp-stacks-navigation-toggle-button-holder' ).css( 'display', '' );
			},      
										
			// If supplied, triggered when the media query transitions 
			// *from a matched state to an unmatched state*.
			unmatch : function() {
				
				//Show the menu in the brick
				$( '.mp-stacks-navigation-container' ).css( 'display', '' );
				//Hide the toggle button in the brick
				$( '.mp-stacks-navigation-toggle-button-holder' ).css( 'display', 'none' );

			}
			
	});
		
});