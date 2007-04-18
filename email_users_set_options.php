<?php
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
?>

<?php
	$err_msg = '';
	
	// Analyse form input, build error messages
	if ( !isset( $_POST['default_subject'] ) ) {
		$err_msg = $err_msg . 'Error concerning the default subject.<br/>';
	}
	else {
		$default_subject = $_POST['default_subject'];
	}
	
	if ( !isset( $_POST['default_body'] ) ) {
		$err_msg = $err_msg . 'Error concerning the default body.<br/>';
	}
	else {
		$default_body = $_POST['default_body'];
	}
	
	// If error, we simply show the form again
	if ( $err_msg!='' ) {
		// Redirect to the form page
		include 'email_users_options_form.php';
	}
	else {
		// No error, set the options
		mailusers_update_default_subject( $default_subject );
		mailusers_update_default_body( $default_body );
?>
	<div class="wrap">
		<p>Options set successfully</p>
	</div>
<?php
	}
?>
