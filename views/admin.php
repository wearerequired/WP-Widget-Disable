<?php
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * the User Interface to the end user.
 */

// phpcs:disable WordPress.NamingConventions, VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable -- Variables are not global.
$sidebar_tab_url = add_query_arg(
	[
		'page' => 'wp-widget-disable',
		'tab'  => 'sidebar',
	]
);

$dashboard_tab_url = add_query_arg(
	[
		'page' => 'wp-widget-disable',
		'tab'  => 'dashboard',
	]
);

$active_tab = $this->sidebar_widgets_option;

// phpcs:ignore WordPress.Security.NonceVerification
if ( is_network_admin() || ( isset( $_GET['tab'] ) && 'dashboard' === $_GET['tab'] ) ) {
	$active_tab = $this->dashboard_widgets_option;
}

$form_action = is_network_admin() ? network_admin_url( 'edit.php?action=wp-widget-disable' ) : admin_url( 'options.php' );
?>

<div class="wrap">
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

	<?php
	if ( ! is_network_admin() ) :
		?>
	<h2 class="nav-tab-wrapper">
		<a href="<?php echo esc_url( $sidebar_tab_url ); ?>" class="nav-tab <?php echo $this->sidebar_widgets_option === $active_tab ? 'nav-tab-active' : ''; ?>">
			<?php _e( 'Sidebar Widgets', 'wp-widget-disable' ); ?>
		</a>
		<a href="<?php echo esc_url( $dashboard_tab_url ); ?>" class="nav-tab <?php echo $this->dashboard_widgets_option === $active_tab ? 'nav-tab-active' : ''; ?>">
			<?php _e( 'Dashboard Widgets', 'wp-widget-disable' ); ?>
		</a>
	</h2>
	<?php endif; ?>

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

	<form method="post" action="<?php echo esc_url( $form_action ); ?>" class="wp-widget-disable-form">
		<?php
		settings_fields( $active_tab );
		do_settings_sections( $active_tab );
		submit_button();
		?>
	</form>
</div>
