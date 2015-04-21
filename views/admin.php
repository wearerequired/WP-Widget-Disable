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
	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
	<?php settings_errors(); ?>

	<?php
	$active_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : $this->sidebar_widgets_option;
	if ( isset( $_GET['tab'] ) ) {
		$active_tab = esc_html( $_GET['tab'] );
	} // end if
	?>

	<h2 class="nav-tab-wrapper">
		<a href="?page=<?php echo esc_attr( 'wp-widget-disable' ); ?>&amp;tab=<?php echo esc_attr( $this->sidebar_widgets_option ); ?>" class="nav-tab <?php echo $active_tab === $this->sidebar_widgets_option ? 'nav-tab-active' : ''; ?>"><?php _e( 'Sidebar Widgets', 'wp-widget-disable' ); ?></a>
		<a href="?page=<?php echo esc_attr( 'wp-widget-disable' ); ?>&amp;tab=<?php echo esc_attr( $this->dashboard_widgets_option ); ?>" class="nav-tab <?php echo $active_tab === $this->dashboard_widgets_option ? 'nav-tab-active' : ''; ?>"><?php _e( 'Dashboard Widgets', 'wp-widget-disable' ); ?></a>
	</h2>

	<script type="text/javascript">
		jQuery(document).ready(function ($) {
			$('#wp_widget_disable_select_all').click(function () {
				var isChecked = $(this).get(0).checked;
				$(this).parents('td').find('input').each(function () {
					$(this).get(0).checked = isChecked;
				});
			});
		});
	</script>

	<form method="post" action="options.php">
		<?php
		settings_fields( $active_tab );
		do_settings_sections( $active_tab );
		submit_button();
		?>
	</form>
</div>
