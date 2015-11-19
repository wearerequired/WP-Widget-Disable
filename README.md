# WP Widget Disable #
Contributors:      wearerequired, neverything, swissspidy  
Donate link:       http://required.ch  
Tags:              widgets, admin, dashboard, sidebar widgets, dashboard widgets, disable widgets  
Requires at least: 3.5.1  
Tested up to:      4.4  
Stable tag:        1.5.0  
License:           GPLv2 or later  
License URI:       http://www.gnu.org/licenses/gpl-2.0.html  

Disable sidebar and dashboard widgets with an easy to use interface.

## Description ##
This simple plugin allows you to disable any sidebar and dashboard widget for the current WordPress site you are on. It provides a simple user interface available to users with `edit_theme_options` capabilities (usually Administrator role) available under Appearance -> Disable Widgets.
After saving the settings, the sidebar and dashboard widgets are removed from and the user can’t see those widgets anymore.

**Developer? Get to know the hooks**

Have a look at the filters we provide:

* `wp_widget_disable_default_sidebar_widgets` - Allows you to exclude certain sidebar widgets from being disabled.
* `wp_widget_disable_default_dashboard_widgets` - Allows you to exclude certain dashboard widgets from being disabled.
**Contributions**

If you would like to contribute to this plugin, report an isse or anything like that, please note that we develop this plugin on [GitHub](https://github.com/wearerequired/WP-Widget-Disable).

Developed by [required+](http://required.ch/ "Team of experienced web professionals from Switzerland & Germany")

## Installation ##

### Manual Installation ###

1. Upload the entire `/wp-widget-disable` directory to the `/wp-content/plugins/` directory.
2. Activate WP Widget Disable through the Plugins menu in WordPress.
3. Go to Appearance -> Disable Widgets to manage sidebar and dashboard widgets.

## Frequently Asked Questions ##

None so far. But you can ask us any time on [twitter](https://twitter.com/wearerequired)!

## Screenshots ##

1. Disable the sidebar widgets you don’t need.
2. Even disable unused dashboard widgets.
3. The simplified widgets overview.
4. The stripped-down dashboard.

## Changelog ##

### 1.5.1 ###
* Enhancement: Re-added two filters.

### 1.5.0 ###
* Enhancement: Translation improvements.
* Enhancement: Lots of simplification under the hood.

### 1.4.0 ###
* New: Allows removal of the welcome panel in the dashboard.
* Fixed: Added some missing closing tags in the HTML.

### 1.3.0 ###
* New: The widget list items are now translatable.
* Enhancement: Code cleanup, ensuring WordPress 4.3 compatibility.

### 1.2.0 ###
* Fixed: Make the settings tabs a bit more robust and secure.
* Complete rewrite of the plugin using our `grunt-wp-plugin` boilerplate.
* Preparation for deployment on WordPress.org

### 1.1.2 ###
* Fixed: Removed obsolete hooks that caused an error on plugin (de)activation.

### 1.1.1 ###
* New: Repdigit version number.
* Fixed: Added correct changelog.
* Fixed: Properly translate "Select all" option.

### 1.1.0 ###
* New: Get Dashboard Widgets without roundtrip to the dashboard first.
* New: "Select all" option to disable all widgets in one go.
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

### 1.5.1 ###
Small update under the hood to bring back two filters.

### 1.5.0 ###
Being 100% compatible with WordPress 4.4, this update includes some smaller enhancements under the hood.

### 1.4.0 ###
Now let’s you disable the welcome panel in the dashboard.

### 1.3.0 ###
Minor code cleanup. Works great with WordPress 4.3!

### 1.2.0 ###
Complete revamp of the plugin to make it more shiny and secure!

### 1.1.2 ###
Removed obsolete code that caused an error on plugin activation.

### 1.1.1 ###
Small changes in this version, only some changelog & translation cleaning.

### 1.1.0 ###
More features with less code! Get Dashboard Widgets without roundtrip to the dashboard first and disable all widgets in one go.

### 1.0.1 ###
I18n improvements

### 1.0.0 ###
Initial Release
