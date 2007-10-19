<?php 
	if ( isset( $_GET['post'] ) ) {
		$notifyUrl = "post-new.php?page=email-users/email_users_send_mail_form.php&is_notification=true&post_id=" . $_GET['post'];
?>

<?php 
		if (false) { 
/* use for future release of wordpress if field referredby don't work correctly */
?>
		<script type="text/javascript">
<!--
			function MM_openBrWindow(theURL,winName,features) { //v2.0
				window.open(theURL,winName,features);
			}
//-->
        </script>
        
		<p class="submit">
			<span style="font-weight: bold; color: red;">
				<?php _e('Any unsaved changes will be lost after pushing the notify users button!', MAILUSERS_I18N_DOMAIN); ?>
			</span>
			<input type="button" 
				name="SubmitNotification" 
				value="<?php _e('Notify blog users of this post', MAILUSERS_I18N_DOMAIN); ?> &raquo;" 
				onclick="MM_openBrWindow('<?php echo $notifyUrl; ?>', '_self', ''); return false;" />
		</p>
<?php 
		} else { 
?>
			<script type="text/javascript">
			<!--
				function goToNotificationPage( thisForm ) {
					thisForm.referredby.value = "<?php echo $notifyUrl; ?>";
					thisForm.submit();
				}
			// -->
			</script>
				
			<input type="hidden" name="is_notification" value="true" />
			<input type="hidden" name="post_id" value="<?php echo $_GET['post']; ?>" />
			<p class="submit">
				<span style="font-weight: bold; color: red;">
					<?php _e('Any unsaved changes will be lost after pushing the notify users button!', MAILUSERS_I18N_DOMAIN); ?>
				</span>
				<input type="submit" 
					name="SubmitNotification" 
					value="<?php _e('Notify blog users of this post', MAILUSERS_I18N_DOMAIN); ?> &raquo;" 
					onclick="goToNotificationPage(this.form); return false;" />
			</p>
<?php 
		} ?>
<?php
	} ?>

