<?php

error_reporting(0);

require_once "svglib/svglib.php";

$svg = SVGDocument::getInstance();
$svg->setWidth($final_width);
$svg->setHeight($final_height);

$style = new SVGStyle();
$style->setFill( '#000' );
$rect = SVGRect::getInstance( 0, 0, null, $final_width, $final_height, $style );
$svg->addShape( $rect );

foreach ( $render['words'] as $key => $val ) {
	static $i = 0;
	
	$style = new SVGStyle();
	$style->setFill( '#' . strtolower( $colors[$i % count($palette)] ) );
	
	$text = SVGText::getInstance( $val['x'] + $render['adjust']['dx'], $val['y'] + $render['adjust']['dy'], null, $key, $style );
	$text->setAttribute( 'font-size', $val['size'] * 1.25 );
	$text->setAttribute( 'font-family', 'Arial, Helvetica, sans-serif' );
	
	if ( $val['angle'] ) {
		$text->rotate( $val['angle'] + 180, $val['x'] + $render['adjust']['dx'], $val['y'] + $render['adjust']['dy'] );
	}
	
	$svg->addShape( $text );

	$i++;
}

$svg->addShape( $text );
$svg->asXML( 'files/' . $svg_file );