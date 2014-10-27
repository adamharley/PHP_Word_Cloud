<?php

require dirname(__FILE__).'/tagcloud.php';

$ignore = file( 'ignore.txt',  FILE_IGNORE_NEW_LINES );

$text = stripslashes( $_POST['text'] );
$text = iconv( 'UTF-8', 'ASCII//TRANSLIT', $text );
$text = str_replace( array( "\n", "\r" ), ' ', $text );
$text = str_replace( array( '.', ',', '(', ')', ':', '!', ';', '\'', '"' ), '', $text );

$arr_text = explode( ' ', $text );

foreach( $arr_text as $k => $word ) {
	$word = strtolower( trim( $word ) );
	
	if ( 1 >= strlen( $word ) ) {
		continue;
	}
	
	if ( empty( $word ) || in_array( $word, $ignore ) || is_numeric( $word ) ) {
		continue;
	}
	
	$new_text[$k] = $word;
}

$full_text = implode( ' ', $new_text );

$colors = array('FFA700', 'FFDF00', 'FF4F00', 'FFEE73');

$font = dirname(__FILE__).'/Arial.ttf';
$width = 1500;
$height = 1500;
$cloud = new WordCloud($width, $height, $font, $full_text);
$palette = Palette::get_palette_from_hex($cloud->get_image(), $colors);
$render = $cloud->render($palette);

$final_width = imagesx( $cloud->get_image() );
$final_height = imagesy( $cloud->get_image() );

// Render the cloud in a temporary file, and return its base64-encoded content
$file = tempnam(getcwd(), 'img');
imagepng($cloud->get_image(), $file);
$img64 = base64_encode(file_get_contents($file));
unlink($file);
imagedestroy($cloud->get_image());

$svg_file = time() . '.svg';
include('svg.php');
?>
<html>
<head>
<title>Snap 9000</title>
<style type="text/css">
body {
    background-color: #000;
    color: #fff;
	text-align: center;
}

a {
	color: #fff;
	text-decoration: none;
	font-style: italic;
}
</style>
</head>
<body>
<p><img src="data:image/png;base64, <?php echo $img64; ?>" border="0"/></p>
<p><a href="files/<?php echo $svg_file; ?>">SVG version</a></p>
</body>
</html>