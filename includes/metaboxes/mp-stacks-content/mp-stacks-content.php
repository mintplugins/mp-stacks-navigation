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
 * Add Image Navigation as a content Type to the dropdown
 *
 * @since    1.0.0
 * @link     http://mintplugins.com/doc/
 * @param    array $args See link for description.
 * @return   void
 */
function mp_stacks_navigation_add_content_type( $mp_stacks_content_types_array ){	
	
	/**
	 * Array which stores all info about the options within the metabox
	 *
	 */
	$mp_stacks_content_types_array[0]['field_select_values']['navigation'] = 'Navigation';
	$mp_stacks_content_types_array[1]['field_select_values']['navigation'] = 'Navigation';
	
	return $mp_stacks_content_types_array;

}
add_filter('mp_stacks_content_types_array', 'mp_stacks_navigation_add_content_type');