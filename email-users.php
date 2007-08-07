<?php
/*
Plugin Name: Email Users
Version: 2.4
Plugin URI: http://email-users.vincentprat.info
Description: Allows the administrator to send an e-mail to the blog users. Credits to <a href="http://www.catalinionescu.com">Catalin Ionescu</a> who gave me some ideas for the plugin and has made a similar plugin. This plugin is using <a href="http://phpmailer.sourceforge.net/">PhpMailer</a>. Bug reports and corrections by Cyril Crua and Pokey.
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
define( 'MAILUSERS_CURRENT_VERSION', '2.4' );

// i18n plugin domain 
define( 'MAILUSERS_I18N_DOMAIN', 'email-users' );

/**
 * Initialise the internationalisation domain
 */
$is_i18n_setup = false;
function mailusers_init_i18n() {
	global $is_i18n_setup;

	if ($is_i18n_setup == false) {
		load_plugin_textdomain(MAILUSERS_I18N_DOMAIN, 'wp-content/plugins/email-users');
		$is_i18n_setup = true;
	}
}

/**
 * Add a new menu under Write:, visible for all users with access levels 8+ (administrator role).
 */
add_action( 'admin_menu', 'mailusers_add_pages' );

function mailusers_add_pages() {
	mailusers_init_i18n();

	add_submenu_page( 'post.php', 
		__('Send email to users', MAILUSERS_I18N_DOMAIN), 
		__('Send email to users', MAILUSERS_I18N_DOMAIN), 
		mailusers_get_mail_user_level(), 
		'post.php?page=email-users/email_users_form.php' );

	add_options_page( __('Email users', MAILUSERS_I18N_DOMAIN), 
		__('Email users', MAILUSERS_I18N_DOMAIN), 
		2, 
		'options-general.php?page=email-users/email_users_options_form.php' );
}

/**
 * Add a new menu under Write:, visible for all users with access levels 8+ (administrator role).
 */
add_action( 'edit_form_advanced', 'mailusers_include_post_notification_block' );

function mailusers_include_post_notification_block() {
	if (mailusers_is_current_user_allowed_to_mail()) {
		include 'email_users_post_notification_block.inc';
	}
}

/**
 * Set default values for the options (check against the version)
 */
add_action('activate_email-users/email-users.php','mailusers_plugin_activation');

function mailusers_plugin_activation() {
	mailusers_init_i18n();

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
			__('[%BLOG_NAME%] A post of interest: %POST_TITLE%', MAILUSERS_I18N_DOMAIN), 
			'The default title to use when using the post notification functionality' );
		add_option( 
			'mailusers_default_body', 
			__('<p>Hello, </p><p>I would like to bring your attention on a new post published on the blog. Details of the post follow; I hope you will find it interesting.</p><p>Best regards, </p><p>%FROM_NAME%</p><hr><p><strong>%POST_TITLE%</strong></p><p>%POST_EXCERPT%</p><ul><li>Link to the post: <a href="%POST_URL%">%POST_URL%</a></li><li>Link to %BLOG_NAME%: <a href="%BLOG_URL%">%BLOG_URL%</a></li></ul>', MAILUSERS_I18N_DOMAIN), 
			'Mail User - The default body to use when using the post notification functionality' );
		add_option(
			'mailusers_mail_method',
			'mail',
			'Mail User - Mailer used to send the mails' );		
		add_option(
			'mailusers_smtp_server',
			'',
			'Mail User - Address of the SMTP server used for mails' );	
		add_option(
			'mailusers_smtp_port',
			'',
			'Mail User - Port of the SMTP server used for mails' );			
		add_option(
			'mailusers_smtp_user',
			'',
			'Mail User - SMTP user' );		
		add_option(
			'mailusers_smtp_password',
			'',
			'Mail User - SMTP password' );		
		add_option(
			'mailusers_default_mail_format',
			'html',
			'Mail User - Default mail format (html or plain text)' );	
		add_option(
			'mailusers_mail_user_level',
			'8',
			'Mail User - Minimal level at which a user can use the plugin functions' );	
	}
	else if ( $installed_version=='2.0' || $installed_version=='2.1' 
			|| $installed_version=='2.2' || $installed_version=='2.3' ) {
		// Version 2.x, a bug was corrected in the template, update it
		update_option( 
			'mailusers_default_subject', 
			__('[%BLOG_NAME%] A post of interest: %POST_TITLE%', MAILUSERS_I18N_DOMAIN) );
		update_option( 
			'mailusers_default_body', 
			__('<p>Hello, </p><p>I would like to bring your attention on a new post published on the blog. Details of the post follow; I hope you will find it interesting.</p><p>Best regards, </p><p>%FROM_NAME%</p><hr><p><strong>%POST_TITLE%</strong></p><p>%POST_EXCERPT%</p><ul><li>Link to the post: <a href="%POST_URL%">%POST_URL%</a></li><li>Link to %BLOG_NAME%: <a href="%BLOG_URL%">%BLOG_URL%</a></li></ul>', MAILUSERS_I18N_DOMAIN) );
		add_option(
			'mailusers_mail_method',
			'mail',
			'Mail User - Mailer used to send the mails' );		
		add_option(
			'mailusers_smtp_port',
			'',
			'Mail User - Port of the SMTP server used for mails' );			
		add_option(
			'mailusers_smtp_server',
			'',
			'Mail User - Address of the SMTP server used for mails' );		
		add_option(
			'mailusers_smtp_user',
			'',
			'Mail User - SMTP user' );		
		add_option(
			'mailusers_mail_user_level',
			'8',
			'Mail User - Minimal level at which a user can use the plugin functions' );		
		add_option(
			'mailusers_smtp_password',
			'',
			'Mail User - SMTP password' );		
		add_option(
			'mailusers_default_mail_format',
			'html',
			'Mail User - Default mail format (html or plain text)' );		
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
 * Wrapper for the option default_mail_format
 */
function mailusers_get_default_mail_format() {
	return get_option( 'mailusers_default_mail_format' );
}

/**
 * Wrapper for the option default_mail_format
 */
function mailusers_update_default_mail_format( $default_mail_format ) {  
	return update_option( 'mailusers_default_mail_format', $default_mail_format ); 
}

/**
 * Wrapper for the option mail_method
 */
function mailusers_get_mail_method() {
	return get_option( 'mailusers_mail_method' );
}

/**
 * Wrapper for the option mail_method
 */
function mailusers_update_mail_method( $mail_method ) {  
	return update_option( 'mailusers_mail_method', $mail_method ); 
}

/**
 * Wrapper for the option smtp_server
 */
function mailusers_get_smtp_server() {
	return get_option( 'mailusers_smtp_server' );
}

/**
 * Wrapper for the option smtp_server
 */
function mailusers_update_smtp_server( $smtp_server ) {  
	return update_option( 'mailusers_smtp_server', $smtp_server ); 
}

/**
 * Wrapper for the option smtp_port
 */
function mailusers_get_smtp_port() {
	return get_option( 'mailusers_smtp_port' );
}

/**
 * Wrapper for the option smtp_port
 */
function mailusers_update_smtp_port( $smtp_port ) {  
	return update_option( 'mailusers_smtp_port', $smtp_port ); 
}

/**
 * Wrapper for the option smtp_user
 */
function mailusers_get_smtp_user() {
	return get_option( 'mailusers_smtp_user' );
}

/**
 * Wrapper for the option smtp_user
 */
function mailusers_update_smtp_user( $smtp_user ) {  
	return update_option( 'mailusers_smtp_user', $smtp_user ); 
}

/**
 * Wrapper for the option smtp_password
 */
function mailusers_get_smtp_password() {
	return get_option( 'mailusers_smtp_password' );
}

/**
 * Wrapper for the option smtp_password
 */
function mailusers_update_smtp_password( $smtp_password ) {  
	return update_option( 'mailusers_smtp_password', $smtp_password ); 
}

/**
 * Wrapper for the option mail_user_level
 */
function mailusers_get_mail_user_level() {
	return get_option( 'mailusers_mail_user_level' );
}

/**
 * Wrapper for the option mail_user_level
 */
function mailusers_update_mail_user_level( $mail_user_level ) {  
	return update_option( 'mailusers_mail_user_level', $mail_user_level ); 
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
 * Check Valid E-Mail Address
 */
function mailusers_is_valid_email($email) {
   $regex = '/^[A-z0-9][\w.-]*@[A-z0-9][\w\-\.]+\.[A-z0-9]{2,6}$/';
   return (preg_match($regex, $email));
}

/**
 * Replace the template variables in a given text.
 */
function mailusers_replace_post_templates($text, $post_title, $post_excerpt, $post_url) {	
	$text = preg_replace( '/%POST_TITLE%/', $post_title, $text );
	$text = preg_replace( '/%POST_EXCERPT%/', $post_excerpt, $text );
	$text = preg_replace( '/%POST_URL%/', $post_url, $text );
	return $text;
}

/**
 * Replace the template variables in a given text.
 */
function mailusers_replace_blog_templates($text) {	
	$blog_url = get_option( 'siteurl' );
	$blog_name = get_option( 'blogname' );

	$text = preg_replace( '/%BLOG_URL%/', $blog_url, $text );
	$text = preg_replace( '/%BLOG_NAME%/', $blog_name, $text );
	return $text;
}

/**
 * Replace the template variables in a given text.
 */
function mailusers_replace_sender_templates($text, $sender_name) {	
	$text = preg_replace( '/%FROM_NAME%/', $sender_name, $text );
	return $text;
}

/**
 * Test if the user is allowed to use the plugin
 */
function mailusers_is_current_user_allowed_to_mail() {
	return current_user_can("level_" . mailusers_get_mail_user_level());
}

/**
 * Test if the user is allowed to configure the plugin
 */
function mailusers_is_current_user_allowed_to_configure() {
	return current_user_can("level_8");
}

?>
