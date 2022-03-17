<?php
/**
 * Holds the main plugin class.
 */

/**
 * Class WP_Widget_Disable
 */
class WP_Widget_Disable {
	/**
	 * Plugin version.
	 */
	const VERSION = '2.1.0';

	/**
	 * Sidebar widgets option key.
	 *
	 * Stores all the disabled sidebar widgets as an array.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $sidebar_widgets_option = 'rplus_wp_widget_disable_sidebar_option';

	/**
	 * Available sidebar widgets.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	protected $sidebar_widgets = [];

	/**
	 * Dashboard widgets option key.
	 *
	 * Stores all the disabled sidebar widgets as an array.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $dashboard_widgets_option = 'rplus_wp_widget_disable_dashboard_option';

	/**
	 * Page hook suffix for the settings page.
	 *
	 * @since 1.7.0
	 *
	 * @var string
	 */
	protected $page_hook = '';

	/**
	 * Adds hooks.
	 */
	public function add_hooks() {
		add_action( 'init', [ $this, 'load_textdomain' ] );

		// Add the options page and menu item.
		add_action( 'admin_menu', [ $this, 'admin_menu' ] );
		add_action( 'admin_init', [ $this, 'register_settings' ] );

		// Multisite compatibility.
		add_action( 'network_admin_menu', [ $this, 'admin_menu' ] );
		add_action( 'network_admin_edit_wp-widget-disable', [ $this, 'save_network_options' ] );

		// Display settings errors.
		add_action( 'admin_notices', [ $this, 'settings_errors' ] );
		add_action( 'network_admin_notices', [ $this, 'settings_errors' ] );

		// Get and disable the sidebar widgets.
		add_action( 'widgets_init', [ $this, 'set_default_sidebar_widgets' ], 100 );
		add_action( 'widgets_init', [ $this, 'disable_sidebar_widgets' ], 100 );

		// Get and disable the dashboard widgets.
		add_action( 'load-index.php', [ $this, 'disable_dashboard_widgets_with_remote_requests' ] );
		add_action( 'wp_dashboard_setup', [ $this, 'disable_dashboard_widgets' ], 100 );
		add_action( 'wp_network_dashboard_setup', [ $this, 'disable_dashboard_widgets' ], 100 );

		// Add an action link pointing to the setting page.
		add_action( 'plugin_action_links_' . $this->get_basename(), [ $this, 'plugin_action_links' ] );
		add_action( 'network_admin_plugin_action_links_' . $this->get_basename(), [ $this, 'plugin_action_links' ] );
	}

	/**
	 * Returns the URL to the plugin directory
	 *
	 * @return string The URL to the plugin directory.
	 */
	protected function get_url() {
		return plugin_dir_url( __DIR__ );
	}

	/**
	 * Returns the path to the plugin directory.
	 *
	 * @return string The absolute path to the plugin directory.
	 */
	protected function get_path() {
		return plugin_dir_path( __DIR__ );
	}

	/**
	 * Returns the plugin basename.
	 *
	 * @since 1.9.0
	 *
	 * @return string The plugin basename.
	 */
	protected function get_basename() {
		return plugin_basename( $this->get_path() . 'wp-widget-disable.php' );
	}

	/**
	 * Initializes the plugin, registers textdomain, etc.
	 */
	public function load_textdomain() {
		load_plugin_textdomain( 'wp-widget-disable' );
	}

	/**
	 * Register the administration menu for this plugin.
	 *
	 * @since 1.0.0
	 */
	public function admin_menu() {
		if ( is_network_admin() ) {
			$this->page_hook = add_submenu_page(
				'settings.php',
				__( 'Disable Dashboard Widgets', 'wp-widget-disable' ),
				__( 'Disable Widgets', 'wp-widget-disable' ),
				'manage_network_options',
				'wp-widget-disable',
				[ $this, 'settings_page_callback' ]
			);
		} else {
			$this->page_hook = add_theme_page(
				__( 'Disable Sidebar and Dashboard Widgets', 'wp-widget-disable' ),
				__( 'Disable Widgets', 'wp-widget-disable' ),
				'edit_theme_options',
				'wp-widget-disable',
				[ $this, 'settings_page_callback' ]
			);
		}

		add_action( "load-{$this->page_hook}", [ $this, 'settings_page_load_callback' ] );
	}

	/**
	 * Runs before the settings page gets rendered.
	 *
	 * Disables remote requests for dashboard nags.
	 *
	 * @since 2.0.0
	 */
	public function settings_page_load_callback() {
		$key = md5( $_SERVER['HTTP_USER_AGENT'] );
		add_filter( 'pre_site_transient_browser_' . $key, '__return_null' );

		$key = md5( phpversion() );
		add_filter( 'pre_site_transient_php_check_' . $key, '__return_null' );
	}

	/**
	 * Prints the content of the settings page.
	 *
	 * Not a closure because `$this` in closures is only possible in PHP 5.4 or higher.
	 *
	 * @since 1.6.0
	 */
	public function settings_page_callback() {
		include trailingslashit( $this->get_path() ) . 'views/admin.php';
	}

	/**
	 * Displays the settings errors on the plugin's settings page.
	 *
	 * @since 1.7.0
	 */
	public function settings_errors() {
		settings_errors( 'wp-widget-disable' );
	}

	/**
	 * Whether there are settings errors for the plugin's settings page.
	 *
	 * @since 1.9.0
	 *
	 * @return bool True if settings errors exist, false if not.
	 */
	public function has_settings_errors() {
		return count( get_settings_errors( 'wp-widget-disable' ) ) > 0;
	}

	/**
	 * Saves network options in the network admin.
	 *
	 * Handles settings errors and redirects back to options page.
	 *
	 * For single sites, this is handled by wp-admin/options.php.
	 *
	 * @since 1.9.0
	 */
	public function save_network_options() {
		$data = [];

		// phpcs:disable WordPress.Security.NonceVerification
		if ( isset( $_POST[ $this->dashboard_widgets_option ] ) ) {
			$data = $this->sanitize_dashboard_widgets( $_POST[ $this->dashboard_widgets_option ] );
		}
		// phpcs:enable

		update_site_option( $this->dashboard_widgets_option, $data );

		// If no settings errors were registered add a general 'updated' message.
		if ( ! $this->has_settings_errors() ) {
			add_settings_error( 'wp-widget-disable', 'settings_updated', __( 'Settings saved.', 'wp-widget-disable' ), 'updated' );
		}

		set_transient( 'settings_errors', get_settings_errors(), 30 );

		wp_safe_redirect(
			add_query_arg(
				[
					'page'             => 'wp-widget-disable',
					'settings-updated' => 1,
				],
				network_admin_url( 'settings.php' )
			)
		);
		exit;
	}

	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since 1.0.0
	 *
	 * @param array $links Plugin action links.
	 * @return array
	 */
	public function plugin_action_links( array $links ) {
		$settings_url = add_query_arg(
			[ 'page' => 'wp-widget-disable' ],
			admin_url( 'themes.php' )
		);

		if ( is_network_admin() ) {
			$settings_url = add_query_arg(
				[ 'page' => 'wp-widget-disable' ],
				network_admin_url( 'settings.php' )
			);
		}

		return array_merge(
			[
				'settings' => sprintf(
					'<a href="%s">%s</a>',
					esc_url( $settings_url ),
					__( 'Settings', 'wp-widget-disable' )
				),
			],
			$links
		);
	}

	/**
	 * Set the default sidebar widgets.
	 */
	public function set_default_sidebar_widgets() {
		$widgets = [];

		if ( ! empty( $GLOBALS['wp_widget_factory'] ) ) {
			$widgets = $GLOBALS['wp_widget_factory']->widgets;
		}

		/**
		 * Filters the available sidebar widgets.
		 *
		 * @param array $widgets The globally available sidebar widgets.
		 */
		$this->sidebar_widgets = apply_filters( 'wp_widget_disable_default_sidebar_widgets', $widgets );
	}

	/**
	 * Get the default dashboard widgets.
	 *
	 * @return array Sidebar widgets.
	 */
	protected function get_default_dashboard_widgets() {
		global $wp_meta_boxes;

		$screen = is_network_admin() ? 'dashboard-network' : 'dashboard';
		$action = is_network_admin() ? 'wp_network_dashboard_setup' : 'wp_dashboard_setup';

		$current_screen = get_current_screen();

		if ( ! isset( $wp_meta_boxes[ $screen ] ) || ! is_array( $wp_meta_boxes[ $screen ] ) ) {
			require_once ABSPATH . '/wp-admin/includes/dashboard.php';

			set_current_screen( $screen );

			remove_action( $action, [ $this, 'disable_dashboard_widgets' ], 100 );

			wp_dashboard_setup();

			if ( is_callable( [ 'Antispam_Bee', 'add_dashboard_chart' ] ) ) {
				Antispam_Bee::add_dashboard_chart();
			}

			add_action( $action, [ $this, 'disable_dashboard_widgets' ], 100 );
		}

		if ( isset( $wp_meta_boxes[ $screen ][0] ) ) {
			unset( $wp_meta_boxes[ $screen ][0] );
		}

		$widgets = [];

		if ( isset( $wp_meta_boxes[ $screen ] ) ) {
			$widgets = $wp_meta_boxes[ $screen ];
		}

		set_current_screen( $current_screen );

		/**
		 * Filters the available dashboard widgets.
		 *
		 * @param array $widgets The globally available dashboard widgets.
		 */
		return apply_filters( 'wp_widget_disable_default_dashboard_widgets', $widgets );
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
		$widgets = (array) get_option( $this->sidebar_widgets_option, [] );
		if ( ! empty( $widgets ) ) {
			foreach ( array_keys( $widgets ) as $widget_class ) {
				unregister_widget( $widget_class );
			}
		}
	}

	/**
	 * Retrieves the value of the option depending on current admin screen.
	 *
	 * @since 2.0.0
	 *
	 * @return array List of disabled widget IDs.
	 */
	protected function get_disabled_dashboard_widgets() {
		$widgets = (array) get_option( $this->dashboard_widgets_option, [] );

		if ( is_network_admin() ) {
			$widgets = (array) get_site_option( $this->dashboard_widgets_option, [] );
		}

		return $widgets;
	}

	/**
	 * Disable dashboard widgets with remote requests.
	 *
	 * Some widgets are added based on the result of a remote request.
	 * To prevent the remote request we filter the transients.
	 *
	 * @since 2.0.0
	 */
	public function disable_dashboard_widgets_with_remote_requests() {
		$widgets = $this->get_disabled_dashboard_widgets();

		if ( ! $widgets ) {
			return;
		}

		foreach ( $widgets as $widget_id => $meta_box ) {
			if ( 'dashboard_browser_nag' === $widget_id ) {
				$key = md5( $_SERVER['HTTP_USER_AGENT'] );
				add_filter( 'pre_site_transient_browser_' . $key, '__return_null' );

				continue;
			}

			if ( 'dashboard_php_nag' === $widget_id ) {
				$key = md5( phpversion() );
				add_filter( 'pre_site_transient_php_check_' . $key, '__return_null' );

				continue;
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
		$widgets = $this->get_disabled_dashboard_widgets();

		if ( ! $widgets ) {
			return;
		}

		foreach ( $widgets as $widget_id => $meta_box ) {
			if ( 'dashboard_welcome_panel' === $widget_id ) {
				remove_action( 'welcome_panel', 'wp_welcome_panel' );

				continue;
			}

			if ( 'try_gutenberg_panel' === $widget_id ) {
				remove_action( 'try_gutenberg_panel', 'wp_try_gutenberg_panel' );

				continue;
			}

			if ( 'dashboard_browser_nag' === $widget_id || 'dashboard_php_nag' === $widget_id ) {
				// Handled by ::disable_dashboard_widgets_with_remote_requests().

				continue;
			}

			remove_meta_box( $widget_id, get_current_screen()->base, $meta_box );
		}
	}

	/**
	 * Sanitize sidebar widgets user input.
	 *
	 * @since 1.0.0
	 *
	 * @param array $input Sidebar widgets to disable.
	 * @return array
	 */
	public function sanitize_sidebar_widgets( $input ) {
		// If there are settings errors the input was already sanitized.
		// See https://core.trac.wordpress.org/ticket/21989.
		if ( $this->has_settings_errors() ) {
			return $input;
		}

		// Create our array for storing the validated options.
		$output  = [];
		$message = null;

		if ( empty( $input ) ) {
			$message = __( 'All sidebar widgets are enabled again.', 'wp-widget-disable' );
		} else {
			// Loop through each of the incoming options.
			foreach ( array_keys( $input ) as $key ) {
				// Check to see if the current option has a value. If so, process it.
				if ( isset( $input[ $key ] ) ) {
					// Strip all HTML and PHP tags and properly handle quoted strings.
					$output[ $key ] = wp_strip_all_tags( stripslashes( $input[ $key ] ) );
				}
			}

			$output_count = count( $output );
			if ( 1 === $output_count ) {
				$message = __( 'Settings saved. One sidebar widget disabled.', 'wp-widget-disable' );
			} else {
				$message = sprintf(
					/* translators: %d: number of disabled widgets */
					_n(
						'Settings saved. %d sidebar widget disabled.',
						'Settings saved. %d sidebar widgets disabled.',
						number_format_i18n( $output_count ),
						'wp-widget-disable'
					),
					$output_count
				);
			}
		}

		if ( $message ) {
			add_settings_error(
				'wp-widget-disable',
				'settings_updated',
				$message,
				'updated'
			);
		}

		return $output;
	}

	/**
	 * Sanitize dashboard widgets user input.
	 *
	 * @since 1.0.0
	 *
	 * @param array $input Dashboards widgets to disable.
	 * @return array
	 */
	public function sanitize_dashboard_widgets( $input ) {
		// If there are settings errors the input was already sanitized.
		// See https://core.trac.wordpress.org/ticket/21989.
		if ( $this->has_settings_errors() ) {
			return $input;
		}

		// Create our array for storing the validated options.
		$output  = [];
		$message = null;

		if ( empty( $input ) ) {
			$message = __( 'All dashboard widgets are enabled again.', 'wp-widget-disable' );
		} else {
			// Loop through each of the incoming options.
			foreach ( array_keys( $input ) as $key ) {
				// Check to see if the current option has a value. If so, process it.
				if ( isset( $input[ $key ] ) ) {
					// Strip all HTML and PHP tags and properly handle quoted strings.
					$output[ $key ] = wp_strip_all_tags( stripslashes( $input[ $key ] ) );
				}
			}

			$output_count = count( $output );
			if ( 1 === $output_count ) {
				$message = __( 'Settings saved. One dashboard widget disabled.', 'wp-widget-disable' );
			} else {
				$message = sprintf(
					/* translators: %d: number of disabled widgets */
					_n(
						'Settings saved. %d dashboard widget disabled.',
						'Settings saved. %d dashboard widgets disabled.',
						number_format_i18n( $output_count ),
						'wp-widget-disable'
					),
					$output_count
				);
			}
		}

		if ( $message ) {
			add_settings_error(
				'wp-widget-disable',
				'settings_updated',
				$message,
				'updated'
			);
		}

		return $output;
	}

	/**
	 * Register the settings.
	 *
	 * @since 1.0.0
	 */
	public function register_settings() {
		register_setting(
			$this->sidebar_widgets_option,
			$this->sidebar_widgets_option,
			[ $this, 'sanitize_sidebar_widgets' ]
		);

		add_settings_section(
			'widget_disable_widget_section',
			__( 'Disable Sidebar Widgets', 'wp-widget-disable' ),
			function () {
				echo '<p>';
				_e( 'Choose the sidebar widgets you would like to disable. Note that developers can still display widgets using PHP.', 'wp-widget-disable' );
				echo '</p>';
			},
			$this->sidebar_widgets_option
		);

		add_settings_field(
			'sidebar_widgets',
			__( 'Sidebar Widgets', 'wp-widget-disable' ),
			[ $this, 'render_sidebar_checkboxes' ],
			$this->sidebar_widgets_option,
			'widget_disable_widget_section'
		);

		register_setting(
			$this->dashboard_widgets_option,
			$this->dashboard_widgets_option,
			[ $this, 'sanitize_dashboard_widgets' ]
		);

		add_settings_section(
			'widget_disable_dashboard_section',
			__( 'Disable Dashboard Widgets', 'wp-widget-disable' ),
			function () {
				echo '<p>';
				_e( 'Choose the dashboard widgets you would like to disable.', 'wp-widget-disable' );
				echo '</p>';
			},
			$this->dashboard_widgets_option
		);

		add_settings_field(
			'dashboard_widgets',
			__( 'Dashboard Widgets', 'wp-widget-disable' ),
			[ $this, 'render_dashboard_checkboxes' ],
			$this->dashboard_widgets_option,
			'widget_disable_dashboard_section'
		);
	}

	/**
	 * Render setting fields
	 *
	 * @since 1.0.0
	 */
	public function render_sidebar_checkboxes() {
		$widgets = $this->sidebar_widgets;

		$widgets = wp_list_sort( $widgets, [ 'name' => 'ASC' ], null, true );

		if ( ! $widgets ) {
			printf(
				'<p>%s</p>',
				__( 'Oops, we could not retrieve the sidebar widgets! Maybe there is another plugin already managing them?', 'wp-widget-disable' )
			);
			return;
		}

		$options                  = (array) get_option( $this->sidebar_widgets_option, [] );
		$widgets_to_hide          = $this->get_widgets_to_hide_from_legacy_widget_block();
		$use_widgets_block_editor = $this->use_widgets_block_editor();

		foreach ( $widgets as $id => $widget_object ) {
			// Hide widgets if widgets block is enabled.
			if ( $use_widgets_block_editor && in_array( $widget_object->id_base, $widgets_to_hide, true ) ) {
				continue;
			}
			printf(
				'<p><input type="checkbox" id="%1$s" name="%2$s" value="disabled" %3$s> <label for="%1$s">%4$s</label></p>',
				esc_attr( $id ),
				esc_attr( $this->sidebar_widgets_option ) . '[' . esc_attr( $id ) . ']',
				checked( array_key_exists( $id, $options ), true, false ),
				sprintf(
					/* translators: 1: widget name, 2: widget class */
					_x( '%1$s (%2$s)', 'sidebar widget', 'wp-widget-disable' ),
					esc_html( $widget_object->name ),
					'<code>' . esc_html( $id ) . '</code>'
				)
			);
		}
		?>
		<p>
			<button type="button" class="button-link" id="wp_widget_disable_select_all"><?php _e( 'Select all', 'wp-widget-disable' ); ?></button> |
			<button type="button" class="button-link" id="wp_widget_disable_deselect_all"><?php _e( 'Deselect all', 'wp-widget-disable' ); ?></button>
		</p>
		<?php
	}

	/**
	 * Render setting fields.
	 *
	 * @since 1.0.0
	 */
	public function render_dashboard_checkboxes() {
		$widgets = $this->get_default_dashboard_widgets();

		$flat_widgets = [];

		foreach ( $widgets as $context => $priority ) {
			foreach ( $priority as $data ) {
				foreach ( $data as $id => $widget ) {
					if ( ! $widget ) {
						continue;
					}

					$widget['title']          = isset( $widget['title'] ) ? $widget['title'] : '';
					$widget['title_stripped'] = wp_strip_all_tags( $widget['title'] );
					$widget['context']        = $context;

					$flat_widgets[ $id ] = $widget;
				}
			}
		}

		$widgets = wp_list_sort( $flat_widgets, [ 'title_stripped' => 'ASC' ], null, true );

		if ( ! $widgets ) {
			printf(
				'<p>%s</p>',
				__( 'Oops, we could not retrieve the dashboard widgets! Maybe there is another plugin already managing them?', 'wp-widget-disable' )
			);
			return;
		}

		$options    = $this->get_disabled_dashboard_widgets();
		$wp_version = get_bloginfo( 'version' );

		if ( ! is_network_admin() ) {
			?>
			<p>
				<input type="checkbox" id="dashboard_welcome_panel" name="rplus_wp_widget_disable_dashboard_option[dashboard_welcome_panel]" value="normal"
					<?php checked( 'dashboard_welcome_panel', ( array_key_exists( 'dashboard_welcome_panel', $options ) ? 'dashboard_welcome_panel' : false ) ); ?>>
				<label for="dashboard_welcome_panel">
					<?php
					/* translators: %s: welcome_panel */
					printf( __( 'Welcome panel (%s)', 'wp-widget-disable' ), '<code>welcome_panel</code>' );
					?>
				</label>
			</p>

			<?php
			if (
				version_compare( $wp_version, '4.9.8-RC1', '>=' ) &&
				version_compare( $wp_version, '5.0-alpha-43807', '<' )
			) :
				?>
				<p>
					<input type="checkbox" id="try_gutenberg_panel" name="rplus_wp_widget_disable_dashboard_option[try_gutenberg_panel]" value="normal"
						<?php checked( 'try_gutenberg_panel', ( array_key_exists( 'try_gutenberg_panel', $options ) ? 'try_gutenberg_panel' : false ) ); ?>>
					<label for="try_gutenberg_panel">
						<?php
						/* translators: %s: try_gutenberg_panel */
						printf( __( 'Try Gutenberg callout (%s)', 'wp-widget-disable' ), '<code>try_gutenberg_panel</code>' );
						?>
					</label>
				</p>
				<?php
			endif;
		}
		?>

		<p>
			<input type="checkbox" id="dashboard_browser_nag" name="rplus_wp_widget_disable_dashboard_option[dashboard_browser_nag]" value="normal"
				<?php checked( 'dashboard_browser_nag', ( array_key_exists( 'dashboard_browser_nag', $options ) ? 'dashboard_browser_nag' : false ) ); ?>>
			<label for="dashboard_browser_nag">
				<?php
				/* translators: %s: dashboard_browser_nag */
				printf( __( 'Browse Happy (%s)', 'wp-widget-disable' ), '<code>dashboard_browser_nag</code>' );
				?>
			</label>
		</p>

		<?php
		if ( version_compare( $wp_version, '5.1.0', '>=' ) ) :
			?>
			<p>
				<input type="checkbox" id="dashboard_php_nag" name="rplus_wp_widget_disable_dashboard_option[dashboard_php_nag]" value="normal"
					<?php checked( 'dashboard_php_nag', ( array_key_exists( 'dashboard_php_nag', $options ) ? 'dashboard_php_nag' : false ) ); ?>>
				<label for="dashboard_php_nag">
					<?php
					/* translators: %s: dashboard_php_nag */
					printf( __( 'PHP Update Required (%s)', 'wp-widget-disable' ), '<code>dashboard_php_nag</code>' );
					?>
				</label>
			</p>
			<?php
		endif;

		foreach ( $widgets as $id => $widget ) {
			if ( empty( $widget['title'] ) ) {
				printf(
					'<p><input type="checkbox" id="%1$s" name="%2$s" value="%3$s" %4$s> <label for="%1$s">%5$s</label></p>',
					esc_attr( $id ),
					esc_attr( $this->dashboard_widgets_option ) . '[' . esc_attr( $id ) . ']',
					esc_attr( $widget['context'] ),
					checked( array_key_exists( $id, $options ), true, false ),
					'<code>' . esc_html( $id ) . '</code>'
				);

				continue;
			}

			printf(
				'<p><input type="checkbox" id="%1$s" name="%2$s" value="%3$s" %4$s> <label for="%1$s">%5$s</label></p>',
				esc_attr( $id ),
				esc_attr( $this->dashboard_widgets_option ) . '[' . esc_attr( $id ) . ']',
				esc_attr( $widget['context'] ),
				checked( array_key_exists( $id, $options ), true, false ),
				sprintf(
					/* translators: 1: widget name, 2: widget ID */
					_x( '%1$s (%2$s)', 'dashboard widget', 'wp-widget-disable' ),
					wp_kses( $widget['title'], [ 'span' => [ 'class' => true ] ] ),
					'<code>' . esc_html( $id ) . '</code>'
				)
			);
		}
		?>
		<p>
			<button type="button" class="button-link" id="wp_widget_disable_select_all"><?php _e( 'Select all', 'wp-widget-disable' ); ?></button> |
			<button type="button" class="button-link" id="wp_widget_disable_deselect_all"><?php _e( 'Deselect all', 'wp-widget-disable' ); ?></button>
		</p>
		<?php
	}

	/**
	 * Check if block editor is enabled for widgets.
	 *
	 * @return bool
	 */
	public function use_widgets_block_editor() {
		if ( function_exists( 'wp_use_widgets_block_editor' ) ) {
			return wp_use_widgets_block_editor();
		}
		return false;
	}

	/**
	 * Get list of widgets to hide from legacy widget block.
	 *
	 * @return array
	 */
	public function get_widgets_to_hide_from_legacy_widget_block() {
		if ( function_exists( 'get_legacy_widget_block_editor_settings' ) ) {
			return get_legacy_widget_block_editor_settings()['widgetTypesToHideFromLegacyWidgetBlock'];
		}
		return [];
	}
}
