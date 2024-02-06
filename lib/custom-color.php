<?php
/**
 * Custom Color
 *
 */

function emarket_adjustBrightness($hexCode, $adjustPercent) {
    $hexCode = ltrim($hexCode, '#');

    if (strlen($hexCode) == 3) {
        $hexCode = $hexCode[0] . $hexCode[0] . $hexCode[1] . $hexCode[1] . $hexCode[2] . $hexCode[2];
    }

    $hexCode = array_map('hexdec', str_split($hexCode, 2));

    foreach ($hexCode as & $color) {
        $adjustableLimit = $adjustPercent < 0 ? $color : 255 - $color;
        $adjustAmount = ceil($adjustableLimit * $adjustPercent);

        $color = str_pad(dechex($color + $adjustAmount), 2, '0', STR_PAD_LEFT);
    }

    return '#' . implode($hexCode);
}
 

function emarket_custom_color(){
	$color = emarket_options()->getCpanelValue( 'scheme_color' );
	$scheme_meta = get_post_meta( get_the_ID(), 'scheme', true );
	$scheme = ( $scheme_meta != '' && $scheme_meta != 'none' && is_page() ) ? $scheme_meta : emarket_options()->getCpanelValue('scheme');
	$url = "'../assets/img/$scheme'";
	if( $color == '' ) {
		switch( $scheme ){
			case 'orange':
				$color = '#ff9600';
			break;
			case 'blue':
				$color = '#18bcec';
			break;
			case 'blue2':
				$color = '#5bc0ec';
			break;
			case 'blue3':
				$color = '#09abfe';
			break;
			case 'brown':
				$color = '#886016';
			break;
			case 'brown2':
				$color = '#a55e40';
			break;
			case 'green':
				$color = '#90b939';
			break;
			case 'green2':
				$color = '#78a206';
			break;
			case 'green3':
				$color = '#388a95';
			break;
			case 'green4':
				$color = '#01728e';
			break;
			case 'green5':
				$color = '#13bf98';
			break;
			case 'green6':
				$color = '#125430';
			break;
			case 'green7':
				$color = '#2cb9a8';
			break;
			case 'green36':
				$color = '#0c9245';
			break;
			case 'green37':
				$color = '#00cc96';
			break;
			case 'orange2':
				$color = '#ff5c00';
			break;
			case 'orange3':
				$color = '#fcb700';
			break;
			case 'orange4':
				$color = '#ffd200';
			break;
			case 'orange5':
				$color = '#fbb71c';
			break;
			case 'orange6':
				$color = '#cb9400';
			break;
			case 'pink':
				$color = '#e30078';
			break;
			case 'plum':
				$color = '#9e0b0f';
			break;
			case 'red':
				$color = '#e82223';
			break;
			case 'red2':
				$color = '#ff4157';
			break;
			case 'red3':
				$color = '#eb0036';
			break;
			case 'red4':
				$color = '#d14031';
			break;
			case 'red5':
				$color = '#ed1b24';
			break;
			case 'red6':
				$color = '#e95225';
			break;
			case 'red7':
				$color = '#c12950';
			break;		
			default:
			$color = '#ff3c20';
		}
	}
	$darken5 = emarket_adjustBrightness( $color, -0.05 );
	$darken10 = emarket_adjustBrightness( $color, -0.1 );
	$darken15 = emarket_adjustBrightness( $color, -0.15 );
	$darken20 = emarket_adjustBrightness( $color, -0.2 );
	$lighten5 = emarket_adjustBrightness( $color, 0.05 );
	$lighten10 = emarket_adjustBrightness( $color, 0.1 );
	$lighten15 = emarket_adjustBrightness( $color, 0.15 );
	$lighten20 = emarket_adjustBrightness( $color, 0.20 );

	$custom_css =" 
		:root {--color: $color; --bg_url: $url; --darken5: $darken5;--darken10: $darken10;--darken15: $darken15;--darken20: $darken20; --lighten5: $lighten5;--lighten10: $lighten10;--lighten15: $lighten15;--lighten20: $lighten20; --url: $url }
	";
	wp_add_inline_style( 'emarket_css', $custom_css );
}
add_action( 'wp_enqueue_scripts', 'emarket_custom_color', 101 );