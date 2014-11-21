// after the document has loaded, we hook up our events and initialize any other js functionality:
jQuery(document).ready(function() {
	wpoa.init();
});

// namespace the wpoa functions to prevent global conflicts, using the 'immediately invoked function expression' pattern:
;(function ( wpoa, undefined ) {

    // <private properties>
	
	var wp_media_dialog_field; // field to populate after the admin selects an image using the wordpress media dialog
	
    // <public methods and properties>
	
	// init the client-side wpoa functionality:
	wpoa.init = function() {
		// store the client's GMT offset (timezone) for converting server time into local time on a per-client basis (this makes the time at which a provider was linked more accurate to the specific user):
		d = new Date; 
		gmtoffset = d.getTimezoneOffset() / 60;
		document.cookie = 'gmtoffset=' + gmtoffset;
		// settings page functionality:
		jQuery(".wpoa-settings input, .wpoa-settings select").focus(function(e) {
			var tip_warning = jQuery(this).parents("tr").find(".tip-warning");
			if (tip_warning.length > 0) {
				tip_warning.fadeIn();
				jQuery(this).parents("tr").find(".tip-message").fadeIn();
			}
		});
		jQuery(".wpoa-settings h3").click(function(e) {
			jQuery(this).parent().find(".form-padding").slideToggle();
		});
		jQuery("#wpoa-settings-sections-on").click(function(e) {
			e.preventDefault();
			jQuery(".wpoa-settings h3").parent().find(".form-padding").slideDown();
		});
		jQuery("#wpoa-settings-sections-off").click(function(e) {
			e.preventDefault();
			jQuery(".wpoa-settings h3").parent().find(".form-padding").slideUp();
		});
		jQuery("#wpoa-settings-tips-on").click(function(e) {
			e.preventDefault();
			jQuery(".tip-message").fadeIn();
		});
		jQuery("#wpoa-settings-tips-off").click(function(e) {
			e.preventDefault();
			jQuery(".tip-message").fadeOut();
		});
		jQuery(".tip-button").click(function(e) {
			e.preventDefault();
			console.log(jQuery(this).parents("tr").find(".tip-message").get(0));
			jQuery(this).parents("tr").find(".tip-message").fadeToggle();
		});
		jQuery("[name=wpoa_login_redirect]").change(function() {
			jQuery("[name=wpoa_login_redirect_url]").hide();
			jQuery("[name=wpoa_login_redirect_page]").hide();
			var val = jQuery(this).val();
			if (val == "specific_page") {
				jQuery("[name=wpoa_login_redirect_page]").show();
			}
			else if (val == "custom_url") {
				jQuery("[name=wpoa_login_redirect_url]").show();
			}
		});
		jQuery("[name=wpoa_login_redirect]").change();
		jQuery("[name=wpoa_logout_redirect]").change(function() {
			jQuery("[name=wpoa_logout_redirect_url]").hide();
			jQuery("[name=wpoa_logout_redirect_page]").hide();
			var val = jQuery(this).val();
			if (val == "specific_page") {
				jQuery("[name=wpoa_logout_redirect_page]").show();
			}
			else if (val == "custom_url") {
				jQuery("[name=wpoa_logout_redirect_url]").show();
			}
		});
		jQuery("[name=wpoa_logout_redirect]").change();
		// show the wordpress media dialog for selecting a logo image:
		jQuery('#wpoa_logo_image_button').click(function(e) {
			e.preventDefault();
			wp_media_dialog_field = jQuery('#wpoa_logo_image');
			wpoa.selectMedia();
		});
		// show the wordpress media dialog for selecting a bg image:
		jQuery('#wpoa_bg_image_button').click(function(e) {
			e.preventDefault();
			wp_media_dialog_field = jQuery('#wpoa_bg_image');
			wpoa.selectMedia();
		});
		// attach unlink button click events:
		jQuery(".wpoa-unlink-account").click(function(event) {
			event.preventDefault();
			var btn = jQuery(this);
			var wpoa_identity_row = btn.data("wpoa-identity-row");
			//jQuery(this).replaceWith("<span>Please wait...</span>");
			btn.hide();
			btn.after("<span> Please wait...</span>");
			var post_data = {
				action: "wpoa_unlink_account",
				wpoa_identity_row: wpoa_identity_row,
			}
			jQuery.ajax({
				type: "POST",
				url: wpoa_cvars.ajaxurl,
				data: post_data,
				success: function(json_response) {
					var oresponse = JSON.parse(json_response);
					if (oresponse["result"] == 1) {
						btn.parent().fadeOut(1000, function() {
							btn.parent().remove();
						});
					}
				}
			});
		});
		// attach provider login/logout button click events and login effects:
		jQuery(".wpoa-login-button").click(function(event) {
			event.preventDefault();
			window.location = jQuery(this).attr("href");
			// fade out the WordPress login form:
			jQuery("#login #loginform").fadeOut();	// the WordPress username/password form.
			jQuery("#login #nav").fadeOut(); // the WordPress "Forgot my password" link.
			jQuery("#login #backtoblog").fadeOut(); // the WordPress "<- Back to blog" link.
			jQuery(".message").fadeOut(); // the WordPress messages (e.g. "You are now logged out.").
			// toggle the loading style:
			jQuery(".wpoa-login-form .wpoa-login-button").not(this).addClass("loading-other");
			jQuery(".wpoa-login-form .wpoa-logout-button").addClass("loading-other");
			jQuery(this).addClass("loading");
			var logging_in_title = jQuery(this).parents(".wpoa-login-form").data("logging-in-title");
			jQuery(".wpoa-login-form #wpoa-title").text(logging_in_title);
			//return false;
		});
		jQuery(".wpoa-logout-button").click(function(event) {
			// fade out the login form:
			jQuery("#login #loginform").fadeOut();
			jQuery("#login #nav").fadeOut();
			jQuery("#login #backtoblog").fadeOut();
			// toggle the loading style:
			jQuery(this).addClass("loading");
			jQuery(".wpoa-login-form .wpoa-logout-button").not(this).addClass("loading-other");
			jQuery(".wpoa-login-form .wpoa-login-button").addClass("loading-other");
			var logging_out_title = jQuery(this).parents(".wpoa-login-form").data("logging-out-title");
			jQuery(".wpoa-login-form #wpoa-title").text(logging_out_title);
			//return false;
		});
		// hide the login form if the admin enabled this setting:
		if (wpoa_cvars.show_login_form && wpoa_cvars.show_login_form.indexOf("default") < 0) {
			jQuery("#login #loginform").hide();
			jQuery("#login #nav").hide();
			jQuery("#login #backtoblog").hide();
		}
		// show or log the client's login result which includes success or error messages:
		var msg = jQuery("#wpoa-result").html();
		//var msg = wpoa_cvars.login_message; // TODO: this method doesn't work that well since we don't clear the session variable at the server...
		if (msg) {
			if (wpoa_cvars.show_login_messages) {
				// notify the client of the login result with a visible, short-lived message at the top of the screen:
				wpoa.notify(msg);
			}
			else {
				// log the message to the dev console; useful for client support, troubleshooting and debugging if the admin has turned off the visible messages:
				console.log(msg);
			}
		}
		// show custom logo and bg if the admin enabled this setting:
		if (document.URL.indexOf("wp-login") >= 0) {
			if (wpoa_cvars.logo_image) {
				jQuery(".login h1 a").css("background-image", "url(" + wpoa_cvars.logo_image + ")");
			}
			if (wpoa_cvars.bg_image) {
				jQuery("body").css("background-image", "url(" + wpoa_cvars.bg_image + ")");
				jQuery("body").css("background-size", "cover");
			}
		}
	}
	
	// shows the associated tip message for a setting:
	wpoa.showTip = function(id) {
		jQuery(id).parents("tr").find(".tip-message").fadeIn();
	}
	
	// shows the default wordpress media dialog for selecting or uploading an image:
	wpoa.selectMedia = function() {
		var custom_uploader;
		if (custom_uploader) {
			custom_uploader.open();
			return;
		}
		custom_uploader = wp.media.frames.file_frame = wp.media({
			title: 'Choose Image',
			button: {
				text: 'Choose Image'
			},
			multiple: false
		});
		custom_uploader.on('select', function() {
			attachment = custom_uploader.state().get('selection').first().toJSON();
			wp_media_dialog_field.val(attachment.url);
		});
		custom_uploader.open();
	}

	// displays a short-lived notification message at the top of the screen:
	wpoa.notify = function(msg) {
		jQuery(".wpoa-login-message").remove();
		var h = "";
		h += "<div class='wpoa-login-message'><span>" + msg + "</span></div>";
		jQuery("body").prepend(h);
		jQuery(".wpoa-login-message").fadeOut(5000);
	}
	
	// logout:
	wpoa.processLogout = function(callback) {
		var data = {
			'action': 'wpoa_logout',
		};
		jQuery.ajax({
			url: wpoa_cvars.ajaxurl,
			data: data,
			success: function(json) {
				window.location = wpoa_cvars.url + "/";
			}
		});
	}
	
    // <private methods>
	
    /* e.g.
	function say(msg) {
        console.log(msg);
    };
	*/
	
    // check to evaluate whether 'wpoa' exists in the global namespace - if not, assign window.wpoa an object literal:
})(window.wpoa = window.wpoa || {});