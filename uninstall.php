<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @package   WP_Widget_Disable
 * @author    Silvan Hagen <silvan@required.ch>
 * @license   GPL-2.0+
 * @link      http://wp.required.ch/plugins/wp-widget-disable
 * @copyright 2015 required gmbh
 */

defined( 'WP_UNINSTALL_PLUGIN' ) || die;

$options = array(
	'rplus_wp_widget_disable_sidebar_option',
	'rplus_wp_widget_disable_dashboard_option',
);

if ( ! is_multisite() ) {
	foreach ( $options as $option ) {
		delete_option( $option );
	}
} else {
	global $wpdb;

	$blog_ids         = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
	$original_blog_id = get_current_blog_id();

	foreach ( $blog_ids as $blog_id ) {
		switch_to_blog( $blog_id );

		foreach ( $options as $option ) {
			delete_option( $option );
		}
	}

	switch_to_blog( $original_blog_id );
}
