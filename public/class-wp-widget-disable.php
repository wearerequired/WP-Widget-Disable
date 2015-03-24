<?php
/**
 * WP Widget Disable.
 *
 * @package   WP_Widget_Disable
 * @author    Silvan Hagen <silvan@required.ch>
 * @license   GPL-2.0+
 * @link      http://wp.required.ch/plugins/wp-widget-disable
 * @copyright 2015 required gmbh
 */

/**
 * Main plugin class to load the textdomain.
 *
 * @package WP_Widget_Disable
 * @author  Silvan Hagen <silvan@required.ch>
 */
class WP_Widget_Disable {
	/**
	 * Instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @var WP_Widget_Disable
	 */
	protected static $instance = null;

	/**
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 *
	 * @since 1.0.0
	 */
	private function __construct() {
		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @return WP_Widget_Disable A single instance of this class.
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since 1.0.0
	 */
	public function load_plugin_textdomain() {
		$locale = apply_filters( 'plugin_locale', get_locale(), 'wp-widget-disable' );

		load_textdomain( 'wp-widget-disable', trailingslashit( WP_LANG_DIR ) . 'wp-widget-disable/wp-widget-disable' . '-' . $locale . '.mo' );
		load_plugin_textdomain( 'wp-widget-disable', false, basename( plugin_dir_path( dirname( __FILE__ ) ) ) . '/languages/' );
	}
}