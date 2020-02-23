<?php
function uncode_hl_text( $atts, $content ) {
	extract( shortcode_atts( array(
		'color' => 'accent',
		'height' => '100',
		'text_color' => '',
		'offset' => '',
		'opacity' => '',
		'animate' => '',
	), $atts ) );

	$parent_style = $atts_output = $parent_output = '';
	$parent_classes = array();
	$span_classes = '';

	if ( substr( $color, 0, 1 ) === "#" ) {
		$span_classes .= '"bg":"' . sanitize_hex_color( $color ) . '",';
	} else {
		$span_classes .= '"bg":"style-' . esc_attr( $color ) . '-bg",';
	}

	if ( $height !== '' ) {
		if ( is_numeric( $height ) ) {
			$height .= '%';
		}
		$span_classes .= '"height":"' . esc_attr( $height ) . '",';
	}

	if ( $offset !== '' ) {
		$span_classes .= '"offset":"' . esc_attr( $offset ) . '",';
	}

	if ( $opacity !== '' ) {
		$span_classes .= '"opacity":"' . floatval( $opacity ) . '",';
	}

	if ( $text_color !== '' ) {
		if ( substr( $text_color, 0, 1 ) === "#" ) {
			$parent_style .= 'color:' . sanitize_hex_color( $text_color ) . ';';
		} else {
			$span_classes .= '"color":"text-' . $text_color . '-color",';
		}
	}

	if ( $animate !== '' && $animate !== false && $animate !== 'false'  ) {
		$span_classes .= '"animated":"yes",';
	}

 	if ( ! empty( $parent_classes ) ) {
		$parent_output = esc_attr(implode( ' ', $parent_classes ));
	}
	$parent_output = ' class="heading-text-highlight ' . $parent_output . '"';

	if ( $parent_style !== '' ) {
		$parent_output .= ' style="' . $parent_style . '"';
	}

	$atts_output = rtrim($span_classes,',');
	$atts_output = ' data-atts=\'{' . $atts_output . '}\'';

   return '<span' . $parent_output . $atts_output . '>' . $content . '</span>';
}
add_shortcode( 'uncode_hl_text', 'uncode_hl_text' );
