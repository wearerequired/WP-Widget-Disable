# WP Widget Disable #
Contributors:      wearerequired  
Donate link:       http://required.ch  
Tags:              widgets, admin, dashboard, sidebar widgets, dashboard widgets, disable widgets  
Requires at least: 3.5.1  
Tested up to:      4.2  
Stable tag:        1.1.2  
License:           GPLv2 or later  
License URI:       http://www.gnu.org/licenses/gpl-2.0.html  

Disable sidebar and dashboard widgets with an easy to use interface.

## Description ##
This simple plugin allows you to disable any Sidebar and Dashboard Widget for the current WordPress site you are on. It provides a simple user interface available to users with `edit_theme_options` capabilities (usually Administrator role) available under Appearance -> Disable Widgets.
After saving the settings, it removes the Sidebar and Dashboard Widgets selected.

**Developer? Get to know the hooks**

First of all, this plugin is strucuted on the shoulders of the fantastic [WordPress Plugin Boilerplate](https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate/) by [Tom McFarlin](http://profiles.wordpress.org/tommcfarlin/), you might could use this too for your next plugin.

Let’s have a look at the filters we provide:

* `rplus_wp_widget_disable_capability` change the min. capability to change the settings, defaults to `edit_theme_options`.
* `rplus_wp_widget_disable_default_sidebar_filter` gives you back the list (array) of all sidebar widgets before we store them, so you could basically remove sidebar widgets from being disabled.
* `rplus_wp_widget_disable_default_dashboard_filter` gives you back the list (array) of all dashboard widgets before we sotre them, so you could basically remove dashboard widgets from being disabled.

**Contributions**

If you would like to contribute to this plugin, report an isse or anything like that, please note that we develop this plugin on [GitHub](https://github.com/wearerequired/WP-Widget-Disable).

Developed by [required+](http://required.ch/ „Team of experienced web professionals from Switzerland & Germany“)

## Installation ##

### Manual Installation ###

1. Upload the entire `/wp-widget-disable` directory to the `/wp-content/plugins/` directory.
2. Activate WP Widget Disable through the Plugins menu in WordPress.
3. Go to Appearance -> Disable Widgets to manage sidebar and dashboard widgets.

## Frequently Asked Questions ##

### Question ###

Answer

## Screenshots ##

1. Description of first screenshot

## Changelog ##

### 1.1.2 ###
* Fixed: Removed obsolote hooks that caused an error on plugin (de)activation.

### 1.1.1 ###
* New: Repdigit version number.
* Fixed: Added correct changelog.
* Fixed: Properly translate „Select all“ option.

### 1.1.0 ###
* New: Get Dashboard Widgets without roundtrip to the dashboard first.
* New: „Select all“ option to disable all widgets in one go.
* New: Added German (Switzerland) translation.
* Enhancement: Code cleanup! We’re doing more with less code now.
* Fixed: Lots of bugs and typos.

### 1.0.1 ###
* Added proper textdomains to strings instead of $this->plugin_slug.
* Added README.md for better readability.
* Fixed an issue that prevented text domain from being loaded.

### 1.0.0 ###
* Initial release of the working plugin.
* German (de_DE) translations added.

## Upgrade Notice ##

### 1.1.2 ###
Removed obsolte code that caused an error on plugin activation.

### 1.1.1 ###
Small changes in this version, only some changelog & translation cleaning.

### 1.1.0 ###
More features with less code! Get Dashboard Widgets without roundtrip to the dashboard first and disable all widgets in one go.

### 1.0.1 ###
I18n improvements

### 1.0.0 ###
Initial Release
