<?php

// TODO: very important that we sanitize all $_POST variables here before using them!
// TODO: this doesn't call wpoa_end_login() which might result in the LAST_URL not being cleared...


class WP_OauthRegister {
  protected $user_id;
  protected $wpoa;

  function __construct( $wpoa )
  {
    $this->wpoa = $wpoa;
  }

  public function register()
  {
    if( $this->can_register() ){
      $authData = $this->get_auth_data();
      $this->user_id = $this->get_user( $authData['username'], $authData['password'] );
      $username = $this->get_username();

      if( $this->set_username( $username ) && $this->set_default_role() ){
        // registration was successful, the user account was created, proceed to login the user automatically...
        // associate the wordpress user account with the now-authenticated third party account:
        $this->wpoa->wpoa_link_account( $this->user_id );
        // attempt to login the new user (this could be error prone):
        $creds = array();
        $creds['user_login'] = $authData['username'];
        $creds['user_password'] = $authData['password'];
        $creds['remember'] = true;
        $user = wp_signon( $creds, false );

        // send a notification e-mail to the admin and the new user (we can also build our own email if necessary):
        if ( !get_option( 'wpoa_suppress_welcome_email' ) ) {
          wp_new_user_notification( $this->user_id, $authData['password'] );
        }

        // finally redirect the user back to the page they were on and notify them of successful registration:
        $_SESSION["WPOA"]["RESULT"] = __( "You have been registered successfully!", "wp-oauth" );
        $this->redirect();
      } else {

      }
    }
  }


  protected function set_username( $username )
  {
    $user_login = update_user_meta( $this->user_id, 'user_login', $username );
    $user_nicename = update_user_meta( $this->user_id, 'user_nicename', $username );
    $display_name = update_user_meta( $this->user_id, 'display_name', $username );

    if( !( $user_login && $user_nicename && $display_name ) ){
      $_SESSION["WPOA"]["RESULT"] = __( "Could not rename the username during registration. Please contact an admin or try again later.", "wp-oauth" );
      $this->redirect();
    } else {
      return true;
    }
  }


  protected function set_default_role()
  {
    // apply the custom default user role:
    $updateRole = wp_update_user(array(
      'ID' => $this->user_id,
      'role' => get_option('wpoa_new_user_role')
    ));

    if( is_wp_error( $updateRole ) ){
      $_SESSION["WPOA"]["RESULT"] = __( "Could not assign default user role during registration. Please contact an admin or try again later.", "wp-oauth" );
      $this->redirect();
    } else {
      return true;
    }
  }


  protected function can_register()
  {
    if( !get_option("users_can_register") ){
      $_SESSION["WPOA"]["RESULT"] = __( "Sorry, user registration is disabled at this time. Your account could not be registered. Please notify the admin or try again later.", "wp-oauth");
      $this->redirect();
    } else {
      return true;
    }
  }


  protected function get_auth_data()
  {
    // registration was initiated from an oauth provider, set the username and password automatically.
    if ($_SESSION["WPOA"]["USER_ID"] != "") {
      $username = uniqid('', true);
      $password = wp_generate_password();
    } else if ( $_SESSION["WPOA"]["USER_ID"] == "" ) {
      // registration was initiated from the standard sign up form, set the username and password that was requested by the user.
      // this registration was initiated from the standard Registration page, create account and login the user automatically
      $username = $_POST['identity'];
      $password = $_POST['password'];
    }

    return array(
      "username" => $username,
      "password" => $password,
    );
  }


  protected function get_user( $username, $password, $email = null )
  {
    if( !$email ){
      $email = $username;
    }
    // now attempt to generate the user and get the user id:
    // we use wp_create_user instead of wp_insert_user so we can handle the
    // error when the user being registered already exists
    $user_id = wp_create_user( $username, $password, $email );

    // check if the user was actually created:
    if ( is_wp_error( $user_id ) ) {
      // there was an error during registration, redirect and notify the user:
      $_SESSION["WPOA"]["RESULT"] = $user_id->get_error_message();
      $this->redirect();
    }

    return $user_id;
  }


  protected function get_username()
  {
    // now try to update the username to something more permanent and recognizable:
    $username = apply_filters( 'wp-oauth/default-username', 'user' );
    return $username . $this->user_id;
  }


  protected function redirect()
  {
    $redirect_to = $_SESSION["WPOA"]["LAST_URL"];
    $redirect_to_default = get_option('wpoa_redirect_if_successful');
    //  TODO: check to see in what situations we should redirect to custom page
    if( 1 == 2 && !empty( $redirect_to_default ) ){
      wp_redirect( $redirect_to_default );
    }else {
      wp_redirect( $redirect_to );
    }
    exit;
  }
}
