WP-OAuth
========

A WordPress plugin that allows users to login or register by authenticating with an existing Google, Facebook or LinkedIn account via OAuth 2.0. Easily drops into new or existing sites, integrates with existing users.

Functions in a similar way to the StackExchange/StackOverflow login system.

![wpoa](http://files.glassocean.net/github/wpoa1.jpg)

Features
--------
* Fully integrates with WordPress. Drops into existing WordPress sites and integrates with existing WordPress users.
* Supports third-party authentication with Google, Facebook and LinkedIn via OAuth 2.0. Providers can be enabled or disabled.
* Authenticated users are automatically registered and/or logged into their WordPress user accounts.
* Users can manage their third-party login providers via the standard "Your Profile" WordPress page.
* Pushes the login result message into the DOM which can be extracted via Javascript for notifying the user. Avoids polluting the response url. This feature can also be disabled.
* Supports WordPress Multisite.
* Supports cURL or stream context for the authentication flow.
* The authentication flow has been rigorously tested and debugged for solid error handling per several OAuth2 documentations and other resources.
* Doesn't require third-party OAuth libraries; everything is built into the plugin first-class. Previously, WP-OpenLogin required LightOpenID and Facebook-PHP-SDK, but this is no longer necessary. Keeps the bloat low and the performance high.

Quick Start
-----------
1. Download and install the WP-OAuth plugin.
2. Setup your desired authentication providers' API key/secret in the WordPress backend under Settings > WP-OAuth.
3. Enable the included login buttons, or add login buttons anywhere to your site/theme with the included shortcodes.

Roadmap
-------
* Login with Github; Login with Reddit.

History
-------
This project is a continuation of [WP-OpenLogin](http://github.com/perrybutler/wp-openlogin) which was originally developed with OpenID in mind. OAuth 2.0 is now the standard.
