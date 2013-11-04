<?php
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   WP_Widget_Disable
 * @author    Silvan Hagen <silvan@required.ch>
 * @license   GPL-2.0+
 * @link      http://wp.required.ch/plugins/wp-widget-disable
 * @copyright 2013 required gmbh
 */
?>

<div class="wrap">

	<?php screen_icon( 'themes' ); ?>
	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
    <?php //settings_errors(); ?>

    <?php
        $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : $this->sidebar_widgets_option;
        if ( isset( $_GET[ 'tab' ] ) ) {
            $active_tab = $_GET[ 'tab' ];
        } // end if
    ?>

    <h2 class="nav-tab-wrapper">
        <a href="?page=<?php echo esc_attr( $this->plugin_slug ); ?>&amp;tab=<?php echo esc_attr( $this->sidebar_widgets_option ); ?>" class="nav-tab <?php echo $active_tab == $this->sidebar_widgets_option ? 'nav-tab-active' : ''; ?>"><?php _e( 'Sidebar Widgets', $this->plugin_slug ); ?></a>
        <a href="?page=<?php echo esc_attr( $this->plugin_slug ); ?>&amp;tab=<?php echo esc_attr( $this->dashboard_widgets_option ); ?>" class="nav-tab <?php echo $active_tab == $this->dashboard_widgets_option ? 'nav-tab-active' : ''; ?>"><?php _e( 'Dashboard Widgets', $this->plugin_slug ); ?></a>
    </h2>

	<form method="post" action="options.php">
    <?php
        settings_fields( $active_tab );
        do_settings_sections( $active_tab );
        submit_button();
    ?>
    </form>

</div>
