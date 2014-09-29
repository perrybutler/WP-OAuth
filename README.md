WP-OAuth
========

A WordPress plugin that allows users to login or register by authenticating with an existing Google, Facebook or LinkedIn account via OAuth 2.0. Easily drops into new or existing sites, integrates with existing users.

Functions in a similar way to the StackExchange/StackOverflow login system.

Features
--------
* Fully integrates with WordPress.
* Supports third-party authentication with Google, Facebook and LinkedIn via OAuth 2.0.
* Authenticated users are automatically registered and/or logged into their WordPress user accounts.
* Users can manage their third-party login providers via the My Profile page.
* Pushes the login result message into the DOM which can be extracted via Javascript for notifying the user. Avoids polluting the response url.
* Supports WordPress Multisite.
* Supports cURL or stream context for the authentication flow.

Quick Start
-----------
1. Download and install the WP-OAuth plugin.
2. Setup your desired authentication providers' API key/secret in the WordPress backend under Settings > WP-OAuth.
3. Enable the included login buttons, or add login buttons to your site/theme with a shortcode.

History
-------
This project is a continuation of [WP-OpenLogin](http://github.com/perrybutler/wp-openlogin) which was originally developed with OpenID in mind. OAuth 2.0 is now the standard.
