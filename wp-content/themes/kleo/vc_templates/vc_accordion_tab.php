<?php
$output = $title = $tooltip_title = $tooltip_text = $tooltip_action = $tooltip_position = '';

extract( shortcode_atts( array(
	'title'            => __( "Section", "js_composer" ),
	'icon'             => '',
	'icon_closed'      => '',
	'tooltip'          => '',
	'tooltip_position' => '',
	'tooltip_title'    => '',
	'tooltip_text'     => '',
	'tooltip_action'   => 'hover',
	'el_id'            => '',

), $atts ) );

$span_closed_data = '';

global $kleo_acc_id, $kleo_acc_active_tab, $kleo_acc_count;
if ( isset( $el_id ) && ! empty( $el_id ) ) {
	$elem_id = $el_id;
} else {
	$elem_id = $kleo_acc_id . '-' . kleo_vc_elem_increment() . '-d';
}

$kleo_acc_count ++;

if ( $icon != '' ) {
	$icon = ' icon-' . $icon;
	if ( $kleo_acc_count != $kleo_acc_active_tab ) {
		$icon .= ' hide';
	}
}

if ( $icon_closed != '' ) {
	$icon_closed = ' icon-' . $icon_closed;

	if ( $kleo_acc_count == $kleo_acc_active_tab ) {
		$icon_closed .= ' hide';
	}
} elseif ( $icon != '' ) {
	$icon_closed = ' icon-' . $icon;
}

$tooltip_class = '';
$tooltip_data  = '';
if ( $tooltip != '' ) {
	if ( $tooltip == 'popover' ) {
		$tooltip_class = ' ' . esc_attr( $tooltip_action ) . '-pop';
		$tooltip_data  .= ' data-toggle="popover" data-container="body" data-title="' . esc_attr( $tooltip_title ) . '" data-content="' . esc_attr( $tooltip_text ) . '" data-placement="' . esc_attr( $tooltip_position ) . '"';
	} else {
		$tooltip_class .= ' ' . esc_attr( $tooltip_action ) . '-tip';
		$tooltip_data  .= ' data-toggle="tooltip" data-original-title="' . esc_attr( $tooltip_title ) . '" data-placement="' . esc_attr( $tooltip_position ) . '"';
	}
}

$icon_closed      .= $tooltip_class;
$span_closed_data .= $tooltip_data;

$extra_content_class = '';
if ( $kleo_acc_count == $kleo_acc_active_tab ) {
	$extra_content_class .= ' in';
}

$output .= '<div class="panel">';

$output .= '<div class="panel-heading">';
$output .= '<div class="panel-title">';
$output .= "\n\t\t\t\t" .
           '<a href="#acc-' . esc_attr( $elem_id ) . '" data-parent="#accordion-' . esc_attr( $kleo_acc_id ) . '" data-toggle="collapse" class="accordion-toggle">' .
           $title
           . '<span class="icon-closed' . esc_attr( $icon_closed ) . '"' . $span_closed_data . '></span> 
					<span class="icon-opened' . esc_attr( $icon ) . '"></span>'
           . '</a>';
$output .= '</div>';
$output .= '</div>';

$output .= '<div id="acc-' . esc_attr( $elem_id ) . '" class="panel-collapse collapse' . esc_attr( $extra_content_class ) . '">';
$output .= '<div class="panel-body">';
$output .= ( $content == '' || $content == ' ' ) ? esc_html__( "Empty section. Edit page to add content here.", "js_composer" ) : "\n\t\t\t\t" . wpb_js_remove_wpautop( $content );
$output .= '</div>';
$output .= '</div>' . $this->endBlockComment( '.panel-collapse' ) . "\n";


$output .= '</div>';

echo $output; // PHPCS: XSS ok.