<?php

namespace Seventhqueen\Panel;

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class Addons_Manager {

	/**
	 * @var Addons_Manager The single instance of the class
	 * @since 1.0.0
	 */
	protected static $_instance = null;

	var $plugins = array();

	/**
	 * @var \TGM_Plugin_Activation Instance
	 */
	var $tgmpa;

	/**
	 * Main Addons_Manager Instance
	 *
	 * Ensures only one instance of Addons_Manager is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see Addons_Manager()
	 * @return Addons_Manager - Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Main class constructor
	 */
	function __construct() {

		//register the plugins in our class
		add_action( 'init', array( $this, 'populate_plugins' ) );

		// Register Ajax actions
		add_action( 'wp_ajax_sq_do_plugin_action', array( $this, 'do_plugin_action' ) );

		//run code on class init
		do_action( 'sq_addons_manager_init' );

		add_filter( 'tgmpa_load', array( $this, 'tgmpa_load_hook' ) );
	}

	public function populate_plugins() {

		$this->tgmpa = \TGM_Plugin_Activation::get_instance();

		$this->tgmpa->populate_file_path();

		$this->plugins = $this->tgmpa->plugins;
	}

	public function tgmpa_load_hook() {
		return is_admin();
	}

	public function do_plugin_action() {

		check_ajax_referer( 'sq_plugins_nonce', 'security' );

		$action = ! empty( $_POST['plugin_action'] ) ? $_POST['plugin_action'] : false;
		$slug   = ! empty( $_POST['slug'] ) ? $_POST['slug'] : false;

		// Perform plugin actions here
		switch ( $action ) {
			case 'enable_plugin':
				$this->do_plugin_activate( $slug );
				break;
			case 'install_plugin':
				$this->do_plugin_install( $slug );

				break;
			case 'disable_plugin':
				$this->do_plugin_deactivate( $slug );
				break;
			case 'update_plugin':
				$this->do_plugin_update( $slug );
				break;
			case 'enable_child_theme':
				$this->enable_child_theme( $slug );
				break;
			case 'install_theme':
				$this->install_child_theme( $slug );
				break;
			default:
				# code...
				break;
		}

	}

	/**
	 * Performs the plugin update
	 *
	 * @param string $slug [description]
	 */
	function do_plugin_update( $slug ) {

		$status = $this->get_plugin_status( $slug );

		$active = false;
		if ( $this->check_plugin_active( $slug ) ) {
			$active = true;
		}

		if ( empty( $this->plugins[ $slug ] ) ) {
			$status['error'] = 'We have no data about this plugin.';
			wp_send_json_error( $status );
		}

		if ( $this->does_plugin_have_update( $slug ) ) {

			if ( ! class_exists( '\Plugin_Upgrader', false ) ) {
				require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
			}

			$upgrader = new \Plugin_Upgrader( new \Automatic_Upgrader_Skin() );
			// Inject our info into the update transient.
			$source                       = $this->get_download_url( $slug );
			$to_inject                    = array( $slug => $this->plugins[ $slug ] );
			$to_inject[ $slug ]['source'] = $source;
			$this->inject_update_info( $to_inject );
			$result = $upgrader->upgrade( $this->plugins[ $slug ]['file_path'] );

			if ( is_wp_error( $result ) ) {
				$status['error'] = $result->get_error_message();
				wp_send_json_error( $status );
			}


			if ( $active === true ) {
				$this->tgmpa->populate_file_path( $slug );
				$result = activate_plugin( $this->plugins[ $slug ]['file_path'] );
				if ( is_wp_error( $result ) ) {
					$status['error'] = wp_kses_post( $result->get_error_message() );
				}
			}

			// Return the status of the plugin
			$status = $this->get_plugin_status( $slug );
			wp_send_json_success( $status );
		}

		$status['error'] = 'The plugin does not have an update.';
		wp_send_json_error( $status );

	}

	/**
	 * Enable a child theme
	 *
	 * @param  string $slug The slug used in the addons config file for the child theme
	 *
	 * @return string A json formatted value
	 */
	function enable_child_theme( $slug ) {

		$status = $this->get_plugin_status( $slug );

		// Get all installed themes
		$current_installed_themes = wp_get_themes();
		// Get the themes currently installed
		$active_theme      = wp_get_theme();
		$theme_folder_name = $active_theme->get_template();

		$child_theme = false;

		if ( is_array( $current_installed_themes ) ) {
			foreach ( $current_installed_themes as $key => $theme_obj ) {
				if ( $theme_obj->get( 'Template' ) === $theme_folder_name ) {
					$child_theme = $theme_obj;
				}
			}
		}

		if ( $child_theme !== false ) {
			switch_theme( $child_theme->get_stylesheet() );
			$status = $this->get_plugin_status( $slug );
		}

		wp_send_json_success( $status );
	}

	function install_child_theme( $slug ) {
		if ( empty( $this->plugins[ $slug ] ) ) {
			wp_send_json_error( array( 'error' => 'We don\'t know anything about this theme' ) );
		}

		$url    = $this->plugins[ $slug ]['source'];
		$status = $this->get_plugin_status( $slug );

		if ( ! current_user_can( 'install_themes' ) ) {
			$status['error'] = 'You don\'t have permissions to install install_themes';
			wp_send_json_error( $status );
		}

		if ( ! class_exists( '\Theme_Upgrader', false ) ) {
			require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		}

		$skin     = new \Automatic_Upgrader_Skin();
		$upgrader = new \Theme_Upgrader( $skin );
		$result   = $upgrader->install( $url );

		// There is a bug in WP where the install method can return null in case the folder already exists
		// see https://core.trac.wordpress.org/ticket/27365
		if ( $result === null && ! empty( $skin->result ) ) {
			$result = $skin->result;
		}

		if ( is_wp_error( $skin->result ) ) {
			$status['error'] = $result->get_error_message();
			wp_send_json_error( $status );
		}

		$status = $this->get_plugin_status( $slug );
		wp_send_json_success( $status );
	}

	/**
	 * Will check if a child theme is installed for the current theme
	 * @return boolean true/false if a child theme is installed or not
	 */
	function is_child_theme_installed() {

		// Get all installed themes
		$current_installed_themes = wp_get_themes();
		// Get the themes currently installed
		$active_theme      = wp_get_theme();
		$theme_folder_name = $active_theme->get_template();

		if ( is_array( $current_installed_themes ) ) {
			foreach ( $current_installed_themes as $key => $theme_obj ) {
				if ( $theme_obj->get( 'Template' ) === $theme_folder_name ) {
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * Checks if a child theme is active or not
	 * @return boolean If the child theme is in use
	 */
	function is_child_theme_active() {
		$active_theme = wp_get_theme();
		$template     = $active_theme->get( 'Template' );

		return ! empty( $template );
	}

	function get_addon_config( $plugin_slug ) {
		if ( ! empty( $this->plugins[ $plugin_slug ] ) ) {
			return $this->plugins[ $plugin_slug ];
		}
	}

	/**
	 * Returns the status and actions for a plugin
	 *
	 * @param  string $plugin_slug The plugin slug
	 *
	 * @return array  The status and actions for the requested plugin
	 */
	function get_plugin_status( $plugin_slug ) {

		$status        = array();
		$plugin_config = $this->get_addon_config( $plugin_slug );

		if ( isset( $plugin_config['addon_type'] ) && $plugin_config['addon_type'] === 'child_theme' ) {
			// We have a theme
			if ( $this->is_child_theme_installed() ) {
				// Check if the theme is active or not
				if ( $this->is_child_theme_active() ) {
					$status['status']      = 'sq-active sq-addons-disabled';
					$status['status_text'] = esc_html__( 'Active', 'kleo' );
					$status['action_text'] = esc_html__( 'Child theme installed and active', 'kleo' );
					$status['action']      = 'no_action';
				} else {
					$status['status']      = 'sq-inactive';
					$status['status_text'] = esc_html__( 'Inactive', 'kleo' );
					$status['action_text'] = esc_html__( 'Activate child theme', 'kleo' );
					$status['action']      = 'enable_child_theme';
				}
			} else {
				$status['status']      = 'sq-needs-install';
				$status['status_text'] = esc_html__( 'Not installed', 'kleo' );
				$status['action_text'] = esc_html__( 'Install child theme', 'kleo' );
				$status['action']      = 'install_theme';

				if ( ! current_user_can( 'install_themes' ) ) {
					$status['status']      = 'sq-not-installed sq-addons-disabled';
					$status['action_text'] = esc_html__( 'Permissions needed to install child themes. Contact site administrator.', 'kleo' );
					$status['action']      = 'contact_network_admin';
				}

			}
		} else {
			if ( $this->is_plugin_installed( $plugin_slug ) ) {
				if ( $this->does_plugin_have_update( $plugin_slug ) ) {
					$status['status']      = 'sq-has-update';
					$status['status_text'] = esc_html__( 'Needs update', 'kleo' );
					$status['action_text'] = esc_html__( 'Update plugin', 'kleo' );
					$status['action']      = 'update_plugin';
				} elseif ( $this->check_plugin_active( $plugin_slug ) ) {
					$status['status']      = 'sq-active';
					$status['status_text'] = esc_html__( 'Active', 'kleo' );
					$status['action_text'] = esc_html__( 'Deactivate plugin', 'kleo' );
					$status['action']      = 'disable_plugin';
				} else {
					$status['status']      = 'sq-inactive';
					$status['status_text'] = esc_html__( 'Inactive', 'kleo' );
					$status['action_text'] = esc_html__( 'Activate plugin', 'kleo' );
					$status['action']      = 'enable_plugin';
				}
			} else {
				$status['status']      = 'sq-not-installed';
				$status['status_text'] = esc_html__( 'Not Installed', 'kleo' );
				$status['action_text'] = esc_html__( 'Install plugin', 'kleo' );
				$status['action']      = 'install_plugin';

				if ( ! current_user_can( 'install_plugins' ) ) {
					$status['status']      = 'sq-not-installed sq-addons-disabled';
					$status['action_text'] = esc_html__( 'You don\'t have permission to install plugins. Contact site administrator.', 'kleo' );
					$status['action']      = 'contact_network_admin';
				}

			}
		}


		return $status;
	}

	/**
	 * Inject information into the 'update_plugins' site transient as WP checks that before running an update.
	 *
	 * @since 1.0.0
	 *
	 * @param array $plugins The plugin information for the plugins which are to be updated.
	 */
	public function inject_update_info( $plugins ) {
		$this->tgmpa->inject_update_info( $plugins );
	}

	/**
	 * Performs plugin update
	 * @return bool
	 */
	function plugin_has_update( $slug ) {
		if ( empty( $this->plugins[ $slug ] ) ) {
			return false;
		}

		$installed_version = $this->get_installed_version( $slug );
		$minimum_version   = $this->plugins[ $slug ]['version'];

		return version_compare( $minimum_version, $installed_version, '>' );

	}

	/**
	 * Performs plugins installation
	 *
	 * @param string $slug
	 * @param boolean $echo
	 *
	 * @return void|array|boolean
	 */
	function do_plugin_install( $slug, $echo = true, $activate_too = true ) {

		if ( empty( $this->plugins[ $slug ] ) ) {
			return false;
		}

		$status = $this->get_plugin_status( $slug );

		if ( ! current_user_can( 'install_plugins' ) ) {
			$status['error'] = 'You don\'t have permissions to install plugins';
			if ( $echo ) {
				wp_send_json_error( $status );
			} else {
				return $status;
			}
		}

		$_GET['plugin']        = $slug;
		$_GET['tgmpa-install'] = 'install-plugin';

		$nonce                   = wp_create_nonce( 'tgmpa-install' );
		$_REQUEST['tgmpa-nonce'] = $nonce;

		if ( $activate_too === true ) {
			$this->tgmpa->is_automatic = true;
		} else {
			$this->tgmpa->is_automatic = false;
		}

		$this->tgmpa->install_plugins_page();

		$status = $this->get_plugin_status( $slug );

		if ( $echo ) {
			wp_send_json_success( $status );
		} else {
			return $status;
		}

	}

	/**
	 * Performs a plugin deactivation
	 * @return type
	 */
	function do_plugin_deactivate( $slug ) {

		$status = $this->get_plugin_status( $slug );

		if ( empty( $this->plugins[ $slug ] ) ) {
			$status['error'] = 'We have no data about this plugin.';
			wp_send_json_error( $status );
		}

		deactivate_plugins( $this->plugins[ $slug ]['file_path'] );

		$status = $this->get_plugin_status( $slug );
		wp_send_json_success( $status );

	}

	/**
	 * Performs plugins activation
	 *
	 * @param string $slug
	 * @param bool $echo
	 *
	 * @return void | array
	 */
	function do_plugin_activate( $slug, $echo = true ) {

		$status = $this->get_plugin_status( $slug );

		if ( empty( $this->plugins[ $slug ] ) ) {
			$status['error'] = 'We have no data about this plugin.';
			if ( $echo ) {
				wp_send_json_error( $status );
			} else {
				return $status;
			}
		}

		$result = activate_plugin( $this->plugins[ $slug ]['file_path'] );
		if ( is_wp_error( $result ) ) {
			$status['error'] = $result->get_error_message();
			if ( $echo ) {
				wp_send_json_error( $status );
			} else {
				return $status;
			}
		}

		$status = $this->get_plugin_status( $slug );
		if ( $echo ) {
			wp_send_json_success( $status );
		} else {
			return $status;
		}
	}

	/**
	 * Returns the install url for the current plugin
	 *
	 * @param string $slug
	 *
	 * @return string
	 */
	public function get_download_url( $slug ) {
		return $this->tgmpa->get_download_url( $slug );
	}

	/**
	 * Check if a plugin is installed. Does not take must-use plugins into account.
	 *
	 * @since 1.0.0
	 *
	 * @param string $slug Plugin slug.
	 *
	 * @return bool True if installed, false otherwise.
	 */
	public function is_plugin_installed( $slug ) {

		return $this->tgmpa->is_plugin_installed( $slug );
	}

	/**
	 * Check whether there is an update available for a plugin.
	 *
	 * @since 1.0.0
	 *
	 * @param string $slug Plugin slug.
	 *
	 * @return false|string Version number string of the available update or false if no update available.
	 */
	public function does_plugin_have_update( $slug ) {
		return $this->tgmpa->does_plugin_have_update( $slug );
	}

	/**
	 * Check if a plugin is active.
	 *
	 * @since 1.0.0
	 *
	 * @param string $slug Plugin slug.
	 *
	 * @return bool True if active, false otherwise.
	 */
	public function check_plugin_active( $slug ) {
		return $this->tgmpa->is_active( $slug );
	}

	/**
	 * Retrieve the version number of an installed plugin.
	 *
	 * @since 1.0.0
	 *
	 * @param string $slug Plugin slug.
	 *
	 * @return string Version number as string or an empty string if the plugin is not installed
	 *                or version unknown (plugins which don't comply with the plugin header standard).
	 */
	public function get_installed_version( $slug ) {

		return $this->tgmpa->get_installed_version( $slug );
	}

	/**
	 * Wrapper around the core WP get_plugins function, making sure it's actually available.
	 *
	 * @since 1.0.0
	 *
	 * @param string $plugin_folder Optional. Relative path to single plugin folder.
	 *
	 * @return array Array of installed plugins with plugin information.
	 */
	public function get_plugins( $plugin_folder = '' ) {
		return $this->tgmpa->get_plugins( $plugin_folder );
	}

}
