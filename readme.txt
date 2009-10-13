=== scbFramework ===
Contributors: scribu
Donate link: http://scribu.net/wordpress
Tags: admin, toolkit, forms, cron, settings
Requires at least: 2.8
Tested up to: 2.9-rare
Stable tag: trunk

A set of useful classes for faster plugin development.

== Description ==

This is a plugin toolkit that helps developers write plugins faster. It consists of several classes which handle common tasks:

* *scbForms* - form generation
* *scbAdminPage* - admin page generation
* *scbBoxesPage* - admin page with dashboard-like widgets
* *scbWidget* - widget generation
* *[scbOptions](http://scribu.net/wordpress/scb-framework/scb-options.html)* - option handling
* *scbCron* - cron handling
* *scbTable* - database table handling

You can find out more about it [here](http://scribu.net/wordpress/scb-framework).

== Changelog ==

= 1.3 =
* AdminPage: ajax submit
* Options: added set(); deprecated update_part()
* Cron: new methods: do_now(), do_once()
* dropped support for WordPress older than 2.8

= 1.2.1 =
* fixed PHP 5.0 compatibility issue

= 1.2 =
* added the scbTable class
* fixed widget input names
* use plugin_dir_url()

= 1.1 =
* better scbBoxesPage
* enhancements for scbAdminPage
* bugfix in scbOptions
* [more info](http://scribu.net/wordpress/scb-framework/sf-1-1.html)

= 1.0 =
* initial release
* [more info](http://scribu.net/wordpress/scb-framework/introducing-scbframework.html)
