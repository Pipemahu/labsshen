<?php
include "php/MySQL.class.php";
$mysql = new mysql("localhost", "labsshe_db", "labsshe_db", "123456");

$id_empresa = $_GET['id'];
$empresa = $mysql->Select("select * from clientes where ID_clientes='$id_empresa'");

header("Content-type: application/vnd.ms-excel;  charset=utf-8");
header("Content-Disposition: attachment; filename=prom_emenentos_".$empresa[0][2].".xls"); 
?>

<table width="90%" border="0" align="center" cellpadding="2" cellspacing="2" bgcolor="#F3F4F6">
  <tr>
    <td>&nbsp;</td>
    <td colspan="2"><strong>Informacion del Cliente</strong></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Rut: 
    <?=$empresa[0][2]?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Razon Social : 
    <?=$empresa[0][3]?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Giro: 
    <?=$empresa[0][4]?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="3">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4"><strong>Lista de Elementos</strong></td>
    </tr>
  <tr>
    <td width="30">&nbsp;</td>
    <td>Elemento</td>
    <td width="66" align="center">Promedio</td>
    <td width="10" align="center">&nbsp;</td>
    </tr>
  
  <? $elementos = $mysql->Select("	SELECT DISTINCT elemento
									FROM Preguntas_ISO");
 	if(count($elementos)!=0){
		foreach ($elementos as $Valor){
			
 ?>
   
  <tr>
    <td>&nbsp;</td>
    <td bgcolor="#FFFFFF"><span style=" font-size:14px;"><?= utf8_encode($Valor[0])?>
    </span>
    <? $preguntas = $mysql->Select("Select ID_preguntas from Preguntas_ISO where elemento = '$Valor[0]' order by num");
	$num_preguntas = count($preguntas); ?>
    <span style="font-size: 10px">(Num Preguntas: <?=$num_preguntas?>)</span></td>
    <td align="center" bgcolor="#FFFFFF">
    <? 
	/* Calcular promedio de notas */
	
	/* algoritmo:
	   
	   - verificar si la pregunta esta visible u pculta para la pregunta
	   - si (pregunta esta visible) 
	   		- obtener la lista de item de la pregunta
			- sumar todas las notas de los item de esa pregunta
			- si(una de las notas del item es 0)
				- entonces el promedio general es -1
			- sino
				- calcular el promedio de la pregunta.
	*/
	
	/* verificar si la pregunta es visible o no */
	
	/* buscamosla lista de preguntas */
	$promedio_elemento = 0;
	$suma_elemento = 0;
	$contador_elemento = 0;
	$error = false;
	$id_cliente = $_GET['id'];
	$error_prom = false;
	
	
	$preguntas = $mysql->Select("Select ID_preguntas from Preguntas_ISO where elemento = '$Valor[0]' order by num");
	$num_preguntas = count($preguntas);
	foreach ($preguntas as $Valor2){
		
		//echo "pre: ".$Valor2[0]."<br>";
		
		$id_pregunta = $Valor2[0];
		$contador = 0;
		$suma = 0;
		$error = false;
		
		$visible = $mysql->Select("select estado from preguntas_no_aplicables where id_pregunta='$id_pregunta' and id_cliente='$id_cliente'");
		$estado_pregunta = $visible[0][0];
		
		if($estado_pregunta=="0"){
			$promedio = 0;	
		} else{
			$items = $mysql->Select("
										select items_ISO.ID_Items, items_ISO.item, notas_ISO.nota
										from items_ISO, notas_ISO
										where items_ISO.ID_preguntas='$id_pregunta'
										and notas_ISO.ID_clientes='$id_cliente'
										and notas_ISO.ID_items = items_ISO.ID_items;");
			$nro_item = $mysql->Select("select ID_items from items_ISO where ID_Preguntas='$id_pregunta'");
			$nro_item = count($nro_item);
		
 			if(count($items)!=0){
				foreach ($items as $Valor3){
					
					if($Valor3[2]=="" || $Valor3[2]=="0")
					{
						$error = true;
					} else {
						$contador ++;
						$suma += $Valor3[2];
					}
				
				}
				$promedio = $suma/$contador;
			} else {
				$promedio = "0";
			}
			
			if($contador != $nro_item) $error=true;
			
		}	
		
		//echo "pro: ".$promedio."<br><br>";
		if($error){
			$error_prom = true;
		}
		if($promedio != "0"){
			$suma_elemento += $promedio;		
			$contador_elemento ++;
		}
	}

	if($contador_elemento!=0)
		$promedio_elemento = $suma_elemento/$contador_elemento;
	else
		$promedio_elemento = 0;

	
	//$razon = $contador_elemento ."/".$num_preguntas;
	
?>
   <span style=" font-size:16px; font-weight:bold"><?=number_format($promedio_elemento,2)?></span>
  <? if($error_prom){
	   ?>
  <? } ?>
   <br />
    </td>
    <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
  </tr>

  <? }} ?>
  </table>