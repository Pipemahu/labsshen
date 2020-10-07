<?
include "php/Configuracion.php";

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
</head>

<body>
<? include "menu.php" ?>

<? 
/* empresa a evaluar */
$empresa = $_GET['id'];
if(isset($empresa)){
	$empresa = $mysql->Select("select * from clientes where ID_clientes=$empresa");
}
?>

<h1>Evaluacion para <?=$empresa[0][3]?></h1>
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td><table width="500" border="0" align="left" cellpadding="0" cellspacing="0">
      <tr>
        <td colspan="2">Informacion del cliente</td>
      </tr>
      <tr>
        <td width="90">Rut</td>
        <td width="410"><?=$empresa[0][2]?></td>
      </tr>
      <tr>
        <td>Razon Social</td>
        <td><?=$empresa[0][3]?></td>
      </tr>
      <tr>
        <td>Giro</td>
        <td><?=$empresa[0][4]?></td>
      </tr>
    </table></td>
  </tr>
</table>
<br />


    <table width="90%" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#E8E8E8">
  <tr>
    <td width="1220"><strong>Lista de Preguntas</strong></td>
    </tr>
  
  <? $empresas = $mysql->Select("select * from preguntas");
 	if(count($empresas)!=0){
		foreach ($empresas as $Valor){
 ?>
   
  <tr>
    <td bgcolor="#FFFFFF"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="147" align="left">Pregunta Nro:
          <?=$Valor[1]?></td>
        <td width="683" align="left">Tipo: 
          <?=$Valor[2]?></td>
        </tr>
      <tr>
        <td align="left">Elemento:</td>
        <td align="left"><?= htmlentities($Valor[3])?></td>
        </tr>
      <tr>
        <td align="left">Sub Elemento:</td>
        <td align="left"><?= htmlentities($Valor[4])?></td>
        </tr>
      <tr>
        <td align="left">Practica:</td>
        <td align="left"><?= htmlentities($Valor[5])?></td>
        </tr>
      <tr>
        <td align="left">Pregunta:</td>
        <td align="left"><?= htmlentities($Valor[6])?></td>
        </tr>
      <tr>
        <td align="left">Intencion de la Pregunta:</td>
        <td align="left"><?= htmlentities($Valor[7])?></td>
        </tr>
      <tr>
        <td colspan="2" align="right"><a href="#">Ver lista de Item</a></td>
        </tr>
      <tr>
        <td colspan="2" align="right" bgcolor="#F2F2F2"><table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td colspan="2">Lista de Items</td>
            </tr>
          
          <? $items = $mysql->Select("select * from items where ID_preguntas='$Valor[0]'");
 			if(count($items)!=0){
				foreach ($items as $Valor2){
 		  ?>
          <tr>
            <td width="91%"><?=htmlentities($Valor2[2])?></td>
            <td width="9%"><a href="#">Responder</a></td>
            </tr>
        <? }} else { ?>
        <tr>
            <td colspan="2" align="center">No hay Item para esta pregunta<a href="#"></a></td>
            </tr>
        <? } ?>
        
        </table></td>
      </tr>
    </table></td>
    </tr>

  <? }} ?>
  </table>

</body>
</html>
