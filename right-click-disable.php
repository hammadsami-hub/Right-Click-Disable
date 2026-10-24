<?php
/**
 * Plugin Name:       Right Click Disable
 * Plugin URI:        https://hammadsami.com
 * Description:       Disables right-click on the front end and shows a short, customizable notification instead.
 * Version:           1.0.0
 * Requires at least: 5.8
 * Requires PHP:      7.4
 * Author:            Hammad Sami
 * Author URI:        https://hammadsami.com
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       right-click-disable
 *
 * @package Right_Click_Disable
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'RCD_VERSION', '1.0.0' );
define( 'RCD_URL', plugin_dir_url( __FILE__ ) );
define( 'RCD_OPTION', 'rcd_options' );
define( 'RCD_PAGE_SLUG', 'right-click-disable' );

/**
 * Default option values.
 *
 * @return array
 */
function rcd_get_defaults() {
	return array(
		'enabled' => 0,
		'message' => __( 'Right click is disabled!', 'right-click-disable' ),
		'design'  => 'dark',
	);
}

/**
 * Available designs (slug => label).
 *
 * @return array
 */
function rcd_get_designs() {
	return array(
		'dark'  => __( 'Dark', 'right-click-disable' ),
		'light' => __( 'Light', 'right-click-disable' ),
		'green' => __( 'Green', 'right-click-disable' ),
		'red'   => __( 'Red', 'right-click-disable' ),
		'blue'  => __( 'Blue', 'right-click-disable' ),
	);
}

/**
 * Get saved options merged with defaults.
 *
 * @return array
 */
function rcd_get_options() {
	return wp_parse_args( (array) get_option( RCD_OPTION, array() ), rcd_get_defaults() );
}

/**
 * Register the settings page under Settings.
 */
function rcd_add_settings_page() {
	add_options_page(
		__( 'Right Click Disable', 'right-click-disable' ),
		__( 'Right Click Disable', 'right-click-disable' ),
		'manage_options',
		RCD_PAGE_SLUG,
		'rcd_render_settings_page'
	);
}
add_action( 'admin_menu', 'rcd_add_settings_page' );

/**
 * Register setting, section and fields (Settings API).
 */
function rcd_register_settings() {
	register_setting(
		'rcd_settings_group',
		RCD_OPTION,
		array(
			'type'              => 'array',
			'sanitize_callback' => 'rcd_sanitize_options',
			'default'           => rcd_get_defaults(),
		)
	);

	add_settings_section(
		'rcd_main_section',
		__( 'Settings', 'right-click-disable' ),
		'__return_false',
		RCD_PAGE_SLUG
	);

	add_settings_field(
		'rcd_enabled',
		__( 'Disable right click', 'right-click-disable' ),
		'rcd_render_enabled_field',
		RCD_PAGE_SLUG,
		'rcd_main_section'
	);

	add_settings_field(
		'rcd_message',
		__( 'Notification message', 'right-click-disable' ),
		'rcd_render_message_field',
		RCD_PAGE_SLUG,
		'rcd_main_section',
		array( 'label_for' => 'rcd_message' )
	);

	add_settings_field(
		'rcd_design',
		__( 'Notification design', 'right-click-disable' ),
		'rcd_render_design_field',
		RCD_PAGE_SLUG,
		'rcd_main_section',
		array( 'label_for' => 'rcd_design' )
	);
}
add_action( 'admin_init', 'rcd_register_settings' );

/**
 * Sanitize the option array before it is saved.
 *
 * @param mixed $input Raw submitted value.
 * @return array
 */
function rcd_sanitize_options( $input ) {
	$defaults = rcd_get_defaults();
	$input    = is_array( $input ) ? $input : array();
	$output   = array();

	// ON/OFF.
	$output['enabled'] = ( isset( $input['enabled'] ) && '1' === (string) $input['enabled'] ) ? 1 : 0;

	// Message: plain text, max 100 characters, fall back to default if empty.
	$message           = isset( $input['message'] ) ? sanitize_text_field( $input['message'] ) : '';
	$message           = mb_substr( $message, 0, 100 );
	$output['message'] = ( '' !== $message ) ? $message : $defaults['message'];

	// Design: must be one of the allowed slugs.
	$design           = isset( $input['design'] ) ? sanitize_key( $input['design'] ) : '';
	$output['design'] = array_key_exists( $design, rcd_get_designs() ) ? $design : $defaults['design'];

	return $output;
}

/**
 * Render the ON/OFF field.
 */
function rcd_render_enabled_field() {
	$options = rcd_get_options();
	?>
	<fieldset>
		<legend class="screen-reader-text"><span><?php esc_html_e( 'Disable right click', 'right-click-disable' ); ?></span></legend>
		<label>
			<input type="radio" name="<?php echo esc_attr( RCD_OPTION ); ?>[enabled]" value="1" <?php checked( 1, (int) $options['enabled'] ); ?> />
			<?php esc_html_e( 'ON', 'right-click-disable' ); ?>
		</label>
		<br />
		<label>
			<input type="radio" name="<?php echo esc_attr( RCD_OPTION ); ?>[enabled]" value="0" <?php checked( 0, (int) $options['enabled'] ); ?> />
			<?php esc_html_e( 'OFF', 'right-click-disable' ); ?>
		</label>
		<p class="description"><?php esc_html_e( 'ON blocks the right-click menu on your website. OFF restores normal right-click.', 'right-click-disable' ); ?></p>
	</fieldset>
	<?php
}

/**
 * Render the message field.
 */
function rcd_render_message_field() {
	$options = rcd_get_options();
	?>
	<input type="text" id="rcd_message" class="regular-text" maxlength="100" name="<?php echo esc_attr( RCD_OPTION ); ?>[message]" value="<?php echo esc_attr( $options['message'] ); ?>" />
	<p class="description"><?php esc_html_e( 'Text shown when a visitor right-clicks (max 100 characters).', 'right-click-disable' ); ?></p>
	<?php
}

/**
 * Render the design field.
 */
function rcd_render_design_field() {
	$options = rcd_get_options();
	?>
	<select id="rcd_design" name="<?php echo esc_attr( RCD_OPTION ); ?>[design]">
		<?php foreach ( rcd_get_designs() as $slug => $label ) : ?>
			<option value="<?php echo esc_attr( $slug ); ?>" <?php selected( $options['design'], $slug ); ?>><?php echo esc_html( $label ); ?></option>
		<?php endforeach; ?>
	</select>
	<p class="description"><?php esc_html_e( 'Choose the look of the notification.', 'right-click-disable' ); ?></p>
	<?php
}

/**
 * Render the settings page.
 */
function rcd_render_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	?>
	<div class="wrap">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
		<form action="options.php" method="post">
			<?php
			settings_fields( 'rcd_settings_group' );
			do_settings_sections( RCD_PAGE_SLUG );
			submit_button();
			?>
		</form>
	</div>
	<?php
}

/**
 * Is this request a page-builder or Customizer preview?
 * Right click is left alone there so editing is not blocked.
 *
 * @return bool
 */
function rcd_is_builder_preview() {
	if ( is_customize_preview() ) {
		return true;
	}

	if ( class_exists( '\Elementor\Plugin' )
		&& isset( \Elementor\Plugin::$instance->preview )
		&& \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
		return true;
	}

	return false;
}

/**
 * Load frontend assets only when the feature is ON.
 */
function rcd_enqueue_assets() {
	$options = rcd_get_options();

	if ( empty( $options['enabled'] ) || rcd_is_builder_preview() ) {
		return;
	}

	wp_enqueue_style(
		'right-click-disable',
		RCD_URL . 'assets/css/right-click-disable.css',
		array(),
		RCD_VERSION
	);

	wp_enqueue_script(
		'right-click-disable',
		RCD_URL . 'assets/js/right-click-disable.js',
		array(),
		RCD_VERSION,
		true
	);

	wp_add_inline_script(
		'right-click-disable',
		'window.rcdSettings = ' . wp_json_encode(
			array(
				'message' => $options['message'],
				'design'  => $options['design'],
			)
		) . ';',
		'before'
	);
}
add_action( 'wp_enqueue_scripts', 'rcd_enqueue_assets' );

/**
 * Remove saved settings when the plugin is deleted.
 */
function rcd_uninstall() {
	delete_option( RCD_OPTION );
}
register_uninstall_hook( __FILE__, 'rcd_uninstall' );
