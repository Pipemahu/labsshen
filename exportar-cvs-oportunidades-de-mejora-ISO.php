<?php
include "php/MySQL.class.php";
$mysql = new mysql("localhost", "labsshe_db", "labsshe_db", "123456");

$id_empresa = $_GET['id'];
$empresa = $mysql->Select("select * from clientes where ID_clientes='$id_empresa'");

header("Content-type: application/vnd.ms-excel"); 
header("Content-Disposition: attachment; filename=oportunidades_".$empresa[0][2].".xls"); 
?>

<table width="90%" border="0" align="center" cellpadding="2" cellspacing="2" bgcolor="#F3F4F6">
  <tr>
    <td colspan="6"><strong>Informacion Cliente</strong></td>
  </tr>
  <tr>
    <td colspan="6">Rut:
    <?=$empresa[0][2]?></td>
  </tr>
  <tr>
    <td colspan="6">Razon Social :
    <?=$empresa[0][3]?></td>
  </tr>
  <tr>
    <td colspan="6">Giro:
    <?=$empresa[0][4]?></td>
  </tr>
  <tr>
    <td colspan="6">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="6"><strong>Listado  de oportunidades de mejora</strong></td>
  </tr>
  <tr>
    <td width="30">&nbsp;</td>
    <td>Oportunidad de Mejora</td>
    <td width="100">Complejidad</td>
    <td width="100">Beneficio</td>
    <td width="100" align="center">Indice</td>
    <td width="10" align="center">&nbsp;</td>
  </tr>
  
  <? $empresas = $mysql->Select("
								SELECT mejoras_ISO.mejora, mejoras_ISO.complejidad, mejoras_ISO.beneficio, mejoras_ISO.complejidad *  mejoras_ISO.beneficio AS indice, mejoras_ISO.ID_items, items_ISO.ID_preguntas
								FROM mejoras_ISO, items_ISO
								WHERE mejoras_ISO.ID_clientes = '$id_empresa'
								AND items_ISO.ID_items = mejoras_ISO.ID_items
								ORDER BY indice DESC
								");
 	if(count($empresas)!=0){
		foreach ($empresas as $Valor){
			
 ?>
   
  <tr>
    <td align="left">&nbsp;</td>
    <td align="left" bgcolor="#FFFFFF">
    <span style=" font-size:14px;">
      <?=($Valor[0])?>
   </span>
    </td>
    <td align="center" bgcolor="#FFFFFF"><span style=" font-size:14px;">
      <?=($Valor[1])?>
    </span></td>
    <td align="center" bgcolor="#FFFFFF"><span style=" font-size:14px;">
      <?=($Valor[2])?>
    </span></td>
    <td align="center" bgcolor="#FFFFFF"><span style=" font-size:14px;">
      <?=($Valor[3])?>
    </span><br />

    </td>
    <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
  </tr>

  <? }} ?>
  </table>