<?php
/**
 * Holds the main plugin class.
 *
 * @package WP_Widget_Disable
 */

/**
 * Class WP_Widget_Disable
 */
class WP_Widget_Disable {
	/**
	 * Plugin version.
	 */
	const VERSION = '1.4.0';

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
	protected $sidebar_widgets_option = 'rplus_wp_widget_disable_sidebar_option';

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
	protected $dashboard_widgets_option = 'rplus_wp_widget_disable_dashboard_option';

	/**
	 * Adds hooks.
	 */
	public function add_hooks() {
		add_action( 'init', array( $this, 'load_textdomain' ) );

		// Add the options page and menu item.
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_init', array( $this, 'register_sidebar_settings' ) );
		add_action( 'admin_init', array( $this, 'register_dashboard_settings' ) );

		// Get and disable the sidebar widgets.
		add_action( 'widgets_init', array( $this, 'set_default_sidebar_widgets' ), 100 );
		add_action( 'widgets_init', array( $this, 'disable_sidebar_widgets' ), 100 );

		// Get and disable the dashboard widgets.
		add_action( 'wp_dashboard_setup', array( $this, 'disable_dashboard_widgets' ), 100 );

		// Add an action link pointing to the options page.
		$plugin_basename = plugin_basename( $this->get_path() . 'wp-widget-disable.php' );
		add_action( 'plugin_action_links_' . $plugin_basename, array( $this, 'plugin_action_links' ) );

		add_action( 'admin_footer_text', array( $this, 'admin_footer_text' ) );
	}

	/**
	 * Returns the URL to the plugin directory
	 *
	 * @return string The URL to the plugin directory.
	 */
	public function get_url() {
		return plugin_dir_url( __DIR__ );
	}

	/**
	 * Returns the path to the plugin directory.
	 *
	 * @return string The absolute path to the plugin directory.
	 */
	public function get_path() {
		return plugin_dir_path( __DIR__ );
	}

	/**
	 * Initializes the plugin, registers textdomain, etc.
	 */
	public function load_textdomain() {
		load_plugin_textdomain( 'wp-widget-disable', false, basename( $this->get_path() ) . '/languages' );
	}

	/**
	 * Register the administration menu for this plugin.
	 *
	 * @since 1.0.0
	 */
	public function admin_menu() {
		add_theme_page(
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
		include( $this->get_path() . 'views/admin.php' );
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
	public function plugin_action_links( array $links ) {
		return array_merge(
			array(
				'settings' => sprintf(
					'<a href="%s">%s</a>',
					admin_url( 'themes.php?page=wp-widget-disable' ),
					__( 'Settings', 'wp-widget-disable' )
				),
			),
			$links
		);
	}

	/**
	 * Add admin footer text to plugins page.
	 *
	 * @since    1.0.0
	 *
	 * @param  string $text Default admin footer text.
	 *
	 * @return string
	 */
	public function admin_footer_text( $text ) {
		$screen = get_current_screen();

		if ( 'appearance_page_wp-widget-disable' === $screen->base || 'wp-widget-disable' === $screen->base ) {
			$text = sprintf( __( '%s is brought to you by %s. We &hearts; WordPress.', 'wp-widget-disable' ), 'WP Widget Disable', '<a href="http://required.ch">required+</a>' );
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
			$this->sidebar_widgets = apply_filters( 'rplus_wp_widget_disable_default_sidebar_filter', $GLOBALS['wp_widget_factory']->widgets );
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
		$widgets = (array) get_option( $this->sidebar_widgets_option, array() );
		if ( ! empty( $widgets ) ) {
			foreach ( array_keys( $widgets ) as $widget_class ) {
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
			if ( 'dashboard_welcome_panel' === $widget_id ) {
				remove_action( 'welcome_panel', 'wp_welcome_panel' );
			} else {
				remove_meta_box( $widget_id, 'dashboard', $meta_box );
			}
		}
	}

	/**
	 * Sanitize sidebar widgets user input.
	 *
	 * @since 1.0.0
	 *
	 * @param array $input Sidebar widgets to disable.
	 *
	 * @return array
	 */
	public function sanitize_sidebar_widgets( $input ) {
		// Create our array for storing the validated options.
		$output = array();
		if ( empty( $input ) ) {
			$message = __( 'All Sidebar Widgets are enabled again.', 'wp-widget-disable' );
		} else {
			// Loop through each of the incoming options.
			foreach ( array_keys( $input ) as $key ) {
				// Check to see if the current option has a value. If so, process it.
				if ( isset( $input[ $key ] ) ) {
					// Strip all HTML and PHP tags and properly handle quoted strings.
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
	 * @param array $input Dashboards widgets to disable.
	 *
	 * @return array
	 */
	public function sanitize_dashboard_widgets( $input ) {
		// Create our array for storing the validated options.
		$output = array();
		if ( empty( $input ) ) {
			$message = __( 'All Dashboard Widgets are enabled again.', 'wp-widget-disable' );
		} else {
			// Loop through each of the incoming options.
			foreach ( array_keys( $input ) as $key ) {
				// Check to see if the current option has a value. If so, process it.
				if ( isset( $input[ $key ] ) ) {
					// Strip all HTML and PHP tags and properly handle quoted strings.
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
			'widget_disable_widget_section',
			__( 'Disable Sidebar Widgets', 'wp-widget-disable' ),
			array( $this, 'render_sidebar_description' ),
			$this->sidebar_widgets_option
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
		$widgets = $this->sidebar_widgets;
		if ( ! $widgets ) {
			_e( 'Oops, we could not retrieve the Sidebar Widgets! Maybe there is another plugin already managing theme?', 'wp-widget-disable' );
		}
		$options = (array) get_option( $this->sidebar_widgets_option );
		?>
		<p>
			<input type="checkbox" id="wp_widget_disable_select_all"/>
			<label for="wp_widget_disable_select_all"><?php _e( 'Select all', 'wp-widget-disable' ); ?></label>
		</p>
		<?php
		foreach ( $widgets as $widget_class => $widget_object ) { ?>
			<p>
			<input type="checkbox" id="<?php echo esc_attr( $widget_class ); ?>"
			       name="<?php echo esc_attr( $this->sidebar_widgets_option ); ?>[<?php echo esc_attr( $widget_class ); ?>]"
			       value="disabled"<?php echo checked( 'disabled', ( array_key_exists( $widget_class, $options ) ? $options[ $widget_class ] : false ), false ); ?>/>
			<label for="<?php echo esc_attr( $widget_class ); ?>">
				<?php printf( __( '%1$s (%2$s)', 'wp-widget-disable' ), esc_html( $widget_object->name ), '<code>' . esc_html( $widget_class ) . '</code>' ); ?>
			</label>
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
			'widget_disable_dashboard_section',
			__( 'Disable Dashboard Widgets', 'wp-widget-disable' ),
			array( $this, 'render_dashboard_description' ),
			$this->dashboard_widgets_option
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
	}

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
		?>
		<p>
			<input type="checkbox" id="wp_widget_disable_select_all"/>
			<label for="wp_widget_disable_select_all"><?php _e( 'Select all', 'wp-widget-disable' ); ?></label>
		</p>
		<p>
			<input type="checkbox" id="dashboard_welcome_panel"
			       name="rplus_wp_widget_disable_dashboard_option[dashboard_welcome_panel]"
			       value="normal"
				<?php checked( 'dashboard_welcome_panel', ( array_key_exists( 'dashboard_welcome_panel', $options ) ? 'dashboard_welcome_panel' : false ) ); ?>>
			<label for="dashboard_welcome_panel">
			<label for="dashboard_welcome_panel">
				<?php printf( __( 'Welcome panel (%s)', 'wp-widget-disable' ), '<code>welcome_panel</code>' ); ?>
			</label>
		</p>
		<?php
		foreach ( $wp_meta_boxes['dashboard'] as $context => $priority ) {
			foreach ( $priority as $data ) {
				foreach ( $data as $id => $widget ) {
					$widget_name = strip_tags( preg_replace( '/( |)<span class="hide-if-js">(.)*span>/im', '', $widget['title'] ) ); ?>
					<p>
					<input type="checkbox" id="<?php echo esc_attr( $id ); ?>"
					       name="<?php echo esc_attr( $this->dashboard_widgets_option ); ?>[<?php echo esc_attr( $id ); ?>]"
					       value="<?php echo esc_attr( $context ); ?>"<?php checked( $id, ( array_key_exists( $id, $options ) ? $id : false ) ); ?>/>
					<label for="<?php echo esc_attr( $id ); ?>">
						<?php printf( __( '%1$s (%2$s)', 'wp-widget-disable' ), esc_html( $widget_name ), '<code>' . esc_html( $id ) . '</code>' ); ?>
					</label>
					</p><?php
				}
			}
		}
	}
}
