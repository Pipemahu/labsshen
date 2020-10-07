<?PHP
/*

Analizador de montos numericos a montos en letra en español

Copyright (C) Juan Carlos Gonzalez BenzaHosting.cl

Version 1.0

------------------------------------------------------------
Modo Uso:

require('PesosAPalabra.class.php');
$NumeroAPalabra = new PesosAPalabra(15288.33);
echo $NumeroAPalabra->getMontoLetra();

RETORNO:
quince mil docientos ochenta y ocho pesos.

*/

class PesosAPalabra{

	var $numerosBasicos=array('0'=>'cero','1'=>'un', '2'=>'dos','3'=>'tres','4'=>'cuatro',
						'5'=>'cinco','6'=>'seis','7'=>'siete','8'=>'ocho','9'=>'nueve');
	var $decenas=array('10'=>'diez','11'=>'once','12'=>'doce','13'=>'trece','14'=>'catorce',
						'15'=>'quince','16'=>'dieciseis','17'=>'diecisiete','18'=>'dieciocho',
						'19'=>'diecinueve');
	var $masDecenas=array('20'=>'veinte','21'=>'veintiun','22'=>'veintidos','23'=>'veintitres',
						'24'=>'veinticuatro','25'=>'veinticinco','26'=>'veintiseis','27'=>'veintisiete',
						'28'=>'veintiocho','29'=>'veintinueve','30'=>'treinta','40'=>'cuarenta',
						'50'=>'cincuenta','60'=>'sesenta','70'=>'setenta','80'=>'ochenta',
						'90'=>'noventa');
	var $centenas=array('100'=>'cien','200'=>'doscientos','300'=>'trescientos',
						'400'=>'cuatrocientos','500'=>'quinientos','600'=>'seiscientos',
						'700'=>'setecientos','800'=>'ochocientos','900'=>'novecientos');
	
	
	var $tipos=array(0=>'centavos',1=>'pesos',2=>'dolares');
	
	var $montoLetra='';
	
	function PesosAPalabra($montoNumerico){
		
		$tempString=strval($montoNumerico);
        $array=explode('.',$tempString);    //si encuentra un punto separa....
        $lenghtEnteros=strlen($array[0]);
        
        if(sizeof($array)>1)  //encontro punto...tendra pesos y centavos
            $punto=true;
        else $punto=false;		//solo tendra pesos o centavos
        if ($punto){	//si tiene fracciones
        	//-
        	$fraccion=substr($array[1],0,2);
        	
        	//-si la longitud es menor a 2
        	if (strlen($fraccion)<2)
        		$fraccion.='0';
        	
        	$fraccion.='/100 M. N.';
        }else $fraccion='00/100 M. N.';
        
        if ($lenghtEnteros>3){	//decenas y centenas
        	//echo 'hol';
        	$flagLongitud=true;
        	$cadenaEntera=$array[0];	//cadena completa
        	$count=0;
        	$enteros='';
        	do {
        		$posfijo='';
        		
        		if (strlen($cadenaEntera)>=3)
        			$fin=substr($cadenaEntera,-3);	//final de la cadena
        		else $fin=$cadenaEntera;	//final de la cadena
        		
        		if (strlen($fin)<3){        			
        			while (strlen($fin)!=3){
        				$fin='0'.$fin;        				
        			}
        			
        			$flagLongitud=false;
        		}else $cadenaEntera=substr($cadenaEntera,0,-3);	//principio de la cadena        		
        		$cantidadTemp=$this->makeMonto3cifras($fin);

        		if(pow(-1,$count)==-1){
        			$posfijo='mil';
        		}elseif ($count) {
        			if (strlen($cadenaEntera)>1 || substr($cadenaEntera,-1)!='1')
        				$posfijo='millones';
        			else $posfijo='millon';        			
        		}
        		
        		if ( $cantidadTemp=='un' && $posfijo=='mil'){
        			$enteros=$posfijo.' '.$enteros;
        		}elseif ($cantidadTemp!=''){
        			$enteros=$cantidadTemp.' '.$posfijo.' '.$enteros;
        		}
        		
        		$count++;	//cuantas veces he pasado por el ciclo
        		
        	}while ($flagLongitud);
        	
        	$this->montoLetra=$enteros.' '.$this->tipos[1];
        	
        }else {
        	$dif=3-$lenghtEnteros;
        	$i=0;
        	$enteros=$array[0];
        	while ($i<$dif){
        		$enteros='0'.$enteros;
        		$i++;
        	}
        	$monto3cifras=$this->makeMonto3cifras($enteros);
        	if ($monto3cifras!='')
        		$this->montoLetra=$monto3cifras.' '.$this->tipos[1];
        	else $this->montoLetra=$this->numerosBasicos['0'].' '.$this->tipos[1];
        }
        //= preg_replace('/\s+/', ' ', $text);
       // echo $this->montoLetra=$this->montoLetra.' '.$fraccion;
        $this->montoLetra=preg_replace('/\s+/', ' ',$this->montoLetra.' '.$fraccion);
	
	}
	//-llega la cantidad con 3 cifras maximo novecientos
	function makeMonto3cifras($monto){
		$string='';
		$substring='';
		if (array_key_exists($monto,$this->centenas)) {	//100, 200 o mas
			$string=$this->centenas[$monto];
		}else {
			if ($monto{0}!='0' && $monto{0}!='1'){
				$string=$this->centenas[$monto{0}.'00'];
			}elseif ($monto{0}==1) $string='ciento';
			$decenas=substr($monto,1,2);
			if (array_key_exists($decenas,$this->decenas)){
				$string.=' '.$this->decenas[$decenas];
			}elseif (array_key_exists($decenas,$this->masDecenas)){
				$string.=' '.$this->masDecenas[$decenas];
			}else {//si no se ajusto a ningun caso especial
				if ($monto{1}=='0'){
					if ($monto{2}!='0')
						$substring.=$this->numerosBasicos[$monto{2}];
			
				}else {
					if (array_key_exists($monto{1}.'0',$this->masDecenas)){
						$substring=$this->masDecenas[$monto{1}.'0'];
						if ($monto{2}!='0')
							$substring.=' y '.$this->numerosBasicos[$monto{2}];
					}
				}
			}
		}
		if ($substring!='' && $string!='')	//si substring tiene algo la concateno
			$string.=' '.$substring;
		elseif ($substring!='')
			$string.=$substring;
		
		return $string;
	}
	
	function getMontoLetra(){
		return $this->montoLetra;
	}
}
?>