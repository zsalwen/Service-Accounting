<?
include 'functions.php';
dbConnect();
$q="SELECT * FROM ps_users where id = '$id'";
$r=@mysql_query($q) or die(mysql_error());
$d=mysql_fetch_array($r, MYSQL_ASSOC);
if ($d[company]){$payTo = $d[company];}else{$payTo = $d[name];}
$line1 = $d[company]; 
if ($d[company]){$line2 = 'ATTN: '.$d[name]; }else{ $line2 = $d[name];}
$line3 = $d[address];
$line4 = $d[city].', '.$d[state].' '.$d[zip];
$canvas = imagecreate( 100, 600);
$white = imagecolorallocate( $canvas, 255, 255, 255 );
$black = imagecolorallocate( $canvas, 0, 0, 0 );
$font = "verdana.ttf";
$size = "12";
imageTTFText( $canvas, $size, 270, 70, 10, $black, $font, $line1 );
imageTTFText( $canvas, $size, 270, 50, 10, $black, $font, $line2 );
imageTTFText( $canvas, $size, 270, 30, 10, $black, $font, $line3 );
imageTTFText( $canvas, $size, 270, 10, 10, $black, $font, $line4 );
header("Content-type: image/jpeg"); 
imagejpeg( $canvas );
?>