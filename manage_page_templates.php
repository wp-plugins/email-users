<div class="wrap">
<h2><?php echo __("Page templates"); ?></h2>
<?php
// define the columns to display, the syntax is 'internal name' => 'display name'
$posts_columns = array(
	'id'         => '<div style="text-align: center">' . __('ID') . '</div>',
	'date'       => __('When'),
	'title'      => __('Title')
);

//$posts_columns['control_view']   = '';
if (post_templates_is_current_user_allowed_to_view()) {
	$posts_columns['control_to_page']   = '';
}
$posts_columns['control_edit']   = '';
$posts_columns['control_delete'] = '';

if (!post_templates_is_current_user_allowed_to_view()) {
?>
<p><strong><?php _e('You are not allowed to view templates') ?></strong></p>
<?php
} else {
?>


<table class="widefat">
	<thead>
	<tr>
<?php foreach($posts_columns as $column_display_name) { ?>
		<th scope="col"><?php echo $column_display_name; ?></th>
<?php } ?>
	</tr>
	</thead>
	
	<tbody id="the-list">
<?php
// Retrieve the templates
$posts = post_templates_get_page_templates();

if ($posts && count($posts)!=0) {	
	$bgcolor = '';	
	$class = ('alternate' == $class) ? '' : 'alternate';
	
	foreach($posts as $post) {
?>
		<tr id='post-<?php echo $post->id; ?>' class='<?php echo $class; ?>'>

<?php
	foreach($posts_columns as $column_name=>$column_display_name) {
		switch($column_name) {
		case 'id':
			?>
			<th scope="row" style="text-align: center"><?php echo $post->ID ?></th>
			<?php
			break;

		case 'date':
			?>
			<td><?php if ( '0000-00-00 00:00:00' ==$post->post_modified ) _e('Unpublished'); else echo get_the_time(get_option('date_format')) . ' ' . get_the_time(get_option('time_format')); ?></td>
			<?php
			break;
			
		case 'title':
			?>
			<td><?php echo get_the_title(); ?></td>
			<?php
			break;

		case 'control_to_page':
			?>
			<td><?php if (post_templates_is_current_user_allowed_to_view()) { 
					$link = "edit.php?page=post-template/new_page_from_template.php&amp;template=$post->ID";
					echo "<a href='$link' class='edit' title='" . __('Create a new page from this template') . "'>" . __('New Page') . "</a>"; 
				} ?></td>
			<?php
			break;

		case 'control_view':
			?>
			<td><a href="<?php $permalink = get_option('home') . '/?p=' . $post->ID;
				echo apply_filters('post_link', $permalink, $post); ?>" rel="permalink" class="edit"><?php _e('View'); ?></a></td>
			<?php
			break;
			
		case 'control_edit':
			?>
			<td><?php if ( current_user_can('edit_post',$post->ID) ) { 
					echo "<a href='post.php?action=edit&amp;post=$post->ID' class='edit'>" . __('Edit') . "</a>"; 
				} ?></td>
			<?php
			break;

		case 'control_delete':
			?>
			<td><?php 
				if (post_templates_is_current_user_allowed_to_admin()) { 
					$link = "post.php?action=delete&amp;post=$post->ID";
					if (function_exists('wp_nonce_url')) {
						$link = wp_nonce_url($link, 'delete-post_' . $post->ID);
					}

					echo "<a href='$link' class='delete' onclick=\"return deleteSomething( 'post', " . $id . ", '" . post_templates_js_escape(sprintf(__("You are about to delete this post '%s'.\n'OK' to delete, 'Cancel' to stop."), get_the_title())) . "' );\">" . __('Delete') . "</a>"; 
				} ?></td>
			<?php
			break;

		default:
			?>
			<td></td>
			<?php
			break;
		}
	}
?>
	</tr> 
<?php
	}
} else {
?>
	<tr style='background-color: <?php echo $bgcolor; ?>'> 
		<td colspan="8"><?php _e('No templates found.') ?></td> 
	</tr> 
<?php
} // end if ($posts)
} // end if (!post_templates_is_current_user_allowed())
?>
	</tbody>
</table>
</div>
	