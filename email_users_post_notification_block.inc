<?php 
	if ( isset( $_GET['post'] ) ) {
?>
		<script language="JavaScript">
		<!--
			function goToNotificationPage( thisForm ) {
				thisForm.action = "post.php?page=email-users/email_users_form.php";
				thisForm.submit();
			}
		// -->
		</script>	
		<input type="hidden" name="is_notification" value="true" />
		<input type="hidden" name="post_id" value="<?php echo $_GET['post']; ?>" />
		<p class="submit">
			<input type="submit" name="SubmitNotification" value="Notify blog users of this post &raquo;" onclick="goToNotificationPage( this.form )" />
		</p>
<?php
	}
?>