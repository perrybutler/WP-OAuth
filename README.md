WP-OAuth
========

A WordPress plugin that allows users to login or register by authenticating with an existing Google, Facebook or LinkedIn account via OAuth 2.0. Easily drops into new or existing sites, integrates with existing users.

Functions in a similar way to the StackExchange/StackOverflow login system.

*The Settings page is where you'll configure the plugin:*

![wpoa](http://files.glassocean.net/github/wpoa1.jpg)

*The Your Profile page is where you'll manage your linked accounts:*

![wpoa](http://files.glassocean.net/github/wpoa2.jpg)

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

FAQ
---
*How is WP-OAuth different than other "OAuth" plugins such as OAuth2 Complete or OAuth Provider?* These turn your site into an OAuth2 *provider* which is probably not what you want. Google, Facebook and LinkedIn are providers and our goal is to be a *consumer* which lets us delegate user authentication to those providers.

*How is WP-OAuth different than other "Connect" plugins such as Google Apps Login or WordPress Social Connect?* These are similar alternatives.

*How is WP-OAuth different than OpenID, OpenID Connect and OpenID Authorization 2.0?* OpenID functions on the assumption that each person will have their own unique identity that can be validated by a third party. However, most people don't have an OpenID and probably don't care for one, which makes OAuth a better choice for non-enterprise login systems because most people *do* have a Google, Facebook or LinkedIn account and should already be familiar with the authentication process of those providers.

*How is WP-OAuth different than Single Sign On?*

Roadmap
-------
* Login with Github; Login with Reddit.

History
-------
This project is a continuation of [WP-OpenLogin](http://github.com/perrybutler/wp-openlogin) which was originally developed with OpenID in mind. OAuth 2.0 is now the standard.
