<?php
/**
 * Included when the plugin is uninstalled.
 */

defined( 'WP_UNINSTALL_PLUGIN' ) || die;

$widget_disable_options = [
	'rplus_wp_widget_disable_sidebar_option',
	'rplus_wp_widget_disable_dashboard_option',
];

if ( ! is_multisite() ) {
	foreach ( $widget_disable_options as $widget_disable_option ) {
		delete_option( $widget_disable_option );
	}
} else {
	global $wpdb;

	$widget_disable_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );

	foreach ( $widget_disable_ids as $widget_disable_id ) {
		switch_to_blog( $widget_disable_id );

		foreach ( $widget_disable_options as $widget_disable_option ) {
			delete_option( $widget_disable_option );
		}

		restore_current_blog( $widget_disable_id );
	}
}
