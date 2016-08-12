<?php

// TODO: very important that we sanitize all $_POST variables here before using them!
// TODO: this doesn't call wpoa_end_login() which might result in the LAST_URL not being cleared...
include_once 'session.php';

global $wpdb;

// initiate the user session:
session_start();

Logger::Instance()->log("Register : Id = " . WPOA_Session::get_id());
Logger::Instance()->dump($oauth_identity);

// prevent users from registering if the option is turned off in the dashboard:
if (!get_option("users_can_register")) {
	$this->fail("Sorry, user registration is disabled at this time. Your account could not be registered. Please notify the admin or try again later.");
}

if (array_key_exists(WPOA_Session::USER_NAME, $oauth_identity)) {
	$username = $oauth_identity[WPOA_Session::USER_NAME];
} else {
	Logger::Instance()->log("Register : User name not set, using it from post");
	$username = $_POST['identity'];
}

if (array_key_exists(WPOA_Session::USER_EMAIL, $oauth_identity)) {
	$email = $oauth_identity[WPOA_Session::USER_EMAIL];
} else {
	Logger::Instance()->log("Register : Email not set, using it from post");
	$email = $_POST['identity'];
}

$password = wp_generate_password();

Logger::Instance()->log("Register : Username = " . $username);
Logger::Instance()->log("Register : Password = " . $password);
Logger::Instance()->log("Register : Email = " . $email);



// now attempt to generate the user and get the user id:
$user_id = wp_create_user($username, $password, $email); // we use wp_create_user instead of wp_insert_user so we can handle the error when the user being registered already exists

// check if the user was actually created:
if (is_wp_error($user_id)) {
	// there was an error during registration, redirect and notify the user:
	$error = $user_id->get_error_message();
	$this->fail($error);
}

// now try to update the username to something more permanent and recognizable:
$update_username_result = $wpdb->update($wpdb->users, array('user_login' => $email, 'user_nicename' => $username, 'display_name' => $username), array('ID' => $user_id));

// apply the custom default user role:
$role = get_option('wpoa_new_user_role');
$update_role_result = wp_update_user(array('ID' => $user_id, 'role' => $role));

// proceed if no errors were detected:
if ($update_username_result == false) {
	Logger::Instance()->log("Register : username = " . $update_username_result . " / nickname = " . $update_nickname_result);
	$this->fail("Could not rename the username during registration. Please contact an admin or try again later.");
}
elseif ($update_role_result == false) {
	$this->fail("Could not assign default user role during registration. Please contact an admin or try again later.");	
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
	WPOA_Session::set_result("You have been registered successfully!");
	header("Location: " . WPOA_Session::get_last_url()); exit;
}
?>