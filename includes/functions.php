<?php

/*
CRM LastPosts Widget
Desde 10/02/2015
Versión 1.0.1
*/

//Función que devuelve la lista de tamaños disponibles
function get_image_sizes( $size = '' ) {

	global $_wp_additional_image_sizes;
	
	$sizes = array();
	$get_intermediate_image_sizes = get_intermediate_image_sizes();
	
	foreach( $get_intermediate_image_sizes as $_size ) {
	
		if ( in_array( $_size, array( 'thumbnail', 'medium', 'large' ) ) ) {
	
			$sizes[ $_size ]['width'] = get_option( $_size . '_size_w' );
			$sizes[ $_size ]['height'] = get_option( $_size . '_size_h' );
			$sizes[ $_size ]['crop'] = (bool) get_option( $_size . '_crop' );
	
		} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
	
			$sizes[ $_size ] = array( 
				'width' => $_wp_additional_image_sizes[ $_size ]['width'],
				'height' => $_wp_additional_image_sizes[ $_size ]['height'],
				'crop' =>  $_wp_additional_image_sizes[ $_size ]['crop']
			);
		}
	}
	
	if ( $size ) {
	
		if( isset( $sizes[ $size ] ) ) {
			return $sizes[ $size ];
		} else {
			return false;
		}
	}
	
	return $sizes;
}

//Función que pasa colores de hexadecimal a RGB y le añade la opacidad
function hex2rgb($hex, $opacity) {
	$hex = str_replace("#", "", $hex);
	
	if(strlen($hex) == 3) {
	  $r = hexdec(substr($hex,0,1).substr($hex,0,1));
	  $g = hexdec(substr($hex,1,1).substr($hex,1,1));
	  $b = hexdec(substr($hex,2,1).substr($hex,2,1));
	} else {
	  $r = hexdec(substr($hex,0,2));
	  $g = hexdec(substr($hex,2,2));
	  $b = hexdec(substr($hex,4,2));
	}
	//$rgb = array($r, $g, $b);
	//return implode(",", $rgb);
	
	$rgb = "rgba(".$r.",".$g.",".$b.",".$opacity.");";
	
	return $rgb;
}

?>