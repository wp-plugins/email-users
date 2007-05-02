<?php
/*
Plugin Name: email users
Version: 2.3
Plugin URI: http://www.vincentprat.info/wordpress/2006/04/19/wordpress-plugin-email-users/
Description: Allows the administrator to send an e-mail to the blog users. Credits to <a href="http://www.catalinionescu.com">Catalin Ionescu</a> who gave me some ideas for the plugin and has made a similar plugin. This plugin is using <a href="http://phpmailer.sourceforge.net/">PhpMailer</a>. Bug reports and corrections by Cyril Crua and Pokey
Author: Vincent Prat (email : vpratfr@yahoo.fr)
Author URI: http://www.vincentprat.info
*/

/*  Copyright 2006 Vincent Prat  (email : vpratfr@yahoo.fr)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

// Version of the plugin
define( 'MAILUSERS_CURRENT_VERSION', '2.3' );


/**
 * Add a new menu under Write:, visible for all users with access levels 8+ (administrator role).
 */
add_action( 'admin_menu', 'mailusers_add_pages' );

function mailusers_add_pages() {
	add_submenu_page( 'post.php', __('Send email to users'), __('Send email to users'), 8, 'post.php?page=email-users/email_users_form.php' );
	add_options_page( __('Email users'), __('Email users'), 8, 'options-general.php?page=email-users/email_users_options_form.php' );
}

/**
 * Add a new menu under Write:, visible for all users with access levels 8+ (administrator role).
 */
add_action( 'edit_form_advanced', 'mailusers_include_post_notification_block' );

function mailusers_include_post_notification_block() {
	include 'email_users_post_notification_block.inc';
}

/**
 * Set default values for the options (check against the version)
 */
add_action('activate_email-users/email-users.php','mailusers_plugin_activation');

function mailusers_plugin_activation() {
	$installed_version = mailusers_get_installed_version();
	
	if ( $installed_version==mailusers_get_current_version() ) {
		// do nothing
	}
	else if ( $installed_version=='' ) {
		// version 1.x, add all options
		add_option(
			'mailusers_version',
			mailusers_get_current_version(),
			'version of the email users plugin' );		
		add_option( 
			'mailusers_default_subject', 
			'[%MAILUSERS_BLOG_NAME%] A post of interest: %MAILUSERS_POST_TITLE%', 
			'The default title to use when using the post notification functionality' );
		add_option( 
			'mailusers_default_body', 
			'<p>Hello, '
				."</p>\n"
				."<p>\n"
				.'I would like to bring your attention on a new post published on the blog. '
				.'Details of the post follow; I hope you will find it interesting. '
				."</p>\n"
				."<p>\n"
				.'Best regards, '
				."</p>\n"
				."<p>\n"
				.'%MAILUSERS_FROM_NAME%'
				."</p>\n"
				."<p>\n"
				.'<strong>%MAILUSERS_POST_TITLE%</strong>'
				."</p>\n"
				."<p>\n"
				.'%MAILUSERS_POST_EXCERPT%'
				."</p>\n"
				."<p>\n"
				.'Link to the post : <a href="%MAILUSERS_POST_URL%">%MAILUSERS_POST_URL%</a>'."<br/>\n"
				.'Link to %MAILUSERS_BLOG_NAME%: <a href="%MAILUSERS_BLOG_URL%">%MAILUSERS_BLOG_URL%</a>'
				."</p>\n", 
			'The default body to use when using the post notification functionality' );
	}
	else if ( $installed_version=='2.0' ) {
		// Version 2.0, a bug was correct in the template, update it
		update_option( 
			'mailusers_default_subject', 
			'[%MAILUSERS_BLOG_NAME%] A post of interest: %MAILUSERS_POST_TITLE%' );
	}
	else {
	}
	
	// Update version number
	update_option( 'mailusers_version', mailusers_get_current_version() );	
}

/**
 * Wrapper for the option 'mailusers_default_subject'
 */
function mailusers_get_default_subject() {
	return get_option( 'mailusers_default_subject' );
}

/**
 * Wrapper for the option 'mailusers_default_subject'
 */
function mailusers_update_default_subject( $subject ) {  
	// --------
	// Bug correction from Cyril Crua, prevents savage backslashes to be inserted and retrieved from database before special characters.
	if (get_magic_quotes_gpc()) {
		$subject = stripslashes($subject);  
	}
	// End of bug correction
	//-------
	return update_option( 'mailusers_default_subject', $subject ); 
}

/**
 * Wrapper for the option 'mailusers_default_body'
 */
function mailusers_get_default_body() {
	return get_option( 'mailusers_default_body' );
}
 
/**
 * Wrapper for the option 'mailusers_default_body'
 */
function mailusers_update_default_body( $body ) {
	// --------
	// Bug correction from Cyril Crua, prevents savage backslashes to be inserted and retrieved from database before special characters.
	if (get_magic_quotes_gpc()) {
		$body = stripslashes($body);
	}
	// End of bug correction
	//-------
	return update_option( 'mailusers_default_body', $body );
}
 
/**
 * Wrapper for the option 'mailusers_version'
 */
function mailusers_get_installed_version() {
	return get_option( 'mailusers_version' );
}

/**
 * Wrapper for the option 'mailusers_version'
 */
function mailusers_get_current_version() {	
	return MAILUSERS_CURRENT_VERSION;
}

/**
 * Get the user names and the user mail addresses, given a level
 */
function mailusers_get_users_from_role( $role ) {
	global $wpdb;
	
	// Bug corrected in version 2.3, better way to select users based on their role. Thanks to Carl for the ideas.
	switch ($role) {
		case 0:		// subscribers		0
			$capability_filter = "meta_value like '%subscriber%'";
			break;
		case 1:		// contributors		1
			$capability_filter = "meta_value like '%contributor%'";
			break;
		case 2:		// authors			2..4
			$capability_filter = "meta_value like '%author%'";
			break;
		case 3:		// editors			5..7
			$capability_filter = "meta_value like '%editor%'";
			break;
		case 4:		// administrators		8..10
			$capability_filter = "meta_value like '%administrator%'";
			break;
		case 5:		// all				0..10
			$capability_filter = "meta_value like '%subscriber%' OR meta_value like '%contributor%' OR meta_value like '%author%' OR meta_value like '%editor%' OR meta_value like '%administrator%'";
			break;
		default:	// error
			return array();
	}
	
	// Bug corrected in version 2.2, $wpdb->prefix was not taken into account. Credits to Pokey
	//--
    $users = $wpdb->get_results( "SELECT display_name, user_email FROM $wpdb->usermeta, $wpdb->users WHERE 
																		(meta_key = '".$wpdb->prefix."capabilities') AND
																		(".$capability_filter.") AND
																		(user_id = id);");
																		
	return $users;
}

/**
 * Function: Check Valid E-Mail Address
 */
function mailusers_is_valid_email($email) {
   $regex = '/^[A-z0-9][\w.-]*@[A-z0-9][\w\-\.]+\.[A-z0-9]{2,6}$/';
   return (preg_match($regex, $email));
}

?>