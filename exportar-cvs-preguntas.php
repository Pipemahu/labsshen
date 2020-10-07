<?php
include "php/MySQL.class.php";
$mysql = new mysql("localhost", "labsshe_db", "labsshe_db", "123456");

$id_empresa = $_GET['id'];
$empresa = $mysql->Select("select * from clientes where ID_clientes='$id_empresa'");

header("Content-type: application/vnd.ms-excel"); 
header("Content-Disposition: attachment; filename=prom_pregunta_".$empresa[0][2].".xls"); 
?>

<table width="90%" border="0" align="center" cellpadding="2" cellspacing="2" bgcolor="#F3F4F6">
  
  <tr>
    <td>&nbsp;</td>
    <td colspan="2"><strong>Informacion del Cliente</strong></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td width="90">Rut</td>
    <td width="410"><?=$empresa[0][2]?></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Razon Social</td>
    <td><?=$empresa[0][3]?></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Giro</td>
    <td><?=$empresa[0][4]?></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="3">&nbsp;</td>
  </tr>
  <tr>
    <td width="34">&nbsp;</td>
    <td colspan="3"><strong>Lista de Preguntas</strong></td>
    </tr>
  <tr>
    <td width="34">&nbsp;</td>
    <td width="120">N&deg; Pregunta</td>
    <td width="740">Intencion</td>
    <td width="59">Promedio</td>
    </tr>
  
  <? $empresas = $mysql->Select("select * from preguntas order by num asc");
 	if(count($empresas)!=0){
		foreach ($empresas as $Valor){
			$estado_pregunta = $mysql->Select("
								  select estado 
								  from preguntas_no_aplicables 
								  where id_cliente='".$empresa[0][0]."' 
								  and id_pregunta='$Valor[0]' ");
			if($estado_pregunta[0][0]=="")
				$estado="1";
			if($estado_pregunta[0][0]=="1")
				$estado="1";
			if($estado_pregunta[0][0]=="0")
				$estado="0";
 ?>
   
  <tr>
    <td width="34">&nbsp;</td>
    <td align="center" bgcolor="#FFFFFF" bordercolor="#000000">
      <span style=" font-size:14px;">
      <?=($Valor[1])?>
      </span>
    </td>
    <td bgcolor="#FFFFFF" bordercolor="#000000"><span style=" font-size:14px;"><?= utf8_encode($Valor[6])?></span></td>
    <td align="center" bgcolor="#FFFFFF" bordercolor="#000000">
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
	$promedio = "";
	$contador = 0;
	$suma = 0;
	$id_cliente = $_GET['id'];
	$id_pregunta = $Valor[0];
	$error = false;
	
	//para modificacion de calculo de promedio
	$promedio_fix = 0;
	$suma_fix = 0;
	$contador_fix = 0;
	$error_fix = false;
	
	
	$visible = $mysql->Select("select estado from preguntas_no_aplicables where id_pregunta='$id_pregunta' and id_cliente='$id_cliente'");
	$estado_pregunta = $visible[0][0];
	
	if($estado_pregunta=="0"){
		$promedio_fix = 0;	
	} else{
		$items = $mysql->Select("
								select items.ID_Items, items.item, notas.nota
								from items, notas
								where items.ID_preguntas='$id_pregunta'
								and notas.ID_clientes='$id_cliente'
								and notas.ID_items = items.ID_items;");
		$nro_item = $mysql->Select("select ID_items from items where ID_Preguntas='$id_pregunta'");
		$nro_item = count($nro_item);
		
 		if(count($items)!=0){
			foreach ($items as $Valor2){
				
				//echo $Valor2[0]."- ".$Valor2[2]."<br>";
				/* actualizacion de fix  */
				if($Valor2[2]=="" || $Valor2[2]=="0")
				{
					$error_fix = true;
				} else {
					$contador_fix ++;
					$suma_fix += $Valor2[2];
				}
				
				/*
				$contador ++;
				$suma += $Valor2[2];
				if($Valor2[2]=="" || $Valor2[2]=="0")
				{
					$error = true;
				}
				
				if(!$error)
					$promedio = $suma/$contador;
				else
					$promedio = "-1";
					
				*/
			}
			
			$promedio_fix=$suma_fix/$contador_fix;
		} else {
			$promedio_fix = "0";
		}
		$razon = $contador_fix."/".$nro_item;
		if($contador_fix != $nro_item) $error_fix = true;
	}	
	
	?>
   <span style=" font-size:16px; font-weight:bold"><?=number_format($promedio_fix,2)?></span> 
   <? if($error_fix){
	   ?>
   <? } ?>
   <br />

    </td>
  </tr>

  <? }} ?>
  </table>

