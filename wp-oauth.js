var wp_media_dialog_field; // field to populate after the admin selects an image using the wordpress media dialog

// store the client's GMT offset (timezone) for converting server time into local time on a per-client basis (this makes the time at which a provider was linked more accurate):
d = new Date; 
gmtoffset = d.getTimezoneOffset() / 60;
document.cookie = 'gmtoffset=' + gmtoffset;

// after the document has loaded, we hook up our events and initialize any other js functionality:
jQuery(document).ready(function() {
	// show the wordpress media dialog for selecting a logo image:
	jQuery('#wpoa_logo_image_button').click(function(e) {
		e.preventDefault();
		wp_media_dialog_field = jQuery('#wpoa_logo_image');
		selectMedia();
	});
	// show the wordpress media dialog for selecting a bg image:
	jQuery('#wpoa_bg_image_button').click(function(e) {
		e.preventDefault();
		wp_media_dialog_field = jQuery('#wpoa_bg_image');
		selectMedia();
	});
	// attach unlink button click events:
	jQuery(".wpoa-unlink-account").click(function(event) {
		event.preventDefault();
		var btn = jQuery(this);
		var wpoa_identity_row = btn.data("wpoa-identity-row");
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
	// attach provider login button click events and login animation:
	jQuery(".wpoa-login-button").click(function(event) {
		jQuery("#login #loginform").fadeOut();
		jQuery("#login #nav").fadeOut();
		jQuery("#login #backtoblog").fadeOut();
		jQuery("#login .wpoa-login-button").not(this).addClass("loading-other");
		//jQuery("#login .wpoa-login-button").not(this).fadeOut();
		jQuery(this).addClass("loading");
		jQuery(this).text("Logging in, please wait...");
	});
	// hide the login form if the admin enabled this setting:
	if (wpoa_cvars.hide_login_form) {
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
			notify(msg);
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
});

// shows the default wordpress media dialog for selecting or uploading an image:
function selectMedia() {
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
function notify(msg) {
	jQuery(".wpoa-login-message").remove();
	var h = "";
	h += "<div class='wpoa-login-message'><span>" + msg + "</span></div>";
	jQuery("body").prepend(h);
	jQuery(".wpoa-login-message").fadeOut(5000);
}

function loginGoogle() {
	window.location = wpoa_cvars.url + "?connect=google";
}

function loginFacebook() {
	window.location = wpoa_cvars.url + "?connect=facebook";
}

function loginLinkedIn() {
	window.location = wpoa_cvars.url + "?connect=linkedin";
}

function loginGithub() {
	window.location = wpoa_cvars.url + "?connect=github";
}

function loginReddit() {
	window.location = wpoa_cvars.url + "?connect=reddit";
}

function loginWindowsLive() {
	window.location = wpoa_cvars.url + "?connect=windowslive";
}

function processLogout(callback) {
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