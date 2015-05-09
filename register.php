<?php

// TODO: very important that we sanitize all $_POST variables here before using them!
// TODO: this doesn't call wpoa_end_login() which might result in the LAST_URL not being cleared...

global $wpdb;

// initiate the user session:
session_start();

// prevent users from registering if the option is turned off in the dashboard:
if (!get_option("users_can_register")) {
	$_SESSION["WPOA"]["RESULT"] = "Sorry, user registration is disabled at this time. Your account could not be registered. Please notify the admin or try again later.";
	header("Location: " . $_SESSION["WPOA"]["LAST_URL"]);
	exit;
}

// registration was initiated from an oauth provider, set the username and password automatically.
if ($_SESSION["WPOA"]["USER_ID"] != "") {

	// Try to use same username as the provider
	if(isset($_SESSION['WPOA']['OA_USERNAME']) && strlen($_SESSION['WPOA']['OA_USERNAME']) > 0){
		$username = $_SESSION['WPOA']['OA_USERNAME'];
	} else {
		$username = uniqid('', true);
	}
	$password = wp_generate_password();
}

// registration was initiated from the standard sign up form, set the username and password that was requested by the user.
if ( $_SESSION["WPOA"]["USER_ID"] == "" ) {
	// this registration was initiated from the standard Registration page, create account and login the user automatically
	$username = $_POST['identity'];
	$password = $_POST['password'];
}

// Try to register with the OA e-mail address if we have it
// NB: Not a security risk. This e-mail address can't be used to log in.
if(isset($_SESSION['WPOA']['OA_EMAIL']) && strlen($_SESSION['WPOA']['OA_EMAIL']) > 0){
	$email = $_SESSION['WPOA']['OA_EMAIL'];
} else {
	$email = $username; // not sure why we do this, but was previous behaviour so leaving it here.
}

// now attempt to generate the user and get the user id:
$user_id = wp_create_user( $username, $password, $email ); // we use wp_create_user instead of wp_insert_user so we can handle the error when the user being registered already exists

// check if the user was actually created:
if (is_wp_error($user_id)) {
	// there was an error during registration, redirect and notify the user:
	$_SESSION["WPOA"]["RESULT"] = $user_id->get_error_message();
	header("Location: " . $_SESSION["WPOA"]["LAST_URL"]);
	exit;
}

// now try to update the username to something more permanent and recognizable:
if(isset($_SESSION['WPOA']['OA_NICENAME']) && strlen($_SESSION['WPOA']['OA_NICENAME']) > 0){
	$nicename = $_SESSION['WPOA']['OA_NICENAME'];
} else {
	$nicename = $username;
}
$update_username_result = $wpdb->update($wpdb->users, array('user_login' => $username, 'user_nicename' => $nicename, 'display_name' => $nicename), array('ID' => $user_id));
$update_nickname_result = update_user_meta($user_id, 'nickname', $nicename);

// Update new user with other OA metadata if we have it
if(isset($_SESSION['WPOA']['GH_BIO']) && strlen($_SESSION['WPOA']['OA_DESC']) > 0){
	update_user_meta( $user_id, 'description', $_SESSION['WPOA']['OA_DESC']);
}
if(isset($_SESSION['WPOA']['OA_URL']) && strlen($_SESSION['WPOA']['OA_URL']) > 0){
	$update_username_result = $wpdb->update($wpdb->users, array('user_url' => $_SESSION['WPOA']['OA_URL']), array('ID' => $user_id));
}




// apply the custom default user role:
$role = get_option('wpoa_new_user_role');
$update_role_result = wp_update_user(array('ID' => $user_id, 'role' => $role));

// proceed if no errors were detected:
if ($update_username_result == false || $update_nickname_result == false) {
	// there was an error during registration, redirect and notify the user:
	$_SESSION["WPOA"]["RESULT"] = "Could not rename the username during registration. Please contact an admin or try again later.";
	header("Location: " . $_SESSION["WPOA"]["LAST_URL"]); exit;
}
elseif ($update_role_result == false) {
	// there was an error during registration, redirect and notify the user:
	$_SESSION["WPOA"]["RESULT"] = "Could not assign default user role during registration. Please contact an admin or try again later.";
	header("Location: " . $_SESSION["WPOA"]["LAST_URL"]); exit;
}
else {
	// registration was successful, the user account was created, proceed to login the user automatically...
	// associate the wordpress user account with the now-authenticated third party account:
	$this->wpoa_link_account($user_id);
	// attempt to login the new user (this could be error prone):
	$creds = array();
	$creds['user_login'] = $username;
	$creds['user_password'] = $password;
	$creds['remember'] = true;
	$user = wp_signon( $creds, false );
	// send a notification e-mail to the admin and the new user (we can also build our own email if necessary):
	if (!get_option('wpoa_suppress_welcome_email')) {
		//wp_mail($username, "New User Registration", "Thank you for registering!\r\nYour username: " . $username . "\r\nYour password: " . $password, $headers);
		wp_new_user_notification( $user_id, $password );
	}
	// finally redirect the user back to the page they were on and notify them of successful registration:
	$_SESSION["WPOA"]["RESULT"] = "You have been registered successfully!";
	$this->wpoa_end_login("You have been registered successfully!");
}
?>
