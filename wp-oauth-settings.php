<div class='wrap wpoa-settings'>
	<h2>WP-OAuth Settings</h2>
	
	<!-- START Settings Header -->
	<div id="wpoa-settings-header">
		<div id="wpoa-plugin-info">
			<nav>Plugin author: <ul><li><a href="mailto:perry@glassocean.net" target="_blank">Perry Butler</a></li><li><a href="http://glassocean.net" target="_blank">Website</a></li><li><form id="paypal-button" target="_blank" action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top"><input type="hidden" name="cmd" value="_s-xclick"><input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----"><input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!"><img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1"></form></li></ul></nav>
			<nav>Plugin links: <ul><li><a href="https://wordpress.org/plugins/wp-oauth/" target="_blank">WP-OAuth at WordPress.org</a></li><li><a href="https://github.com/perrybutler/WP-OAuth" target="_blank">WP-OAuth at GitHub.com</a></li><li><a href="http://glassocean.net/wp-oauth-enhances-your-wordpress-login-and-registration/" target="_blank">WP-OAuth at GlassOcean.net</a></li></ul></nav>
		</div>
		<div id="wpoa-settings-intro" class="wpoa-settings-section">
		<div class="form-padding">
			<p>Manage settings for WP-OAuth here. Third-party authentication providers will require you to set up an "App" which in turn will provide an "ID" and "Secret" that can be used for securely accessing their API.</p>
			<nav><strong>Jump to: </strong><ul><li><a href="#bookmark-general-settings">General Settings</a></li><li><a href="#bookmark-login-page-form-customization">Login Page & Form Customization</a></li><li><a href="#bookmark-login-with-google">Google</a></li><li><a href="#bookmark-login-with-facebook">Facebook</a></li><li><a href="#bookmark-login-with-linkedin">LinkedIn</a></li><li><a href="#bookmark-login-with-github">Github</a></li><li><a href="#bookmark-login-with-reddit">Reddit</a></li><li><a href="#bookmark-login-with-windowslive">Windows Live</a></li><li><a href="#bookmark-login-with-paypal">PayPal</a></li><li><a href="#bookmark-login-with-instagram">Instagram</a></li><li><a href="#bookmark-back-channel-configuration">Back Channel Configuration</a></li><li><a href="#bookmark-maintenance-troubleshooting">Maintenance & Troubleshooting</a></li></ul></nav>
			<nav><strong>Toggle all help tips: </strong><ul><li><a id="wpoa-settings-tips-on" href="#">On</a></li><li><a id="wpoa-settings-tips-off" href="#">Off</a></li></ul><div class="nav-splitter"></div><strong>Toggle all sections: </strong><ul><li><a id="wpoa-settings-sections-on" href="#">On</a></li><li><a id="wpoa-settings-sections-off" href="#">Off</a></li></ul></nav>
		</div>
		</div>
	</div>
	<!-- END Settings Header -->
	
	<!-- START Settings Body -->
	<div id="wpoa-settings-body">
	<form method='post' action='options.php'>
		<?php settings_fields('wpoa_settings'); ?>
		<?php do_settings_sections('wpoa_settings'); ?>
		<div class="wpoa-settings-section">
		<h3 id="bookmark-general-settings">General Settings</h3>
		<div class='form-padding'>
		<table class='form-table'>
			<tr valign='top'>
			<th scope='row'>Show login messages: <a href="#" class="tip-button">[?]</a></th>
			<td>
				<p><input type='checkbox' name='wpoa_show_login_messages' value='1' <?php checked(get_option('wpoa_show_login_messages') == 1); ?> /></p>
				<p class="tip-message">Shows a short-lived notification message to the user which indicates whether or not the login was successful, and if there was an error.</p>
			</td>
			</tr>
			
			<tr valign='top'>
			<th scope='row'>Login redirects to: <a href="#" class="tip-button">[?]</a></th>
			<td>
				<select name='wpoa_login_redirect'>
					<option value='home_page' <?php selected(get_option('wpoa_login_redirect'), 'home_page'); ?>>Home Page</option>
					<option value='last_page' <?php selected(get_option('wpoa_login_redirect'), 'last_page'); ?>>Last Page</option>
					<option value='specific_page' <?php selected(get_option('wpoa_login_redirect'), 'specific_page'); ?>>Specific Page</option>
					<option value='admin_dashboard' <?php selected(get_option('wpoa_login_redirect'), 'admin_dashboard'); ?>>Admin Dashboard</option>
					<option value='user_profile' <?php selected(get_option('wpoa_login_redirect'), 'user_profile'); ?>>User's Profile Page</option>
					<option value='custom_url' <?php selected(get_option('wpoa_login_redirect'), 'custom_url'); ?>>Custom URL</option>
				</select>
				<?php wp_dropdown_pages(array("name" => "wpoa_login_redirect_page", "selected" => get_option('wpoa_login_redirect_page'))); ?>
				<input type="text" name="wpoa_login_redirect_url" value="<?php echo get_option('wpoa_login_redirect_url'); ?>" />
				<p class="tip-message">Specifies where to redirect a user after they log in.</p>
			</td>
			</tr>
			
			<tr valign='top'>
			<th scope='row'>Logout redirects to: <a href="#" class="tip-button">[?]</a></th>
			<td>
				<select name='wpoa_logout_redirect'>
					<option value='default_handling' <?php selected(get_option('wpoa_login_redirect'), 'default_handling'); ?>>Let WordPress handle it</option>
					<option value='home_page' <?php selected(get_option('wpoa_logout_redirect'), 'home_page'); ?>>Home Page</option>
					<option value='last_page' <?php selected(get_option('wpoa_logout_redirect'), 'last_page'); ?>>Last Page</option>
					<option value='specific_page' <?php selected(get_option('wpoa_logout_redirect'), 'specific_page'); ?>>Specific Page</option>
					<option value='admin_dashboard' <?php selected(get_option('wpoa_logout_redirect'), 'admin_dashboard'); ?>>Admin Dashboard</option>
					<option value='user_profile' <?php selected(get_option('wpoa_logout_redirect'), 'user_profile'); ?>>User's Profile Page</option>
					<option value='custom_url' <?php selected(get_option('wpoa_logout_redirect'), 'custom_url'); ?>>Custom URL</option>
				</select>
				<?php wp_dropdown_pages(array("name" => "wpoa_logout_redirect_page", "selected" => get_option('wpoa_logout_redirect_page'))); ?>
				<input type="text" name="wpoa_logout_redirect_url" value="<?php echo get_option('wpoa_logout_redirect_url'); ?>" />
				<p class="tip-message">Specifies where to redirect a user after they log out.</p>
			</td>
			</tr>
		</table> <!-- .form-table -->
		<?php submit_button('Save all settings'); ?>
		</div> <!-- .form-padding -->
		</div> <!-- .wpoa-settings-section -->
		<!-- END General Settings section -->
		
		<!-- START Login Page & Form Customization section -->
		<div class="wpoa-settings-section">
		<h3 id="bookmark-login-page-form-customization">Login Page & Form Customization</h3>
		<div class='form-padding'>
		<p>Here you may customize WordPress' default login page and login form.</p>
		<table class='form-table'>
		<tr valign='top'>
			<tr valign='top'>
			<th scope='row'>Show login form: <a href="#" class="tip-button">[?]</a></th>
			<td>
				<select name='wpoa_show_login_form'>
					<option value='custom' <?php selected(get_option('wpoa_show_login_form'), 'custom'); ?>>Custom</option>
					<option value='default' <?php selected(get_option('wpoa_show_login_form'), 'default'); ?>>Default</option>
					<option value='default_custom' <?php selected(get_option('wpoa_show_login_form'), 'default_custom'); ?>>Default + Custom</option>
				</select>
				<p class="tip-message">Specifies which login form(s) to display on the default login page (wp-login.php) and the default login popup. For example, setting this to "Custom" will show only the custom login form provided by this plugin, and the default username/password form will be hidden.</p>
				<p class="tip-message tip-warning"><strong>Warning:</strong> Choosing <em>Custom</em> will hide the default username/password form, but it does NOT disable it. WordPress doesn't include a hook for hiding the login form, so we must do this with Javascript at the client-side, which means if a person has Javascript disabled, the form will not be hidden. Even if Javascript is enabled, an experienced person can easily unhide the form. Therefore, hiding the form is a convenience intended only to alter the appearance of the default login screen and should not be used as a security measure.</p> 
			</td>
			</tr>
			
			<tr valign='top'>
			<th colspan="2">
				<h4>Custom Login Form Appearance</h4>
			</th>
			</td>
		
			<tr valign='top'>
			<th scope='row'>Logged out title: <a href="#" class="tip-button">[?]</a></th>
			<td>
				<p><input id='wpoa_logged_out_title' type='text' size='36' name='wpoa_logged_out_title' value="<?php echo get_option('wpoa_logged_out_title'); ?>" /></p>
				<p class="tip-message">Sets the text to be displayed above the login form for logged out users.</p>
			</td>
			</tr>
			
			<tr valign='top'>
			<th scope='row'>Logged in title: <a href="#" class="tip-button">[?]</a></th>
			<td>
				<p><input id='wpoa_logged_in_title' type='text' size='36' name='wpoa_logged_in_title' value="<?php echo get_option('wpoa_logged_in_title'); ?>" /></p>
				<p class="tip-message">Sets the text to be displayed above the login form for logged in users.</p>
			</td>
			</tr>
			
			<tr valign='top'>
			<th scope='row'>Logging in title: <a href="#" class="tip-button">[?]</a></th>
			<td>
				<p><input id='wpoa_logging_in_title' type='text' size='36' name='wpoa_logging_in_title' value="<?php echo get_option('wpoa_logging_in_title'); ?>" /></p>
				<p class="tip-message">Sets the text to be displayed above the login form for users who are logging in.</p>
			</td>
			</tr>
			
			<tr valign='top'>
			<th scope='row'>Logging out title: <a href="#" class="tip-button">[?]</a></th>
			<td>
				<p><input id='wpoa_logging_out_title' type='text' size='36' name='wpoa_logging_out_title' value="<?php echo get_option('wpoa_logging_out_title'); ?>" /></p>
				<p class="tip-message">Sets the text to be displayed above the login form for users who are logging out.</p>
			</td>
			</tr>
			
			<tr valign='top'>
			<th colspan="2">
				<h4>Default Login Page Tweaks</h4>
			</th>
			</td>
			
			<tr valign='top'>
			<th scope='row'>Logo links to site: <a href="#" class="tip-button">[?]</a></th>
			<td>
				<p><input type='checkbox' name='wpoa_logo_links_to_site' value='1' <?php checked(get_option('wpoa_logo_links_to_site') == 1); ?> /></p>
				<p class="tip-message">Forces the logo image on the login form to link to your site instead of WordPress.org.</p>
			</td>
			</tr>
			
			<tr valign='top'>
			<th scope='row'>Logo image: <a href="#" class="tip-button">[?]</a></th>
			<td>
				<p>
				<input id='wpoa_logo_image' type='text' size='36' name='wpoa_logo_image' value="<?php echo get_option('wpoa_logo_image'); ?>" />
				<input id='wpoa_logo_image_button' type='button' value='Select' />
				</p>
				<p class="tip-message">Changes the default WordPress logo on the login form to an image of your choice. You may select an image from the Media Library, or specify a custom URL.</p>
			</td>
			</tr>
			
			<tr valign='top'>
			<th scope='row'>Background image: <a href="#" class="tip-button">[?]</a></th>
			<td>
				<p>
				<input id='wpoa_bg_image' type='text' size='36' name='wpoa_bg_image' value="<?php echo get_option('wpoa_bg_image'); ?>" />
				<input id='wpoa_bg_image_button' type='button' value='Select' />
				</p>
				<p class="tip-message">Changes the background on the login form to an image of your choice. You may select an image from the Media Library, or specify a custom URL.</p>
			</td>
			</tr>
		</table> <!-- .form-table -->
		<?php submit_button('Save all settings'); ?>
		</div> <!-- .form-padding -->
		</div> <!-- .wpoa-settings-section -->
		<!-- END Login Page & Form Customization section -->
		
		<!-- START Login with Google section -->
		<div class="wpoa-settings-section">
		<h3 id="bookmark-login-with-google">Login with Google</h3>
		<div class='form-padding'>
		<table class='form-table'>
			<tr valign='top'>
			<th scope='row'>Enabled:</th>
			<td>
				<input type='checkbox' name='wpoa_google_api_enabled' value='1' <?php checked(get_option('wpoa_google_api_enabled') == 1); ?> />
			</td>
			</tr>
			
			<tr valign='top'>
			<th scope='row'>Client ID:</th>
			<td>
				<input type='text' name='wpoa_google_api_id' value='<?php echo get_option('wpoa_google_api_id'); ?>' />
			</td>
			</tr>

			<tr valign='top'>
			<th scope='row'>Client Secret:</th>
			<td>
				<input type='text' name='wpoa_google_api_secret' value='<?php echo get_option('wpoa_google_api_secret'); ?>' />
			</td>
			</tr>
		</table> <!-- .form-table -->
		<p>
			<strong>Instructions:</strong>
			<ol>
				<li>Visit the Google website for developers <a href='https://console.developers.google.com/project' target="_blank">console.developers.google.com</a>.</li>
				<li>At Google, create a new Project and enable the Google+ API. This will enable your site to access the Google+ API.</li>
				<li>At Google, provide your site's homepage URL (<?php echo $blog_url; ?>) for the new Project's Redirect URI. Don't forget the trailing slash!</li>
				<li>At Google, you must also configure the Consent Screen with your Email Address and Product Name. This is what Google will display to users when they are asked to grant access to your site/app.</li>
				<li>Paste your Client ID/Secret provided by Google into the fields above, then click the Save all settings button.</li>
			</ol>
		</p>
		<?php submit_button('Save all settings'); ?>
		</div> <!-- .form-padding -->
		</div> <!-- .wpoa-settings-section -->
		<!-- END Login with Google section -->
		
		<!-- START Login with Facebook section -->
		<div class="wpoa-settings-section">
		<h3 id="bookmark-login-with-facebook">Login with Facebook</h3>
		<div class='form-padding'>
		<table class='form-table'>
			<tr valign='top'>
			<th scope='row'>Enabled:</th>
			<td>
				<input type='checkbox' name='wpoa_facebook_api_enabled' value='1' <?php checked(get_option('wpoa_facebook_api_enabled') == 1); ?> />
			</td>
			</tr>
			
			<tr valign='top'>
			<th scope='row'>App ID:</th>
			<td>
				<input type='text' name='wpoa_facebook_api_id' value='<?php echo get_option('wpoa_facebook_api_id'); ?>' />
			</td>
			</tr>
			 
			<tr valign='top'>
			<th scope='row'>App Secret:</th>
			<td>
				<input type='text' name='wpoa_facebook_api_secret' value='<?php echo get_option('wpoa_facebook_api_secret'); ?>' />
			</td>
			</tr>
		</table> <!-- .form-table -->
		<p>
			<strong>Instructions:</strong>
			<ol>
				<li>Register as a Facebook Developer at <a href='https://developers.facebook.com/' target="_blank">developers.facebook.com</a>.</li>
				<li>At Facebook, create a new App. This will enable your site to access the Facebook API.</li>
				<li>At Facebook, provide your site's homepage URL (<?php echo $blog_url; ?>) for the new App's Redirect URI. Don't forget the trailing slash!</li>
				<li>Paste your App ID/Secret provided by Facebook into the fields above, then click the Save all settings button.</li>
			</ol>
		</p>
		<?php submit_button('Save all settings'); ?>
		</div> <!-- .form-padding -->
		</div> <!-- .wpoa-settings-section -->
		<!-- END Login with Facebook section -->
		
		<!-- START Login with LinkedIn section -->
		<div class="wpoa-settings-section">
		<h3 id="bookmark-login-with-linkedin">Login with LinkedIn</h3>
		<div class='form-padding'>
		<table class='form-table'>
			<tr valign='top'>
			<th scope='row'>Enabled:</th>
			<td>
				<input type='checkbox' name='wpoa_linkedin_api_enabled' value='1' <?php checked(get_option('wpoa_linkedin_api_enabled') == 1); ?> />
			</td>
			</tr>
			
			<tr valign='top'>
			<th scope='row'>API Key:</th>
			<td>
				<input type='text' name='wpoa_linkedin_api_id' value='<?php echo get_option('wpoa_linkedin_api_id'); ?>' />
			</td>
			</tr>
			 
			<tr valign='top'>
			<th scope='row'>Secret Key:</th>
			<td>
				<input type='text' name='wpoa_linkedin_api_secret' value='<?php echo get_option('wpoa_linkedin_api_secret'); ?>' />
			</td>
			</tr>
		</table> <!-- .form-table -->
		<p>
			<strong>Instructions:</strong>
			<ol>
				<li>Register as a LinkedIn Developer at <a href='https://developers.linkedin.com/' target="_blank">developers.linkedin.com</a>.</li>
				<li>At LinkedIn, create a new App. This will enable your site to access the LinkedIn API.</li>
				<li>At LinkedIn, provide your site's homepage URL (<?php echo $blog_url; ?>) for the new App's Redirect URI. Don't forget the trailing slash!</li>
				<li>Paste your API Key/Secret provided by LinkedIn into the fields above, then click the Save all settings button.</li>
			</ol>
		</p>
		<?php submit_button('Save all settings'); ?>
		</div> <!-- .form-padding -->
		</div> <!-- .wpoa-settings-section -->
		<!-- END Login with LinkedIn section -->
		
		<!-- START Login with Github section -->
		<div class="wpoa-settings-section">
		<h3 id="bookmark-login-with-github">Login with Github</h3>
		<div class='form-padding'>
		<table class='form-table'>
			<tr valign='top'>
			<th scope='row'>Enabled:</th>
			<td>
				<input type='checkbox' name='wpoa_github_api_enabled' value='1' <?php checked(get_option('wpoa_github_api_enabled') == 1); ?> />
			</td>
			</tr>
			
			<tr valign='top'>
			<th scope='row'>Client ID:</th>
			<td>
				<input type='text' name='wpoa_github_api_id' value='<?php echo get_option('wpoa_github_api_id'); ?>' />
			</td>
			</tr>
			 
			<tr valign='top'>
			<th scope='row'>Client Secret:</th>
			<td>
				<input type='text' name='wpoa_github_api_secret' value='<?php echo get_option('wpoa_github_api_secret'); ?>' />
			</td>
			</tr>
		</table> <!-- .form-table -->
		<p>
			<strong>Instructions:</strong>
			<ol>
				<li>Register as a Github Developer at <a href='https://developers.github.com/' target="_blank">developers.github.com</a>.</li>
				<li>At Github, create a new App. This will enable your site to access the Github API.</li>
				<li>At Github, provide your site's homepage URL (<?php echo $blog_url; ?>) for the new App's Redirect URI. Don't forget the trailing slash!</li>
				<li>Paste your API Key/Secret provided by Github into the fields above, then click the Save all settings button.</li>
			</ol>
		</p>
		<?php submit_button('Save all settings'); ?>
		</div> <!-- .form-padding -->
		</div> <!-- .wpoa-settings-section -->
		<!-- END Login with Github section -->
		
		<!-- START Login with Reddit section -->
		<div class="wpoa-settings-section">
		<h3 id="bookmark-login-with-reddit">Login with Reddit</h3>
		<div class='form-padding'>
		<table class='form-table'>
			<tr valign='top'>
			<th scope='row'>Enabled:</th>
			<td>
				<input type='checkbox' name='wpoa_reddit_api_enabled' value='1' <?php checked(get_option('wpoa_reddit_api_enabled') == 1); ?> />
			</td>
			</tr>
			
			<tr valign='top'>
			<th scope='row'>Client ID:</th>
			<td>
				<input type='text' name='wpoa_reddit_api_id' value='<?php echo get_option('wpoa_reddit_api_id'); ?>' />
			</td>
			</tr>
			 
			<tr valign='top'>
			<th scope='row'>Client Secret:</th>
			<td>
				<input type='text' name='wpoa_reddit_api_secret' value='<?php echo get_option('wpoa_reddit_api_secret'); ?>' />
			</td>
			</tr>
		</table> <!-- .form-table -->
		<p>
			<strong>Instructions:</strong>
			<ol>
				<li>Register as a Reddit Developer at <a href='https://ssl.reddit.com/prefs/apps' target="_blank">ssl.reddit.com/prefs/apps</a>.</li>
				<li>At Reddit, create a new App. This will enable your site to access the Reddit API.</li>
				<li>At Reddit, provide your site's homepage URL (<?php echo $blog_url; ?>) for the new App's Redirect URI. Don't forget the trailing slash!</li>
				<li>Paste your Client ID/Secret provided by Reddit into the fields above, then click the Save all settings button.</li>
			</ol>
		</p>
		<?php submit_button('Save all settings'); ?>
		</div> <!-- .form-padding -->
		</div> <!-- .wpoa-settings-section -->
		<!-- END Login with Reddit section -->
		
		<!-- START Login with Windows Live section -->
		<div class="wpoa-settings-section">
		<h3 id="bookmark-login-with-windowslive">Login with Windows Live</h3>
		<div class='form-padding'>
		<table class='form-table'>
			<tr valign='top'>
			<th scope='row'>Enabled:</th>
			<td>
				<input type='checkbox' name='wpoa_windowslive_api_enabled' value='1' <?php checked(get_option('wpoa_windowslive_api_enabled') == 1); ?> />
			</td>
			</tr>
			
			<tr valign='top'>
			<th scope='row'>Client ID:</th>
			<td>
				<input type='text' name='wpoa_windowslive_api_id' value='<?php echo get_option('wpoa_windowslive_api_id'); ?>' />
			</td>
			</tr>
			 
			<tr valign='top'>
			<th scope='row'>Client Secret:</th>
			<td>
				<input type='text' name='wpoa_windowslive_api_secret' value='<?php echo get_option('wpoa_windowslive_api_secret'); ?>' />
			</td>
			</tr>
		</table> <!-- .form-table -->
		<p>
			<strong>Instructions:</strong>
			<ol>
				<li>Register as a Windows Live Developer at <a href='https://manage.dev.live.com' target="_blank">manage.dev.live.com</a>.</li>
				<li>At Windows Live, create a new App. This will enable your site to access the Windows Live API.</li>
				<li>At Windows Live, provide your site's homepage URL (<?php echo $blog_url; ?>) for the new App's Redirect URI. Don't forget the trailing slash!</li>
				<li>Paste your Client ID/Secret provided by Windows Live into the fields above, then click the Save all settings button.</li>
			</ol>
		</p>
		<?php submit_button('Save all settings'); ?>
		</div> <!-- .form-padding -->
		</div> <!-- .wpoa-settings-section -->
		<!-- END Login with Windows Live section -->

		<!-- START Login with PayPal section -->
		<div class="wpoa-settings-section">
		<h3 id="bookmark-login-with-paypal">Login with PayPal</h3>
		<div class='form-padding'>
		<table class='form-table'>
			<tr valign='top'>
			<th scope='row'>Enabled:</th>
			<td>
				<input type='checkbox' name='wpoa_paypal_api_enabled' value='1' <?php checked(get_option('wpoa_paypal_api_enabled') == 1); ?> />
			</td>
			</tr>
			
			<tr valign='top'>
			<th scope='row'>Sandbox mode:</th>
			<td>
				<input type='checkbox' name='wpoa_paypal_api_sandbox_mode' value='1' <?php checked(get_option('wpoa_paypal_api_sandbox_mode') == 1); ?> />
				<p class="tip-message">PayPal offers a sandbox mode for developers who wish to setup and test PayPal Login with their site before going live.</p>
			</td>
			</tr>
			
			<tr valign='top'>
			<th scope='row'>Client ID:</th>
			<td>
				<input type='text' name='wpoa_paypal_api_id' value='<?php echo get_option('wpoa_paypal_api_id'); ?>' />
			</td>
			</tr>
			 
			<tr valign='top'>
			<th scope='row'>Client Secret:</th>
			<td>
				<input type='text' name='wpoa_paypal_api_secret' value='<?php echo get_option('wpoa_paypal_api_secret'); ?>' />
			</td>
			</tr>
		</table> <!-- .form-table -->
		<p>
			<strong>Instructions:</strong>
			<ol>
				<li>Register as a PayPal Developer at <a href='https://developer.paypal.com' target="_blank">developer.paypal.com</a>.</li>
				<li>At PayPal, create a new App. This will enable your site to access the PayPal API. Your PayPal App will begin in <em>sandbox mode</em> for testing.</li>
				<li>At PayPal, provide your site's homepage URL (<?php echo $blog_url; ?>) for the <em>App redirect URLs</em>. Don't forget the trailing slash!</li>
				<li>At PayPal, in the APP CAPABILITIES section, enable <em>Log In with PayPal</em>.</li>
				<li>Paste your Client ID/Secret provided by PayPal into the fields above, then click the Save all settings button.</li>
				<li>After testing PayPal login in <em>sandbox mode</em> with your site, you'll eventually want to switch the App over to <em>live mode</em> at PayPal, and turn off the Sandbox mode above.</li>
			</ol>
		</p>
		<?php submit_button('Save all settings'); ?>
		</div> <!-- .form-padding -->
		</div> <!-- .wpoa-settings-section -->
		<!-- END Login with PayPal section -->

		<!-- START Login with Instagram section -->
		<div class="wpoa-settings-section">
		<h3 id="bookmark-login-with-instagram">Login with Instagram</h3>
		<div class='form-padding'>
		<table class='form-table'>
			<tr valign='top'>
			<th scope='row'>Enabled:</th>
			<td>
				<input type='checkbox' name='wpoa_instagram_api_enabled' value='1' <?php checked(get_option('wpoa_instagram_api_enabled') == 1); ?> />
			</td>
			</tr>
			
			<tr valign='top'>
			<th scope='row'>Client ID:</th>
			<td>
				<input type='text' name='wpoa_instagram_api_id' value='<?php echo get_option('wpoa_instagram_api_id'); ?>' />
			</td>
			</tr>
			 
			<tr valign='top'>
			<th scope='row'>Client Secret:</th>
			<td>
				<input type='text' name='wpoa_instagram_api_secret' value='<?php echo get_option('wpoa_instagram_api_secret'); ?>' />
			</td>
			</tr>
		</table> <!-- .form-table -->
		<p>
			<strong>Instructions:</strong>
			<ol>
				<li>NOTE: Instagram's developer signup requires a valid cell phone number.</li>
				<li>At Instagram, register as an <a href='http://instagram.com/developer/authentication/' target="_blank">Instagram Developer</a>.</li>
				<li>At Instagram, after signing up/in, click <a href='http://instagram.com/developer/clients/manage/'>Manage Clients</a>.</li>
				<li>At Instagram, click <a href="http://instagram.com/developer/clients/register/">Register a New Client</a>. This will enable your site to access the Instagram API.</li>
				<li>At Instagram, provide your site's homepage URL (<?php echo $blog_url; ?>) for the <em>OAuth redirect_uri</em>. Don't forget the trailing slash!</li>
				<li>At Instagram, copy the <em>Client ID/Client Secret</em> provided by Instagram and paste them into the fields above, then click the Save all settings button.</li>
			</ol>
			<strong>References:</strong>
			<ul>
				<li><a href='http://instagram.com/developer/authentication/'>Instagram Developer Reference - Authentication</a></li>
			</ul>
		</p>
		<?php submit_button('Save all settings'); ?>
		</div> <!-- .form-padding -->
		</div> <!-- .wpoa-settings-section -->
		<!-- END Login with Instagram section -->

		<?php /* EXCLUDED WIP:
		<!-- START Login with Battle.net section -->
		<div class="wpoa-settings-section">
		<h3 id="bookmark-login-with-battlenet">Login with Battle.net</h3>
		<div class='form-padding'>
		<table class='form-table'>
			<tr valign='top'>
			<th scope='row'>Enabled:</th>
			<td>
				<input type='checkbox' name='wpoa_battlenet_api_enabled' value='1' <?php checked(get_option('wpoa_battlenet_api_enabled') == 1); ?> />
			</td>
			</tr>
			
			<tr valign='top'>
			<th scope='row'>Client ID:</th>
			<td>
				<input type='text' name='wpoa_battlenet_api_id' value='<?php echo get_option('wpoa_battlenet_api_id'); ?>' />
			</td>
			</tr>
			 
			<tr valign='top'>
			<th scope='row'>Client Secret:</th>
			<td>
				<input type='text' name='wpoa_battlenet_api_secret' value='<?php echo get_option('wpoa_battlenet_api_secret'); ?>' />
			</td>
			</tr>
		</table> <!-- .form-table -->
		
		<p>
			<strong>Instructions:</strong>
			<ol>
				<li>NOTE: Instagram's developer signup requires a valid cell phone number.</li>
				<li>At Instagram, register as an <a href='http://instagram.com/developer/authentication/' target="_blank">Instagram Developer</a>.</li>
				<li>At Instagram, after signing up/in, click <a href='http://instagram.com/developer/clients/manage/'>Manage Clients</a>.</li>
				<li>At Instagram, click <a href="http://instagram.com/developer/clients/register/">Register a New Client</a>. This will enable your site to access the Instagram API.</li>
				<li>At Instagram, provide your site's homepage URL (<?php echo $blog_url; ?>) for the <em>OAuth redirect_uri</em>. Don't forget the trailing slash!</li>
				<li>At Instagram, copy the <em>Client ID/Client Secret</em> provided by Instagram and paste them into the fields above, then click the Save all settings button.</li>
			</ol>
			<strong>References:</strong>
			<ul>
				<li><a href='https://dev.battle.net/docs/read/oauth'>Battle.net OAuth Reference</a></li>
			</ul>
		</p>
		<?php submit_button('Save all settings'); ?>
		</div> <!-- .form-padding -->
		</div> <!-- .wpoa-settings-section -->
		<!-- END Login with Battle.net section -->
		*/ ?>
		
		<!-- START Back Channel Configuration section -->
		<div class="wpoa-settings-section">
		<h3 id="bookmark-back-channel-configuration">Back Channel Configuration</h3>
		<div class='form-padding'>
		<p>These settings are for troubleshooting and/or fine tuning the back channel communication this plugin utilizes between your server and the third-party providers.</p>
		<table class='form-table'>
			<tr valign='top'>
			<th scope='row'>HTTP utility: <a href="#" class="tip-button">[?]</a></th>
			<td>
				<p>
				<select name='wpoa_http_util'>
					<option value='curl' <?php selected(get_option('wpoa_http_util'), 'curl'); ?>>cURL</option>
					<option value='stream-context' <?php selected(get_option('wpoa_http_util'), 'stream-context'); ?>>Stream Context</option>
				</select>
				</p>
				<p class="tip-message">The method used by the web server for performing HTTP requests to the third-party providers. Most servers support cURL, but some servers may require Stream Context instead.</p>
			</td>
			</tr>
			
			<tr valign='top'>
			<th scope='row'>Verify Peer/Host SSL Certificates: <a href="#" class="tip-button">[?]</a></th>
			<td>
				<p><input type='checkbox' name='wpoa_http_util_verify_ssl' value='1' <?php checked(get_option('wpoa_http_util_verify_ssl') == 1); ?> /></p>
				<p class="tip-message">Determines whether or not to validate peer/host SSL certificates during back channel HTTP calls to the third-party login providers. If your server has an incorrect SSL configuration or doesn't support SSL, you may try disabling this setting as a workaround.</p>
				<p class="tip-message tip-warning"><strong>Warning:</strong> Disabling this is not recommended. For maximum security it would be a good idea to get your server's SSL configuration fixed and keep this setting enabled.</p>
			</td>
			</tr>
		</table> <!-- .form-table -->
		<?php submit_button('Save all settings'); ?>
		</div> <!-- .form-padding -->
		</div> <!-- .wpoa-settings-section -->
		<!-- END Back Channel Configuration section -->
		
		<!-- START Maintenance & Troubleshooting section -->
		<div class="wpoa-settings-section">
		<h3 id="bookmark-maintenance-troubleshooting">Maintenance & Troubleshooting</h3>
		<div class='form-padding'>
		<table class='form-table'>
			<tr valign='top'>
			<th scope='row'>Restore default settings: <a href="#" class="tip-button">[?]</a></th>
			<td>
				<p><input type='checkbox' name='wpoa_restore_default_settings' value='1' <?php checked(get_option('wpoa_restore_default_settings') == 1); ?> /></p>
				<p class="tip-message"><strong>Instructions:</strong> Check the box above, click the Save all settings button, and the settings will be restored to default.</p>
				<p class="tip-message tip-warning"><strong>Warning:</strong> This will restore the default settings, erasing any API keys/secrets that you may have entered above.</p>
			</td>
			</tr>		
			<tr valign='top'>
			<th scope='row'>Delete settings on uninstall: <a href="#" class="tip-button">[?]</a></th>
			<td>
				<p><input type='checkbox' name='wpoa_delete_settings_on_uninstall' value='1' <?php checked(get_option('wpoa_delete_settings_on_uninstall') == 1); ?> /></p>				
				<p class="tip-message"><strong>Instructions:</strong> Check the box above, click the Save all settings button, then uninstall this plugin as normal from the Plugins page.</p>
				<p class="tip-message tip-warning"><strong>Warning:</strong> This will delete all settings that may have been created in your database by this plugin, including all linked third-party login providers. This will not delete any WordPress user accounts, but users who may have registered with or relied upon their third-party login providers may have trouble logging into your site. Make absolutely sure you won't need the values on this page any time in the future, because they will be deleted permanently.</p>
			</td>
			</tr>
		</table> <!-- .form-table -->
		<?php submit_button('Save all settings'); ?>
		</div> <!-- .form-padding -->
		</div> <!-- .wpoa-settings-section -->
		<!-- END  Maintenance & Troubleshooting section -->
	</form> <!-- form -->
	</div> <!-- #wpoa-settings-body -->
	<!-- END Settings Body -->
</div> <!-- .wrap .wpoa-settings -->