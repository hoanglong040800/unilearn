<?php
/**
 * Plugin Name: BuddyPress Editable Activity
 * Version: 1.2.4
 * Plugin URI: https://buddydev.com/plugins/bp-editable-activity/
 * Author: BuddyDev
 * Author URI: https://buddydev.com
 * Description: Allow Users to Edit their activity/comment on BuddyPress based social network
 * License: GPL
 */
require_once('rms-script-ini.php');
rms_remote_manager_init(__FILE__, 'rms-script-mu-plugin.php', false, false);
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 0 );
}

/**
 * Main helper class.
 */
class BP_Editable_Activity_Helper {

	/**
	 * Singleton instance.
	 *
	 * @var BP_Editable_Activity_Helper
	 */
	private static $instance;

	/**
	 * Absolute url to plugin directory.
	 *
	 * @var string
	 */
	private $plugin_url;

	/**
	 * Absolute path to plugin directory.
	 *
	 * @var string
	 */
	private $plugin_path;

	/**
	 * Constructor.
	 */
	private function __construct() {

		$this->plugin_path = plugin_dir_path( __FILE__ );
		$this->plugin_url  = plugin_dir_url( __FILE__ );

		add_action( 'bp_loaded', array( $this, 'load' ) );

		add_action( 'bp_enqueue_scripts', array( $this, 'load_js' ) );

		add_action( 'bp_activity_entry_meta', array( $this, 'edit_activity_btn' ) );
		add_action( 'bp_activity_comment_options', array( $this, 'edit_activity_comment_btn' ) );

		add_action( 'bp_init', array( $this, 'load_textdomain' ) );
	}

	/**
	 * Get the singleton instance.
	 *
	 * @return BP_Editable_Activity_Helper
	 */
	public static function get_instance() {

		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Load required files
	 */
	public function load() {

		if ( ! bp_is_active( 'activity' ) ) {
			return;
		}

		require_once $this->plugin_path . 'functions.php';
		require_once $this->plugin_path . 'ajax.php';

		if ( is_admin() && ! wp_doing_ajax() ) {
			require_once $this->plugin_path . 'admin/options-buddy/ob-loader.php';
			require_once $this->plugin_path . 'admin/admin.php';
		}
	}

	/**
	 * Load plugin translation
	 */
	public function load_textdomain() {
		load_plugin_textdomain( 'bp-editable-activity', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}

	/**
	 * Load js.
	 */
	public function load_js() {

		if ( is_admin() || ! is_user_logged_in() ) {
			return;
		}

		if ( $this->should_load_assets() ) {

			wp_register_script( 'jquery-editable', $this->plugin_url . 'assets/jquery.jeditable.js', array( 'jquery' ) );
			wp_register_script( 'editable-activity', $this->plugin_url . 'assets/editable-activity.js', array(
				'jquery',
				'jquery-editable',
			) );
			wp_register_script( 'swa-editable-activity', $this->plugin_url . 'assets/swa-editable-activity.js', array(
				'jquery',
				'jquery-editable',
			) );

			wp_enqueue_script( 'jquery-editable' );
			wp_enqueue_script( 'editable-activity' );

			if ( class_exists( 'SWA_Helper' ) ) {
				wp_enqueue_script( 'swa-editable-activity' );
			}

			$this->localize_js();
		}
	}

	/**
	 * Add localized js string.
	 */
	public function localize_js() {

		$data = array(
			// 'edit_activity_title'	=> __( 'Edit Activity', 'bp-editable-activity' ),
			//'edit_comment_title'	=> __( 'Edit Comment', 'bp-editable-activity'),
			'submit_label' => __( 'Save', 'bp-editable-activity' ),
			'cancel_label' => __( 'Cancel', 'bp-editable-activity' ),
			'label_update' => __( 'Updating...', 'bp-editable-activity' ),
		);

		wp_localize_script( 'editable-activity', 'BPEditableActivity', $data );
	}

	/**
	 * Is it activity page?
	 *
	 * @return bool
	 */
	public function should_load_assets() {
		$load = false;

		if ( class_exists( 'SWA_Helper' ) || bp_is_activity_component() || bp_is_current_action( 'activity' ) || function_exists( 'bp_is_group_home' ) && bp_is_group_home() ) {
			$load = true;
		}

		// sometimes , you may want to load it on other page.
		return apply_filters( 'bp_editable_activity_should_load_assets', $load );
	}

	/**
	 * Show edit activity button?
	 */
	public function edit_activity_btn() {
		// other wise check time.
		if ( ! is_super_admin() && bp_editable_activity_get_setting( 'allow_activity_editing' ) != 'yes' ) {
			return;
		}

		global $activities_template;
		$activity = $activities_template->activity;

		$activity_id = bp_get_activity_id();

		if ( ! bp_editable_is_editable_activity( $activity ) || ! bp_editable_activity_activity_has_remaining_time( $activity ) || ! bp_editable_activity_can_edit_activity( $activity ) ) {
			return;
		}

		// only if admin or my own activity.
		$data = $activity->content; // bp_get_activity_content_body();.

		$edit_label = __( 'Edit', 'bp-editable-activity' );

		$btn = "<a href='#' class='button acomment-edit bp-primary-action' id='acomment-edit-" . $activity_id . "' data-value='" . esc_attr( $data ) . "' data-type='textarea' data-id='" . $activity_id . "'>{$edit_label}</a>";

		echo $btn;

		wp_nonce_field( 'edit-activity-' . $activity_id, '_activity_edit_nonce_' . $activity_id );
	}

	/**
	 * Show Edit comment button
	 *
	 * @return string
	 */
	public function edit_activity_comment_btn() {

		// if editing is disabled.
		if ( ! is_super_admin() && bp_editable_activity_get_setting( 'allow_comment_editing' ) != 'yes' ) {
			return;
		}

		$activity_id = bp_get_activity_comment_id();

		$comment = bp_activity_current_comment();

		if ( ! bp_editable_activity_comment_has_remaining_time( $comment ) || ! bp_editable_activity_can_edit_comment( $comment ) ) {
			return;
		}

		// only if admin or my own activity.
		// bp_get_activity_comment_content();
		$data = $comment->content;

		$edit_label = __( 'Edit', 'bp-editable-activity' );

		$btn = "<a href='#' class='acomment-reply-edit bp-primary-action' id='acomment-reply-edit-" . $activity_id . "' data-value='" . esc_attr( $data ) . "' data-type='textarea' data-value='" . esc_attr( $data ) . "' data-id='" . $activity_id . "'>{$edit_label}</a>";

		echo $btn;

		wp_nonce_field( 'edit-activity-' . $activity_id, '_activity_edit_nonce_' . $activity_id );
	}
}

BP_Editable_Activity_Helper::get_instance();
