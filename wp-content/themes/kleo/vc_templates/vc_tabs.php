<?php
$output = $title = $interval = $el_class = '';
extract( shortcode_atts( array(
	'title'       => '',
	'type'        => 'tabs',
	'active_tab'  => '1',
	'style'       => 'default',
	'style_pills' => 'square',
	'align'       => '',
	'margin_top'  => '',
	'interval'    => 0,
	'position'    => '',
	'no_tab_drop' => '',
	'el_class'    => ''
), $atts ) );

if ( '' !== $el_class ) {
	$el_class = ' ' . str_replace( '.', '', $el_class );
}

$element = 'kleo-tabs';

if ( isset( $this ) ) {
	$shortcode = $this->shortcode;
}

if ( 'vc_tour' == $shortcode ) {
	$element = 'wpb_tour';
	$type    = 'tab';
}

$align = $align != "" ? " tabs-" . $align : "";


if ( $type == 'pills' ) {
	$style = $style_pills;
}

$style_att = '';
if ( $margin_top != '' ) {
	$style_att .= ' style="margin-top:' . (int) $margin_top . 'px"';
}

// Extract tab titles
//preg_match_all( '/vc_tab title="([^\"]+)"(\stab_id\=\"([^\"]+)\"){0,1}(\sicon\=\"([^\"]+)\")*/i', $content, $matches, PREG_OFFSET_CAPTURE );
preg_match_all( '/(?:kleo_tab|vc_tab)([^\]]+)/i', $content, $matches, PREG_OFFSET_CAPTURE );

$tab_titles = [];

/**
 * vc_tabs
 *
 */
$i = 1;
global $kleo_tab_active;

$active_tab = (int) $active_tab != 0 ? $active_tab : 1;

if ( isset( $matches[0] ) ) {
	$tab_titles = $matches[0];
}

$tabs_nav = '';
$extra_class = '';
if( $no_tab_drop != '') {
    $extra_class = ' no-tabdrop';
}
$tabs_nav .= '<ul class="nav nav-' . $type . ' responsive-' . $type . ' ' . $type . '-style-' . $style . $align . $extra_class . '">';
foreach ( $tab_titles as $tab ) {
	$tab_atts = shortcode_parse_atts( $tab[0] );

	$iconClass = '';
	if ( isset( $tab_atts['icon'] ) && $tab_atts['icon'] ) {
		$iconClass = 'icon-' . str_replace( "icon-", "", $tab_atts['icon'] );
	} elseif ( isset( $tab_atts['icon_type'] ) ) {
		$iconClass = isset( $tab_atts[ "icon_" . $tab_atts['icon_type'] ] ) ? $tab_atts[ "icon_" . $tab_atts['icon_type'] ] : "";
	}
	if ( isset( $tab_atts['title'] ) ) {
		$tabid = ( ( isset( $tab_atts['tab_id'] ) && $tab_atts['tab_id'] != __( "Tab", "js_composer" ) ) ? $tab_atts['tab_id'] : esc_attr( str_replace( "%", "", sanitize_title_with_dashes( $tab_atts['title'] ) ) ) );

		$icon = $iconClass != '' ? '<i class="' . esc_attr( $iconClass ) . '"></i> ' : '';

		$tabs_nav .= '<li' . ( $i == $active_tab ? ' class="active"' : '' ) . '>' .
		             '<a href="#tab-' . esc_attr( $tabid ) . '" data-toggle="tab" onclick="return false;">' .
		             $icon . $tab_atts['title'] .
		             '</a></li>';
		if ( $i == $active_tab ) {
			$kleo_tab_active = $tabid;
		}
	}
	$i ++;
}
$tabs_nav .= '</ul>' . "\n";

$css_class = apply_filters( 'vc_shortcodes_css_class', trim( $element . ' tabbable ' . $el_class ), $shortcode, $atts );

if ( $position != '' ) {
	$css_class .= ' pos-' . $position;
}

$output .= "\n\t" . '<div class="' . $css_class . '"' . $style_att . ' data-interval="' . $interval . '">';
//$output .= wpb_widget_title(array('title' => $title, 'extraclass' => $element.'_heading'));
$output .= "\n\t\t\t" . $tabs_nav;
$output .= '<div class="tab-content">';
$output .= "\n\t\t\t" . kleo_remove_wpautop( $content );
if ( 'vc_tour' == $shortcode ) {
	$output .= "\n\t\t\t" . '<div class="wpb_tour_next_prev_nav clearfix">' .
	           '<small>' .
	           '<span class="tour_prev_slide">' .
	           '<a href="#" title="' . esc_html__( 'Previous section', 'kleo' ) . '">' . esc_html__( 'Previous section', 'kleo' ) . '</a>' .
	           '</span> | ' .
	           '<span class="tour_next_slide">' .
	           '<a href="#" title="' . esc_html__( 'Next section', 'kleo' ) . '">' . esc_html__( 'Next section', 'kleo' ) . '</a>' .
	           '</span></small></div>';
}
$output .= '</div>';
$output .= "\n\t" . '</div> ';

echo $output; // PHPCS: XSS ok.