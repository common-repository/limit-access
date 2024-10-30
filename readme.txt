=== Limit Access WP Plugin ===
Contributors: owenconti
Tags: limit, users, accounts, multiple, sessions, login, online
Requires at least: 3.2
Tested up to: 3.2
Stable tag: 1.0

== Description ==

The Limit Access WP Plugin is a simple plugin that limits the number of IPs that can be accessing an account at the same time. 

Eg. If $allowedIPs is set to 3, the account "JohnDoe" can be logged into from three separate IPs. When the the account is accessed the fourth time, the user will be logged out and redirected to a custom page made by you.

If you have any questions or problems with the plugin, please let me know!

== Installation ==

	1. Install the plugin via automatic method or manual upload method
	2. Create a new page with a slug name: "limited-access";
	3. Edit this "limited-access" page to have a message for the user for when they get logged out. 
	4. Activate the plugin.
	5. Congrats you're done!

Example: 
	
	Settings:  $allowedIPs = 3;

	The account, "test_user" can be logged in from 3 different IPs, but when the fourth instance is accessed, the user will be logged out and redirected to a page explaining what happened. 

You have the ability to change the number of allowed IPs, the time each record is stored in the database, and if there are special circumstances for certain users.

Lines for settings:

	To change the allowed number of IPs: 		line 9;
	To change the time each record is stored:		line 21;
	To alter special circumstances, per user:		line 44 - 50;

	You can add multiple special users by using the OR operator. Eg:

		if ( $user_login == 'admin' || $user_login == 'joe' ) {
			exit;
		}

== Frequently Asked Questions ==

= Why use this plugin? =

If you have ever had the need to limit only X-amount of sessions per user, per IP on a WP  site, this plugin will do it for you!

=== Changelog ===
= 1.0 =
* Initial build
= 1.1 =
* Updated the README

