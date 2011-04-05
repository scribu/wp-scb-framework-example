=== scbFramework ===
Contributors: scribu
Donate link: http://scribu.net/wordpress
Tags: admin, toolkit, framework, forms, cron, settings, rewrite
Requires at least: 3.0
Tested up to: 3.1
Stable tag: trunk

A set of useful classes for faster plugin development.

== Description ==

This is a plugin toolkit that helps developers write plugins faster. It consists of several classes which handle common tasks:

* *[scbForms](http://scribu.net/wordpress/scb-framework/scb-forms.html)* - form generator
* *[scbOptions](http://scribu.net/wordpress/scb-framework/scb-options.html)* - option handling
* *scbAdminPage* - admin page creation
* *scbBoxesPage* - admin page with meta boxes
* *scbWidget* - widget creation
* *scbQueryManipulation* - easier way to manipulate SQL in WP_Query
* *scbCron* - wp-cron handling
* *scbTable* - database table creation
* *scbUtil* - useful functions

Want to take a quick look at the code? Go [here](http://plugins.trac.wordpress.org/browser/scb-framework/trunk/scb).

You can download this plugin to get a quick start on using the framework.

Links: [Framework News & Docs](http://scribu.net/wordpress/scb-framework) | [Author's Site](http://scribu.net)

== Installation ==

1. Download and unzip the archive.
2. Go to wp-content and create the *mu-plugins* folder if you don't have it.
2. Move *scb-load.php* file and *scb* folder from *scb-framework* to *mu-plugins*, so that it looks like this:

`
mu-plugins/scb/
mu-plugins/scb-load.php
`

All plugins and themes will now have access to the scb classes and functions.

Plugins that come with scbFramework pre-packaged will also use the version in mu-plugins.

== Changelog ==

= 05 Apr 2011 (r34) =
* fixed activation hooks when using scb-load.php
* add ability to define column widths in scbBoxesPages (props pento)
* introduce scbUtil::get_current_url()

= 02 Feb 2011 (r31) =
* scbQueryManipulation WP 3.1 compatibility
* scbCron enhancements
* introduced debug_cron(), debug_ts() and debug_h()
* html() knows about self-closing tags

= 09 Sep 2010 (r24) =
* delayed activation (scbLoad4)
* replaced scbQuery with scbQueryManipulation
* removed scbRewrite
* added attributes param to html()
* [more info](http://scribu.net/wordpress/scb-framework/revision-24.html)

= 1.6 (r9) =
* load the most recent version available
* move debugging functions to separate file
* new methods in scbUtil: array_pluck(), objects_to_assoc(), split_at()
* auto-uninstall for scbWidget
* [more info](http://scribu.net/wordpress/scb-framework/sf-1-6.html)

= 1.5 =
* new methods for scbOptions: get_defaults(); cleanup(); __isset();
* new method for scbAdminPage: page_help();
* new method for scbUtil: add_uninstall_hook();
* scbAdminPage::submit_button() accepts an array of arguments
* scbAdminPage can create top level menus
* scbBoxesPage can assign the same handler to multiple boxes, with different arguments
* debug() outputs at the end of the page, only for administrators
* [more info](http://scribu.net/wordpress/scb-framework/sf-1-5.html)

= 1.4 =
* new classes: scbUtil & scbRewrite
* faster loading method
* scbWidget applies 'widget_title' filter
* [more info](http://scribu.net/wordpress/scb-framework/sf-1-4.html)

= 1.3.1 =
* scbTable respects collation & charset

= 1.3 =
* AdminPage: ajax submit
* Options: added set(); deprecated update_part()
* Cron: new methods: do_now(), do_once()
* dropped support for WordPress older than 2.8
* [more info](http://scribu.net/wordpress/scb-framework/sf-1-3.html)

= 1.2.1 =
* fixed PHP 5.0 compatibility issue

= 1.2 =
* added the scbTable class
* fixed widget input names
* use plugin_dir_url()
* [more info](http://scribu.net/wordpress/scb-framework/sf-1-2.html)

= 1.1 =
* better scbBoxesPage
* enhancements for scbAdminPage
* bugfix in scbOptions
* [more info](http://scribu.net/wordpress/scb-framework/sf-1-1.html)

= 1.0 =
* initial release
* [more info](http://scribu.net/wordpress/scb-framework/introducing-scbframework.html)
