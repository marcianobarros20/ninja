<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * @package  Pagination
 *
 * Pagination configuration is defined in groups which allows you to easily switch
 * between different pagination settings for different website sections.
 * Note: all groups inherit and overwrite the default group.
 *
 * Group Options:
 *  directory      - Views folder in which your pagination style templates reside
 *  style          - Pagination style template (matches view filename)
 *  uri_segment    - URI segment (int or 'label') in which the current page number can be found
 *  query_string   - Alternative to uri_segment: query string key that contains the page number
 *  items_per_page - Number of items to display per page
 *  auto_hide      - Automatically hides pagination for single pages
 */
$config['default'] = array
(
	'directory'      => Kohana::config('config.theme_path').Kohana::config('config.current_theme').'pagination',
	'style'          => 'digg',
	'query_string'   => 'page',
	'items_per_page' => 100,
	'auto_hide'      => false
);

# step is used to generate drop-down for
# nr of items per page to show
$config['paging_step'] = 100;

# check for custom config files that
# won't be overwritten on upgrade
if (file_exists(realpath(dirname(__FILE__)).'/custom/'.basename(__FILE__))) {
	include(realpath(dirname(__FILE__)).'/custom/'.basename(__FILE__));
}
