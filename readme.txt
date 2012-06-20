=== Email Users ===
Contributors: vprat, mpwalsh8
Donate link: http://www.marvinlabs.com
Tags: email, users, list, admin
Requires at least: 3.1
Tested up to: 3.3.2
Stable tag: 4.2.0

A plugin for wordpress which allows you to send an email to the registered blog users. Users can send personal emails to each other. Power users can email groups of users and even notify group of users of posts.

== Description ==

A plugin for wordpress which allows you to send an email to the registered blog users. Users can send personal emails to each other. Power users can email groups of users and even notify group of users of posts.

All the instructions for installation, the support forums, a FAQ, etc. can be found on the [plugin home page](http://www.marvinlabs.com).

This plugin is available under the GPL license, which means that it's free. If you use it for a commercial web site, if you appreciate my efforts or if you want to encourage me to develop and maintain it, please consider making a donation using Paypal, a secured payment solution. You just need to click the donate button on the [plugin home page](http://www.marvinlabs.com) and follow the instructions.

== Changelog ==

= Version 4.3.0 =
* Replaced slow, inefficient SQL queries to build "nice" user names with more efficient queries.  Thanks to the WP Hackers mailing list for the assistance.

= Version 4.2.0 =
* Fixed serious flaw in User Setting implementation which uses the WP_List_Table class.  The logic was not accounting for large number of users and would slow to a crawl because it was processing the entire list of users instead of just a subset for each page.

= Version 4.1.0 =
* Fixed bug which prevented default settings for a user from being added when a user was registered.
* Added new plugin options to set default state for notifications and mass email.  It is now possible to default new users to any combination of email and notifications settings.
 
= Version 4.0.0 =
* Code updated to use WordPress Menu API for Dashboard Menus.
* Code updated to use WordPress Options API for plugin settings.
* Updated plugin to eliminate WordPress deprecated function notices.
* Added new User Settings page to the pluin menu where bulk settings can be applied to one or more users.  This page makes reviewing user settings much easier than looking at users one at a time.
