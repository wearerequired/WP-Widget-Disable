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
 * @copyright 2015 required gmbh
 */
?>

<div class="wrap">
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

	<?php
	$active_tab = $this->sidebar_widgets_option;

	if ( isset( $_GET['tab'] ) && 'dashboard' === $_GET['tab'] ) {
		$active_tab = $this->dashboard_widgets_option;
	}
	?>

	<h2 class="nav-tab-wrapper">
		<a href="<?php echo esc_url( add_query_arg( array(
			'page' => 'wp-widget-disable',
			'tab'  => 'sidebar'
		) ) ); ?>" class="nav-tab <?php echo $this->sidebar_widgets_option === $active_tab ? 'nav-tab-active' : ''; ?>"><?php _e( 'Sidebar Widgets', 'wp-widget-disable' ); ?></a>
		<a href="<?php echo esc_url( add_query_arg( array(
			'page' => 'wp-widget-disable',
			'tab'  => 'dashboard'
		) ) ); ?>" class="nav-tab <?php echo $this->dashboard_widgets_option === $active_tab ? 'nav-tab-active' : ''; ?>"><?php _e( 'Dashboard Widgets', 'wp-widget-disable' ); ?></a>
	</h2>

	<script type="text/javascript">
		jQuery( document ).ready( function( $ ) {
			$( '#wp_widget_disable_select_all, #wp_widget_disable_deselect_all' ).click( function() {
				var isChecked = 'wp_widget_disable_select_all' === $( this ).attr( 'id' );
				$( this ).parents( 'td' ).find( 'input' ).each( function() {
					$( this ).get( 0 ).checked = isChecked;
				} );
			} );
		} );
	</script>

	<form method="post" action="options.php" class="wp-widget-disable-form">
		<?php
		settings_fields( $active_tab );
		do_settings_sections( $active_tab );
		submit_button();
		?>
	</form>
</div>
