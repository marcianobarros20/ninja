<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
* 	Set up a specific user to be authorized for
* 	viewing a ninja widget on an external web.
* 	Please note that this user will ONLY be able to
* 	view the external widget. Set this user by editing the line at
* 	the bottom of this script to
*
* 	$config['username'] = 'your-user-here';
*
* 	(yes, the quotes are necessary :))
*
* 	Setting the 'groups' property to a non-false value configures
* 	the user's rights - the defalt goes by the user's contacts, but having
*
* 	$config['groups'] = array('op5_readonly');
*
* 	quickly gives the external user right to see everything.
*
* 	Specifying the 'widget_name' below is not necessary
* 	but will make it possible to call the external_widget
* 	controller without specifying what widget to use.
*
* 	A simple iframe on the external page like the following
* 	should work if a valid user is configured:
*
* 	<iframe
* 		src="http://<SERVER_NAME>/ninja/index.php/external_widget/show_widget/<OPTIONAL WIDGET_NAME>"
* 		height="500px" frameborder=0 width="600px" scrolling='no'></iframe>
*/
$config['widget_name'] = 'netw_health';
$config['username'] = false;
$config['groups'] = false;
