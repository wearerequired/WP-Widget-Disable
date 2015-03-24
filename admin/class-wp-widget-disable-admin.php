<?php
/**
 * WP Widget Disable.
 *
 * @package   WP_Widget_Disable_Admin
 * @author    Silvan Hagen <silvan@required.ch>
 * @license   GPL-2.0+
 * @link      http://wp.required.ch/plugins/wp-widget-disable
 * @copyright 2015 required gmbh
 */

/**
 * Plugin class. This class should ideally be used to work with the
 * administrative side of the WordPress site.
 *
 * If you're interested in introducing public-facing
 * functionality, then refer to `class-plugin-name.php`
 *
 * @package WP_Widget_Disable_Admin
 * @author  Silvan Hagen <silvan@required.ch>
 */
class WP_Widget_Disable_Admin {

	/**
	 * Instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @var WP_Widget_Disable_Admin
	 */
	protected static $instance = null;

	/**
	 * Slug of the plugin screen.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $plugin_screen_hook_suffix = null;


	/**
	 * Sidebar Widgets option key.
	 *
	 * Stores all the disabled sidebar widgets as
	 * an array.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	private $sidebar_widgets_option = 'rplus_wp_widget_disable_sidebar_option';

	/**
	 * Available Sidebar Widgets.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	protected $sidebar_widgets = array();

	/**
	 * Dashboard Widgets option key.
	 *
	 * Stores all the disabled sidebar widgets as
	 * an array.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	private $dashboard_widgets_option = 'rplus_wp_widget_disable_dashboard_option';

	/**
	 * Initialize the plugin by loading admin scripts & styles and adding a
	 * settings page and menu.
	 *
	 * @since 1.0.0
	 */
	private function __construct() {
		// Add the options page and menu item.
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'register_sidebar_settings' ) );
		add_action( 'admin_init', array( $this, 'register_dashboard_settings' ) );

		// Get and disable the sidebar widgets.
		add_action( 'widgets_init', array( $this, 'set_default_sidebar_widgets' ), 100 );
		add_action( 'widgets_init', array( $this, 'disable_sidebar_widgets' ), 100 );

		// Get and disable the dashboard widgets.
		add_action( 'wp_dashboard_setup', array( $this, 'disable_dashboard_widgets' ), 100 );

		// Add an action link pointing to the options page.
		$plugin_basename = plugin_basename( plugin_dir_path( dirname( __FILE__ ) ) . 'wp-widget-disable.php' );
		add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'add_action_links' ) );

		add_filter( 'admin_footer_text', array( $this, 'add_admin_footer' ) );
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @return WP_Widget_Disable_Admin A single instance of this class.
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since 1.0.0
	 */
	public function add_plugin_admin_menu() {
		// Add a settings page for this plugin to the Appearances menu.
		$this->plugin_screen_hook_suffix = add_theme_page(
			__( 'Disable Sidebar and Dashboard Widgets', 'wp-widget-disable' ),
			__( 'Disable Widgets', 'wp-widget-disable' ),
			apply_filters( 'rplus_wp_widget_disable_capability', 'edit_theme_options' ),
			'wp-widget-disable',
			array( $this, 'display_plugin_admin_page' )
		);
	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since 1.0.0
	 */
	public function display_plugin_admin_page() {
		include_once( 'views/admin.php' );

	}

	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since 1.0.0
	 *
	 * @param array $links Plugin action links.
	 *
	 * @return array
	 */
	public function add_action_links( $links ) {

		return array_merge(
			array(
				'settings' => '<a href="' . admin_url( 'themes.php?page=wp-widget-disable' ) . '">' . __( 'Settings', 'wp-widget-disable' ) . '</a>'
			),
			$links
		);

	} // add_action_links

	/**
	 * Add admin footer text to plugins page.
	 *
	 * @since    1.0.0
	 *
	 * @param  string $text Default admin footer text.
	 *
	 * @return string
	 */
	public function add_admin_footer( $text ) {
		$screen = get_current_screen();

		if ( 'appearance_page_wp-widget-disable' === $screen->base || 'wp-widget-disable' === $screen->base ) {
			$text = 'WP Widget Disable ' . sprintf( __( 'is brought to you by %s. We &hearts; WordPress.', 'wp-widget-disable' ), '<a href="http://required.ch">required+</a>' );
		}

		return $text;
	}

	/**
	 * Set default sidebar widgets.
	 *
	 * Set an array with all the available sidebar widgets
	 * if we can get them.
	 *
	 * @since 1.0.0
	 */
	public function set_default_sidebar_widgets() {
		if ( ! empty( $GLOBALS['wp_widget_factory'] ) ) {
			$this->sidebars_widgets = apply_filters( 'rplus_wp_widget_disable_default_sidebar_filter', $GLOBALS['wp_widget_factory']->widgets );
		}
	}

	/**
	 * Disable Sidebar Widgets.
	 *
	 * Gets the list of disabled sidebar widgets and disables
	 * them for you in WordPress.
	 *
	 * @since 1.0.0
	 */
	public function disable_sidebar_widgets() {
		$widgets = (array) get_option( $this->sidebar_widgets_option );

		if ( ! empty( $widgets ) ) {
			foreach ( $widgets as $widget_class => $value ) {
				unregister_widget( $widget_class );
			}
		}
	}

	/**
	 * Disable dashboard widgets.
	 *
	 * Gets the list of disabled dashboard widgets and
	 * disables them for you in WordPress.
	 *
	 * @since 1.0.0
	 */
	public function disable_dashboard_widgets() {
		$widgets = (array) get_option( $this->dashboard_widgets_option );

		if ( ! $widgets ) {
			return;
		}

		foreach ( $widgets as $widget_id => $meta_box ) {
			//$meta_box = explode( ',', $meta_box );
			remove_meta_box( $id = $widget_id, $screen = 'dashboard', $context = $meta_box );
		}
	}

	/**
	 * Sanitize sidebar widgets user input.
	 *
	 * @since 1.0.0
	 *
	 * @param array $input
	 *
	 * @return array
	 */
	public function sanitize_sidebar_widgets( $input ) {
		// Create our array for storing the validated options
		$output = array();

		if ( empty( $input ) ) {
			$message = __( 'All Sidebar Widgets are enabled again.', 'wp-widget-disable' );
		} else {
			// Loop through each of the incoming options
			foreach ( $input as $key => $value ) {
				// Check to see if the current option has a value. If so, process it.
				if ( isset( $input[ $key ] ) ) {
					// Strip all HTML and PHP tags and properly handle quoted strings
					$output[ $key ] = strip_tags( stripslashes( $input[ $key ] ) );
				}
			}

			$message = sprintf( _n( 'Settings saved. Disabled %s Sidebar Widget for you.', 'Settings saved. Disabled %s Sidebar Widgets for you.', count( $output ), 'wp-widget-disable' ), count( $output ) );
		}

		add_settings_error(
			'wp-widget-disable',
			esc_attr( 'settings_updated' ),
			$message,
			'updated'
		);

		return apply_filters( 'rplus_wp_widget_disable_validate_sidebar_widgets', $output, $input );
	}

	/**
	 * Sanitize dashboard widgets user input.
	 *
	 * @since 1.0.0
	 *
	 * @param array $input
	 *
	 * @return array
	 */
	public function sanitize_dashboard_widgets( $input ) {
		// Create our array for storing the validated options
		$output = array();

		if ( empty( $input ) ) {
			$message = __( 'All Dashboard Widgets are enabled again.', 'wp-widget-disable' );
		} else {
			// Loop through each of the incoming options
			foreach ( $input as $key => $value ) {
				// Check to see if the current option has a value. If so, process it.
				if ( isset( $input[ $key ] ) ) {
					// Strip all HTML and PHP tags and properly handle quoted strings
					$output[ $key ] = strip_tags( stripslashes( $input[ $key ] ) );
				}
			}

			$message = sprintf( _n( 'Settings saved. Disabled %s Dashboard Widget for you.', 'Settings saved. Disabled %s Dashboard Widgets for you.', count( $output ), 'wp-widget-disable' ), count( $output ) );
		}

		add_settings_error(
			'wp-widget-disable',
			esc_attr( 'settings_updated' ),
			$message,
			'updated'
		);

		return apply_filters( 'rplus_wp_widget_disable_validate_dashboard_widgets', $output, $input );
	}

	/**
	 * Register sidebar widgets settings.
	 *
	 * @since 1.0.0
	 */
	public function register_sidebar_settings() {
		register_setting(
			$this->sidebar_widgets_option,
			$this->sidebar_widgets_option,
			array( $this, 'sanitize_sidebar_widgets' )
		);

		add_settings_section(
			'widget_disable_widget_section', // ID
			__( 'Disable Sidebar Widgets', 'wp-widget-disable' ), // Title
			array( $this, 'render_sidebar_description' ), // Callback
			$this->sidebar_widgets_option // Page
		);

		add_settings_field(
			'sidebar_widgets',
			__( 'Sidebar Widgets', 'wp-widget-disable' ),
			array( $this, 'render_sidebar_checkboxes' ),
			$this->sidebar_widgets_option,
			'widget_disable_widget_section'
		);
	}

	/**
	 * Render setting description.
	 *
	 * @since    1.0.0
	 */
	public function render_sidebar_description() {
		echo '<p>' . __( 'Check the boxes with the <strong>Sidebar Widgets</strong> you would like to disable for this site. Please note that a widget could still be called using code.', 'wp-widget-disable' ) . '</p>';
	}

	/**
	 * Render setting fields
	 *
	 * @since 1.0.0
	 */
	public function render_sidebar_checkboxes() {
		$widgets = $this->sidebars_widgets;

		if ( ! $widgets ) {
			_e( 'Oops, it looks like something is already maniging the Sidebar Widgets for you, because we can\'t get them for you.', 'wp-widget-disable' );
		}

		$options = (array) get_option( $this->sidebar_widgets_option );

		foreach ( $widgets as $widget_class => $widget_object ) { ?>
			<p>
			<input type="checkbox" id="<?php echo esc_attr( $widget_class ); ?>" name="<?php echo $this->sidebar_widgets_option; ?>[<?php echo $widget_class; ?>]" value="disabled"<?php echo checked( 'disabled', ( array_key_exists( $widget_class, $options ) ? $options[ $widget_class ] : false ), false ); ?>/>
			<label for="<?php echo esc_attr( $widget_class ); ?>"><?php echo esc_html( $widget_object->name ); ?> (<code>class <?php echo esc_html( $widget_class ); ?></code>)</label>
			</p><?php
		}
	}

	/**
	 * Register dashboard widgets settings.
	 *
	 * @since 1.0.0
	 */
	public function register_dashboard_settings() {
		register_setting(
			$this->dashboard_widgets_option,
			$this->dashboard_widgets_option,
			array( $this, 'sanitize_dashboard_widgets' )
		);

		add_settings_section(
			'widget_disable_dashboard_section', // ID
			__( 'Disable Dashboard Widgets', 'wp-widget-disable' ), // Title
			array( $this, 'render_dashboard_description' ), // Callback
			$this->dashboard_widgets_option // Page
		);

		add_settings_field(
			'dashboard_widgets',
			__( 'Dashboard Widgets', 'wp-widget-disable' ),
			array( $this, 'render_dashboard_checkboxes' ),
			$this->dashboard_widgets_option,
			'widget_disable_dashboard_section'
		);
	}

	/**
	 * Render setting description.
	 *
	 * @since 1.0.0
	 */
	public function render_dashboard_description() {
		echo '<p>' . __( 'Check the boxes with the <strong>Dashboard Widgets</strong> you would like to disable for this site.', 'wp-widget-disable' ) . '</p>';
	} // render_dashboard_description

	/**
	 * Render setting fields
	 *
	 * @since 1.0.0
	 */
	public function render_dashboard_checkboxes() {
		global $wp_meta_boxes;

		if ( ! is_array( $wp_meta_boxes['dashboard'] ) ) {
			require_once( ABSPATH . '/wp-admin/includes/dashboard.php' );
			set_current_screen( 'dashboard' );
			remove_action( 'wp_dashboard_setup', array( $this, 'disable_dashboard_widgets' ), 100 );
			wp_dashboard_setup();
			add_action( 'wp_dashboard_setup', array( $this, 'disable_dashboard_widgets' ), 100 );
			set_current_screen( 'wp-widget-disable' );
		}

		if ( isset( $wp_meta_boxes['dashboard'][0] ) ) {
			unset( $wp_meta_boxes['dashboard'][0] );
		}

		$options = (array) get_option( $this->dashboard_widgets_option );

		foreach ( $wp_meta_boxes['dashboard'] as $context => $data ) {
			foreach ( $data as $priority => $data ) {
				foreach ( $data as $widget => $data ) {
					$widget_name = strip_tags( preg_replace( '/( |)<span class="hide-if-js">(.)*span>/im', '', $data['title'] ) ); ?>
					<p>
					<input type="checkbox" id="<?php echo esc_attr( $widget ); ?>" name="<?php echo $this->dashboard_widgets_option; ?>[<?php echo $widget; ?>]" value="<?php echo $context; ?>"<?php echo checked( $widget, ( array_key_exists( $widget, $options ) ? $widget : false ), false ); ?>/>
					<label for="<?php echo esc_attr( $widget ); ?>"><?php echo esc_html( $widget_name ); ?> (<code>ID <?php echo esc_html( $widget ); ?></code>)</label>
					</p><?php
				}
			}
		}
	}
}