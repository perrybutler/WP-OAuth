<div class='wrap wpoa-settings'>
	<div id="wpoa-settings-meta">Toggle tips: <ul><li><a id="wpoa-settings-tips-on" href="#">On</a></li><li><a id="wpoa-settings-tips-off" href="#">Off</a></li></ul><div class="nav-splitter"></div>Toggle sections: <ul><li><a id="wpoa-settings-sections-on" href="#">On</a></li><li><a id="wpoa-settings-sections-off" href="#">Off</a></li></ul></div>
	<h2>WP-OAuth Settings</h2>
	<!-- START Settings Header -->
	<div id="wpoa-settings-header">
		<?php 
			//echo WPOA::wpoa_get_login_form_design('Login Screen', true);
		?>
	</div>
	<!-- END Settings Header -->
	<!-- START Settings Body -->
	<div id="wpoa-settings-body">
	<!-- START Settings Column 2 -->
	<div id="wpoa-settings-col2" class="wpoa-settings-column">
		<div id="" class="wpoa-settings-section">
			<h3 id="bookmark-login-page-form-customization">About</h3>
			<div class='form-padding'>
				<div id="wpoa-logo" style="width:64px; height:64px; float:right; background-size:100% 100%;"></div>
				<p><span style="font-size:1.1em;"><strong>WP-OAuth <?php echo WPOA::PLUGIN_VERSION; ?></strong></span><br/>by <strong>Perry Butler</strong></p>
				<p>Rate it 5 stars: <a id="wpoa-rate-5stars" href="https://wordpress.org/support/view/plugin-reviews/wp-oauth?rate=5" target="_blank"><img src="http://ps.w.org/wp-oauth/assets/5stars.png" style="vertical-align:text-top;"></img></a></p>
				<nav><ul><li><a href="mailto:perry@glassocean.net">Contact me</a></li><li><a href="http://glassocean.net" target="_blank">Visit my website</a></li><li><a href="https://wordpress.org/plugins/wp-oauth/" target="_blank">WP-OAuth at WordPress.org</a></li><li><a href="https://github.com/perrybutler/WP-OAuth" target="_blank">WP-OAuth at GitHub.com</a></li><li><a href="http://glassocean.net/wp-oauth-enhances-your-wordpress-login-and-registration/" target="_blank">WP-OAuth at GlassOcean.net</a></li></ul></nav>
			</div>
		</div>
		<div id="" class="wpoa-settings-section">
			<h3 id="bookmark-login-page-form-customization">Donate</h3>
			<div class='form-padding'>
				<p>Do you enjoy using this plugin? Do you want to help improve it? Consider donating via <strong>PayPal</strong>!</p><p>My promise to you is that WP-OAuth will always remain free of restrictions, advertisements, branding, etc. <em>Your donations help make this possible.</em></p>
				<form action="https://www.paypal.com/cgi-bin/webscr" target="_blank" method="post">
					$ <input type="text" style="width: 50px" name="amount" value="10">
					<span>USD</span>
					<input type="hidden" value="_xclick" name="cmd">
					<input type="hidden" value="hectavex@gmail.com" name="business">
					<input type="hidden" value="WP-OAuth" name="item_name">
					<input type="hidden" value="0" name="no_shipping">
					<input type="hidden" value="1" name="no_note">
					<input type="hidden" value="Return to your dashboard" name="cbt">
					<input type="hidden" value="USD" name="currency_code">
					<input type="submit" id="wpoa-paypal-button" class="button" value="Donate">
				</form>
			</div>
		</div>
		<div id="" class="wpoa-settings-section">
			<h3 id="bookmark-login-page-form-customization">Live Demo</h3>
			<div class='form-padding'>
				<p>A live demo is available at <a href="http://choiceschances.com">choiceschances.com</a>.</p>
			</div>
		</div>
		<div id="" class="wpoa-settings-section">
			<h3 id="bookmark-login-page-form-customization">Support</h3>
			<div class='form-padding'>
				<p>Your general questions can be asked in the plugin <a href="https://wordpress.org/support/plugin/wp-oauth" target="_blank">support forum</a>.</p>
				<p>Need advanced plugin customizations or special features, installation and setup assistance, or hands-on / one-on-one technical support? <a href="mailto:perry@glassocean.net">Contact me</a>.</p>
			</div>
		</div>
	</div>
	<!-- END Settings Column 2 -->
	<!-- START Settings Column 1 -->
	<div id="wpoa-settings-col1" class="wpoa-settings-column">
		<form method='post' action='options.php'>
			<?php settings_fields('wpoa_settings'); ?>
			<?php do_settings_sections('wpoa_settings'); ?>
			<!-- START General Settings section -->
			<div class="wpoa-settings-section">
			<h3 id="bookmark-general-settings">General Settings</h3>
			<div class='form-padding'>
			<table class='form-table'>
				<tr valign='top'>
				<th scope='row'>Show login messages: <a href="#" class="tip-button">[?]</a></th>
				<td>
					<input type='checkbox' name='wpoa_show_login_messages' value='1' <?php checked(get_option('wpoa_show_login_messages') == 1); ?> />
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
					<?php wp_dropdown_pages(array("id" => "wpoa_login_redirect_page", "name" => "wpoa_login_redirect_page", "selected" => get_option('wpoa_login_redirect_page'))); ?>
					<input type="text" name="wpoa_login_redirect_url" value="<?php echo get_option('wpoa_login_redirect_url'); ?>" style="display:none;" />
					<p class="tip-message">Specifies where to redirect a user after they log in.</p>
				</td>
				</tr>
				
				<tr valign='top'>
				<th scope='row'>Logout redirects to: <a href="#" class="tip-button">[?]</a></th>
				<td>
					<select name='wpoa_logout_redirect'>
						<option value='default_handling' <?php selected(get_option('wpoa_logout_redirect'), 'default_handling'); ?>>Let WordPress handle it</option>
						<option value='home_page' <?php selected(get_option('wpoa_logout_redirect'), 'home_page'); ?>>Home Page</option>
						<option value='last_page' <?php selected(get_option('wpoa_logout_redirect'), 'last_page'); ?>>Last Page</option>
						<option value='specific_page' <?php selected(get_option('wpoa_logout_redirect'), 'specific_page'); ?>>Specific Page</option>
						<option value='admin_dashboard' <?php selected(get_option('wpoa_logout_redirect'), 'admin_dashboard'); ?>>Admin Dashboard</option>
						<option value='user_profile' <?php selected(get_option('wpoa_logout_redirect'), 'user_profile'); ?>>User's Profile Page</option>
						<option value='custom_url' <?php selected(get_option('wpoa_logout_redirect'), 'custom_url'); ?>>Custom URL</option>
					</select>
					<?php wp_dropdown_pages(array("id" => "wpoa_logout_redirect_page", "name" => "wpoa_logout_redirect_page", "selected" => get_option('wpoa_logout_redirect_page'))); ?>
					<input type="text" name="wpoa_logout_redirect_url" value="<?php echo get_option('wpoa_logout_redirect_url'); ?>" style="display:none;" />
					<p class="tip-message">Specifies where to redirect a user after they log out.</p>
				</td>
				</tr>
				
				<tr valign='top'>
				<th scope='row'>Automatically logout inactive users: <a href="#" class="tip-button">[?]</a></th>
				<td>
					<select name='wpoa_logout_inactive_users'>
						<option value='0' <?php selected(get_option('wpoa_logout_inactive_users'), '0'); ?>>Never</option>
						<option value='1' <?php selected(get_option('wpoa_logout_inactive_users'), '1'); ?>>After 1 minute</option>
						<option value='5' <?php selected(get_option('wpoa_logout_inactive_users'), '5'); ?>>After 5 minutes</option>
						<option value='15' <?php selected(get_option('wpoa_logout_inactive_users'), '15'); ?>>After 15 minutes</option>
						<option value='30' <?php selected(get_option('wpoa_logout_inactive_users'), '30'); ?>>After 30 minutes</option>
						<option value='60' <?php selected(get_option('wpoa_logout_inactive_users'), '60'); ?>>After 1 hour</option>
						<option value='120' <?php selected(get_option('wpoa_logout_inactive_users'), '120'); ?>>After 2 hours</option>
						<option value='240' <?php selected(get_option('wpoa_logout_inactive_users'), '240'); ?>>After 4 hours</option>
					</select>
					<p class="tip-message">Specifies whether to log out users automatically after a period of inactivity.</p>
					<p class="tip-message tip-warning"><strong>Warning:</strong> When a user logs out of WordPress, they will remain logged into their third-party provider until they close their browser. Logging out of WordPress DOES NOT log you out of Google, Facebook, LinkedIn, etc...</p>
				</td>
				</tr>
			</table> <!-- .form-table -->
			<?php submit_button('Save all settings'); ?>
			</div> <!-- .form-padding -->
			</div> <!-- .wpoa-settings-section -->
			<!-- END General Settings section -->
			
			<!-- START Login Page & Form Customization section -->
			<div class="wpoa-settings-section">
			<h3 id="bookmark-login-page-form-customization">Login Forms</h3>
			<div class='form-padding'>
			<table class='form-table'>
				
				<tr valign='top'>
				<th colspan="2">
					<h4>Default Login Form / Page / Popup</h4>
				</th>
				</td>
				
				<tr valign='top'>
				<th scope='row'>Hide the WordPress login form: <a href="#" class="tip-button">[?]</a></th>
				<td>
					<input type='checkbox' name='wpoa_hide_wordpress_login_form' value='1' <?php checked(get_option('wpoa_hide_wordpress_login_form') == 1); ?> />
					<p class="tip-message">Use this to hide the WordPress username/password login form that is shown by default on the Login Screen and Login Popup.</p>
					<p class="tip-message tip-warning"><strong>Warning: </strong>Hiding the WordPress login form may prevent you from being able to login. If you normally rely on this method, DO NOT enable this setting. Furthermore, please make sure your login provider(s) are active and working BEFORE enabling this setting.</p>
				</td>
				</tr>
				
				<tr valign='top'>
				<th scope='row'>Logo links to site: <a href="#" class="tip-button">[?]</a></th>
				<td>
					<input type='checkbox' name='wpoa_logo_links_to_site' value='1' <?php checked(get_option('wpoa_logo_links_to_site') == 1); ?> />
					<p class="tip-message">Forces the logo image on the login form to link to your site instead of WordPress.org.</p>
				</td>
				</tr>
				
				<tr valign='top'>
				<th scope='row'>Logo image: <a href="#" class="tip-button">[?]</a></th>
				<td>
					<p>
					<input id='wpoa_logo_image' type='text' size='' name='wpoa_logo_image' value="<?php echo get_option('wpoa_logo_image'); ?>" />
					<input id='wpoa_logo_image_button' type='button' class='button' value='Select' />
					</p>
					<p class="tip-message">Changes the default WordPress logo on the login form to an image of your choice. You may select an image from the Media Library, or specify a custom URL.</p>
				</td>
				</tr>
				
				<tr valign='top'>
				<th scope='row'>Background image: <a href="#" class="tip-button">[?]</a></th>
				<td>
					<p>
					<input id='wpoa_bg_image' type='text' size='' name='wpoa_bg_image' value="<?php echo get_option('wpoa_bg_image'); ?>" />
					<input id='wpoa_bg_image_button' type='button' class='button' value='Select' />
					</p>
					<p class="tip-message">Changes the background on the login form to an image of your choice. You may select an image from the Media Library, or specify a custom URL.</p>
				</td>
				</tr>
				
				<tr valign='top'>
				<th colspan="2">
					<h4>Custom Login Forms</h4>
				</th>
				</td>
			
				<tr valign='top'>
				<th scope='row'>Custom form to show on the login screen: <a href="#" class="tip-button">[?]</a></th>
				<td>
					<?php echo WPOA::wpoa_login_form_designs_selector('wpoa-login-form-show-login-screen'); ?>
					<p class="tip-message">Create or manage these login form designs in the CUSTOM LOGIN FORM DESIGNS section.</p>
				</td>
				</tr>
				
				<tr valign='top'>
				<th scope='row'>Custom form to show on the user's profile page: <a href="#" class="tip-button">[?]</a></th>
				<td>
					<?php echo WPOA::wpoa_login_form_designs_selector('wpoa-login-form-show-profile-page'); ?>
					<p class="tip-message">Create or manage these login form designs in the CUSTOM LOGIN FORM DESIGNS section.</p>
				</td>
				</tr>
				
				<tr valign='top'>
				<th scope='row'>Custom form to show in the comments section: <a href="#" class="tip-button">[?]</a></th>
				<td>
					<?php echo WPOA::wpoa_login_form_designs_selector('wpoa-login-form-show-comments-section'); ?>
					<p class="tip-message">Create or manage these login form designs in the CUSTOM LOGIN FORM DESIGNS section.</p>
				</td>
				</tr>
			</table> <!-- .form-table -->
			<?php submit_button('Save all settings'); ?>
			</div> <!-- .form-padding -->
			</div> <!-- .wpoa-settings-section -->
			<!-- END Login Page & Form Customization section -->

			<!-- START Custom Login Form Designs section -->
			<div class="wpoa-settings-section">
			<h3 id="bookmark-login-buttons-customization">Custom Login Form Designs</h3>
			<div class='form-padding'>
			<p>You may create multiple login form <strong><em>designs</em></strong> and use them throughout your site. A design is essentially a re-usable <em>shortcode preset</em>. Instead of writing out the login form shortcode ad-hoc each time you want to use it, you can build a design here, save it, and then specify that design in the shortcode's <em>design</em> attribute. For example: <pre><code>[wpoa_login_form design='CustomDesign1']</code></pre></p>
			<table class='form-table'>
				<tr valign='top'>
				<th scope='row'>Design: <a href="#" class="tip-button">[?]</a></th>
				<td>
					<?php echo WPOA::wpoa_login_form_designs_selector('wpoa-login-form-design', true); ?>
					<p>
					<input type="button" id="wpoa-login-form-new" class="button" value="New">
					<input type="button" id="wpoa-login-form-edit" class="button" value="Edit">
					<input type="button" id="wpoa-login-form-delete" class="button" value="Delete">
					</p>
					<p class="tip-message">Here you may create a new design, select an existing design to edit, or delete an existing design.</p>
					<p class="tip-message tip-info"><strong>Tip: </strong>Make sure to click the <em>Save all settings</em> button after making changes here.</p>
				</td>
				</tr>
			</table> <!-- .form-table -->
			
			<table class="form-table" id="wpoa-login-form-design-form">
				<tr valign='top'>
				<th colspan="2">
					<h4>Edit Design</h4>
				</th>
				</td>
				
				<tr valign='top'>
				<th scope='row'>Design name: <a href="#" class="tip-button">[?]</a></th>
				<td>
					<input id='wpoa-login-form-design-name' type='text' size='36' name='wpoa_login_form_design_name' value="" />
					<p class="tip-message">Sets the name to use for this design.</p>
				</td>
				</tr>
				
				<tr valign='top'>
				<th scope='row'>Icon set: <a href="#" class="tip-button">[?]</a></th>
				<td>
					<select name='wpoa_login_form_icon_set'>
						<option value='none'>None</option>
						<option value='hex'>Hex</option>
					</select>
					<p class="tip-message">Specifies which icon set to use for displaying provider icons on the login buttons.</p>
				</td>
				</tr>
				
				<tr valign='top'>
				<th scope='row'>Show login buttons: <a href="#" class="tip-button">[?]</a></th>
				<td>
					<select name='wpoa_login_form_show_login'>
						<option value='always'>Always</option>
						<option value='conditional'>Conditional</option>
						<option value='never'>Never</option>
					</select>
					<p class="tip-message">Determines when the login buttons should be shown.</p>
				</td>
				</tr>
				
				<tr valign='top'>
				<th scope='row'>Show logout button: <a href="#" class="tip-button">[?]</a></th>
				<td>
					<select name='wpoa_login_form_show_logout'>
						<option value='always'>Always</option>
						<option value='conditional'>Conditional</option>
						<option value='never'>Never</option>
					</select>
					<p class="tip-message">Determines when the logout button should be shown.</p>
				</td>
				</tr>
				
				<tr valign='top'>
				<th scope='row'>Layout: <a href="#" class="tip-button">[?]</a></th>
				<td>
					<select name='wpoa_login_form_layout'>
						<option value='links-row'>Links Row</option>
						<option value='links-column'>Links Column</option>
						<option value='buttons-row'>Buttons Row</option>
						<option value='buttons-column'>Buttons Column</option>
					</select>
					<p class="tip-message">Sets vertical or horizontal layout for the buttons.</p>
				</td>
				</tr>
				
				<tr valign='top'>
				<th scope='row'>Login button prefix: <a href="#" class="tip-button">[?]</a></th>
				<td>
					<input id='wpoa_login_form_button_prefix' type='text' size='36' name='wpoa_login_form_button_prefix' value="" />
					<p class="tip-message">Sets the text prefix to be displayed on the social login buttons.</p>
				</td>
				</tr>
			
				<tr valign='top'>
				<th scope='row'>Logged out title: <a href="#" class="tip-button">[?]</a></th>
				<td>
					<input id='wpoa_login_form_logged_out_title' type='text' size='36' name='wpoa_login_form_logged_out_title' value="" />
					<p class="tip-message">Sets the text to be displayed above the login form for logged out users.</p>
				</td>
				</tr>
				
				<tr valign='top'>
				<th scope='row'>Logged in title: <a href="#" class="tip-button">[?]</a></th>
				<td>
					<input id='wpoa_login_form_logged_in_title' type='text' size='36' name='wpoa_login_form_logged_in_title' value="" />
					<p class="tip-message">Sets the text to be displayed above the login form for logged in users.</p>
				</td>
				</tr>
				
				<tr valign='top'>
				<th scope='row'>Logging in title: <a href="#" class="tip-button">[?]</a></th>
				<td>
					<input id='wpoa_login_form_logging_in_title' type='text' size='36' name='wpoa_login_form_logging_in_title' value="" />
					<p class="tip-message">Sets the text to be displayed above the login form for users who are logging in.</p>
				</td>
				</tr>
				
				<tr valign='top'>
				<th scope='row'>Logging out title: <a href="#" class="tip-button">[?]</a></th>
				<td>
					<input id='wpoa_login_form_logging_out_title' type='text' size='36' name='wpoa_login_form_logging_out_title' value="" />
					<p class="tip-message">Sets the text to be displayed above the login form for users who are logging out.</p>
				</td>
				</tr>
				
				<?php /*
				<tr valign='top'>
				<th scope='row'>Show provider icons: <a href="#" class="tip-button">[?]</a></th>
				<td>
					<input type='checkbox' name='wpoa_login_form_show_icons' value='1' />
					<p class="tip-message">Specifies whether or not to show provider icons on the login buttons.</p>
				</td>
				</tr>
				
				<tr valign='top'>
				<th scope='row'>Show prefix text: <a href="#" class="tip-button">[?]</a></th>
				<td>
					<input type='checkbox' name='wpoa_login_buttons_show_prefix' value='1' />
					<p class="tip-message">Specifies whether or not to show provider icons on the login buttons.</p>
				</td>
				</tr>
				
				<tr valign='top'>
				<th scope='row'>Show provider names: <a href="#" class="tip-button">[?]</a></th>
				<td>
					<input type='checkbox' name='wpoa_login_buttons_show_names' value='1' />
					<p class="tip-message">Specifies whether or not to show provider names on the login buttons.</p>
				</td>
				</tr>
				
				<tr valign='top'>
				<th scope='row'>Background style: <a href="#" class="tip-button">[?]</a></th>
				<td>
					<input type='text' name='wpoa_login_buttons_background_style' value='<?php get_option('wpoa_login_buttons_background_style') == 1; ?>' />
					<p class="tip-message">Specifies the background style (CSS) for the buttons.</p>
				</td>
				</tr>
				
				<tr valign='top'>
				<th scope='row'>Background style (hover): <a href="#" class="tip-button">[?]</a></th>
				<td>
					<input type='text' name='wpoa_login_buttons_background_style_hover' value='<?php get_option('wpoa_login_buttons_background_style_hover') == 1; ?>' />
					<p class="tip-message">Specifies the background style (CSS) on hover for the buttons.</p>
				</td>
				</tr>
				
				<tr valign='top'>
				<th scope='row'>Icon size: <a href="#" class="tip-button">[?]</a></th>
				<td>
					<select name='wpoa_login_buttons_icon_size'>
						<option value='auto' <?php selected(get_option('wpoa_login_buttons_icon_size'), 'auto'); ?>>Auto</option>
						<option value='16' <?php selected(get_option('wpoa_login_buttons_icon_size'), '16'); ?>>16x16</option>
						<option value='32' <?php selected(get_option('wpoa_login_buttons_icon_size'), '32'); ?>>32x32</option>
						<option value='64' <?php selected(get_option('wpoa_login_buttons_icon_size'), '64'); ?>>64x64</option>
						<option value='custom' <?php selected(get_option('wpoa_login_buttons_icon_size'), 'custom'); ?>>Custom</option>
					</select>
					<input type="text" name="wpoa_login_buttons_icon_size" value="<?php echo get_option('wpoa_login_buttons_icon_size'); ?>" />
					<p class="tip-message">The size of the provider icons.</p>
				</td>
				</tr>
				*/ ?>
				
				<tr valign='top' id='wpoa-login-form-actions'>
				<th scope='row'>
					<input type="button" id="wpoa-login-form-ok" name="wpoa_login_form_ok" class="button" value="OK">
					<input type="button" id="wpoa-login-form-cancel" name="wpoa_login_form_cancel" class="button" value="Cancel">
				</th>
				<td>
					
				</td>
				</tr>
			</table> <!-- .form-table -->
			<?php submit_button('Save all settings'); ?>
			</div> <!-- .form-padding -->
			</div> <!-- .wpoa-settings-section -->
			<!-- END Login Buttons section -->
			
			<!-- START User Registration section -->
			<div class="wpoa-settings-section">
			<h3 id="bookmark-user-registration">User Registration</h3>
			<div class='form-padding'>
			<table class='form-table'>
				<tr valign='top'>
				<th scope='row'>Suppress default welcome email: <a href="#" class="tip-button">[?]</a></th>
				<td>
					<input type='checkbox' name='wpoa_suppress_welcome_email' value='1' <?php checked(get_option('wpoa_suppress_welcome_email') == 1); ?> />
					<p class="tip-message">Prevents WordPress from sending an email to newly registered users by default, which contains their username and password.</p>
				</td>
				</tr>
				
				<tr valign='top'>
				<th scope='row'>Assign new users to the following role: <a href="#" class="tip-button">[?]</a></th>
				<td>
					<select name="wpoa_new_user_role"><?php wp_dropdown_roles(get_option('wpoa_new_user_role')); ?></select>
					<p class="tip-message">Specifies what user role will be assigned to newly registered users.</p>
				</td>
				</tr>
			</table> <!-- .form-table -->
			<?php submit_button('Save all settings'); ?>
			</div> <!-- .form-padding -->
			</div> <!-- .wpoa-settings-section -->
			<!-- END User Registration section -->
			
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
					<select name='wpoa_http_util'>
						<option value='curl' <?php selected(get_option('wpoa_http_util'), 'curl'); ?>>cURL</option>
						<option value='stream-context' <?php selected(get_option('wpoa_http_util'), 'stream-context'); ?>>Stream Context</option>
					</select>
					<p class="tip-message">The method used by the web server for performing HTTP requests to the third-party providers. Most servers support cURL, but some servers may require Stream Context instead.</p>
				</td>
				</tr>
				
				<tr valign='top'>
				<th scope='row'>Verify Peer/Host SSL Certificates: <a href="#" class="tip-button">[?]</a></th>
				<td>
					<input type='checkbox' name='wpoa_http_util_verify_ssl' value='1' <?php checked(get_option('wpoa_http_util_verify_ssl') == 1); ?> />
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
					<input type='checkbox' name='wpoa_restore_default_settings' value='1' <?php checked(get_option('wpoa_restore_default_settings') == 1); ?> />
					<p class="tip-message"><strong>Instructions:</strong> Check the box above, click the Save all settings button, and the settings will be restored to default.</p>
					<p class="tip-message tip-warning"><strong>Warning:</strong> This will restore the default settings, erasing any API keys/secrets that you may have entered above.</p>
				</td>
				</tr>		
				<tr valign='top'>
				<th scope='row'>Delete settings on uninstall: <a href="#" class="tip-button">[?]</a></th>
				<td>
					<input type='checkbox' name='wpoa_delete_settings_on_uninstall' value='1' <?php checked(get_option('wpoa_delete_settings_on_uninstall') == 1); ?> />
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
	</div>
	<!-- END Settings Column 1 -->
	</div> <!-- #wpoa-settings-body -->
	<!-- END Settings Body -->
</div> <!-- .wrap .wpoa-settings -->