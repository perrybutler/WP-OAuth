<?php

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
      $_SESSION["WPOA"]['IS_NEW_USER'] = false;
      $authData = $this->get_auth_data();
      $sessionAuth = isset( $_SESSION['WPOA']['NEW_USER'] ) ? $_SESSION['WPOA']['NEW_USER'] : '';
      $email = isset( $sessionAuth['email'] ) ? $sessionAuth['email'] : null;
      $this->user_id = $this->get_user( $authData['username'], $authData['password'], $email );

      if( $this->set_username( $authData['username'] ) && $this->set_default_role() ){
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
        $_SESSION["WPOA"]["LAST_URL"] = get_bloginfo('url');
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

    if( isset( $_SESSION['WPOA']['NEW_USER'] ) ){
      $this->set_user_info( $_SESSION['WPOA']['NEW_USER'] );
    }

    if( !( $user_login && $user_nicename && $display_name ) ){
      $_SESSION["WPOA"]["RESULT"] = __( "Could not rename the username during registration. Please contact an admin or try again later.", "wp-oauth" );
      $this->redirect();
    } else {
      return true;
    }
  }


  public function set_user_info( $oauth_info )
  {
    $existing_user = get_user_by( 'id', $this->user_id );

    $updated_user =  array(
      "ID" => $this->user_id,
    );


    if( empty( $existing_user->data->user_email ) && !empty( $oauth_info['email'] ) ){
      $updated_user["user_email"] = $oauth_info['email'];
    }

    if( empty( $existing_user->data->user_nicename ) && !empty( $oauth_info['name'] ) ){
      $updated_user["user_nicename"] = $oauth_info['name'];
    }
    if( empty( $existing_user->data->nickname ) && !empty( $oauth_info['name'] ) ){
      $updated_user["nickname"] = $oauth_info['name'];
    }

    if( empty( $existing_user->data->display_name ) && !empty( $oauth_info['name'] ) ){
      $updated_user["display_name"] = $oauth_info['name'];
    }

    if( empty( $existing_user->data->first_name ) && !empty( $oauth_info['first_name'] ) ){
      $updated_user["first_name"] = $oauth_info['first_name'];
    }

    if( empty( $existing_user->data->last_name ) && !empty( $oauth_info['last_name'] ) ){
      $updated_user["last_name"] = $oauth_info['last_name'];
    }

    if( !empty( $oauth_info['website'] ) ){
      $updated_user["user_url"] = apply_filters( 'wpoa/user_url', $oauth_info['website'] );
    }

    $updated_user = apply_filters( 'wpoa/before_set_user_info', $updated_user, $this->user_id, $oauth_info );
    wp_update_user( $updated_user );
    do_action( 'wpoa/set_user_info', $this->user_id, $oauth_info );
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
    if( isset( $_SESSION['WPOA']['NEW_USER']['username'] ) ) {
      $username = $_SESSION['WPOA']['NEW_USER']['username'];
      $password = wp_generate_password();
    } else if ($_SESSION["WPOA"]["USER_ID"] != "") {
      $base_username = $_SESSION["WPOA"]["USER_ID"];
      $username = uniqid( $base_username, true );
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
    } else {
      $existing_user = get_user_by( 'email', $email );
      if( $existing_user && !is_wp_error( $existing_user ) ){
        return $existing_user->ID;
      }
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

    $_SESSION["WPOA"]['IS_NEW_USER'] = true;

    return $user_id;
  }

  public function set_user_id( $id )
  {
    $this->user_id = $id;
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