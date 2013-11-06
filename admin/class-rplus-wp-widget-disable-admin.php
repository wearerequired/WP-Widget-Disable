<?php
/**
 * WP Widget Disable.
 *
 * @package   WP_Widget_Disable_Admin
 * @author    Silvan Hagen <silvan@required.ch>
 * @license   GPL-2.0+
 * @link      http://wp.required.ch/plugins/wp-widget-disable
 * @copyright 2013 required gmbh
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
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Slug of the plugin screen.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;


	private $sidebar_widgets_option 			= 'rplus_wp_widget_disable_sidebar_option';
	private $dashboard_widgets_option 			= 'rplus_wp_widget_disable_dashboard_option';
	private $dashboard_widgets_default_option 	= 'rplus_wp_widget_disable_dashboard_default_option';

	protected $sidebar_widgets;

	/**
	 * Initialize the plugin by loading admin scripts & styles and adding a
	 * settings page and menu.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

		/*
		 * Call $plugin_slug from public plugin class.
		 */
		$plugin = WP_Widget_Disable::get_instance();
		$this->plugin_slug 	= $plugin->get_plugin_slug();

		// Add the options page and menu item.
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'register_sidebar_settings' ) );
		add_action( 'admin_init', array( $this, 'register_dashboard_settings' ) );

		// Get and disable the sidebar widgets.
		add_action( 'widgets_init', array( $this, 'set_default_sidebar_widgets' ), 100 );
		add_action( 'widgets_init', array( $this, 'disable_sidebar_widgets' ), 100 );

		// Get and disable the dashboard widgets.
		add_action( 'wp_dashboard_setup', array( $this, 'set_default_dashboard_widgets' ), 100 );
		add_action( 'wp_dashboard_setup', array( $this, 'disable_dashboard_widgets' ), 100 );

		// Add an action link pointing to the options page.
		$plugin_basename = plugin_basename( plugin_dir_path( dirname( __FILE__ ) ) . $this->plugin_slug . '.php' );
		add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'add_action_links' ) );

	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
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
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu() {

		/*
		 * Add a settings page for this plugin to the Theme menu.
		 */
		$this->plugin_screen_hook_suffix = add_theme_page(
			__( 'Disable Sidebar and Dashboard Widgets', $this->plugin_slug ),
			__( 'Disable Widgets', $this->plugin_slug ),
			apply_filters( 'rplus_wp_widget_disable_capability', 'edit_theme_options' ),
			$this->plugin_slug,
			array( $this, 'display_plugin_admin_page' )
		);

	}

	public function register_sidebar_settings() {

		register_setting(
        	$this->sidebar_widgets_option,
			$this->sidebar_widgets_option
        );

        add_settings_section(
            'widget_disable_widget_section', // ID
            __( 'Disable Sidebar Widgets', $this->plugin_slug ), // Title
            array( $this, 'render_sidebar_description' ), // Callback
            $this->sidebar_widgets_option // Page
        );

        add_settings_field(
        	'sidebar_widgets',
        	__( 'Sidebar Widgets', $this->plugin_slug ),
        	array( $this, 'render_sidebar_checkboxes' ),
        	$this->sidebar_widgets_option,
        	'widget_disable_widget_section'
        );

	}

	public function render_sidebar_description() {

		echo '<p>' . __( 'Check the boxes with the <strong>Sidebar Widgets</strong> you would like to disable for this site. Please note that a widget could still be called using code.', $this->plugin_slug ) . '</p>';

	}

	public function render_sidebar_checkboxes() {

        $widgets = $this->sidebars_widgets;

        if ( ! $widgets ) {

        	_e( 'Oops, it looks like something is already maniging the Widgets for you, because we can\'t get them for you', $this->plugin_slug );

        }

        $options = (array) get_option( $this->sidebar_widgets_option );

        foreach ( $widgets as $widget_class => $widget_object ) : ?>
        	<input type="checkbox" id="<?php echo esc_attr( $widget_class ); ?>" name="<?php echo $this->sidebar_widgets_option; ?>[<?php echo $widget_class; ?>]" value="disabled"<?php echo checked( 'disabled', ( array_key_exists( $widget_class, $options ) ? $options[$widget_class] : false ), false ); ?>/>
			&nbsp;
			<label for="<?php echo esc_attr( $widget_class ); ?>"><?php echo esc_html( $widget_object->name ); ?> (<code>class <?php echo esc_html( $widget_class ); ?></code>)</label>
			<br>

        <?php endforeach;

	}

	public function register_dashboard_settings() {

        register_setting(
        	$this->dashboard_widgets_option,
			$this->dashboard_widgets_option
        );

        add_settings_section(
            'widget_disable_dashboard_section', // ID
            __( 'Disable Dashboard Widgets', $this->plugin_slug ), // Title
            array( $this, 'render_dashboard_description' ), // Callback
            $this->dashboard_widgets_option // Page
        );

        add_settings_field(
        	'dashboard_widgets',
        	__( 'Dashboard Widgets', $this->plugin_slug ),
        	array( $this, 'render_dashboard_checkboxes' ),
        	$this->dashboard_widgets_option,
        	'widget_disable_dashboard_section'
        );

	}

	public function render_dashboard_description() {

		echo '<p>' . __( 'Check the boxes with the <strong>Dashboard Widgets</strong> you would like to disable for this site.', $this->plugin_slug ) . '</p>';

	}

	public function render_dashboard_checkboxes() {

		$default_widgets = get_option( $this->dashboard_widgets_default_option );

		if ( ! $default_widgets ) {

			echo '<p>';
			printf( __( 'We know it\'s inconvenient, but please travel to the %s first to make the list of dashboard widgets available to this plugin.', $this->plugin_slug ), '<a href="' . get_admin_url() . '">' . __( 'Dashboard', $this->plugin_slug ) . '</a>' );
			echo '</p>';

		} else {

			$options = (array) get_option( $this->dashboard_widgets_option );

			foreach ( $default_widgets as $context => $data ) {
				foreach ( $data as $priority => $data ) {
					foreach( $data as $widget => $data ) {
						$widget_name = strip_tags( preg_replace( '/( |)<span.*span>/im', '', $data['title'] ) ); ?>
						<input type="checkbox" id="<?php echo esc_attr( $widget ); ?>" name="<?php echo $this->dashboard_widgets_option; ?>[<?php echo $widget; ?>]" value="<?php echo $context; ?>"<?php echo checked( $widget, ( array_key_exists( $widget, $options ) ? $widget : false ), false ); ?>/>
						&nbsp;
						<label for="<?php echo esc_attr( $widget ); ?>"><?php echo esc_html( $widget_name ); ?> (<code>ID <?php echo esc_html( $widget ); ?></code>)</label>
						<br><?php
					}
				}
			}

		}

	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_admin_page() {

		include_once( 'views/admin.php' );
	}

	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since    1.0.0
	 */
	public function add_action_links( $links ) {

		return array_merge(
			array(
				'settings' => '<a href="' . admin_url( 'themes.php?page=' . $this->plugin_slug ) . '">' . __( 'Settings', $this->plugin_slug ) . '</a>'
			),
			$links
		);

	}

	public function set_default_sidebar_widgets() {

		$this->sidebars_widgets = $GLOBALS['wp_widget_factory']->widgets;

	}

	public function disable_sidebar_widgets() {

		$widgets = (array) get_option( $this->sidebar_widgets_option );

		if ( ! empty( $widgets ) ) {

			foreach ( $widgets as $widget_class => $value ) {
				unregister_widget( $widget_class );
			}

		}

	}

	public function set_default_dashboard_widgets() {

		global $wp_meta_boxes;

		if ( is_array( $wp_meta_boxes['dashboard'] ) ) {

			update_option( $this->dashboard_widgets_default_option, $wp_meta_boxes['dashboard'] );

		}

	}

	public function disable_dashboard_widgets() {

		global $wp_meta_boxes;

		$widgets = (array) get_option( $this->dashboard_widgets_option );

		if ( ! empty( $widgets ) ) {

			foreach ($widgets as $widget_id => $meta_box ) {

				//$meta_box = explode( ',', $meta_box );
				remove_meta_box( $id = $widget_id, $screen = 'dashboard', $context = $meta_box );

			}

		}

	}

}
