<?php
/**
 * WP Widget Disable
 *
 * A plugin that allows you to disable WordPress and Dashboard Widgets in an easy to use fashion.
 * Simply go to the admin page under Settings.
 *
 * @package   WP_Widget_Disable
 * @author    Silvan Hagen <silvan@required.ch>
 * @license   GPL-2.0+
 * @link      http://wp.required.ch/plugins/wp-widget-disable/
 * @copyright 2015 required gmbh
 *
 * @wordpress-plugin
 * Plugin Name:       WP Widget Disable
 * Plugin URI:        http://wp.required.ch/plugins/wp-widget-disable/
 * Description:       Disable WordPress and Dashboard Widgets with an easy to use interface. Simply use the checkboxes provided under <strong>Appearance -> Disable Widgets</strong> and select the Widgets you'd like to hide.
 * Version:           1.0.1
 * Author:            required+ (Silvan Hagen)
 * Author URI:        http://required.ch
 * Text Domain:       wp-widget-disable
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/wearerequired/wp-widget-disable-plugin
 */

// If this file is called directly, abort.
defined ( 'ABSPATH' ) or die;

/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/
require_once( plugin_dir_path( __FILE__ ) . '/public/class-wp-widget-disable.php' );

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 */
register_activation_hook( __FILE__, array( 'WP_Widget_Disable', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'WP_Widget_Disable', 'deactivate' ) );

add_action( 'plugins_loaded', array( 'WP_Widget_Disable', 'get_instance' ) );

/*----------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
 *----------------------------------------------------------------------------*/
if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {
	require_once( plugin_dir_path( __FILE__ ) . '/admin/class-wp-widget-disable-admin.php' );
	add_action( 'plugins_loaded', array( 'WP_Widget_Disable_Admin', 'get_instance' ) );
}