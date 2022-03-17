# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

### [2.1.0] - 2022-03-17

* Enhancement: Hide disbaled sidebar widgets when widget block editor is enabled.
* Changed: Requires at least PHP 5.6 and WordPress 4.7.

## [2.0.0] - 2020-02-02

* New: Allows removal of the "Browse Happy" and "PHP Update Required" widgets in the dashboard to suppress remote API requests.

## [1.9.1] - 2019-03-11

* Enhancement: Code cleanup, ensuring WordPress 5.1 compatibility.

## [1.9.0] - 2018-07-26

* Enhancement: Added Multisite support.
* Enhancement: Allows hiding the Try Gutenberg callout in WordPress 4.9.8.

## [1.8.0] - 2017-12-12

* Enhancement: Widgets are now ordered by name on the settings screen.

## [1.7.0] - 2017-02-15

- Fixed: Improved messages when saving settings.
- Enhancement: Improved compatibility with latest versions of WordPress.
- Enhancement: Various accessibility improvements on the settings screen.

## [1.6.0] - 2016-09-01

- Fixed: Re-added two filters to modify the available widgets.
- Fixed: Fixed the title of the Quick Draft widget
- Enhancement: Added compatibility with Antispam Bee.

## [1.5.0] - 2015-11-19

- Enhancement: Translation improvements.
- Enhancement: Lots of simplification under the hood.

## [1.4.0] - 2015-10-06

- New: Allows removal of the welcome panel in the dashboard.
- Fixed: Added some missing closing tags in the HTML.

## [1.3.0] - 2015-09-01

* New: The widget list items are now translatable.
* Enhancement: Code cleanup, ensuring WordPress 4.3 compatibility.

## [1.2.0] - 2015-04-23

* Fixed: Make the settings tabs a bit more robust and secure.
* Complete rewrite of the plugin using our `grunt-wp-plugin` boilerplate.
* Preparation for deployment on WordPress.org

## [1.1.2] - 2015-03-25
* Fixed: Removed obsolete hooks that caused an error on plugin (de)activation.

## [1.1.1] - 2015-03-25
* New: Repdigit version number.
* Fixed: Added correct changelog.
* Fixed: Properly translate "Select all" option.

## 1.1.0 - 2015-03-25
* New: Get Dashboard Widgets without roundtrip to the dashboard first.
* New: "Select all" option to disable all widgets in one go.
* New: Added German (Switzerland) translation.
* Enhancement: Code cleanup! We’re doing more with less code now.
* Fixed: Lots of bugs and typos.

## 1.0.1
* Added proper textdomains to strings instead of $this->plugin_slug.
* Added README.md for better readability.
* Fixed an issue that prevented text domain from being loaded.

## 1.0.0
* Initial release of the working plugin.
* German (de_DE) translations added.

[Unreleased]: https://github.com/wearerequired/WP-Widget-Disable/compare/2.0.0...master
[2.1.0]: https://github.com/wearerequired/WP-Widget-Disable/compare/2.0.0...2.1.0
[2.0.0]: https://github.com/wearerequired/WP-Widget-Disable/compare/1.9.0...2.0.0
[1.9.1]: https://github.com/wearerequired/WP-Widget-Disable/compare/1.9.0...1.9.1
[1.9.0]: https://github.com/wearerequired/WP-Widget-Disable/compare/1.8.0...1.9.0
[1.8.0]: https://github.com/wearerequired/WP-Widget-Disable/compare/1.7.0...1.8.0
[1.7.0]: https://github.com/wearerequired/WP-Widget-Disable/compare/1.6.0...1.7.0
[1.6.0]: https://github.com/wearerequired/WP-Widget-Disable/compare/1.5.0...1.6.0
[1.5.0]: https://github.com/wearerequired/WP-Widget-Disable/compare/1.4.0...1.5.0
[1.4.0]: https://github.com/wearerequired/WP-Widget-Disable/compare/1.3.0...1.4.0
[1.3.0]: https://github.com/wearerequired/WP-Widget-Disable/compare/1.2.0...1.3.0
[1.2.0]: https://github.com/wearerequired/WP-Widget-Disable/compare/1.1.2...1.2.0
[1.1.2]: https://github.com/wearerequired/WP-Widget-Disable/compare/1.1.1...1.1.2
[1.1.1]: https://github.com/wearerequired/WP-Widget-Disable/compare/1.1.0...1.1.1
