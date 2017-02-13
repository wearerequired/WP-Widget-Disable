<?php
/**
 * Plugin Name: WP Widget Disable
 * Plugin URI:  https://required.com/services/wordpress-plugins/wp-widget-disable/
 * Description: Disable Sidebar and Dashboard Widgets with an easy to use interface. Simply use the checkboxes provided under <strong>Appearance -> Disable Widgets</strong> and select the Widgets you'd like to hide.
 * Version:     1.6.0
 * Author:      required
 * Author URI:  https://required.com
 * License:     GPLv2+
 * Text Domain: wp-widget-disable
 *
 * Copyright (c) 2015-2017 required (email: support@required.ch)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2 or, at
 * your discretion, any later version, as published by the Free
 * Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

if ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
	require dirname( __FILE__ ) . '/vendor/autoload.php';
}

$requirements_check = new WP_Requirements_Check( array(
	'title' => 'WP Widget Disable',
	'php'   => '5.3',
	'wp'    => '4.0',
	'file'  => __FILE__,
) );

if ( $requirements_check->passes() ) {
	// Pull in the plugin classes and initialize.
	include( dirname( __FILE__ ) . '/classes/class-wp-widget-disable.php' );

	$wp_widget_disable = new WP_Widget_Disable();
	add_action( 'plugins_loaded', array( $wp_widget_disable, 'add_hooks' ) );

	register_activation_hook( __FILE__, array( $wp_widget_disable, 'set_default_options' ) );
}

unset( $requirements_check );
