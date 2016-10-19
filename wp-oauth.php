<?php

/*
Plugin Name: WP-OAuth
Plugin URI: http://github.com/perrybutler/wp-oauth
Description: A WordPress plugin that allows users to login or register by authenticating with an existing Google, Facebook, LinkedIn, Github, Reddit or Windows Live account via OAuth 2.0. Easily drops into new or existing sites, integrates with existing users.
Version: 0.4.1.1
Author: Perry Butler
Author URI: http://glassocean.net
License: GPL2
*/

// start the user session for persisting user/login state during ajax, header redirect, and cross domain calls:
if (!isset($_SESSION)) {
    session_start();
}

// plugin class:
Class WPOA {

	// ==============
	// INITIALIZATION
	// ==============

	// set a version that we can use for performing plugin updates, this should always match the plugin version:
	const PLUGIN_VERSION = "0.4.1.1";
	static $WPOA_LOGIN_PROVIDERS = array(
		"google" => "Google",
		"facebook" => "Facebook",
		"linkedin" => "LinkedIn",
		"github" => "GitHub",
		"itembase" => "itembase",
		"reddit" => "Reddit",
		"windowslive" => "Windows Live",
		"paypal" => "PayPal",
		"instagram" => "Instagram",
		"battlenet" => "Battlenet",
		"custom" => "Other"
	);

	// singleton class pattern:
	protected static $instance = NULL;
	public static function get_instance() {
		NULL === self::$instance and self::$instance = new self;
		return self::$instance;
	}

	// define the settings used by this plugin; this array will be used for registering settings, applying default values, and deleting them during uninstall:
	private $settings = array(
		'wpoa_show_login_messages' => 0,								// 0, 1
		'wpoa_login_redirect' => 'home_page',							// home_page, last_page, specific_page, admin_dashboard, profile_page, custom_url
		'wpoa_login_redirect_page' => 0,								// any whole number (wordpress page id)
		'wpoa_login_redirect_url' => '',								// any string (url)
		'wpoa_logout_redirect' => 'home_page',							// home_page, last_page, specific_page, admin_dashboard, profile_page, custom_url, default_handling
		'wpoa_logout_redirect_page' => 0,								// any whole number (wordpress page id)
		'wpoa_logout_redirect_url' => '',								// any string (url)
		'wpoa_logout_inactive_users' => 0,								// any whole number (minutes)
		'wpoa_hide_wordpress_login_form' => 0,							// 0, 1
		'wpoa_logo_links_to_site' => 0,									// 0, 1
		'wpoa_logo_image' => '',										// any string (image url)
		'wpoa_bg_image' => '',											// any string (image url)
		'wpoa_login_form_show_login_screen' => 'Login Screen',			// any string (name of a custom login form shortcode design)
		'wpoa_login_form_show_profile_page' => 'Profile Page',			// any string (name of a custom login form shortcode design)
		'wpoa_login_form_show_comments_section' => 'None',				// any string (name of a custom login form shortcode design)
		'wpoa_login_form_designs' => array(								// array of shortcode designs to be included by default; same array signature as the shortcode function uses
			'Login Screen' => array(
				'icon_set' => 'none',
				'layout' => 'buttons-column',
				'align' => 'center',
				'show_login' => 'conditional',
				'show_logout' => 'conditional',
				'button_prefix' => 'Log in with',
				'logged_out_title' => 'Please log in:',
				'logged_in_title' => 'You are already logged in.',
				'logging_in_title' => 'Logging in...',
				'logging_out_title' => 'Logging out...',
				'style' => '',
				'class' => '',
				),
			'Profile Page' => array(
				'icon_set' => 'none',
				'layout' => 'buttons-row',
				'align' => 'left',
				'show_login' => 'always',
				'show_logout' => 'never',
				'button_prefix' => 'Link',
				'logged_out_title' => 'Select a provider:',
				'logged_in_title' => 'Select a provider:',
				'logging_in_title' => 'Authenticating...',
				'logging_out_title' => 'Logging out...',
				'style' => '',
				'class' => '',
				),
			),
		'wpoa_suppress_welcome_email' => 0,								// 0, 1
		'wpoa_new_user_role' => 'contributor',							// role
		'wpoa_new_user' => null,											// default new user
		'wpoa_google_api_enabled' => 0,									// 0, 1
		'wpoa_google_api_id' => '',										// any string
		'wpoa_google_api_secret' => '',									// any string
		'wpoa_facebook_api_enabled' => 0,								// 0, 1
		'wpoa_facebook_api_id' => '',									// any string
		'wpoa_facebook_api_secret' => '',								// any string
		'wpoa_linkedin_api_enabled' => 0,								// 0, 1
		'wpoa_linkedin_api_id' => '',									// any string
		'wpoa_linkedin_api_secret' => '',								// any string
		'wpoa_github_api_enabled' => 0,									// 0, 1
		'wpoa_github_api_id' => '',										// any string
		'wpoa_github_api_secret' => '',									// any string
		'wpoa_itembase_api_enabled' => 0,								// 0, 1
		'wpoa_itembase_api_id' => '',									// any string
		'wpoa_itembase_api_secret' => '',								// any string
		'wpoa_reddit_api_enabled' => 0,									// 0, 1
		'wpoa_reddit_api_id' => '',										// any string
		'wpoa_reddit_api_secret' => '',									// any string
		'wpoa_windowslive_api_enabled' => 0,							// 0, 1
		'wpoa_windowslive_api_id' => '',								// any string
		'wpoa_windowslive_api_secret' => '',							// any string
		'wpoa_paypal_api_enabled' => 0,									// 0, 1
		'wpoa_paypal_api_id' => '',										// any string
		'wpoa_paypal_api_secret' => '',									// any string
		'wpoa_paypal_api_sandbox_mode' => 0,							// 0, 1
		'wpoa_instagram_api_enabled' => 0,								// 0, 1
		'wpoa_instagram_api_id' => '',									// any string
		'wpoa_instagram_api_secret' => '',								// any string
		'wpoa_battlenet_api_enabled' => 0,								// 0, 1
		'wpoa_battlenet_api_id' => '',									// any string
		'wpoa_battlenet_api_secret' => '',							// any string
		'wpoa_custom_api_enabled' => 0,								// 0, 1
		'wpoa_custom_api_id' => '',									// any string
		'wpoa_custom_api_secret' => '',							// any string
		'wpoa_custom_api_scope' => '',							// any string
		'wpoa_custom_api_auth_url' => '',							// any string
		'wpoa_custom_api_token_url' => '',							// any string
		'wpoa_custom_api_user_url' => '',							// any string
		'wpoa_custom_api_identity_id' => '',							// any string
		'wpoa_custom_api_identity_preferred_username' => '',							// any string
		'wpoa_oauth_server_api_enabled' => 0,							// 0, 1
		'wpoa_oauth_server_api_id' => '',								// any string
		'wpoa_oauth_server_api_secret' => '',							// any string
		'wpoa_oauth_server_api_endpoint' => '',							// any string
		'wpoa_oauth_server_api_button_text' => '',						// any string
		'wpoa_http_util' => 'curl',										// curl, stream-context
		'wpoa_http_util_verify_ssl' => 1,								// 0, 1
		'wpoa_restore_default_settings' => 0,							// 0, 1
		'wpoa_delete_settings_on_uninstall' => 0,						// 0, 1
	);

	// when the plugin class gets created, fire the initialization:
	function __construct() {
		// hook activation and deactivation for the plugin:
		register_activation_hook(__FILE__, array($this, 'wpoa_activate'));
		register_deactivation_hook(__FILE__, array($this, 'wpoa_deactivate'));
		// hook load event to handle any plugin updates:
		add_action('plugins_loaded', array($this, 'wpoa_update'));
		// hook init event to handle plugin initialization:
		add_action('init', array($this, 'init'));
	}

	// a wrapper for wordpress' get_option(), this basically feeds get_option() the setting's correct default value as specified at the top of this file:
	/*
	function wpoa_option($name) {
		// TODO: create the option with a default value if it doesn't exist?
		$val = get_option($name, $settings[$name]);
		return $val;
	}
	*/

	// do something during plugin activation:
	function wpoa_activate() {
	}

	// do something during plugin deactivation:
	function wpoa_deactivate() {
	}

	// do something during plugin update:
	function wpoa_update() {
		$plugin_version = WPOA::PLUGIN_VERSION;
		$installed_version = get_option("wpoa_plugin_version");
		if (!$installed_version || $installed_version <= 0 || $installed_version != $plugin_version) {
			// version mismatch, run the update logic...
			// add any missing options and set a default (usable) value:
			$this->wpoa_add_missing_settings();
			// set the new version so we don't trigger the update again:
			update_option("wpoa_plugin_version", $plugin_version);
			// create an admin notice:
			add_action('admin_notices', array($this, 'wpoa_update_notice'));
		}
	}

	// indicate to the admin that the plugin has been updated:
	function wpoa_update_notice() {
		$settings_link = "<a href='options-general.php?page=WP-OAuth.php'>Settings Page</a>"; // CASE SeNsItIvE filename!
		?>
		<div class="updated">
			<p>WP-OAuth has been updated! Please review the <?php echo $settings_link ?>.</p>
		</div>
		<?php
	}

	// adds any missing settings and their default values:
	function wpoa_add_missing_settings() {
		foreach($this->settings as $setting_name => $default_value) {
			// call add_option() which ensures that we only add NEW options that don't exist:
			if (is_array($this->settings[$setting_name])) {
				$default_value = json_encode($default_value);
			}
			$added = add_option($setting_name, $default_value);
		}
	}

	// restores the default plugin settings:
	function wpoa_restore_default_settings() {
		foreach($this->settings as $setting_name => $default_value) {
			// call update_option() which ensures that we update the setting's value:
			if (is_array($this->settings[$setting_name])) {
				$default_value = json_encode($default_value);
			}
			update_option($setting_name, $default_value);
		}
		add_action('admin_notices', array($this, 'wpoa_restore_default_settings_notice'));
	}

	// indicate to the admin that the plugin has been updated:
	function wpoa_restore_default_settings_notice() {
		$settings_link = "<a href='options-general.php?page=WP-OAuth.php'>Settings Page</a>"; // CASE SeNsItIvE filename!
		?>
		<div class="updated">
			<p>The default settings have been restored. You may review the <?php echo $settings_link ?>.</p>
		</div>
		<?php
	}

	// initialize the plugin's functionality by hooking into wordpress:
	function init() {

		// restore default settings if necessary; this might get toggled by the admin or forced by a new version of the plugin:
		if (get_option("wpoa_restore_default_settings")) {$this->wpoa_restore_default_settings();}
		// hook the query_vars and template_redirect so we can stay within the wordpress context no matter what (avoids having to use wp-load.php)

		$this->wpoa_login_redirect_if_single_provider();

		add_filter('query_vars', array($this, 'wpoa_qvar_triggers'));
		add_action('template_redirect', array($this, 'wpoa_qvar_handlers'));
		// hook scripts and styles for frontend pages:
		add_action('wp_enqueue_scripts', array($this, 'wpoa_init_frontend_scripts_styles'));
		// hook scripts and styles for backend pages:
		add_action('admin_enqueue_scripts', array($this, 'wpoa_init_backend_scripts_styles'));
		add_action('admin_menu', array($this, 'wpoa_settings_page'));
		add_action('admin_init', array($this, 'wpoa_register_settings'));
		$plugin = plugin_basename(__FILE__);
		add_filter("plugin_action_links_$plugin", array($this, 'wpoa_settings_link'));
		// hook scripts and styles for login page:
		add_action('login_enqueue_scripts', array($this, 'wpoa_init_login_scripts_styles'));
		if (get_option('wpoa_logo_links_to_site') == true) {add_filter('login_headerurl', array($this, 'wpoa_logo_link'));}
		add_filter('login_message', array($this, 'wpoa_customize_login_screen'));
		// hooks used globally:
		add_filter('comment_form_defaults', array($this, 'wpoa_customize_comment_form_fields'));
		//add_action('comment_form_top', array($this, 'wpoa_customize_comment_form'));
		add_action('show_user_profile', array($this, 'wpoa_linked_accounts'));
		add_action('wp_logout', array($this, 'wpoa_end_logout'));
		add_action('wp_ajax_wpoa_logout', array($this, 'wpoa_logout_user'));
		add_action('wp_ajax_wpoa_unlink_account', array($this, 'wpoa_unlink_account'));
		add_action('wp_ajax_nopriv_wpoa_unlink_account', array($this, 'wpoa_unlink_account'));
		add_shortcode('wpoa_login_form', array($this, 'wpoa_login_form'));
		// push login messages into the DOM if the setting is enabled:
		if (get_option('wpoa_show_login_messages') !== false) {
			add_action('wp_footer', array($this, 'wpoa_push_login_messages'));
			add_filter('admin_footer', array($this, 'wpoa_push_login_messages'));
			add_filter('login_footer', array($this, 'wpoa_push_login_messages'));
		}
	}

	// init scripts and styles for use on FRONTEND PAGES:
	function wpoa_init_frontend_scripts_styles() {
		// here we "localize" php variables, making them available as a js variable in the browser:
		$wpoa_cvars = array(
			// basic info:
			'ajaxurl' => admin_url('admin-ajax.php'),
			'template_directory' => get_bloginfo('template_directory'),
			'stylesheet_directory' => get_bloginfo('stylesheet_directory'),
			'plugins_url' => plugins_url(),
			'plugin_dir_url' => plugin_dir_url(__FILE__),
			'url' => get_bloginfo('url'),
			'logout_url' => wp_logout_url(),
			// other:
			'show_login_messages' => get_option('wpoa_show_login_messages'),
			'logout_inactive_users' => get_option('wpoa_logout_inactive_users'),
			'logged_in' => is_user_logged_in(),
		);
		wp_enqueue_script('wpoa-cvars', plugins_url('/cvars.js', __FILE__));
		wp_localize_script('wpoa-cvars', 'wpoa_cvars', $wpoa_cvars);
		// we always need jquery:
		wp_enqueue_script('jquery');
		// load the core plugin scripts/styles:
		wp_enqueue_script('wpoa-script', plugin_dir_url( __FILE__ ) . 'wp-oauth.js', array());
		wp_enqueue_style('wpoa-style', plugin_dir_url( __FILE__ ) . 'wp-oauth.css', array());
	}

	// init scripts and styles for use on BACKEND PAGES:
	function wpoa_init_backend_scripts_styles() {
		// here we "localize" php variables, making them available as a js variable in the browser:
		$wpoa_cvars = array(
			// basic info:
			'ajaxurl' => admin_url('admin-ajax.php'),
			'template_directory' => get_bloginfo('template_directory'),
			'stylesheet_directory' => get_bloginfo('stylesheet_directory'),
			'plugins_url' => plugins_url(),
			'plugin_dir_url' => plugin_dir_url(__FILE__),
			'url' => get_bloginfo('url'),
			'show_login_messages' => get_option('wpoa_show_login_messages'),
			'logout_inactive_users' => get_option('wpoa_logout_inactive_users'),
			'logged_in' => is_user_logged_in(),
		);
		wp_enqueue_script('wpoa-cvars', plugins_url('/cvars.js', __FILE__));
		wp_localize_script('wpoa-cvars', 'wpoa_cvars', $wpoa_cvars);
		// we always need jquery:
		wp_enqueue_script('jquery');
		// load the core plugin scripts/styles:
		wp_enqueue_script('wpoa-script', plugin_dir_url( __FILE__ ) . 'wp-oauth.js', array());
		wp_enqueue_style('wpoa-style', plugin_dir_url( __FILE__ ) . 'wp-oauth.css', array());
		// load the default wordpress media screen:
		wp_enqueue_media();
	}

	// init scripts and styles for use on the LOGIN PAGE:
	function wpoa_init_login_scripts_styles() {
		if (isset($_SESSION['WPOA']['RESULT'])) {
			$login_message = $_SESSION['WPOA']['RESULT'];
		} else {
			$login_message = '';
		}
		// here we "localize" php variables, making them available as a js variable in the browser:
		$wpoa_cvars = array(
			// basic info:
			'ajaxurl' => admin_url('admin-ajax.php'),
			'template_directory' => get_bloginfo('template_directory'),
			'stylesheet_directory' => get_bloginfo('stylesheet_directory'),
			'plugins_url' => plugins_url(),
			'plugin_dir_url' => plugin_dir_url(__FILE__),
			'url' => get_bloginfo('url'),
			// login specific:
			'hide_login_form' => get_option('wpoa_hide_wordpress_login_form'),
			'logo_image' => get_option('wpoa_logo_image'),
			'bg_image' => get_option('wpoa_bg_image'),
			'login_message' => $login_message,
			'show_login_messages' => get_option('wpoa_show_login_messages'),
			'logout_inactive_users' => get_option('wpoa_logout_inactive_users'),
			'logged_in' => is_user_logged_in(),
		);
		wp_enqueue_script('wpoa-cvars', plugins_url('/cvars.js', __FILE__));
		wp_localize_script('wpoa-cvars', 'wpoa_cvars', $wpoa_cvars);
		// we always need jquery:
		wp_enqueue_script('jquery');
		// load the core plugin scripts/styles:
		wp_enqueue_script('wpoa-script', plugin_dir_url( __FILE__ ) . 'wp-oauth.js', array());
		wp_enqueue_style('wpoa-style', plugin_dir_url( __FILE__ ) . 'wp-oauth.css', array());
	}

	// add a settings link to the plugins page:
	function wpoa_settings_link($links) {
		$settings_link = "<a href='options-general.php?page=WP-OAuth.php'>Settings</a>"; // CASE SeNsItIvE filename!
		array_unshift($links, $settings_link);
		return $links;
	}

	// ===============
	// GENERIC HELPERS
	// ===============

	// adds basic http auth to a given url string:
	function wpoa_add_basic_auth($url, $username, $password) {
		$url = str_replace("https://", "", $url);
		$url = "https://" . $username . ":" . $password . "@" . $url;
		return $url;
	}

	// ===================
	// LOGIN FLOW HANDLING
	// ===================

	// define the querystring variables that should trigger an action:
	function wpoa_qvar_triggers($vars) {
		$vars[] = 'connect';
		$vars[] = 'code';
		$vars[] = 'error_description';
		$vars[] = 'error_message';
		return $vars;
	}

	// Redirect users to the provider if there's only single provider (and login form is disabled)
	function wpoa_login_redirect_if_single_provider() {
		if ( $_SERVER['REQUEST_METHOD'] == 'GET' && (is_page( 'login' ) || $GLOBALS['pagenow'] === 'wp-login.php' || in_array( $_SERVER['PHP_SELF'], array( '/wp-login.php', '/wp-register.php' ) )) ) {
			// We're on the login page!
			// Check if we only have one provider
			$count = 0;
			$provider = "";
			foreach (self::$WPOA_LOGIN_PROVIDERS as $key => $value) {
				if (get_option("wpoa_" . $key . "_api_enabled")) {
					$count = $count + 1;
					$provider = $key;
				}
			}
			if ( $count == 1 && get_option("wpoa_hide_wordpress_login_form") == 1) {
				$_SESSION['WPOA']['PROVIDER'] = $provider;
				$this->wpoa_include_connector($provider);
				return;
			}
		}
	}

	// handle the querystring triggers:
	function wpoa_qvar_handlers() {
		if (get_query_var('connect')) {
			$provider = get_query_var('connect');
			$this->wpoa_include_connector($provider);
		}
		elseif (get_query_var('code')) {
			$provider = $_SESSION['WPOA']['PROVIDER'];
			$this->wpoa_include_connector($provider);
		}
		elseif (get_query_var('error_description') || get_query_var('error_message')) {
			$provider = $_SESSION['WPOA']['PROVIDER'];
			$this->wpoa_include_connector($provider);
		}
	}

	// load the provider script that is being requested by the user or being called back after authentication:
	function wpoa_include_connector($provider) {
		// normalize the provider name (no caps, no spaces):
		$provider = strtolower($provider);
		$provider = str_replace(" ", "", $provider);
		$provider = str_replace(".", "", $provider);
		// include the provider script:
		include 'login-' . $provider . '.php';
	}

	// =======================
	// LOGIN / LOGOUT HANDLING
	// =======================

	// match the oauth identity to an existing wordpress user account:
	function wpoa_match_wordpress_user($oauth_identity) {
		// attempt to get a wordpress user id from the database that matches the $oauth_identity['id'] value:
		global $wpdb;
		$usermeta_table = $wpdb->usermeta;
		$query_string = "SELECT $usermeta_table.user_id FROM $usermeta_table WHERE $usermeta_table.meta_key = 'wpoa_identity' AND $usermeta_table.meta_value LIKE '%" . $oauth_identity['provider'] . "|" . $oauth_identity['id'] . "%'";
		//print_r( $query_string ); exit;
		$query_result = $wpdb->get_var($query_string);
		//print_r( $query_result ); exit;
		// attempt to get a wordpress user with the matched id:
		$user = get_user_by('id', $query_result);
		return $user;
	}

	// login (or register and login) a wordpress user based on their oauth identity:
	function wpoa_login_user($oauth_identity) {
		// store the user info in the user session so we can grab it later if we need to register the user:
		$_SESSION["WPOA"]["USER_ID"] = $oauth_identity["id"];
		// try to find a matching wordpress user for the now-authenticated user's oauth identity:
		$matched_user = $this->wpoa_match_wordpress_user($oauth_identity);
		$mapped_user = false;
		// handle the matched user if there is one:
		if ( $matched_user ) {
			// there was a matching wordpress user account, log it in now:
			$user_id = $matched_user->ID;
			$user_login = $matched_user->user_login;
			wp_set_current_user( $user_id, $user_login );
			wp_set_auth_cookie( $user_id );
			do_action( 'wp_login', $user_login, $matched_user );
			// after login, redirect to the user's last location
			$this->wpoa_end_login("Logged in successfully!");
			$mapped_user = true;
		} else if(get_option("wpoa_new_user")!=null && get_option("wpoa_new_user") != '' && get_option("wpoa_new_user") != -1 && get_userdata(get_option("wpoa_new_user")) != false) {
			$_SESSION["WPOA"]["USER_ID"] = get_option("wpoa_new_user");

			$user_id = $_SESSION["WPOA"]["USER_ID"];
			$user_login = get_userdata($user_id)->user_login;
			wp_set_current_user( $user_id, $user_login );
			wp_set_auth_cookie( $user_id );
			do_action( 'wp_login', $user_login, $matched_user );
			// after login, redirect to the user's last location
			$this->wpoa_end_login("Logged in successfully as " . $user_login . "!");
			$mapped_user = true;
		}
		// handle the already logged in user if there is one:
		if ( is_user_logged_in() ) {
			// there was a wordpress user logged in, but it is not associated with the now-authenticated user's email address, so associate it now:
			global $current_user;
			get_currentuserinfo();
			$user_id = $current_user->ID;
			$this->wpoa_link_account($user_id);
			// after linking the account, redirect user to their last url
			$this->wpoa_end_login("Your account was linked successfully with your third party authentication provider.");
			return;
		}
		// handle the logged out user or no matching user (register the user):
		if ( !is_user_logged_in() && !$mapped_user ) {
			// this person is not logged into a wordpress account and has no third party authentications registered, so proceed to register the wordpress user:
			$_SESSION["WPOA"]["PREFERRED_USERNAME"] = $oauth_identity["preferred_username"];
			include 'register.php';
			return;
		}
		// we shouldn't be here, but just in case...
		$this->wpoa_end_login("Sorry, we couldn't log you in. The login flow terminated in an unexpected way. Please notify the admin or try again later.");
	}

	// ends the login request by clearing the login state and redirecting the user to the desired page:
	function wpoa_end_login($msg) {
		$last_url = $_SESSION["WPOA"]["LAST_URL"];
		unset($_SESSION["WPOA"]["LAST_URL"]);
		$_SESSION["WPOA"]["RESULT"] = $msg;
		$this->wpoa_clear_login_state();
		$redirect_method = get_option("wpoa_login_redirect");
		$redirect_url = "";
		switch ($redirect_method) {
			case "home_page":
				$redirect_url = site_url();
				break;
			case "last_page":
				$redirect_url = $last_url;
				break;
			case "specific_page":
				$redirect_url = get_permalink(get_option('wpoa_login_redirect_page'));
				break;
			case "admin_dashboard":
				$redirect_url = admin_url();
				break;
			case "user_profile":
				$redirect_url = get_edit_user_link();
				break;
			case "custom_url":
				$redirect_url = get_option('wpoa_login_redirect_url');
				break;
		}
		//header("Location: " . $redirect_url);
		wp_safe_redirect($redirect_url);
		die();
	}

	// logout the wordpress user:
	// TODO: this is usually called from a custom logout button, but we could have the button call /wp-logout.php?action=logout for more consistency...
	function wpoa_logout_user() {
		// logout the user:
		$user = null; 		// nullify the user
		session_destroy(); 	// destroy the php user session
		wp_logout(); 		// logout the wordpress user...this gets hooked and diverted to wpoa_end_logout() for final handling
	}

	// ends the logout request by redirecting the user to the desired page:
	function wpoa_end_logout() {
		$_SESSION["WPOA"]["RESULT"] = 'Logged out successfully.';
		if (is_user_logged_in()) {
			// user is logged in and trying to logout...get their Last Page:
			$last_url = $_SERVER['HTTP_REFERER'];
		}
		else {
			// user is NOT logged in and trying to logout...get their Last Page minus the querystring so we don't trigger the logout confirmation:
			$last_url = strtok($_SERVER['HTTP_REFERER'], "?");
		}
		unset($_SESSION["WPOA"]["LAST_URL"]);
		$this->wpoa_clear_login_state();
		$redirect_method = get_option("wpoa_logout_redirect");
		$redirect_url = "";
		switch ($redirect_method) {
			case "default_handling":
				return false;
			case "home_page":
				$redirect_url = site_url();
				break;
			case "last_page":
				$redirect_url = $last_url;
				break;
			case "specific_page":
				$redirect_url = get_permalink(get_option('wpoa_logout_redirect_page'));
				break;
			case "admin_dashboard":
				$redirect_url = admin_url();
				break;
			case "user_profile":
				$redirect_url = get_edit_user_link();
				break;
			case "custom_url":
				$redirect_url = get_option('wpoa_logout_redirect_url');
				break;
		}
		//header("Location: " . $redirect_url);
		wp_safe_redirect($redirect_url);
		die();
	}

	// links a third-party account to an existing wordpress user account:
	function wpoa_link_account($user_id) {
		if ($_SESSION['WPOA']['USER_ID'] != '') {
			add_user_meta( $user_id, 'wpoa_identity', $_SESSION['WPOA']['PROVIDER'] . '|' . $_SESSION['WPOA']['USER_ID'] . '|' . time());
		}
	}

	// unlinks a third-party provider from an existing wordpress user account:
	function wpoa_unlink_account() {
		// get wpoa_identity row index that the user wishes to unlink:
		$wpoa_identity_row = $_POST['wpoa_identity_row']; // SANITIZED via $wpdb->prepare()
		// get the current user:
		global $current_user;
		get_currentuserinfo();
		$user_id = $current_user->ID;
		// delete the wpoa_identity record from the wp_usermeta table:
		global $wpdb;
		$usermeta_table = $wpdb->usermeta;
		$query_string = $wpdb->prepare("DELETE FROM $usermeta_table WHERE $usermeta_table.user_id = $user_id AND $usermeta_table.meta_key = 'wpoa_identity' AND $usermeta_table.umeta_id = %d", $wpoa_identity_row);
		$query_result = $wpdb->query($query_string);
		// notify client of the result;
		if ($query_result) {
			echo json_encode( array('result' => 1) );
		}
		else {
			echo json_encode( array('result' => 0) );
		}
		// wp-ajax requires death:
		die();
	}

	// pushes login messages into the dom where they can be extracted by javascript:
	function wpoa_push_login_messages() {
		if (isset($_SESSION['WPOA']['RESULT'])) {
			$result = $_SESSION['WPOA']['RESULT'];
			echo "<div id='wpoa-result'>" . $result . "</div>";
		}
		$_SESSION['WPOA']['RESULT'] = '';
	}

	// clears the login state:
	function wpoa_clear_login_state() {
		unset($_SESSION["WPOA"]["USER_ID"]);
		unset($_SESSION["WPOA"]["USER_EMAIL"]);
		unset($_SESSION["WPOA"]["ACCESS_TOKEN"]);
		unset($_SESSION["WPOA"]["EXPIRES_IN"]);
		unset($_SESSION["WPOA"]["EXPIRES_AT"]);
		//unset($_SESSION["WPOA"]["LAST_URL"]);
	}

	// ===================================
	// DEFAULT LOGIN SCREEN CUSTOMIZATIONS
	// ===================================

	// force the login screen logo to point to the site instead of wordpress.org:
	function wpoa_logo_link() {
		return get_bloginfo('url');
	}

	// show a custom login form on the default login screen:
	function wpoa_customize_login_screen() {
		$html = "";
		$design = get_option('wpoa_login_form_show_login_screen');
		if ($design != "None") {
			// TODO: we need to use $settings defaults here, not hard-coded defaults...
			$html .= $this->wpoa_login_form_content($design, 'none', 'buttons-column', 'Connect with', 'center', 'conditional', 'conditional', 'Please login:', 'You are already logged in.', 'Logging in...', 'Logging out...');
		}
		echo $html;
	}

	// ===================================
	// DEFAULT COMMENT FORM CUSTOMIZATIONS
	// ===================================

	// show a custom login form at the top of the default comment form:
	function wpoa_customize_comment_form_fields($fields) {
		$html = "";
		$design = get_option('wpoa_login_form_show_comments_section');
		if ($design != "None") {
			// TODO: we need to use $settings defaults here, not hard-coded defaults...
			$html .= $this->wpoa_login_form_content($design, 'none', 'buttons-column', 'Connect with', 'center', 'conditional', 'conditional', 'Please login:', 'You are already logged in.', 'Logging in...', 'Logging out...');
			$fields['logged_in_as'] = $html;
		}
		return $fields;
	}

	// show a custom login form at the top of the default comment form:
	function wpoa_customize_comment_form() {
		$html = "";
		$design = get_option('wpoa_login_form_show_comments_section');
		if ($design != "None") {
			// TODO: we need to use $settings defaults here, not hard-coded defaults...
			$html .= $this->wpoa_login_form_content($design, 'none', 'buttons-column', 'Connect with', 'center', 'conditional', 'conditional', 'Please login:', 'You are already logged in.', 'Logging in...', 'Logging out...');
		}
		echo $html;
	}

	// =========================
	// LOGIN / LOGOUT COMPONENTS
	// =========================

	// shortcode which allows adding the wpoa login form to any post or page:
	function wpoa_login_form( $atts ){
		$a = shortcode_atts( array(
			'design' => '',
			'icon_set' => 'none',
			'button_prefix' => '',
			'layout' => 'links-column',
			'align' => 'left',
			'show_login' => 'conditional',
			'show_logout' => 'conditional',
			'logged_out_title' => 'Please login:',
			'logged_in_title' => 'You are already logged in.',
			'logging_in_title' => 'Logging in...',
			'logging_out_title' => 'Logging out...',
			'style' => '',
			'class' => '',
		), $atts );
		// convert attribute strings to proper data types:
		//$a['show_login'] = filter_var($a['show_login'], FILTER_VALIDATE_BOOLEAN);
		//$a['show_logout'] = filter_var($a['show_logout'], FILTER_VALIDATE_BOOLEAN);
		// get the shortcode content:
		$html = $this->wpoa_login_form_content($a['design'], $a['icon_set'], $a['layout'], $a['button_prefix'], $a['align'], $a['show_login'], $a['show_logout'], $a['logged_out_title'], $a['logged_in_title'], $a['logging_in_title'], $a['logging_out_title'], $a['style'], $a['class']);
		return $html;
	}

	// gets the content to be used for displaying the login/logout form:
	function wpoa_login_form_content($design = '', $icon_set = 'icon_set', $layout = 'links-column', $button_prefix = '', $align = 'left', $show_login = 'conditional', $show_logout = 'conditional', $logged_out_title = 'Please login:', $logged_in_title = 'You are already logged in.', $logging_in_title = 'Logging in...', $logging_out_title = 'Logging out...', $style = '', $class = '') { // even though wpoa_login_form() will pass a default, we might call this function from another method so it's important to re-specify the default values
		// if a design was specified and that design exists, load the shortcode attributes from that design:
		if ($design != '' && WPOA::wpoa_login_form_design_exists($design)) { // TODO: remove first condition not needed
			$a = WPOA::wpoa_get_login_form_design($design);
			$icon_set = $a['icon_set'];
			$layout = $a['layout'];
			$button_prefix = $a['button_prefix'];
			$align = $a['align'];
			$show_login = $a['show_login'];
			$show_logout = $a['show_logout'];
			$logged_out_title = $a['logged_out_title'];
			$logged_in_title = $a['logged_in_title'];
			$logging_in_title = $a['logging_in_title'];
			$logging_out_title = $a['logging_out_title'];
			$style = $a['style'];
			$class = $a['class'];
		}
		// build the shortcode markup:
		$html = "";
		$html .= "<div class='wpoa-login-form wpoa-layout-$layout wpoa-layout-align-$align $class' style='$style' data-logging-in-title='$logging_in_title' data-logging-out-title='$logging_out_title'>";
		$html .= "<nav>";
		if (is_user_logged_in()) {
			if ($logged_in_title) {
				$html .= "<p id='wpoa-title'>" . $logged_in_title . "</p>";
			}
			if ($show_login == 'always') {
				$html .= $this->wpoa_login_buttons($icon_set, $button_prefix);
			}
			if ($show_logout == 'always' || $show_logout == 'conditional') {
				$html .= "<a class='wpoa-logout-button' href='" . wp_logout_url() . "' title='Logout'>Logout</a>";
			}
		}
		else {
			if ($logged_out_title) {
				$html .= "<p id='wpoa-title'>" . $logged_out_title . "</p>";
			}
			if ($show_login == 'always' || $show_login == 'conditional') {
				$html .= $this->wpoa_login_buttons($icon_set, $button_prefix);
			}
			if ($show_logout == 'always') {
				$html .= "<a class='wpoa-logout-button' href='" . wp_logout_url() . "' title='Logout'>Logout</a>";
			}
		}
		$html .= "</nav>";
		$html .= "</div>";
		return $html;
	}

	// generate and return the login buttons, depending on available providers:
	function wpoa_login_buttons($icon_set, $button_prefix) {
		// generate the atts once (cache them), so we can use it for all buttons without computing them each time:
		$site_url = get_bloginfo('url');
		if( force_ssl_admin() ) { $site_url = set_url_scheme( $site_url, 'https' ); }
		$redirect_to = urlencode($_GET['redirect_to']);
		if ($redirect_to) {$redirect_to = "&redirect_to=" . $redirect_to;}
		// get shortcode atts that determine how we should build these buttons:
		$icon_set_path = plugins_url('icons/' . $icon_set . '/', __FILE__);
		$atts = array(
			'site_url' => $site_url,
			'redirect_to' => $redirect_to,
			'icon_set' => $icon_set,
			'icon_set_path' => $icon_set_path,
			'button_prefix' => $button_prefix,
		);
		// generate the login buttons for available providers:
		$html = "";
		foreach (self::$WPOA_LOGIN_PROVIDERS as $key => $value) {
			$html .= $this->wpoa_login_button($key, $value, $atts);
		}
		$html .= $this->wpoa_login_button( 'oauth_server' , get_option( 'wpoa_oauth_server_api_button_text' ), $atts );
		if ($html == '') {
			$html .= 'Sorry, no login providers have been enabled.';
		}
		return $html;
	}

	// generates and returns a login button for a specific provider:
	function wpoa_login_button($provider, $display_name, $atts) {
		$html = "";
		if (get_option("wpoa_" . $provider . "_api_enabled")) {
			$html .= "<a id='wpoa-login-" . $provider . "' class='wpoa-login-button' href='" . $atts['site_url'] . "?connect=" . $provider . $atts['redirect_to'] . "'>";
			if ($atts['icon_set'] != 'none') {
				$html .= "<img src='" . $atts['icon_set_path'] . $provider . ".png' alt='" . $display_name . "' class='icon'></img>";
			}
			$html .= $atts['button_prefix'] . " " . $display_name;
			$html .= "</a>";
		}
		return $html;
	}

	// output the custom login form design selector:
	function wpoa_login_form_designs_selector($id = '', $master = false) {
		$html = "";
		$designs_json = get_option('wpoa_login_form_designs');
		$designs_array = json_decode($designs_json);
		$name = str_replace('-', '_', $id);
		$html .= "<select id='" . $id . "' name='" . $name . "'>";
		if ($master == true) {
			foreach($designs_array as $key => $val) {
				$html .= "<option value=''>" . $key . "</option>";
			}
			$html .= "</select>";
			$html .= "<input type='hidden' id='wpoa-login-form-designs' name='wpoa_login_form_designs' value='" . $designs_json . "'>";
		}
		else {
			$html .= "<option value='None'>" . 'None' . "</option>";
			foreach($designs_array as $key => $val) {
				$html .= "<option value='" . $key . "' " . selected(get_option($name), $key, false) . ">" . $key . "</option>";
			}
			$html .= "</select>";
		}
		return $html;
	}

	// returns a saved login form design as a shortcode atts string or array for direct use via the shortcode
	function wpoa_get_login_form_design($design_name, $as_string = false) {
		$designs_json = get_option('wpoa_login_form_designs');
		$designs_array = json_decode($designs_json, true);
		foreach($designs_array as $key => $val) {
			if ($design_name == $key) {
				$found = $val;
				break;
			}
		}
		$atts;
		//echo print_r($found);
		if ($found) {
			if ($as_string) {
				$atts = json_encode($found);
			}
			else {
				$atts = $found;
			}
		}
		return $atts;
	}

	function wpoa_login_form_design_exists($design_name) {
		$designs_json = get_option('wpoa_login_form_designs');
		$designs_array = json_decode($designs_json, true);
		foreach($designs_array as $key => $val) {
			if ($design_name == $key) {
				$found = $val;
				break;
			}
		}
		if ($found) {
			return true;
		}
		else {
			return false;
		}
	}

	// shows the user's linked providers, used on the 'Your Profile' page:
	function wpoa_linked_accounts() {
		// get the current user:
		global $current_user;
		get_currentuserinfo();
		$user_id = $current_user->ID;
		// get the wpoa_identity records:
		global $wpdb;
		$usermeta_table = $wpdb->usermeta;
		$query_string = "SELECT * FROM $usermeta_table WHERE $user_id = $usermeta_table.user_id AND $usermeta_table.meta_key = 'wpoa_identity'";
		$query_result = $wpdb->get_results($query_string);
		// list the wpoa_identity records:
		echo "<div id='wpoa-linked-accounts'>";
		echo "<h3>Linked Accounts</h3>";
		echo "<p>Manage the linked accounts which you have previously authorized to be used for logging into this website.</p>";
		echo "<table class='form-table'>";
		echo "<tr valign='top'>";
		echo "<th scope='row'>Your Linked Providers</th>";
		echo "<td>";
		if ( count($query_result) == 0) {
			echo "<p>You currently don't have any accounts linked.</p>";
		}
		echo "<div class='wpoa-linked-accounts'>";
		foreach ($query_result as $wpoa_row) {
			$wpoa_identity_parts = explode('|', $wpoa_row->meta_value);
			$oauth_provider = $wpoa_identity_parts[0];
			$oauth_id = $wpoa_identity_parts[1]; // keep this private, don't send to client
			$time_linked = $wpoa_identity_parts[2];
			$local_time = strtotime("-" . $_COOKIE['gmtoffset'] . ' hours', $time_linked);
			echo "<div>" . $oauth_provider . " on " . date('F d, Y h:i A', $local_time) . " <a class='wpoa-unlink-account' data-wpoa-identity-row='" . $wpoa_row->umeta_id . "' href='#'>Unlink</a></div>";
		}
		echo "</div>";
		echo "</td>";
		echo "</tr>";
		echo "<tr valign='top'>";
		echo "<th scope='row'>Link Another Provider</th>";
		echo "<td>";
		$design = get_option('wpoa_login_form_show_profile_page');
		if ($design != "None") {
			// TODO: we need to use $settings defaults here, not hard-coded defaults...
			echo $this->wpoa_login_form_content($design, 'none', 'buttons-row', 'Link', 'left', 'always', 'never', 'Select a provider:', 'Select a provider:', 'Authenticating...', '');
		}
		echo "</div>";
		echo "</td>";
		echo "</td>";
		echo "</table>";
	}

	// ====================
	// PLUGIN SETTINGS PAGE
	// ====================

	// registers all settings that have been defined at the top of the plugin:
	function wpoa_register_settings() {
		foreach ($this->settings as $setting_name => $default_value) {
			register_setting('wpoa_settings', $setting_name);
		}
	}

	// add the main settings page:
	function wpoa_settings_page() {
		add_options_page( 'WP-OAuth Options', 'WP-OAuth', 'manage_options', 'WP-OAuth', array($this, 'wpoa_settings_page_content') );
	}

	// render the main settings page content:
	function wpoa_settings_page_content() {
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
		$blog_url = rtrim(site_url(), "/") . "/";
		include 'wp-oauth-settings.php';
	}
} // END OF WPOA CLASS

// instantiate the plugin class ONCE and maintain a single instance (singleton):
WPOA::get_instance();
?>
