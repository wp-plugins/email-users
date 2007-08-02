=== Post Templates ===
Contributors: vprat
Donate link: http://www.vincentprat.info/wordpress/2007/03/01/wordpress-plugin-post-templates/
Tags: post, model, templates, page, posts, manage
Requires at least: 2.0
Tested up to: 2.1
Stable tag: 1.1.3

A plugin for wordpress which allows you to send an e-mail to your registered blog users, based on their role.

== Description ==

It happens quite often that a blogger publishes posts or static pages on a regular basis which have the same structure. Think about for example a "picture of the day" daily post. With current wordpress state, we need to spend a lot of time doing copy/paste between posts instead of actually writing content. Be happy because I have developped the missing plugin to maintain post templates and allow simple writing of regular posts.

[plugin home page](http://www.vincentprat.info/wordpress/2007/03/01/wordpress-plugin-post-templates/).

This plugin is available under the GPL license, which means that it's free. If you use it for a commercial web site, if you appreciate my efforts or if you want to encourage me to develop and maintain it, please consider making a donation using Paypal, a secured payment solution. You just need to click the button on the [plugin home page](http://www.vincentprat.info/wordpress/2007/03/01/wordpress-plugin-post-templates/) and follow the instructions.

== Installation ==

To install the plugin, nothing simpler: unzip the latest version, put the folder post-template inside your wordpress "wp_content/plugins" folder. Finally, activate the plugin using your wordpress administration interface.

== Options ==

The plugin adds a configuration page labelled Post templates inside the option panel of the administration interface. It presents only three combo box to assign some permissions for the use of the templates and they are described in that same page. Permissions by default should be fine for most uses.

== Use ==

**Creating a template from a post**

To create a post template, you need to copy an existing post. Go inside the "manage article" section of the administration interface and have a look at the listing of the posts. A new column has appeared and offers a "templatize" button. This button will create a template based on the post in the same row and redirect you to the template management page.

Remember that template creation is only accessible if you have the required user level or "role".

You can also create a template by pressing the "Templatize" button found in the "post edition" page.

**Creating a template from a page**

To create a page template, you need to copy an existing page. Go inside the "manage page" section of the administration interface and edit the page you want to copy. Once inside the "page edition" page, you will see a "Templatize" button under the "Save" button. This button will create a template based on the page and redirect you to the template management page.

Remember that template creation is only accessible if you have the required user level or "role".

**Creating a post from a template**

To create a post from a template, nothing simpler. Get to the template management page labelled Post templates found inside the manage panel of the administration console. The templates are listed and you get access (according to the permissions) to 3 functions : delete the template, edit the template (see the limitations in the next section) and make a new post from the template which will take you to the write page.

**Creating a page from a template**

To create a page from a template, nothing simpler. Get to the template management page labelled Page templates found inside the manage panel of the administration console. The templates are listed and you get access (according to the permissions) to 3 functions : delete the template, edit the template (see the limitations in the next section) and make a new page from the template which will take you to the write page.

== Limitations ==

* I am using the standard wordpress page to edit the post templates. When you then save the template, it gets transformed back into a standard post. This is because when saving the post, wordpress erases the "post_type" field that was set to "template" and sets it to "post". You then have to turn the newly created post back into a template and then erase the previous post. To get around this, I would have to make my own page to edit the template. If anybody feels like correcting this, you will be credited for your work here.
* Because the templates are assigned a special "post_type" value, you cannot get a preview when editing them.
* Because Wordpress API does not provide a way to insert columns in the "page management" page (unlike for the "post management" page), you need to edit a page and then use the "Templatize" button to make a template from it.

== Change log ==

**v1.1.3**

[*] Corrected the links that got messed up due to moving the plugin to the WordPress.org hosting (changing names)

**v1.1.1**

[*] Corrected the bug linked to the call to function wp_get_current_user (compatibility for WP2.x)
[*] Corrected the bug linked to the call to function js_escape (compatibility for WP2.x)

**v1.1**

[+] Showing the active version in option page (deactivate and activate again to update)
[+] Templates for pages
[+] Button in the post edit page to make a template from current post
[+] Button in the page edit page to make a template from current page
[*] Checking that function wp_nonce_url exists (compatibility for WP2.x)