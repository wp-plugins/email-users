<?php
/*
Plugin Name: Post Template
Plugin URI: http://www.vincentprat.info/wordpress/2007/03/01/wordpress-plugin-post-template/
Description: A plugin that allows you to create post templates in order to save time writing posts having the same structure. When activated, you can check your new <a href='edit.php?page=post-template/manage_post_templates.php' title='Manage the templates'>template management page</a> and the <a href='options-general.php?page=post-template/post_templates_admin.php' title='Post template options'>option page</a>.
Version: 1.1.3
Author: Vincent Prat
Author URI: http://www.vincentprat.info

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
The license is also available at http://www.gnu.org/copyleft/gpl.html
*/

// Version of the plugin
define('POST_TEMPLATES_CURRENT_VERSION', '1.1.3' );
define('POST_TEMPLATES_PROMOTE_POST_TO_TEMPLATE_COLUMN', 'control_promote_post_to_template');
define('POST_TEMPLATES_VIEW_USER_LEVEL_OPTION', 'post_templates_view_user_level');
define('POST_TEMPLATES_CREATE_USER_LEVEL_OPTION', 'post_templates_create_user_level');
define('POST_TEMPLATES_ADMIN_USER_LEVEL_OPTION', 'post_templates_admin_user_level');
define('POST_TEMPLATES_TEMPLATE_POST_TYPE', 'template');
define('POST_TEMPLATES_TEMPLATE_PAGE_TYPE', 'template-page');

/**
 * Plugin activation
 */
add_action('activate_post-template/post-template.php','post_templates_plugin_activation');

function post_templates_plugin_activation() {
	$installed_version = post_templates_get_installed_version();
	
	if ( $installed_version==post_templates_get_current_version() ) {
		// do nothing
	} else if ( $installed_version=='' ) {
		// Add all options, nothing already installed
		add_option(
			POST_TEMPLATES_VIEW_USER_LEVEL_OPTION,
			'2',
			'Default user level to view the templates and create posts from them' );
		add_option(
			POST_TEMPLATES_CREATE_USER_LEVEL_OPTION,
			'5',
			'Default user level to create the templates' );
		add_option(
			POST_TEMPLATES_ADMIN_USER_LEVEL_OPTION,
			'8',
			'Default user level to change the plugin options' );
	}
	
	// Update version number
	update_option( 'post_templates_version', post_templates_get_current_version() );	
}

/**
 * Add a new menu under Manage, visible for all users with template viewing level.
 */
add_action( 'admin_menu', 'post_templates_add_pages' );

function post_templates_add_pages() {
	$view_level = post_templates_get_view_user_level();
	add_submenu_page( 'edit.php', __('Manage post templates'), 
		__('Post templates'), $view_level, 
		'edit.php?page=post-template/manage_post_templates.php' );
		
	add_submenu_page( 'edit.php', __('Manage page templates'), 
		__('Page templates'), $view_level, 
		'edit.php?page=post-template/manage_page_templates.php' );
		
	$admin_level = post_templates_get_admin_user_level();
	add_options_page( __('Post templates'), __('Post templates'), $admin_level,
		'options-general.php?page=post-template/post_templates_admin.php' );
}

/**
 * Add a column in the post listing
 */
add_filter('manage_posts_columns', 'post_templates_add_promote_post_to_template_column');
add_action('manage_posts_custom_column', 'post_templates_make_promote_to_template_link', 10, 2);

function post_templates_add_promote_post_to_template_column($columns) {
	if (post_templates_is_current_user_allowed_to_create()) {
		$columns[POST_TEMPLATES_PROMOTE_POST_TO_TEMPLATE_COLUMN] = '';
	}
	return $columns;
}

function post_templates_make_promote_to_template_link($column_name, $id) {
	if (post_templates_is_current_user_allowed_to_create()) {
		if ($column_name == POST_TEMPLATES_PROMOTE_POST_TO_TEMPLATE_COLUMN) {
			echo "<a href='edit.php?page=post-template/templatize_post.php&amp;post=" . $id 
				. "' title='" . __("Make a template from this post") 
				. "' class='edit'>" . __("Templatize") . "</a>";
		}
	}
}

/**
 * Add a button in the post edit form to create a template from current page
 */
add_action( 'edit_form_advanced', 'post_templates_add_promote_post_to_template_button' );

function post_templates_add_promote_post_to_template_button() {
	if ( isset( $_GET['post'] ) && post_templates_is_current_user_allowed_to_create()) {
?>
		<script language="JavaScript">
		<!--
			function promote_post_to_template( thisForm ) {
				thisForm.action = "edit.php?page=post-template/templatize_post.php";
				thisForm.submit();
			}
		// -->
		</script>	
		<input type="hidden" name="post" value="<?php echo $_GET['post']; ?>" />
		<p class="submit">
			<input type="submit" name="SubmitNotification" value="<?php echo __('Templatize &raquo;'); ?>" onclick="promote_post_to_template( this.form )" />
		</p>
<?php
	}
}

/**
 * Add a button in the page edit form to create a template from current page
 */
add_action( 'edit_page_form', 'post_templates_add_promote_page_to_template_button' );

function post_templates_add_promote_page_to_template_button() {
	if ( isset( $_GET['post'] ) && post_templates_is_current_user_allowed_to_create()) {
?>
		<script language="JavaScript">
		<!--
			function promote_page_to_template( thisForm ) {
				thisForm.action = "edit.php?page=post-template/templatize_page.php";
				thisForm.submit();
			}
		// -->
		</script>	
		<input type="hidden" name="post" value="<?php echo $_GET['post']; ?>" />
		<p class="submit">
			<input type="submit" name="SubmitNotification" value="<?php echo __('Templatize &raquo;'); ?>" onclick="promote_page_to_template( this.form )" />
		</p>
<?php
	}
}

/**
 * Wrapper for the option 'post_templates_view_user_level'
 */
function post_templates_get_view_user_level() {
	return get_option( POST_TEMPLATES_VIEW_USER_LEVEL_OPTION );
}

/**
 * Wrapper for the option 'post_templates_view_user_level'
 */
function post_templates_set_view_user_level($new_level) {
	return update_option( POST_TEMPLATES_VIEW_USER_LEVEL_OPTION, $new_level );
}

/**
 * Wrapper for the option 'post_templates_create_user_level'
 */
function post_templates_get_create_user_level() {
	return get_option( POST_TEMPLATES_CREATE_USER_LEVEL_OPTION );
}

/**
 * Wrapper for the option 'post_templates_create_user_level'
 */
function post_templates_set_create_user_level($new_level) {
	return update_option( POST_TEMPLATES_CREATE_USER_LEVEL_OPTION, $new_level );
}

/**
 * Wrapper for the option 'post_templates_admin_user_level'
 */
function post_templates_get_admin_user_level() {
	return get_option( POST_TEMPLATES_ADMIN_USER_LEVEL_OPTION );
}

/**
 * Wrapper for the option 'post_templates_admin_user_level'
 */
function post_templates_set_admin_user_level($new_level) {
	return update_option( POST_TEMPLATES_ADMIN_USER_LEVEL_OPTION, $new_level );
}

/**
 * Wrapper for the option 'post_templates_version'
 */
function post_templates_get_installed_version() {
	return get_option( 'post_templates_version' );
}

/**
 * Wrapper for the defined constant POST_TEMPLATES_CURRENT_VERSION
 */
function post_templates_get_current_version() {	
	return POST_TEMPLATES_CURRENT_VERSION;
}

/**
 * Test if the user is allowed to view the templates & create posts
 */
function post_templates_is_current_user_allowed_to_view() {
	return current_user_can("level_" . post_templates_get_view_user_level());
}

/**
 * Test if the user is allowed to create templates
 */
function post_templates_is_current_user_allowed_to_create() {
	return current_user_can("level_" . post_templates_get_create_user_level());
}

/**
 * Test if the user is allowed to administrate the plugin
 */
function post_templates_is_current_user_allowed_to_admin() {
	return current_user_can("level_" . post_templates_get_admin_user_level());
}

/**
 * Get a level given a role
 */ 
function post_templates_get_level_from_role($role) {
	switch ($role) {
	case 0:		// subscribers		0
		return 0;
	case 1:		// contributors		1
		return 1;
	case 2:		// authors			2..4
		return 2;
	case 3:		// editors			5..7
		return 5;
	case 4:		// administrators		8..10
		return 8;		
	default:	// error
		return 0;
	}
}

/**
 * Get a role given a level
 */ 
function post_templates_get_role_from_level($level) {
	if ($level<=0) {
		// subscribers		0
		return 0;
	} else if ($level==1) {
		// contributors		1
		return 1;
	} else if ($level>=2 && $level<=4) {
		// authors			2..4
		return 2;
	} else if ($level>=5 && $level<=7) {
		// editors			5..7
		return 3;
	} else if ($level>=8) {
		// admins			8..10
		return 4;
	}	
	return 0;
}

/**
 * Get the currently registered user
 */
function post_templates_get_current_user() {
	if (function_exists('wp_get_current_user')) {
		return wp_get_current_user();
	} else if (function_exists('get_currentuserinfo')) {
		global $userdata;
		get_currentuserinfo();
		return $userdata;
	} else {
		$user_login = $_COOKIE[USER_COOKIE];
		$current_user = $wpdb->get_results("SELECT * FROM $wpdb->users WHERE user_login='$user_login'");
		return $current_user;
	}
}

/**
 * Escape single quotes, specialchar double quotes, and fix line endings.
 */
function post_templates_js_escape($text) {
	if (function_exists('js_escape')) {
		return js_escape($text);
	} else {
		$safe_text = str_replace('&&', '&#038;&', $text);
		$safe_text = str_replace('&&', '&#038;&', $safe_text);
		$safe_text = preg_replace('/&(?:$|([^#])(?![a-z1-4]{1,8};))/', '&#038;$1', $safe_text);
		$safe_text = str_replace('<', '&lt;', $safe_text);
		$safe_text = str_replace('>', '&gt;', $safe_text);
		$safe_text = str_replace('"', '&quot;', $safe_text);
		$safe_text = str_replace('&#039;', "'", $safe_text);
		$safe_text = preg_replace("/\r?\n/", "\\n", addslashes($safe_text));
		return safe_text;
	}
}

/**
 * Get the post templates from the database
 */
function post_templates_get_post_templates() {
	global $wpdb;
	$template_post_type = POST_TEMPLATES_TEMPLATE_POST_TYPE;
	$templates = $wpdb->get_results("SELECT * FROM $wpdb->posts WHERE post_type='$template_post_type' ORDER BY post_date_gmt DESC");
	return $templates;
}

/**
 * Get the page templates from the database
 */
function post_templates_get_page_templates() {
	global $wpdb;
	$template_post_type = POST_TEMPLATES_TEMPLATE_PAGE_TYPE;
	$templates = $wpdb->get_results("SELECT * FROM $wpdb->posts WHERE post_type='$template_post_type' ORDER BY post_date_gmt DESC");
	return $templates;
}

/**
 * Get a page from the database
 */
function post_templates_get_page($id) {
	global $wpdb;
	$post = $wpdb->get_results("SELECT * FROM $wpdb->posts WHERE ID=$id");
	return $post[0];
}

/**
 * Get a post from the database
 */
function post_templates_get_post($id) {
	global $wpdb;
	$post = $wpdb->get_results("SELECT * FROM $wpdb->posts WHERE ID=$id");
	return $post[0];
}

/**
 * Copy the categories of a post to another post
 */
function post_templates_copy_post_categories($id, $new_id) {
	global $wpdb;
	$post_categories = $wpdb->get_results("SELECT category_id FROM $wpdb->post2cat WHERE post_id=$id");
	if (count($post_categories)!=0) {
		$sql_query = "INSERT INTO $wpdb->post2cat (post_id, category_id) ";
		
		for ($i=0; $i<count($post_categories); $i++) {
			$post_category = $post_categories[$i]->category_id;
			
			if ($i<count($post_categories)-1) {
				$sql_query .= "SELECT $new_id, $post_category UNION ALL ";
			} else {
				$sql_query .= "SELECT $new_id, $post_category";
			}
		}
	
		$wpdb->query($sql_query);	
	} 
}

/**
 * Copy the meta information of a post to another post
 */
function post_templates_copy_post_meta_info($id, $new_id) {
	global $wpdb;
	$post_meta_infos = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$id");
	
	if (count($post_meta_infos)!=0) {
		$sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
		
		for ($i=0; $i<count($post_meta_infos); $i++) {
			$meta_info = $post_meta_infos[$i];
			
			if ($i<count($post_meta_infos)-1) {
				$sql_query .= "SELECT $new_id, '$meta_info->meta_key', '$meta_info->meta_value' UNION ALL ";
			} else {
				$sql_query .= "SELECT $new_id, '$meta_info->meta_key', '$meta_info->meta_value'";
			}
		}
	
		$wpdb->query($sql_query);	
	} 
}

/**
 * Create a template from a post
 */
function post_templates_create_template_from_post($post) {
	global $wpdb;
	$template_post_type = POST_TEMPLATES_TEMPLATE_POST_TYPE;
	$new_post_date = current_time('mysql');
	$new_post_date_gmt = get_gmt_from_date($new_post_date);
	
	$post_content    = str_replace("'", "''", $post->post_content);
	$post_content_filtered = str_replace("'", "''", $post->post_content_filtered);
	$post_excerpt    = str_replace("'", "''", $post->post_excerpt);
	$post_title      = str_replace("'", "''", $post->post_title);
	$post_status     = str_replace("'", "''", $post->post_status);
	$post_name       = str_replace("'", "''", $post->post_name);
	$comment_status  = str_replace("'", "''", $post->comment_status);
	$ping_status     = str_replace("'", "''", $post->ping_status);
	
	// Insert the new template in the post table
	$wpdb->query(
			"INSERT INTO $wpdb->posts
			(post_author, post_date, post_date_gmt, post_content, post_content_filtered, post_title, post_excerpt,  post_status, post_type, comment_status, ping_status, post_password, post_name, to_ping, pinged, post_modified, post_modified_gmt, post_parent, menu_order, post_mime_type)
			VALUES
			('$post->post_author', '$new_post_date', '$new_post_date_gmt', '$post_content', '$post_content_filtered', '$post_title', '$post_excerpt', 'private', '$template_post_type', '$comment_status', '$ping_status', '$post->post_password', '$post_name', '$post->to_ping', '$post->pinged', '$new_post_date', '$new_post_date_gmt', '$post->post_parent', '$post->menu_order', '$post->post_mime_type')");
			
	$new_post_id = $wpdb->insert_id;
	
	// Copy the categories
	post_templates_copy_post_categories($post->ID, $new_post_id);
	
	// Copy the meta information
	post_templates_copy_post_meta_info($post->ID, $new_post_id);
	
	return $new_post_id;
}

/**
 * Create a template from a page
 */
function post_templates_create_template_from_page($post) {
	global $wpdb;
	$template_post_type = POST_TEMPLATES_TEMPLATE_PAGE_TYPE;
	$new_post_date = current_time('mysql');
	$new_post_date_gmt = get_gmt_from_date($new_post_date);
	
	$post_content    = str_replace("'", "''", $post->post_content);
	$post_content_filtered = str_replace("'", "''", $post->post_content_filtered);
	$post_excerpt    = str_replace("'", "''", $post->post_excerpt);
	$post_title      = str_replace("'", "''", $post->post_title);
	$post_status     = str_replace("'", "''", $post->post_status);
	$post_name       = str_replace("'", "''", $post->post_name);
	$comment_status  = str_replace("'", "''", $post->comment_status);
	$ping_status     = str_replace("'", "''", $post->ping_status);
	
	// Insert the new template in the post table
	$wpdb->query(
			"INSERT INTO $wpdb->posts
			(post_author, post_date, post_date_gmt, post_content, post_content_filtered, post_title, post_excerpt,  post_status, post_type, comment_status, ping_status, post_password, post_name, to_ping, pinged, post_modified, post_modified_gmt, post_parent, menu_order, post_mime_type)
			VALUES
			('$post->post_author', '$new_post_date', '$new_post_date_gmt', '$post_content', '$post_content_filtered', '$post_title', '$post_excerpt', 'private', '$template_post_type', '$comment_status', '$ping_status', '$post->post_password', '$post_name', '$post->to_ping', '$post->pinged', '$new_post_date', '$new_post_date_gmt', '$post->post_parent', '$post->menu_order', '$post->post_mime_type')");
			
	$new_page_id = $wpdb->insert_id;
	
	// Copy the meta information
	post_templates_copy_post_meta_info($post->ID, $new_page_id);
	
	return $new_page_id;
}

/**
 * Create a post from a template
 */
function post_templates_create_post_from_template($template) {
	global $wpdb;
	$new_post_type = 'post';
	$new_post_author = post_templates_get_current_user();
	$new_post_date = current_time('mysql');
	$new_post_date_gmt = get_gmt_from_date($new_post_date);
		
	$post_content    = str_replace("'", "''", $template->post_content);
	$post_content_filtered = str_replace("'", "''", $post->post_content_filtered);
	$post_excerpt    = str_replace("'", "''", $template->post_excerpt);
	$post_title      = str_replace("'", "''", $template->post_title);
	$post_status     = str_replace("'", "''", $template->post_status);
	$post_name       = str_replace("'", "''", $template->post_name);
	$comment_status  = str_replace("'", "''", $template->comment_status);
	$ping_status     = str_replace("'", "''", $template->ping_status);
	
	// Insert the new template in the post table
	$wpdb->query(
			"INSERT INTO $wpdb->posts
			(post_author, post_date, post_date_gmt, post_content, post_content_filtered, post_title, post_excerpt,  post_status, post_type, comment_status, ping_status, post_password, post_name, to_ping, pinged, post_modified, post_modified_gmt, post_parent, menu_order, post_mime_type)
			VALUES
			('$new_post_author->ID', '$new_post_date', '$new_post_date_gmt', '$post_content', '$post_content_filtered', '$post_title', '$post_excerpt', 'draft', '$new_post_type', '$comment_status', '$ping_status', '$template->post_password', '$post_name', '$template->to_ping', '$template->pinged', '$new_post_date', '$new_post_date_gmt', '$template->post_parent', '$template->menu_order', '$template->post_mime_type')");
			
	$new_post_id = $wpdb->insert_id;
		
	// Copy the categories
	post_templates_copy_post_categories($template->ID, $new_post_id);
	
	// Copy the meta information
	post_templates_copy_post_meta_info($template->ID, $new_post_id);
	
	return $new_post_id;
}

/**
 * Create a page from a template
 */
function post_templates_create_page_from_template($template) {
	global $wpdb;
	$new_post_type = 'page';
	$new_post_author = post_templates_get_current_user();
	$new_post_date = current_time('mysql');
	$new_post_date_gmt = get_gmt_from_date($new_post_date);
		
	$post_content    = str_replace("'", "''", $template->post_content);
	$post_content_filtered = str_replace("'", "''", $post->post_content_filtered);
	$post_excerpt    = str_replace("'", "''", $template->post_excerpt);
	$post_title      = str_replace("'", "''", $template->post_title);
	$post_status     = str_replace("'", "''", $template->post_status);
	$post_name       = str_replace("'", "''", $template->post_name);
	$comment_status  = str_replace("'", "''", $template->comment_status);
	$ping_status     = str_replace("'", "''", $template->ping_status);
	
	// Insert the new template in the post table
	$wpdb->query(
			"INSERT INTO $wpdb->posts
			(post_author, post_date, post_date_gmt, post_content, post_content_filtered, post_title, post_excerpt,  post_status, post_type, comment_status, ping_status, post_password, post_name, to_ping, pinged, post_modified, post_modified_gmt, post_parent, menu_order, post_mime_type)
			VALUES
			('$new_post_author->ID', '$new_post_date', '$new_post_date_gmt', '$post_content', '$post_content_filtered', '$post_title', '$post_excerpt', 'draft', '$new_post_type', '$comment_status', '$ping_status', '$template->post_password', '$post_name', '$template->to_ping', '$template->pinged', '$new_post_date', '$new_post_date_gmt', '$template->post_parent', '$template->menu_order', '$template->post_mime_type')");
			
	$new_page_id = $wpdb->insert_id;
	
	// Copy the meta information
	post_templates_copy_post_meta_info($template->ID, $new_page_id);
	
	return $new_page_id;
}


?>