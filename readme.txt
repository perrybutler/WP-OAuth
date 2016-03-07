=== WP-OAuth ===
Contributors: hectavex
Donate link: http://glassocean.net
Tags: login, membership, users, registration, oauth, social, social networking, community, security, connect with, authentication, authorization
Requires at least: 4.0
Tested up to: 4.1
Stable tag: 0.4.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Allows users to login or register by authenticating with an existing Google, Facebook, LinkedIn, Github, Reddit (and more!) account via OAuth 2.0.

== Description ==

New providers and features are being added regularly! See the [Changelog](https://wordpress.org/plugins/wp-oauth/changelog/) for details.

As a reminder, WP-OAuth is a still a pre-v1.0 release, which means some features may not work as intended or might change over time. Please report any bugs/issues to the support forum so they can be fixed as soon as possible. Thank you!

= Facts =

* With so many sites offering membership now, users may suffer registration fatigue and forget their passwords, or use the same password for several sites, increasing their security risk.
* [56% of consumers](http://www1.janrain.com/rs/janrain/images/Industry-Research-Value-of-Social-Login-2013.pdf) have at least one major social network profile they could be using for membership, registration and login purposes across multiple websites without having to maintain multiple accounts and passwords (2013).
* [40% of consumers](http://www.marketingprofs.com/charts/2012/7060/social-media-users-prefer-social-login-over-traditional) currently utilize social login (2012).

= Features =

* Free, unlimited, unbranded and white-labeled from the beginning. No upselling, no payment plans, no SaaS, no proxy authentication! WP-OAuth communicates from your WordPress site *directly* with the *trusted* third-party login providers.
* WP-OAuth collects and stores ONLY the user's *OAuth identity* in the WordPress database for future logins; no other user information is collected or stored.
* Fully integrates with WordPress. Drops into existing WordPress sites and integrates with existing WordPress users.
* Supports third-party authentication with Google, Facebook, LinkedIn, Github, Reddit, Windows Live, PayPal and Instagram via OAuth 2.0 / OpenID Connect. Providers can be enabled or disabled.
* Automatic user registration if *Anyone can register* has been enabled under Settings > General > Membership.
* Users can manage their third-party login providers via the standard "Your Profile" WordPress page. They may link more providers, or unlink existing providers.
* Displays a message via Javascript to the user when they login or logout. This feature can also be disabled.
* Add a custom login form to any post or page using the [wpoa_login_form] shortcode. Choose from 4 different layouts. See [Installation](https://wordpress.org/plugins/wp-oauth/installation/) for details.
* Customize the default login screen with a logo or background. Point the logo URL to your home page instead of WordPress.org. Hide the default username/password login form if you want. Automatically include login buttons for any providers that are enabled.
* Supports cURL or stream context for the authentication flow, meaning the plugin should be compatible with a wide range of PHP servers.
* The authentication flow was adapted from code samples provided by Google, Facebook and LinkedIn. It has been updated, rigorously tested and debugged for solid error handling. Provider implementations share much of the same code (very high code re-use) and the differences between the providers have been fully documented.
* The user experience and on-boarding process was inspired by StackExchange/StackOverflow login system.
* Extremely light-weight, optimized code base for high performance. Doesn't require third-party OAuth libraries; everything is built first-class into the plugin. Previously, WP-OpenLogin required LightOpenID and Facebook-PHP-SDK, but this is no longer necessary. Keeps the bloat low and the performance high. Tested with P3 Plugin Profiler, WP-OAuth's plugin overhead is around 0.001 seconds which is 6x less overhead than Akismet in the same run! That means there shouldn't be a performance hit.

= How to Contribute =

Visit the [GitHub development repository](https://github.com/perrybutler/WP-OAuth).

== Installation ==

*Note: Javascript is required for much of this plugin's functionality.*

= Quick Start =

1. Download and install the WP-OAuth plugin.
2. Setup your desired authentication providers' API key/secret in the WordPress backend under *Settings > WP-OAuth*.
3. Enable the custom login form via *Settings > WP-OAuth > Show login form*, or add it anywhere to your site with the [wpoa_login_form] shortcode.

= Shortcode - [wpoa_login_form] =

Add a custom login form to your site using the [wpoa_login_form] shortcode. For example:

    [wpoa_login_form layout="buttons-column" align="left"]
	
*Possible shortcode attributes:*

* **layout** - determines whether to display the login buttons as links or buttons, stacked vertically or lined up horizontally. Possible values: links-row, links-column, buttons-row, buttons-column
* **align** - sets the horizontal alignment of the custom form elements. Possible values: left, middle, right
* **show_login** - determines when the login buttons will be shown. Possible values: never, conditional, always
* **show_logout** - determines when the logout button will be shown. Possible values: never, conditional, always
* **logged_out_title** - sets the text to display above the custom login form when the user is logged out. Possible values: any text
* **logged_in_title** - sets the text to display above the custom login form when the user is logged in. Possible values: any text
* **logging_in_title** - sets the text to display above the custom login form when the user is logging ing. Possible values: any text
* **logging_out_title** - sets the text to display above the custom login form when the user is logging out. Possible values: any text
* **style** - sets the custom css style to apply to the custom login form. Possible values: any text
* **class** - sets the custom css class to apply to the custom login form. Possible values: any text

== Frequently Asked Questions ==

= Plugin Specific Questions =

= Is it compatible with WordPress MultiSite? =

Not yet, see the [Roadmap](https://github.com/perrybutler/WP-OAuth#roadmap) at our Github development repo for details.

= Is it compatible with BuddyPress? =

I haven't tried it yet, but if you do let us know! It's on the [Roadmap](https://github.com/perrybutler/WP-OAuth#roadmap) anyhow.

= How is WP-OAuth different than other "OAuth" plugins such as OAuth2 Complete or OAuth Provider? =

These turn your site into an OAuth2 *provider* which is probably not what you want. Google, Facebook and LinkedIn are providers and our goal is to be a *consumer* which lets us delegate user authentication to those providers.

= How is WP-OAuth different than other "Connect" plugins such as WordPress Social Connect, WordPress Social Login, Google Apps Login or WP Glogin? =

These are similar alternatives. Some of them may include or require the use of other third-party libraries, which may add bloat or become outdated. WP-OAuth is a complete solution with zero dependencies.

= OAuth Specific Questions =

= I don't get it...what's OAuth? Why does it matter? =

With WP-OAuth, people will have the ability of logging into your WordPress site using their existing Google, Facebook or LinkedIn account. Most people probably already have one of those accounts, and they probably don't want to register/manage yet another account. That's where WP-OAuth bridges the gap by offering a fast and secure alternative login method which might even boost your membership and user retention. Think about it...people like to use the same passwords everywhere; they might even use their same online banking password for logging into your WordPress site. You never know, and you don't want to be held responsible or liable for that.

= Okay then, how does OAuth work? =

Let's say a person chooses to log into your WordPress site with Google. First they click the Google button on your site and are immediately redirected to Google's login page. Right after logging into Google they will be prompted to grant your website permission to access some[1] of their Google user info. Right here, you are observing OAuth in its very basic form. So once they grant access, they are redirected back to your WordPress site, along with an OAuth identity provided by Google. This identity is unique for every Google user and it never changes, so we tie the Google user's identity to a WordPress user account. Now any time in the future when that Google user logs in with Google, we'll match the Google user's identity to an existing WordPress account and log them into your site automatically. If they don't have a WordPress account, WP-OAuth can register one for them automatically as well.

[1] We only request the minimum amount of user info from Google (and all other providers) that will give us the user's OAuth identity. During a request for user info, Google (and all other providers) may send additional information about the user, such as their full name or public email address. We have no way of turning that off, but it shouldn't matter because we discard this info immediately after extracting the user's OAuth identity. I'm not aware of any OAuth provider that lets us *only* ask for a user's OAuth identity, which is kind of a let down and makes me wonder why that is.

= Can I trust WP-OAuth with keeping sensitive user info private and secure? Can WP-OAuth or someone malicious steal a user's  password or hijack their account? If a site gets hacked where WP-OAuth is installed, will sensitive user info be at risk? =

WP-OAuth (and the official [OAuth2 spec](https://tools.ietf.org/html/rfc6749)) will never get access to a user's password from their third-party provider; instead they will be redirected to the third-party provider and then redirected back to your site with an identity. There are no exceptions, we don't need your password and we certainly don't want the responsibility of storing it securely! That is the wonderful nature of OAuth. When a user decides to authenticate with a third-party provider through WP-OAuth, we only ask for their basic user info - the minimum info required to obtain the user's OAuth identity. This is not to say WP-OAuth or the OAuth2 spec is fool-proof, as nothing truly is. It turns out that each provider has decided to implement their OAuth2 API slightly differently, which leaves a lot of room for human error when it comes to developers who build and implement OAuth clients.

What makes matters worse is that developers are forced to make unavoidable assumptions as to how an OAuth client should identify users. Some implementations will identify a user by their OAuth email address, while others identify a user by their OAuth id. Technically both camps are correct - the OAuth email and id are both unique so they can be used for identifying an individual user, but we must keep in mind that even though an email is unique, it can change over time. If you ask me, a "complete" identity should consist of the OAuth email AND id. Without the email, we have no method of reverse lookup, no way to contact the user if they need to recover their account. And without the id, we could lose track of the user when he/she changes their email address.

OAuth2 doesn't abide by strict standards, and that is what I believe to be the most troubling aspect of it, which the ex-lead developer of OAuth2 has [spoken openly about](http://hueniverse.com/2012/07/26/oauth-2-0-and-the-road-to-hell/). With all that said, OAuth2 is still viable and will remain prevalent in the web landscape for years to come. I think we just need stronger and stricter specifications so it is easier to implement correctly for both providers and clients. With WP-OAuth, we've taken the time to understand these gory details in the attempt to deliver a solid plugin and clean code base.

For more information, see:

* [Understanding OAuth - What Happens When You Log Into a Site With Google, Twitter or Facebook](http://lifehacker.com/5918086/understanding-oauth-what-happens-when-you-log-into-a-site-with-google-twitter-or-facebook).
* [Four Attacks on OAuth - How to Secure Your OAuth Implementation](http://www.sans.org/reading-room/whitepapers/application/attacks-oauth-secure-oauth-implementation-33644)

= How is OAuth different than OpenID, OpenID Connect and OpenID Authorization 2.0? =

OpenID functions on the assumption that each person will have their own unique identity that can be validated by a third party. However, most people don't have an OpenID and probably don't care for one, which makes OAuth a better choice for non-enterprise login systems because most people *do* have a Google, Facebook or LinkedIn account and should already be familiar with the authentication process of those providers. OpenID Connect is just OAuth 2.0 tailored for user authentication, and WP-OAuth aims to support providers that use either one.

= How is OAuth different than SAML or Single Sign-On? =

The latter two technologies are for enterprise-scale apps and environments where a single identity is used to gain access to any site or app within an enterprise environment, whether locally or remotely. Basically, the user signs in once and for the lifetime of their session they will have secure, authenticated access to all resources in the enterprise environment. This is not exactly what OAuth2 was designed for. OAuth2 lets us request information about a user from a trusted third-party, which we can assume to be authentic, allowing us to identify the user in some way and associate that user identity with a user account in our site or app. The main difference is that with OAuth2, each site or app that is requesting your user information *must be explicitly granted permission by you* in order to access it. This access can also be revoked *by you*. This gives users full control over their personal info and what sites or apps may use it. OAuth2 was designed with REST in mind and functions exclusively over the HTTP protocol, whereas SAML allows for other implementations.

== Screenshots ==

1. The Settings page is where you'll configure the plugin.
2. The Your Profile page is where users will manage their linked accounts.
3. The Login page can easily be customized.

== Changelog ==

= 0.4.1 =
* Removed demo from Settings page, it was a bad link.

= 0.4 =
= New features / enhancements =
* Added basic Battle.net support. Requires SSL.
* Now compatible with CloudFlare Flexible SSL.
* Settings page improvements.

= 0.3.1 =
= Fixes =
* The wp_login action now properly passes the $user for improved compatibility with other plugins such as Simple History.

= 0.3 =
= New features / enhancements =
* Settings page improvements.

= Fixes =
* Now uses site_url instead of SERVER_NAME which should reflect a more accurate redirect_url.
* Now compatible with Heroku cloud hosting.

= 0.2.2 =
= New features / enhancements =
* New login provider: Instagram.
* New setting: *Verify Peer/Host SSL Certificates*. Disable this if you have SSL errors, and make sure to read the warning message about disabling this!
* Improved Settings page layout.

= 0.2.1 =
= Fixes =
* Major fixes to user registration and HTTP utility/stream context which were using $this out of context.
* The *Show login messages* setting now shows "Logged out successfully" messages.
* Improved *Login Redirects To* and *Logout Redirects To* Last Page behavior.

= New features / enhancements =
* New login provider: PayPal.

= 0.2 =
= Fixes =
* Custom login buttons now show their dynamic styling while logging in/out.
* Redirect to Last Page now uses the default redirect_to querystring parameter if available. This means if a user is redirected to the default login screen and then logs in, we can still redirect that user to the correct page instead of redirecting back to the login screen.

= New features / enhancements =
* Custom login form now shows logout button.
* Merged the *Hide login form* and *Show provider buttons* settings into a single setting *Show login form*. This is to prevent admins from accidentally hiding both the default login form and custom login buttons, resulting in an empty login screen.
* All instances of the custom login form now flow through a single function/shortcode which allows for a variety of custom login forms.
* Nine new attributes for the [wpoa_login_form] shortcode; see [Installation](https://wordpress.org/plugins/wp-oauth/installation/) for details on usage.
* Four new settings for customizing the custom login form appearance on the default login screen.
* Two new settings: *Login Redirects To* and *Logout Redirects To* will redirect users to Home Page, Last Page, Specific Page, User's Profile Page, Admin Dashboard, or Custom URL.
* New setting: *Restore default settings*.
* Settings page has been fully restyled and redesigned for responsive layout and includes author info, help tips, auto warnings, etc.
* Improved the plugin class architecture.

= Known issues =
* Compatibility issue with W3 Total Cache plugin: user redirection after login may fail. Probably need to whitelist/exclude WP-OAuth files/directory in W3TC settings. Fix coming...

= 0.1.2 =
* Fixed [wpoa_login_form] shortcode.

= 0.1.1 =
* Fixed an issue where the WordPress media dialog that was implemented for choosing a Logo or Background image for the login screen was conflicting with the default one for Posts and Pages.

= 0.1 =
* First release.

== Upgrade Notice ==

= 0.2 =
* Updated readme.
* Updated readme.

= 0.1.2 =
* Updated readme.

== History ==

This project is a continuation of [WP-OpenLogin](http://github.com/perrybutler/wp-openlogin) which was originally developed with OpenID in mind. We're moving on; OAuth 2.0 is now the standard.