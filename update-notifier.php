<?php
/**
 * Plugin Name: Update Notifier WordPress
 * Description: Sends an email to the site administrator and a predefined address whenever WordPress core, plugins, or themes are updated.
 * Author: e-studio.dk | Michael Bay Sørensen
 * Version: 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Build an array of human readable item names based on the update type.
 *
 * @param array  $hook_extra Data describing the update.
 * @param string $type       Update type (plugin|theme|core|translation).
 *
 * @return array
 */
function update_notifier_get_items( $hook_extra, $type ) {
	$items = array();

	switch ( $type ) {
		case 'plugin':
			if ( ! function_exists( 'get_plugin_data' ) ) {
				require_once ABSPATH . 'wp-admin/includes/plugin.php';
			}

			$plugins = array();
			if ( ! empty( $hook_extra['plugins'] ) ) {
				$plugins = (array) $hook_extra['plugins'];
			} elseif ( ! empty( $hook_extra['plugin'] ) ) {
				$plugins = array( $hook_extra['plugin'] );
			}

			foreach ( $plugins as $plugin_file ) {
				$plugin_path = WP_PLUGIN_DIR . '/' . $plugin_file;
				if ( file_exists( $plugin_path ) ) {
					$data    = get_plugin_data( $plugin_path, false, false );
					$items[] = ! empty( $data['Name'] ) ? $data['Name'] : $plugin_file;
				} else {
					$items[] = $plugin_file;
				}
			}
			break;

		case 'theme':
			$themes = array();
			if ( ! empty( $hook_extra['themes'] ) ) {
				$themes = (array) $hook_extra['themes'];
			} elseif ( ! empty( $hook_extra['theme'] ) ) {
				$themes = array( $hook_extra['theme'] );
			}

			foreach ( $themes as $theme_slug ) {
				$theme = wp_get_theme( $theme_slug );
				if ( $theme instanceof WP_Theme && $theme->exists() ) {
					$items[] = $theme->get( 'Name' );
				} else {
					$items[] = $theme_slug;
				}
			}
			break;

		case 'core':
			global $wp_version;
			$items[] = 'WordPress core (current version: ' . $wp_version . ')';
			break;

		case 'translation':
		default:
			$items[] = 'Translations or other components';
			break;
	}

	if ( empty( $items ) ) {
		$items[] = 'Unknown items';
	}

	return $items;
}

/**
 * Prepare and send an update email.
 *
 * @param WP_Upgrader $upgrader   Upgrader instance.
 * @param array       $hook_extra Update context.
 *
 * @return void
 */
function update_notifier_send_email( $upgrader, $hook_extra ) {
	if ( empty( $hook_extra['action'] ) || 'update' !== $hook_extra['action'] ) {
		return;
	}

	$type  = ! empty( $hook_extra['type'] ) ? $hook_extra['type'] : 'unknown';
	$items = update_notifier_get_items( $hook_extra, $type );

	$site_name = wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES );
	$site_url  = home_url();

	$subject = sprintf(
		'[Update Notice] %s updated %s',
		$site_name,
		( 'core' === $type ) ? 'WordPress core' : $type . ( count( $items ) > 1 ? 's' : '' )
	);

	$message_lines = array(
		sprintf( 'Site: %s (%s)', $site_name, $site_url ),
		sprintf( 'Update type: %s', ucfirst( $type ) ),
		'Updated item(s):',
	);

	foreach ( $items as $item ) {
		$message_lines[] = ' - ' . $item;
	}

	$message_lines[] = '';
	$message_lines[] = 'This notification was sent when the update completed.';

	$admin_email   = get_option( 'admin_email' );
	$support_email = 'your-email@example.com';
	$recipients    = array_unique( array_filter( array( $admin_email, $support_email ) ) );

	if ( empty( $recipients ) ) {
		return;
	}

	wp_mail( $recipients, $subject, implode( PHP_EOL, $message_lines ) );
}

add_action( 'upgrader_process_complete', 'update_notifier_send_email', 10, 2 );

