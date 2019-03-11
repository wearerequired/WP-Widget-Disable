# Widget Disable #
Contributors:      wearerequired, neverything, swissspidy, ocean90, grapplerulrich
Tags:              widgets, admin, dashboard, sidebar widgets, dashboard widgets, disable widgets
Requires at least: 4.0
Tested up to:      5.1
Requires PHP:      5.4
Stable tag:        1.9.1
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

If you would like to contribute to this plugin, report an isse or anything like that, please note that we develop this plugin on [GitHub](https://github.com/wearerequired/WP-Widget-Disable).

Developed by [required](https://required.com/ "Team of experienced web professionals from Switzerland & Germany")

## Installation

### Manual Installation

1. Upload the entire `/wp-widget-disable` directory to the `/wp-content/plugins/` directory.
2. Activate WP Widget Disable through the Plugins menu in WordPress.
3. Go to Appearance -> Disable Widgets to manage sidebar and dashboard widgets.

## Frequently Asked Questions

None so far. But you can ask us any time on [twitter](https://twitter.com/wearerequired)!

## Screenshots

1. Disable the sidebar widgets you don’t need.
2. Even disable unused dashboard widgets.
3. The simplified widgets overview.
4. The stripped-down dashboard.

## Changelog

### 1.9.1

* Enhancement: Cleanup and compatibility with WordPress 5.1.

### 1.9.0

* Enhancement: Added Multisite support.
* Enhancement: Allows hiding the Try Gutenberg callout in WordPress 4.9.8.

For older versions see Changelog.md.
