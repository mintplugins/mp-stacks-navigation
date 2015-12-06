<?php
/**
 * This page contains functions for modifying the metabox for navigation as a media type
 *
 * @link http://mintplugins.com/doc/
 * @since 1.0.0
 *
 * @package    MP Stacks Navigation
 * @subpackage Functions
 *
 * @copyright   Copyright (c) 2015, Mint Plugins
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @author      Philip Johnston
 */
 
/**
 * Add Image Navigation as a Media Type to the dropdown
 *
 * @since    1.0.0
 * @link     http://mintplugins.com/doc/
 * @param    array $args See link for description.
 * @return   void
 */
function mp_stacks_navigation_create_meta_box(){
	
	/**
	 * Array which stores all info about the new metabox
	 *
	 */
	$mp_stacks_navigation_add_meta_box = array(
		'metabox_id' => 'mp_stacks_navigation_metabox', 
		'metabox_title' => __( '"Navigation" Content-Type', 'mp_stacks_navigation'), 
		'metabox_posttype' => 'mp_brick', 
		'metabox_context' => 'advanced', 
		'metabox_priority' => 'low',
		'metabox_content_via_ajax' => true,
	);
	
	$navigation_menus_from_wp = get_terms( 'nav_menu', array( 'hide_empty' => true ) );
	$navigation_menu_array = array();
	
	foreach( $navigation_menus_from_wp as $menu_object ){
		
		$navigation_menu_array[$menu_object->term_id] = $menu_object->name;
			
	}
	
	/**
	 * Array which stores all info about the options within the metabox
	 *
	 */
	$mp_stacks_navigation_items_array = array(
	
		array(
			'field_id'			=> 'mp_stacks_navigation_menu',
			'field_title' 	=> __( 'Nav Menu', 'mp_stacks_navigation'),
			'field_description' 	=> __( 'Which Navigation Menu should be shown in this Brick?', 'mp_stacks_navigation' ),
			'field_type' 	=> 'select',
			'field_value' => 'none',
			'field_select_values' => $navigation_menu_array,
		),
		
		array(
			'field_id'			=> 'mp_stacks_navigation_style_settings',
			'field_title' 	=> __( 'Navigation Style Settings:', 'mp_stacks_navigation'),
			'field_description' 	=> __( '', 'mp_stacks_navigation' ),
			'field_type' 	=> 'showhider',
			'field_value' => '',
		),
		/*
		//This may be added int he future depending on user requests.
		array(
			'field_id'			=> 'mp_stacks_navigation_alignment',
			'field_title' 	=> __( 'Nav Menu Alignments:', 'mp_stacks_navigation'),
			'field_description' 	=> __( 'How should the navigation fields be aligned?', 'mp_stacks_navigation' ),
			'field_type' 	=> 'select',
			'field_value' => 'left',
			'field_select_values' => array( 
				'horizontal' => __('Horizontal Menu', 'mp_stacks_navigation' ),
				'vertical' => __('Vertical Menu', 'mp_stacks_navigation' ),
			),
			'field_showhider' => 'mp_stacks_navigation_style_settings',
		),
		*/
		array(
			'field_id'			=> 'mp_stacks_navigation_item_font_size',
			'field_title' 	=> __( 'Nav Font-Size:', 'mp_stacks_navigation'),
			'field_description' 	=> __( 'What size should the Field Titles be? Default: 20', 'mp_stacks_navigation' ),
			'field_type' 	=> 'number',
			'field_value' => '20',
			'field_showhider' => 'mp_stacks_navigation_style_settings',
		),
		array(
			'field_id'			=> 'mp_stacks_navigation_item_font_color',
			'field_title' 	=> __( 'Nav Font-Color', 'mp_stacks_navigation'),
			'field_description' 	=> __( 'What color should the font of the main menu be? Default #FFF (White)', 'mp_stacks_navigation' ),
			'field_type' 	=> 'colorpicker',
			'field_value' => '#FFF',
			'field_showhider' => 'mp_stacks_navigation_style_settings',
		),
		array(
			'field_id'			=> 'mp_stacks_navigation_item_font_color_hover',
			'field_title' 	=> __( 'Nav Font-Color Hover', 'mp_stacks_navigation'),
			'field_description' 	=> __( 'What color should the font of the main menu be when the mouse is over it? Default #FFF (White)', 'mp_stacks_navigation' ),
			'field_type' 	=> 'colorpicker',
			'field_value' => '#FFF',
			'field_showhider' => 'mp_stacks_navigation_style_settings',
		),
		array(
			'field_id'			=> 'mp_stacks_navigation_item_horizontal_spacing',
			'field_title' 	=> __( 'Nav Menu Item Spacing (Horizontal)', 'mp_stacks_navigation'),
			'field_description' 	=> __( 'How much space should there be in between each nav item horizontally? Default: 20px', 'mp_stacks_navigation' ),
			'field_type' 	=> 'number',
			'field_value' => '20',
			'field_showhider' => 'mp_stacks_navigation_style_settings',
		),
		
		array(
			'field_id'			=> 'mp_stacks_navigation_mobile_switchout_width',
			'field_title' 	=> __( 'Mobile Switchout Width', 'mp_stacks_navigation'),
			'field_description' 	=> __( 'When the screen gets this small (or smaller), switch to a "Pop-Out" style navigation. Default: 600.', 'mp_stacks_navigation' ),
			'field_type' 	=> 'number',
			'field_value' => '600',
			'field_showhider' => 'mp_stacks_navigation_style_settings',
		),
		array(
			'field_id'			=> 'mp_stacks_navigation_mobile_right_or_left',
			'field_title' 	=> __( 'Open Mobile Menu From...', 'mp_stacks_navigation'),
			'field_description' 	=> __( 'Should the mobile menu open from the right or the left side of the screen? Default: Right.', 'mp_stacks_navigation' ),
			'field_type' 	=> 'select',
			'field_value' => 'right',
			'field_select_values' => array( 
				'right' => __( 'Right', 'mp_stacks_navigation' ),
				'left' => __( 'Left', 'mp_stacks_navigation' ),
			),
			'field_showhider' => 'mp_stacks_navigation_style_settings',
		),
		array(
			'field_id'			=> 'mp_stacks_navigation_mobile_toggle_button_color',
			'field_title' 	=> __( 'Mobile Toggle Button Color', 'mp_stacks_navigation'),
			'field_description' 	=> __( 'When screens are small (mobile/phones), what color should the "Toggle" button be? Default: #fff (white)', 'mp_stacks_navigation' ),
			'field_type' 	=> 'colorpicker',
			'field_value' => '#fff',
			'field_showhider' => 'mp_stacks_navigation_style_settings',
		),
		array(
			'field_id'			=> 'mp_stacks_navigation_mobile_toggle_button_color_hover',
			'field_title' 	=> __( 'Mobile Toggle Button Color', 'mp_stacks_navigation'),
			'field_description' 	=> __( 'When screens are small (mobile/phones), what color should the "Toggle" button be when the mouse goes over it? Default: #fff (white)', 'mp_stacks_navigation' ),
			'field_type' 	=> 'colorpicker',
			'field_value' => '#fff',
			'field_showhider' => 'mp_stacks_navigation_style_settings',
		),
		
	);
	
	
	/**
	 * Custom filter to allow for add-on plugins to hook in their own data for add_meta_box array
	 */
	$mp_stacks_navigation_add_meta_box = has_filter('mp_stacks_navigation_meta_box_array') ? apply_filters( 'mp_stacks_navigation_meta_box_array', $mp_stacks_navigation_add_meta_box) : $mp_stacks_navigation_add_meta_box;
	
	//Globalize the and populate mp_stacks_features_items_array (do this before filter hooks are run)
	global $global_mp_stacks_navigation_items_array;
	$global_mp_stacks_navigation_items_array = $mp_stacks_navigation_items_array;
	
	/**
	 * Custom filter to allow for add on plugins to hook in their own extra fields 
	 */
	$mp_stacks_navigation_items_array = has_filter('mp_stacks_navigation_items_array') ? apply_filters( 'mp_stacks_navigation_items_array', $mp_stacks_navigation_items_array) : $mp_stacks_navigation_items_array;
	
	/**
	 * Create Metabox class
	 */
	global $mp_stacks_navigation_meta_box;
	$mp_stacks_navigation_meta_box = new MP_CORE_Metabox($mp_stacks_navigation_add_meta_box, $mp_stacks_navigation_items_array);
}
add_action('mp_brick_ajax_metabox', 'mp_stacks_navigation_create_meta_box');
add_action('wp_ajax_mp_stacks_navigation_metabox_content', 'mp_stacks_navigation_create_meta_box');