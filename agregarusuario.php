<?
session_start();
//include "php/Configuracion.php";
include "php/ManejoArchivos.class.php";
include "php/Utiles.class.php";
include "php/MySQL.class.php";

/* instanciando las clases */
$util = new utiles();
define('DB_SERVER','localhost');
define('DB_NAME','labsshe_db');
define('DB_USER','labsshe_db');
define('DB_PASS','123456');
$con = mysql_connect(DB_SERVER,DB_USER,DB_PASS);
mysql_select_db(DB_NAME,$con);
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

</style>

</head>

<body>
<? include "menuadmin.php" ?>

<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<?
echo "<div>$error</div>";

?>


<table width="300" border="0" align="center" cellpadding="0" cellspacing="1"  bgcolor="#F2F2F2">
<tr>
<td colspan="4"><h2><strong>Agregar nuevo usuario o cambie su clave</strong></h2></td>
</tr>








<form action="" method="post">
<table width="300" border="0" align="center" cellpadding="0" cellspacing="1"  bgcolor="#F2F2F2">
  <tr>
<td colspan="4"><h2><strong>Contraseña debe contener almenos: <br> -6 caracteres. <br> -1 mayúscula. <br> -1 minúscula. <br> -1 número.</strong></h2></td>
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
    <td bgcolor="#FFFFFF">Repita Contraseña</td>
    <td bgcolor="#FFFFFF"><input type="password" name="c" id="c" /></td>
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
      <input type="submit" name="d" id="d" value="guardar" />
    </label></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>

<?

$usuario = $_POST['a'];
$contrasena = $_POST['b'];
$recontraseña = $_POST['c'];
$guardar = $_POST['d'];
$mensaje = "";

if(isset($_POST['d']))
{
    if($_POST['a'] == '' or $_POST['b'] == '' or $_POST['c'] == '')
    {
        $mensaje="Por favor llene todos los campos.";
    }
    elseif(strlen($contrasena)< 5){
        
        $mensaje="Contraseña debe contener al menos 6 caracteres";
        
    }
    elseif(ctype_lower($contrasena)){
        
        $mensaje="Contraseña debe contener al menos 1 mayuscula";
        
    }
    elseif(ctype_upper($contrasena)){
        
        $mensaje="Contraseña debe contener al menos 1 minuscula";
        
    }
    elseif(ctype_alpha($contrasena)){
        
        $mensaje="Contraseña debe contener al menos 1 numero";
        
    }
    else
    {
        $sql = 'SELECT * FROM usuarios';
        $rec = mysql_query($sql);
        $verificar_usuario = 0;
        
        while($result = mysql_fetch_object($rec))
        {
            if($result->usuario == $_POST['a'])
            {
                $verificar_usuario = 1;
            }
        }
        
        if($verificar_usuario == 0)
        {
            if($_POST['b'] == $_POST['c'])
            {
                $usuario = $_POST['a'];
                $password = md5($_POST['b']);
                $sql = "INSERT INTO usuarios (usuario,pass) VALUES ('$usuario','$password')";
                mysql_query($sql);
                
                $mensaje="Usted se ha registrado correctamente.";
            }
            else
            {
                $mensaje="Las claves no son iguales, intente nuevamente.";
            }
        }
        if($verificar_usuario == 1)
        {
            $usuario = $_POST['a'];
            $password = md5($_POST['b']);
            $sql = "UPDATE usuarios SET pass='$password' WHERE usuario='$usuario'";
            mysql_query($sql);
            
            $mensaje="Usted ha cambiado su clave correctamente.";
        }
    }
}
?>
  <tr>
    <td colspan="4"><h2><strong><?=$mensaje ?></strong></h2></td>
  </tr>
</table>
</form>

</body>
</html>