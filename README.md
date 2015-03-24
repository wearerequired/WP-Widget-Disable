# WP Team List #
**Contributors:** wearerequired, neverything, swissspidy  
**Donate link:** http://required.ch/  
**Tags:** widgets, admin, dashboard, sidebar widgets, dashboard widgets, disable widgets  
**Requires at least:** 3.5.1  
**Tested up to:** 4.2  
**Stable tag:** 1.0.1  
**License:** GPLv2 or later  
**License URI:** http://www.gnu.org/licenses/gpl-2.0.html  

Disable Sidebar and Dashboard Widgets with an easy to use interface.

## Description ##

This simple plugin allows you to disable any Sidebar and Dashboard Widget for the current WordPress site you are on. It provides a simple user interface available to users with `edit_theme_options` capabilities (usually Administrator role) available under Appearance -> Disable Widgets.
After saving the settings, it removes the Sidebar and Dashboard Widgets selected.

**Developer? Get to know the hooks**

First of all, this plugin is strucuted on the shoulders of the fantastic [WordPress Plugin Boilerplate](https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate/) by [Tom McFarlin](http://profiles.wordpress.org/tommcfarlin/), you might could use this too for your next plugin.

Let's have a look at the filters we provide:

* `rplus_wp_widget_disable_capability` change the min. capability to change the settings, defaults to `edit_theme_options`.
* `rplus_wp_widget_disable_default_sidebar_filter` gives you back the list (array) of all sidebar widgets before we store them, so you could basically remove sidebar widgets from being disabled.
* `rplus_wp_widget_disable_default_dashboard_filter` gives you back the list (array) of all dashboard widgets before we sotre them, so you could basically remove dashboard widgets from being disabled.

**Contributions**

If you would like to contribute to this plugin, report an isse or anything like that, please note that we develop this plugin on [GitHub](https://github.com/wearerequired/WP-Widget-Disable).

Developed by [required+](http://required.ch/ "Team of experienced web professionals from Switzerland & Germany")

## Installation ##

Here is how you install WP Widget Disable.

**Using The WordPress Dashboard**

1. Navigate to the 'Add New' in the plugins dashboard
2. Search for 'WP Widget Disable'
3. Click 'Install Now'
4. Activate the plugin on the Plugin dashboard

**Uploading in WordPress Dashboard**

1. Navigate to the 'Add New' in the plugins dashboard
2. Navigate to the 'Upload' area
3. Select `rplus-wp-widget-disable.zip` from your computer
4. Click 'Install Now'
5. Activate the plugin in the Plugin dashboard

**Using FTP**

1. Download `rplus-wp-widget-disable.zip`
2. Extract the `rplus-wp-widget-disable` directory to your computer
3. Upload the `rplus-wp-widget-disable` directory to the `/wp-content/plugins/` directory
4. Activate the plugin in the Plugin dashboard

## Screenshots ##

No Screenshots yet.

## Changelog ##

### 1.0.1 ###
* Added proper textdomains to strings instead of $this->plugin_slug.
* Added README.md for better readability.
* Fixed an issue that prevented text domain from being loaded.

### 1.0.0 ###
* Initial release of the working plugin.
* German (de_DE) translations added.

## Upgrade Notice ##

### 1.0.1 ###
I18n improvements

### 1.0.0 ###
Initial Release