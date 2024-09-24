# Widget Disable #
Contributors:      wearerequired, neverything, swissspidy, ocean90, grapplerulrich, hubersen
Tags:              widgets, admin, dashboard, sidebar widgets, dashboard widgets, disable widgets
Requires at least: 6.0
Tested up to:      6.6
Requires PHP:      7.4
Stable tag:        3.0.1
License:           GPLv2 or later
License URI:       http://www.gnu.org/licenses/gpl-2.0.html

Disable sidebar and dashboard widgets with an easy to use interface.

## Description
This simple plugin allows you to disable any sidebar and dashboard widget for the current WordPress site you are on. It provides a simple user interface available to users with `edit_theme_options` capabilities (usually Administrator role) available under Appearance -> Disable Widgets.
After saving the settings, the sidebar and dashboard widgets are removed from and the user can’t see those widgets anymore.

**Developer? Get to know the hooks**

Have a look at the filters we provide:

* `wp_widget_disable_default_sidebar_widgets` - Allows you to exclude certain sidebar widgets from being disabled.
* `wp_widget_disable_default_dashboard_widgets` - Allows you to exclude certain dashboard widgets from being disabled.

**Contributions**

If you would like to contribute to this plugin, report an issue or anything like that, please note that we develop this plugin on [GitHub](https://github.com/wearerequired/WP-Widget-Disable).

Developed by [required](https://required.com/ "Team of experienced web professionals from Switzerland & Germany")

## Installation

### Manual Installation

1. Upload the entire `/wp-widget-disable` directory to the `/wp-content/plugins/` directory.
2. Activate WP Widget Disable through the Plugins menu in WordPress.
3. Go to Appearance -> Disable Widgets to manage sidebar and dashboard widgets.

## Screenshots

1. Disable the sidebar widgets you don’t need.
2. Even disable unused dashboard widgets.
3. The simplified widgets overview.
4. The stripped-down dashboard.

## Changelog

### 3.0.1 - 2024-09-19

* Enhancement: Code cleanup, ensuring WordPress 6.6 compatibility.


### 3.0.0 - 2023-03-26

* Fixed: Replace use of jQuery to avoid deprecation warnings.
* Changed: Requires at least PHP 7.4 and WordPress 6.0.

### 2.1.0 - 2022-03-17

* Enhancement: Hide disabled sidebar widgets when widget block editor is enabled.
* Changed: Requires at least PHP 5.6 and WordPress 4.7.

### 2.0.0 - 2020-02-02

* New: Allows removal of the "Browse Happy" and "PHP Update Required" widgets in the dashboard to suppress remote API requests.

### 1.9.1 - 2019-03-11

* Enhancement: Cleanup and compatibility with WordPress 5.1.

### 1.9.0 - 2018-07-26

* Enhancement: Added Multisite support.
* Enhancement: Allows hiding the Try Gutenberg callout in WordPress 4.9.8.

For older versions see CHANGELOG.md.
