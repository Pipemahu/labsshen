<?
session_start();
//include "php/Configuracion.php";
include "php/ManejoArchivos.class.php";
include "php/Utiles.class.php";
include "php/MySQL.class.php";

/* instanciando las clases */
$mysql = new mysql("localhost", "labsshe_db", "labsshe_db", "123456");
$util = new utiles();

$usuario = $_POST['a'];
$contrasena = $_POST['b'];
$entrar = $_POST['c'];

if(isset($entrar)){
	$contrasena2 = $mysql->Select("select pass from usuarios where usuario='$usuario'");
	//echo "if(md5(".$contrasena.")==".$contrasena2[0][0]."){";
	if(md5($contrasena)==$contrasena2[0][0]){
        if($usuario == eduardo){
            $id= $mysql->Select("select ID_usuarios from usuarios where usuario='$usuario'");
            $_SESSION['id'] = $id[0][0];
            $_SESSION['admin'] = $usuario;
            header("location: indexadmin.php");
        } else {
            $id= $mysql->Select("select ID_usuarios from usuarios where usuario='$usuario'");
            $_SESSION['id'] = $id[0][0];
            $_SESSION['admin'] = $usuario;
            header("location: index.php");
        }
	} else {
		$error = "usuario o contrasena incorrecta";	
	}
	$error = "usuario o contrasena incorrecta";	
}

$empresa = $mysql->Select("select ID_clientes from clientes where razon_social='$usuario'");
$_SESSION['nro'] = $empresa[0][0] ;



?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>

<link href="css/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
<!--
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
//-->
</script>
<style type="text/css">
<!--
div {
	text-align: center;
}
-->
</style>
</head>

<body>

<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<?
echo "<div>$error</div>";

?>
<form action="" method="post">
<table width="253" border="0" align="center" cellpadding="0" cellspacing="1"  bgcolor="#F2F2F2">
  <tr>
    <td colspan="4"><h2><strong>Identifiquese</strong></h2></td>
  </tr>
  <tr>
    <td width="3">&nbsp;</td>
    <td width="86" bgcolor="#FFFFFF">&nbsp;</td>
    <td width="154" bgcolor="#FFFFFF">&nbsp;</td>
    <td width="3">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td bgcolor="#FFFFFF">Usuario</td>
    <td bgcolor="#FFFFFF"><label>
      <input type="text" name="a" id="a" />
    </label></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td bgcolor="#FFFFFF">Contraseña</td>
    <td bgcolor="#FFFFFF"><input type="password" name="b" id="b" /></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td bgcolor="#FFFFFF">&nbsp;</td>
    <td bgcolor="#FFFFFF">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td bgcolor="#FFFFFF">&nbsp;</td>
    <td bgcolor="#FFFFFF"><label>
      <input type="submit" name="c" id="c" value="Entrar" />
    </label></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  
</table>
</form>

</body>
</html>
