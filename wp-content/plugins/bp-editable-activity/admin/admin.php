<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 0 );
}

/**
 * Admin helper.
 */
class BP_Editable_Activity_Admin {

	/**
	 * Reference to Options Buddy Page.
	 *
	 * @var OptionsBuddy_Settings_Page
	 */
	private $page;

	/**
	 * Constructor.
	 */
	public function __construct() {
		// create a options page.
		// make sure to read the code below.
		$this->page = new OptionsBuddy_Settings_Page( 'bp-editable-activity' );
		// make it to use bp_get_option/bp_update_option.
		$this->page->set_bp_mode();

		add_action( 'admin_init', array( $this, 'admin_init' ) );
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_footer', array( $this, 'admin_css' ) );
	}

	/**
	 * Setup.
	 */
	public function admin_init() {

		$page = $this->page;
		// add_section
		// you can pass section_id, section_title, section_description, the section id must be unique for this page, section descriptiopn is optional.
		$page->add_section( 'basic_section', __( 'BP Editable Activity Settings', 'bp-editable-activity' ), __( 'You can manage all the settings for BP editable activity from here.', 'bp-editable-activity' ) );

		// add fields.
		$page->get_section( 'basic_section' )->add_fields( array(
			array(
				'name'    => 'allow_activity_editing',
				'label'   => __( 'Allow editing Activity?', 'bp-editable-activity' ),
				'desc'    => __( 'Do you want your Users to be able to edit activity?', 'bp-editable-activity' ),
				'type'    => 'radio',
				'default' => 'yes',
				'options' => array(
					'yes' => __( 'Yes', 'bp-editable-activity' ),
					'no'  => __( 'No', 'bp-editable-activity' ),
				),
			),
			array(
				'name'    => 'activity_allowed_time',
				'label'   => __( 'Allow editing only before the given time', 'bp-editable-activity' ),
				'desc'    => __( 'Put a number. This is the number of minute after posting of activity. Zero means no limit', 'bp-editable-activity' ),
				'type'    => 'text',
				'default' => 10, // 10 minutes.
			),
			array(
				'name'    => 'keep_log',
				'label'   => __( 'Keep Log of Edited activity Items?', 'bp-editable-activity' ),
				'desc'    => __( 'If you enable, It will store the original activity content in activity meta table. Not suggested if you don\'t plan to use it in future!', 'bp-editable-activity' ),
				'type'    => 'radio',
				'default' => 'yes',
				'options' => array(
					'yes' => __( 'Yes', 'bp-editable-activity' ),
					'no'  => __( 'No', 'bp-editable-activity' ),
				),

			),
			array(
				'name'    => 'allow_comment_editing',
				'label'   => __( 'Allow Editing of activity Comment', 'bp-editable-activity' ),
				'desc'    => __( 'Do you want your users to be able to edit their activity comment?', 'bp-editable-activity' ),
				'type'    => 'radio',
				'default' => 'yes',
				'options' => array(
					'yes' => __( 'Yes', 'bp-editable-activity' ),
					'no'  => __( 'No', 'bp-editable-activity' ),
				),
			),
			array(
				'name'    => 'comment_allowed_time',
				'label'   => __( 'Allow editing only before the given time', 'bp-editable-activity' ),
				'desc'    => __( 'Put a number. This is the number of minute after posting of activity. Zero means no limit', 'bp-editable-activity' ),
				'type'    => 'text',
				'default' => 10,

			),

		) );


		$page->init();

	}

	/**
	 * Add admin menu.
	 */
	public function admin_menu() {
		add_options_page( __( 'BP Editable Activity', 'bp-editable-activity' ), __( 'BP Editable Activity', 'bp-editable-activity' ), 'manage_options', 'bp-editable-activity', array(
			$this->page,
			'render',
		) );
	}

	/**
	 * Add css.
	 */
	public function admin_css() {

		if ( ! isset( $_GET['page'] ) || $_GET['page'] != 'bp-editable-activity' ) {
			return;
		}

		?>

        <style type="text/css">
            .wrap .postbox {
                padding: 10px;
            }
        </style>

		<?php
	}
}

new BP_Editable_Activity_Admin();
