=== CCBPress Core ===
Contributors: firetree, danielmilner
Tags: church, ccb, church community builder, chms
Requires at least: 4.3
Tested up to: 4.9
Requires PHP: 5.3
Stable tag: 1.2.1
License: GPLv2 or later
License URI: http://ww.gnu.org/licenses/gpl-2.0.html

Introducing the easiest way to display information from Church Community Builder
(CCB) on your church WordPress site.

== Description ==

Introducing the easiest way to display information from Church Community Builder
(CCB) on your church WordPress site.

Requires a [Church Community Builder](http://churchcommunitybuilder.com/) account.

= Features: =
* API Status Dashboard Widget
* Group Information Widget
* CCB Login Widget
* Online Giving Widget

= Do More With Add-ons =

Extend the capabilities of CCBPress with these add-ons:

* [Events](https://ccbpress.com/downloads/events/) - Display event data from Church Community Builder.
* [Gravity Forms](https://ccbpress.com/downloads/gravityforms/) - Add people to group, events, and process queues.
* [Groups](https://ccbpress.com/downloads/groups/) - Display group data from Church Community Builder.

== Installation ==

1. Upload the `ccbpress-core` folder to the `/wp-content/plugins/` directory.
2. Activate the CCBPress Core plugin through the 'Plugins' menu in WordPress.
3. Configure the plugin by going to the CCBPress menu that appears in your WordPress Admin.

== Screenshots ==

1. Welcome
2. Getting Started

== Changelog ==

= 1.2.1 =
* Improved the reliability of the function that determines when a form is active.
* Increased the import status ajax call from every 3 seconds to every 5 seconds.
* Fixed a bug where Connection Settings would state that they were successful when they failed.
* More descriptive error messages when an API service fails while checking services.

= 1.2.0 =
* Modified the import process to respect Church Community Builder's new API rate limits.
* Resetting the import while it is running will now stop the import job.
* Changed the last import date to reflect when the last import started rather than when it finished.
* Fixed a bug when checking if a form is active.
* Fixed a bug when initializing the Chosen JavaScript library.

= 1.1.12 =
* Fixed some situations where the import would not reschedule correctly after running.
* Fixed some issues with import jobs related to Multisite setups.

= 1.1.11 =
* Reworked the maintenance job to reschedule the import if it stops running for some reason.
* Moved imports to their own cron job.

= 1.1.10 =
* Fixed an issue with the expired transient cleanup SQL query.
* Updated the background process library.

= 1.1.9 =
* Reworked the import process to unschedule itself until it has completed.

= 1.1.8 =
* Fixed an issue with a currently unused function in preparation for an upcoming add-on.

= 1.1.7 =
* Improved some backend interface elements.
* Added more translatable strings to the `.pot` file.

= 1.1.6 =
* Fixed an issue with the `is_form_valid()` function not working properly.

= 1.1.5 =
* Changed the role required to see the CCBPress menu to `manage_options`.
* Added the `ccbpress-core.pot` file for translations.

= 1.1.4 =
* Import progress is also reset upon resetting the import status.
* Admin Bar link has been fixed.

= 1.1.3 =
* Disabled autocomplete on the Church Community Builder username and password fields.
* Added a button to reset the last import status.
* Fixed the date formatting for license key expiration dates.

= 1.1.2 =
* Add-ons page is now available.
* Changed the import schedule to run hourly.
* License key expiration dates are now shown once a license key is activated.

= 1.1.1 =
* Fixed the Group Info widget - show/hide options were not working.
* Purging the Image Cache now resets the Last Import date.

= 1.1.0 =
* Consolidated all Data Import jobs into one interface.
* Reworked the entire import process.
* Removed the need to create database tables in the Core plugin.
* Removed a ton of unused code.

= 1.0.2 =
* Added more options for purging cache.
* Added a notice when an add-on requires data, but the database is empty.
* Added option to include event images during the import process.
* Fixed an issue where the Data Import options would not save.

= 1.0.1 =
* Fixed an issue with the cache not working properly.
* A cache cleanup function is now properly unscheduled on plugin deactivation.
* Group images should now update correctly.
* Minor display adjustments.

= 1.0.0 =
* Fixed group images not downloading.
* No longer downloads images that are placeholders.
* Added option to completely delete data/options created by CCBPress upon uninstall.
* Added a backend to sync group/event data to a custom table for add-ons that support it.

= 0.9.8 =
* Fixed an issue with the Purge All Cache menu item.

= 0.9.7 =
* Fixed incorrect opening PHP statement.
* Removed code that was no longer in use.
* Removed the activation redirect in favor of a dismissible notification banner.

= 0.9.6 =
* Removed files that were no longer necessary.
* Added some missing styles to the stylesheet.

= 0.9.5 =
* There was a git merge mixup, incorrect version was tagged for release.

= 0.9.4 =
* Added a form to subscribe to our newsletter.
* Changed the style of the Welcome tabs.
* Made more of the text strings translatable.

= 0.9.3 =
* Switched to the Select2 library for performance improvements.
* Tweaked some of the CSS styles.

= 0.9.2 =
* Fixed some merge issues from the previous version.

= 0.9.1 =
* Fixed some issues with handling widgets before CCB is connected.

= 0.9.0 =
* Beta release
