<?php
/*###########################################################################
# Clase: Utiles
# Escrito por: Juan Carlos Gonzalez Ulloa
# Contacto: jcgonzalez@benzahosting.cl | www.benzahosting.cl
# Version 1.0
############################################################################# */
class Utiles {

    function FormatoMoneda($numero, $moneda) {

	$longitud = strlen($numero);
	$punto = substr($numero, -1,1);
	$punto2 = substr($numero, 0,1);
	$separador = ".";

	if($punto == "."){
		$numero = substr($numero, 0,$longitud-1);
		$longitud = strlen($numero);
	}
	if($punto2 == "."){
		$numero = "0".$numero;
		$longitud = strlen($numero);
	}

	$num_entero = strpos ($numero, $separador);
	$centavos = substr ($numero, ($num_entero));
	$l_cent = strlen($centavos);
	if($l_cent == 2){
        	$centavos = $centavos."0";
	}
	elseif($l_cent == 3){
		$centavos = $centavos;
	}
	elseif($l_cent > 3){
		$centavos = substr($centavos, 0,3);
	}
	$entero = substr($numero, -$longitud,$longitud-$l_cent);
	if(!$num_entero){
		$num_entero = $longitud;
		$centavos = ".00";
		$entero = substr($numero, -$longitud,$longitud);
	}
	$start = floor($num_entero/3);
	$res = $num_entero-($start*3);
	if($res == 0){
		$coma = $start-1;
		$init = 0;
	}else{
		$coma = $start;
		$init = 3-$res;
	}
	$d= $init;
	$i = 0;
	$c = $coma;
	while($i <= $num_entero){
		if($d == 3 && $c > 0){
			$d = 0;
			$sep = ",";
			$c = $c-1;
		}else{
			$sep = "";
		}
		$final .=  $sep.$entero[$i];
		$i = $i+1; // todos los digitos
		$d = $d+1; // poner las comas
	}

	if($moneda == "pesos")  {
		$moneda = "$";
		return $moneda." ".$final;
	}
	elseif($moneda == "dolares"){
		$moneda = "USD";
		return $moneda." ".$final.$centavos;
	}
	elseif($moneda == "euros")  {
		$moneda = "EUR";
		return $final.$centavos." ".$moneda;
	}
    }

function RedimencionarImagen($image,$newwidth,$newheight,$archivo,$modo) {
	$extencion = DetectaExtencion($image);

	if($extencion == "jpg" || $extencion == "JPG")
		$src = imagecreatefromjpeg("$image");
	if($extencion == "gif" || $extencion == "GIF")
		$src = imagecreatefromgif("$image");
	if($extencion == "png" || $extencion == "PNG")
		$src = imagecreatefrompng("$image");

	$size = getimagesize ("$image");

	if($modo == "proporcional")
	{
		$porcentaje1 = $newwidth/$size[0];
		$porcentaje2 = $newheight/$size[1];

		if($size[0]>$newwidth || $size[1]>$newheight)
		{
			if($porcentaje1<$porcentaje2)
				$p = $porcentaje1;
			else
				$p = $porcentaje2;
		}
		else
		{
			$p = 1;
		}

		$height = $size[1]*$p;
		$width = $size[0]*$p;

		if($extencion == "png" || $extencion == "PNG")
			$im = imagecreate($width,$height);
		else
			$im = imagecreatetruecolor($width,$height);

		imagecopyresampled($im,$src,0,0,0,0,$width,$height,$size[0],$size[1]);

	}

	if($modo == "estricto")
	{

		$x=imagesx($src);
    	$y=imagesy($src);

    if ($x > $y)
        {
        $w=$newwidth;
        $h=$y*($newheight/$x);
        }

    if ($x < $y)
        {
        $w=$x*($newwidth/$y);
        $h=$newheight;
        }

    if ($x == $y)
        {
        $w=$newwidth;
        $h=$newheight;
        }


		//creacion de la imagen de destino
		if($extencion == "png" || $extencion == "PNG")
			$im = imagecreate($newwidth,$newheight);
		else
			$im = imagecreatetruecolor($newwidth,$newheight);

		//creamos una imagen


		imagecopyresampled($im,$src,0,0,0,0,$newwidth,$newheight,$x,$y);
		//imagecopyresampled($im,$src,0,0,0,0,$w,$h,$x,$y);
	}


	if($extencion == "jpg" || $extencion == "JPG")
		imagejpeg($im, $archivo,100);
	if($extencion == "gif" || $extencion == "GIF")
		imagegif($im, $archivo);
	if($extencion == "png" || $extencion == "PNG")
		imagepng($im, $archivo);

	imagedestroy($im);

}
}

function DetectaExtencion($mi_extension){
		return end(explode(".", $mi_extension));
	}
	
	function envio_mail($de,$mensaje,$para,$asunto)
{
	$header = 'From: ' . $de. " \r\n";
	$header .= "X-Mailer: PHP/" . phpversion() . " \r\n";
	$header .= "Mime-Version: 1.0 \r\n";
	$header .= "Content-Type: text/plain";
	
	if (!mail($para, $asunto, utf8_decode($mensaje), $header))
	{
		echo "error en envio de mail";
	}

}
?>
