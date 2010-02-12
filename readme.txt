=== scbFramework ===
Contributors: scribu
Donate link: http://scribu.net/wordpress
Tags: admin, toolkit, forms, cron, settings, rewrite
Requires at least: 2.8
Tested up to: 2.9
Stable tag: 1.4

A set of useful classes for faster plugin development.

== Description ==

This is a plugin toolkit that helps developers write plugins faster. It consists of several classes which handle common tasks:

* *scbForms* - form creation
* *scbAdminPage* - admin page creation
* *scbBoxesPage* - admin page with meta boxes
* *scbWidget* - widget creation
* *[scbOptions](http://scribu.net/wordpress/scb-framework/scb-options.html)* - option handling
* *scbCron* - wp-cron handling
* *scbRewrite* - rewrite rules handling
* *scbTable* - database table creation
* *scbUtil* - useful functions

You can find out more about it [here](http://scribu.net/wordpress/scb-framework).

== Changelog ==

= 1.5 =
* added scbAdminPage::page_help()
* scbAdminPage can create top level menus
* scbBoxesPage can handle multiple boxes with the same callbacks
* scbAdminPage::submit_button() accepts an array of arguments
* added scbOptions::get_defaults()

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
