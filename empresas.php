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
<? include "menuadmin.php" ?>

<h1>Lista de empresas Registradas</h1>
<br />
 <? 
	$c = $_POST['enviar'];
	$rut = $_POST['rut'];
	$razon_social = utf8_decode($_POST['razon_social']);
	$giro = utf8_decode($_POST['giro']);
	
	if(isset($c)){
		$mysql->Insert("insert into clientes values('0','".$_SESSION['id']."','$rut','$razon_social','$giro')");
	}
	
	$c = $_POST['guardar'];
	$rut = $_POST['rut'];
	$razon_social = utf8_decode($_POST['razon_social']);
	$giro = utf8_decode($_POST['giro']);
	$empresa=$_POST['empresa'];
	
	if(isset($c)){
		$mysql->Insert("update clientes set rut='$rut', razon_social='$razon_social', giro='$giro' where ID_clientes='$empresa'");
	}
	
	
	
	?>
<form action="empresas.php" method="post">
    <table width="90%" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#E8E8E8">
  <tr>
    <td width="23"><strong>ID</strong></td>
    <td width="150"><strong>Rut</strong></td>
    <td width="266"><strong>Razon Social</strong></td>
    <td width="267"><strong>Giro</strong></td>
    <td width="120" align="center">Opciones</td>
  </tr>
  
  <? $empresas = $mysql->Select("select * from clientes order by razon_social asc");
 	if(count($empresas)!=0){
		foreach ($empresas as $Valor){
 ?>
  
  <? 
  $accion = $_GET['accion'];
  $id = $_GET['id'];
  
  if(isset($accion) && $accion=="editar" && isset($id) && $id==$Valor[0]){ ?>
   <tr>
    <td align="center" bgcolor="#FFFFFF"><?=$Valor[0]?></td>
    <td align="center" bgcolor="#FFFFFF"><input type="text" name="rut" id="rut" style="width:90%" value="<?=$Valor[2]?>" /></td>
    <td align="center" bgcolor="#FFFFFF"><label>
      <input type="text" name="razon_social" id="razon_social" style="width:90%" value="<?=utf8_encode($Valor[3])?>" />
    </label></td>
    <td align="center" bgcolor="#FFFFFF"><input type="text" name="giro" id="giro" style="width:90%" value="<?=utf8_encode($Valor[4])?>" /></td>
    <td align="center" bgcolor="#FFFFFF"><label>
      <input type="submit" name="guardar" id="guardar" value="Guardar Cambios" />
    </label><input name="empresa" type="hidden" value="<?=$Valor[0]?>" /></td>
  </tr>
  <? } else { ?>
  <tr>
    <td bgcolor="#FFFFFF"><?=$Valor[0]?></td>
    <td bgcolor="#FFFFFF"><?=$Valor[2]?></td>
    <td bgcolor="#FFFFFF"><?=utf8_encode($Valor[3])?></td>
    <td bgcolor="#FFFFFF"><?=utf8_encode($Valor[4])?></td>
    <td align="center" bgcolor="#FFFFFF"><a href="empresas.php?accion=editar&amp;id=<?=$Valor[0]?>"><img src="images/edit.gif" alt="Editar Empresa" width="16" height="16" border="0" align="absmiddle" /></a> <a href="evaluar2.php?id=<?=$Valor[0]?>"> <img src="images/24.png" alt="Evaluar Empresa" width="16" height="16" border="0" align="absmiddle" /></a></td>
  </tr>
  <? } ?>
  <? }
  
  } else { ?>
  <tr>
    <td colspan="5" align="center" bgcolor="#FFFFFF">No hay Empresas Registradas Para su Usuario</td>
    </tr>
  <? } ?>
  </table>
 </form><br />

  <form action="empresas.php" method="post">
  <table width="90%" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#E8E8E8">
  <tr>
    <td colspan="5" align="center"><strong>Agregar Nueva Empresa</strong></td>
    </tr>
  <tr>
    <td width="23" align="center" bgcolor="#FFFFFF">&nbsp;</td>
    <td width="150" align="center" bgcolor="#FFFFFF"><input type="text" name="rut" id="rut" style="width:90%" /></td>
    <td width="266" align="center" bgcolor="#FFFFFF"><label>
      <input type="text" name="razon_social" id="razon_social" style="width:90%" />
      </label></td>
    <td width="267" align="center" bgcolor="#FFFFFF"><input type="text" name="giro" id="giro" style="width:90%" /></td>
    <td width="120" align="center" bgcolor="#FFFFFF"><label>
      <input type="submit" name="enviar" id="enviar" value="Agregar Empresa" />
      </label></td>
  </tr>
</table>
 </form>
 <br />
Sistema desarrollado por <a href="http://benzahosting.cl">BenzaHosting </a>
</body>
</html>
