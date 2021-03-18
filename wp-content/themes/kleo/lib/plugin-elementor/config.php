<?php
/**
 * Elementor integration
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SQElementorWidgets {

	private static $instance = null;

	public static function get_instance() {
		if ( ! self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public function init() {
		add_action( 'elementor/elements/categories_registered', array( $this, 'add_widget_categories' ) );
		add_action( 'elementor/widgets/widgets_registered', array( $this, 'widgets_registered' ) );

		add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'admin_styles' ] );

		add_action( 'elementor/documents/register_controls', [ $this, 'register_template_control' ] );

		add_action( 'elementor/element/section/section_background/after_section_start', [
			$this,
			'custom_section_field'
		], 10, 2 );

		// Section changes.
		add_action( 'elementor/element/after_add_attributes', [ $this, 'section_custom_changes' ] );
		add_filter( 'elementor/section/print_template', [ $this, 'section_js_content' ], 10, 2 );


		add_action( 'customize_save_after', [ $this, 'update_color_scheme' ] );

		add_action( 'wp', [ $this, 'apply_page_settings' ] );

		if ( isset( $_GET['elementor_library'] ) ) {

			add_filter( 'template_include', function ( $template ) {
				$new_tpl = locate_template( 'page-templates/full-width.php' );
				if ( $new_tpl ) {
					return $new_tpl;
				}

				return $template;
			} );

			add_filter( 'option_bp-pages', function ( $value ) {
				$value['register'] = - 1;

				return $value;
			} );
		}

		add_action( 'elementor/controls/controls_registered', [ $this, 'modify_icons' ] );

		//only on edit screen
		if ( defined( 'ELEMENTOR_VERSION' ) && isset( $_GET['elementor-preview'] ) ) {
			add_action( 'wp_footer', [ $this, 'wp_footer' ] );
		}

		/* Elementor Pro */
		add_action( 'elementor/theme/register_locations', [ $this, 'register_elementor_locations' ] );
	}

	/**
	 * @param $elementor_theme_manager
	 */
	public function register_elementor_locations( $elementor_theme_manager ) {
		$elementor_theme_manager->register_location(
			'header',
			[
				'hook'         => 'kleo_header',
				'remove_hooks' => [ 'kleo_show_header' ],
			]
		);
	}

	public function custom_section_field( $section, $args ) {
		$section->add_control( 'kleo_section_style',
			[
				'label'       => esc_html__( 'Section default colors', 'kleo' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'default'     => 'main',
				'options'     => array(
					'main'      => 'Main',
					'alternate' => 'Alternate',
					'header'    => 'Header',
					'side'      => 'Side',
					'footer'    => 'Footer',
					'socket'    => 'Socket',
				),
				'description' => 'These are the color schemes defined in Customizer - Styling options',
			]
		);
	}

	/**
	 * Front-end changes based on custom settings for widgets
	 *
	 * @param \Elementor\Widget_Base $element
	 */
	public function section_custom_changes( $element ) {

		// Get the settings
		$settings = $element->get_settings();

		if ( $settings['kleo_section_style'] !== '' ) {
			$element->add_render_attribute( '_wrapper', 'class', $settings['kleo_section_style'] . '-color' );
		}

	}

	/**
	 * Adding section style field
	 *
	 * @param $content
	 * @param \Elementor\Widget_Base $widget
	 *
	 * @return string
	 */
	public function section_js_content( $content, $widget ) {

		$after   = "<# if (settings.kleo_section_style !== 'main' && settings.kleo_section_style !== '') { " .
		           "jQuery(view.el).attr('class', function(_, old){
                        return jQuery.grep(old.split(/ +/), function(v){
                             return !v.match(/-color$/);
                        }).join(' ');
                    });" .
		           "jQuery(view.el).addClass( settings.kleo_section_style + '-color' );" .
		           "} #>";
		$content .= $after;

		return $content;
	}


	/**
	 * Add our icon sets
	 *
	 * @param $controls_registry
	 */
	function modify_icons( $controls_registry ) {
		// Get existing icons
		$icons = $controls_registry->get_control( 'icon' )->get_settings( 'options' );
		// Append new icons
		$new_icons = array_merge(
			kleo_icons_array( 'icon-' ),
			$icons
		);
		// Then we set a new list of icons as the options of the icon control
		$controls_registry->get_control( 'icon' )->set_settings( 'options', $new_icons );
	}

	public function apply_page_settings() {


		if ( ! is_singular() ) {
			return;
		}

		// Get the current post id
		$post_id = get_the_ID();

		// Get the page settings manager
		$page_settings_manager = \Elementor\Core\Settings\Manager::get_settings_managers( 'page' );

		// Get the settings model for current post
		$page_settings_model = $page_settings_manager->get_model( $post_id );

		$header_transparent = $page_settings_model->get_settings( 'svq_transparent_header' );
		$header_menu_color  = $page_settings_model->get_settings( 'svq_transparent_menu_color' );
		$header_top_bar     = $page_settings_model->get_settings( 'svq_top_bar' );

		if ( ! isset( $_GET['elementor-preview'] ) && $header_transparent == 1 ) {
			add_filter( 'body_class', function ( $classes ) use ( $header_menu_color ) {
				$classes[] = 'navbar-transparent';

				if ( $header_menu_color === 'black' ) {
					$classes[] = 'on-light-bg';
				} else {
					$classes[] = 'on-dark-bg';
				}

				return $classes;
			} );
		}

		if ( $header_top_bar == 1 ) {
			add_filter( 'kleo_show_top_bar', '__return_zero' );
		}

	}


	public function register_template_control( $document, $control_id = 'template' ) {

		if ( ! $document instanceof \Elementor\Core\DocumentTypes\Post && ! $document instanceof \Elementor\Modules\Library\Documents\Page ) {
			return;
		}

		if ( ! \Elementor\Utils::is_cpt_custom_templates_supported() ) {
			return;
		}

		$document->start_injection( [
			'of'       => 'post_status',
			'fallback' => [
				'of' => 'post_title',
			],
		] );

		$document->add_control(
			'kleo_page_settings_sep',
			[
				'type'  => \Elementor\Controls_Manager::DIVIDER,
				'style' => 'thick',
				'label' => 'Test'
			]
		);

		$document->add_control(
			'kleo_page_settings_title',
			[
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw'  => '<strong>' . esc_html__( 'Kleo Settings', 'kleo' ) . '</strong>',
			]
		);

		$document->add_control(
			'svq_header',
			[
				'label'        => esc_html__( 'Hide Header', 'kleo' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_off'    => esc_html__( 'Off', 'kleo' ),
				'label_on'     => esc_html__( 'On', 'kleo' ),
				'default'      => '',
				'return_value' => '1',
				'selectors'    => [
					'#header' => 'display: none',
				]
			]
		);

		$document->add_control(
			'svq_transparent_header',
			[
				'label'        => esc_html__( 'Transparent Header', 'kleo' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_off'    => esc_html__( 'Off', 'kleo' ),
				'label_on'     => esc_html__( 'On', 'kleo' ),
				'default'      => '',
				'return_value' => '1',
			]
		);

		$document->add_control(
			'svq_transparent_menu_color',
			[
				'label'   => esc_html__( 'Transparent Menu Color', 'kleo' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'white' => __( 'White text', 'kleo' ),
					'black' => __( 'Black text', 'kleo' ),
				],
				'default' => 'white',
			]
		);

		$document->add_control(
			'svq_top_bar',
			[
				'label'        => esc_html__( 'Hide Top Header', 'kleo' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_off'    => esc_html__( 'Off', 'kleo' ),
				'label_on'     => esc_html__( 'On', 'kleo' ),
				'default'      => '',
				'return_value' => '1',
				'selectors'    => [
					'.social-header' => 'display: none',
				]
			]
		);

		$document->add_control(
			'svq_title_breadcrumb',
			[
				'label'        => esc_html__( 'Hide Title/Breadcrumb section', 'kleo' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_off'    => esc_html__( 'Off', 'kleo' ),
				'label_on'     => esc_html__( 'On', 'kleo' ),
				'default'      => '',
				'return_value' => '1',
				'selectors'    => [
					'.breadcrumbs-container' => 'display: none',
					'.page-title'            => 'display: none'
				]
			]
		);

		$document->add_control(
			'svq_breadcrumb',
			[
				'label'        => esc_html__( 'Hide Breadcrumb', 'kleo' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_off'    => esc_html__( 'Off', 'kleo' ),
				'label_on'     => esc_html__( 'On', 'kleo' ),
				'default'      => '',
				'return_value' => '1',
				'selectors'    => [
					'.breadcrumb-extra .breadcrumb' => 'display: none',
				]
			]
		);
		$document->add_control(
			'svq_info',
			[
				'label'        => esc_html__( 'Hide Extra Info', 'kleo' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_off'    => esc_html__( 'Off', 'kleo' ),
				'label_on'     => esc_html__( 'On', 'kleo' ),
				'default'      => '',
				'return_value' => '1',
				'selectors'    => [
					'.breadcrumb-extra .page-info' => 'display: none',
				]
			]
		);

		$document->end_injection();
	}

	public function update_color_scheme() {

		$style_options = kleo_style_options();
		if ( isset( $style_options['main'] ) ) {
			$style_options = $style_options['main'];

			$current = get_option( 'elementor_scheme_color' );
			//texts
			if ( $style_options['text'] ) {
				$current[1] = $style_options['text'];
				$current[3] = $style_options['text'];
			}
			//primary
			if ( $style_options['high_bg'] ) {
				$current[4] = $style_options['high_bg'];
			}
			//secondary
			if ( $style_options['alt_bg'] ) {
				$current[2] = $style_options['alt_bg'];
			}

			update_option( 'elementor_scheme_color', $current );
		}
	}

	public function get_elements() {
		$elements = [
			'revslider' => [
				'name'  => 'revslider',
				'cat'   => 'kleo-elements',
				'class' => 'KLeoElementorRevslider',
			],
			'divider'   => [
				'name'  => 'kleo-divider',
				'cat'   => 'kleo-elements',
				'class' => 'KLeoElementorDivider',
			],
            'testimonials'   => [
				'name'  => 'kleo-testimonials',
				'cat'   => 'kleo-elements',
				'class' => 'KleoElementorTestimonials',
			],

		];

		if ( function_exists( 'bp_is_active' ) ) {
			$elements += [
				'bp-members-grid'     => [
					'name'  => 'kleo-members-grid',
					'cat'   => 'kleo-elements',
					'class' => 'KleoElementorMembersGrid',
				],
				'bp-members-carousel' => [
					'name'  => 'kleo-members-carousel',
					'cat'   => 'kleo-elements',
					'class' => 'KleoElementorMembersCarousel',
				],
			];
		}

		return $elements;
	}

	public function get_tpl_path( $name ) {
		$widget_file   = 'overrides/elementor/' . $name . '.php';
		$template_file = locate_template( $widget_file );
		if ( ! $template_file || ! is_readable( $template_file ) ) {
			$template_file = dirname( __FILE__ ) . '/templates/' . $name . '.php';
		}
		if ( $template_file && is_readable( $template_file ) ) {
			return $template_file;
		}

		return false;
	}

	public function add_widget_categories() {
		if ( defined( 'ELEMENTOR_PATH' ) && class_exists( '\Elementor\Widget_Base' ) ) {
			if ( class_exists( '\Elementor\Plugin' ) ) {
				if ( is_callable( '\Elementor\Plugin', 'instance' ) ) {
					\Elementor\Plugin::instance()->elements_manager->add_category(
						'kleo-elements',
						[
							'title' => 'KLEO',
							'icon'  => 'fa fa-plug'
						]
					);
				}
			}
		}
	}

	public function widgets_registered() {

		if ( defined( 'ELEMENTOR_PATH' ) && class_exists( 'Elementor\Widget_Base' ) ) {
			// get our own widgets up and running:
			if ( class_exists( 'Elementor\Plugin' ) ) {
				if ( is_callable( 'Elementor\Plugin', 'instance' ) ) {
					$elementor = Elementor\Plugin::instance();

					if ( isset( $elementor->widgets_manager ) ) {
						if ( method_exists( $elementor->widgets_manager, 'register_widget_type' ) ) {

							$elements = $this->get_elements();
							foreach ( $elements as $k => $element ) {
								if ( $template_file = $this->get_tpl_path( $k ) ) {

									require_once $template_file;
									$class_name = 'Elementor\\' . $element['class'];
									Elementor\Plugin::instance()->widgets_manager->register_widget_type( new $class_name );
								}
							}
						}
					}
				}
			}
		}
	}

	public function admin_styles() {
		wp_register_style( 'svq-admin-icons', kleo_get_fonts_path(), false, '1.0', 'all' );
		wp_enqueue_style( 'svq-admin-icons' );
	}

	/**
	 * Loads only in edit mode
	 */
	public function wp_footer() {
		?>
        <script>
            jQuery(function ($) {

                elementor.settings.page.addChangeCallback('svq_transparent_header', svqElementorRefreshPage);
                elementor.settings.page.addChangeCallback('svq_transparent_menu_color', svqElementorRefreshPage);

                function svqElementorRefreshPage(newValue) {

                    elementor.reloadPreview();
                }

                if (window.elementorFrontend) {
                    elementorFrontend.hooks.addAction('frontend/element_ready/widget', function ($scope) {
                        if (jQuery($scope).find('.kleo-carousel-items')) {
                            setTimeout(function () {
                                if ($.fn.carouFredSel) {
                                    KLEO.main.carouselItems()
                                }
                            }, 200);
                        }
                    });
                }
            });
        </script>
		<?php
	}
}

SQElementorWidgets::get_instance()->init();

function svq_elementor_the_content( $page_id ) {
	if ( defined( 'ELEMENTOR_PATH' ) ) {
		echo \Elementor\Plugin::$instance->frontend->get_builder_content( $page_id );
	} else {
		$page = get_post( $page_id );
		echo apply_filters( 'the_content', $page->post_content );
	}
}

function svq_elementor_built_with_it( $page_id = null ) {
	if ( ! $page_id ) {
		return false;
	}

	if ( get_post_meta( $page_id, '_elementor_data', true ) && get_post_meta( $page_id, '_elementor_edit_mode', true ) ) {
		return true;
	}

	return false;
}
