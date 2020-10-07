<?
include "php/Configuracion.php";

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<script type="text/javascript" src="js/jquery-1.4.2.js"></script>
<script>
function mostrar_ocultar(div){
	if(document.getElementById(div).style.display == "none"){
		$("#"+div).slideToggle("slow");
	} else {
		$("#"+div).slideToggle("slow");
	
	}
	
	
}
</script>
</head>

<body>
<? include "menuadmin.php" ?>

<? 
/* empresa a evaluar */
$empresa = $_GET['idcliente'];
$pregunta = $_GET['idpregunta'];
if(isset($empresa) && isset($pregunta)){
	$empresa = $mysql->Select("select * from clientes where ID_clientes='$empresa'");
	$pregunta = $mysql->Select("select * from Preguntas_LARC where ID_preguntas='$pregunta'");
}
?>

<h1>Evaluacion para <?= utf8_encode($empresa[0][3])?><br />
Pregunta: <?= utf8_encode($pregunta[0][6])?></h1>
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="10">
  <tr>
    <td width="300" align="left" valign="top"><table width="300" border="0" align="left" cellpadding="0" cellspacing="0">
      <tr>
        <td colspan="2">Informacion del cliente</td>
      </tr>
      <tr>
        <td width="90">Rut</td>
        <td width="194"><?=$empresa[0][2]?></td>
      </tr>
      <tr>
        <td>Razon Social</td>
        <td><?=utf8_encode($empresa[0][3])?></td>
      </tr>
      <tr>
        <td>Giro</td>
        <td><?=utf8_encode($empresa[0][4])?></td>
      </tr>
    </table></td>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="2" align="left">Informacion Pregunta</td>
        </tr>
        <tr>
        <td width="155" align="left">Pregunta Nro:
          <?=$pregunta[0][1]?></td>
        <td align="left">Tipo: 
          <?=utf8_encode($pregunta[0][2])?></td>
        </tr>
      <tr>
        <td align="left">Elemento:</td>
        <td align="left"><?= utf8_encode($pregunta[0][3])?></td>
        </tr>
      <tr>
        <td align="left">Sub Elemento:</td>
        <td align="left"><?= utf8_encode($pregunta[0][4])?></td>
        </tr>
      <tr>
        <td align="left">Practica:</td>
        <td align="left"><?=utf8_encode($pregunta[0][5])?></td>
        </tr>
      <tr>
        <td align="left">Pregunta:</td>
        <td align="left"><?= utf8_encode($pregunta[0][6])?></td>
        </tr>
      <tr>
        <td align="left">Intencion de la Pregunta:</td>
        <td align="left"><?= utf8_encode($pregunta[0][7])?></td>
        </tr>
    </table></td>
  </tr>
</table>
<br />

<?

/* lista de opciones disponibles */

/* guardar nota */
$accion = $_POST['accion'];
$id_cliente = $_POST['cliente'];
$id_pregunta = $_POST['pregunta']; 
$id_item = $_POST['item'];
$nota = $_POST['nota'];
$comentario = $_POST['comentario'];

if(isset($accion) && $accion=="guardar-nota"){
	$notas = $mysql->Select("select ID_notas,nota,comentario from notas_LARC where ID_items='".$id_item."' and ID_clientes='".$id_cliente."'");

	if($notas[0][0]=="")	
		$mysql->Insert("insert into notas_LARC values('0','$id_item','$id_cliente','$nota','$comentario')");
	else
		$mysql->Insert("update notas_LARC set nota='$nota', comentario='$comentario' where ID_notas='".$notas[0][0]."'");
	
}

/* agregar mejora */
/* guardar nota */
$accion = $_POST['accion'];
$id_cliente = $_POST['cliente'];
$id_pregunta = $_POST['pregunta']; 
$id_item = $_POST['item'];
$mejora = $_POST['mejora'];
$complejidad = $_POST['complejidad'];
$beneficio = $_POST['beneficio'];

if(isset($accion) && $accion=="agregar-mejoras"){
	
	$mysql->Insert("insert into mejoras_LARC values('0','$id_item','$id_cliente','$mejora','$complejidad','$beneficio')");
	
}

/* borrarmejora */
$borrarmejora = $_GET['borrarmejora'];
if(isset($borrarmejora)){
	$mysql->Delete("delete from mejoras_LARC where ID_mejoras='$borrarmejora'");
}
?>


<table width="90%" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#E8E8E8">
  <tr>
    <td colspan="2"><strong>Lista de Items</strong></td>
  </tr>
  
  <? $items = $mysql->Select("select * from items_LARC where ID_preguntas='".$pregunta[0][1]."' order by orden_item asc");
 	if(count($items)!=0){
		foreach ($items as $Valor){
 ?>
  
  <tr>
    <td width="722" bgcolor="#FFFFFF" class="item"><?=utf8_encode($Valor[3])?>
    </td>
    <td width="107" align="center" bgcolor="#FFFFFF"><a href="#" onclick="mostrar_ocultar('respuestas<?=$Valor[0]?>'); return false;">Mostrar / Ocultar</a><a href="evaluarLARC.php?id=<?=$Valor[0]?>"></a></td>
  </tr>
  <tr>
    <td colspan="2" bgcolor="#FFFFFF">
    <div id="respuestas<?=$Valor[0]?>" <? if($_GET['item'] == $Valor[0]){ ?> <? }else {?> style="display:none" <? } ?>>
    <strong><br />
    </strong>
    <?
    $id_empresa = $_GET['idcliente'];
    $id_pregunta = $_GET['idpregunta'];
	?>
    
     <form action="preguntaLARC.php?idcliente=<?=$id_empresa?>&idpregunta=<?=$id_pregunta?>&item=<?=$Valor[0]?>" method="post">
      <table width="90%" border="0" align="center" cellpadding="0" cellspacing="10" bgcolor="#F8F8F8">
      <tr>
        <td width="28%">Nota General: 
        
        <input name="accion" type="hidden" value="guardar-nota" />
        <input name="cliente" type="hidden" value="<?=$empresa[0][0]?>" />
        <input name="pregunta" type="hidden" value="<?=$pregunta[0][0]?>" />
        <input name="item" type="hidden" value="<?=$Valor[0]?>" />
        <? $nota = $mysql->Select("select nota,comentario from notas_LARC where ID_items='".$Valor[0]."' and ID_clientes='".$empresa[0][0]."'")?>
        <input type="text" name="nota" id="nota" style="width:100px" value="<?=$nota[0][0]?>" />
          </td>
        <td width="55%">Observaciones: 
         
            <input type="text" name="comentario" id="comentario" style="width:75%" value="<?=utf8_encode($nota[0][1])?>" />
         </td>
        <td width="17%"><input type="submit" name="enviar" id="enviar" value="Guardar Cambios" /></td>
      </tr>
      </table>
     </form>
      <table width="90%" border="0" align="center" cellpadding="0" cellspacing="10" bgcolor="#F8F8F8">
      <tr>
        <td colspan="3">
        <form action="preguntaLARC.php?idcliente=<?=$id_empresa?>&idpregunta=<?=$id_pregunta?>&item=<?=$Valor[0]?>" method="post">
        	<table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td>Oportunidades de Mejora</td>
            <td width="150">
            
            Complejidad</td>
            <td width="150">Beneficio</td>
            <td width="100">&nbsp;</td>
          </tr>
        <? $mejoras = $mysql->Select("select * from mejoras_LARC where ID_items = '$Valor[0]' and ID_clientes = '".$empresa[0][0]."' ");
 	if(count($mejoras)!=0){
		foreach ($mejoras as $Valor2){
 ?> 
          <tr>
            <td align="left"><?=utf8_encode($Valor2[3])?></td>
            <td align="left"><?=utf8_encode($Valor2[4])?></td>
            <td align="left"><?=utf8_encode($Valor2[5])?></td>
            <td align="center"><a href="preguntaLARC.php?idcliente=<?=$id_empresa?>&idpregunta=<?=$id_pregunta?>&borrarmejora=<?=$Valor2[0]?>&item=<?=$Valor[0]?>"><img src="images/delete.gif" width="16" height="16" border="0" /></a></td>
          </tr>
        
   <? }} ?>     
        <tr>
            <td align="center"><label>
              <input name="accion" type="hidden" value="agregar-mejoras" />
        	<input name="cliente" type="hidden" value="<?=$empresa[0][0]?>" />
        	<input name="pregunta" type="hidden" value="<?=$pregunta[0][0]?>" />
        	<input name="item" type="hidden" value="<?=$Valor[0]?>" />
              <input type="text" name="mejora" id="mejora" style="width:98%" />
            </label></td>
            <td align="center"><label>
              <input type="text" name="complejidad" id="complejidad" style="width:140px" />
            </label></td>
            <td align="center"><input type="text" name="beneficio" id="beneficio" style="width:140px" /></td>
            <td align="center"><label>
              <input type="submit" name="enviar" id="enviar" value="Agregar" />
            </label></td>
          </tr>
        </table>
        </form>
        </td>
        </tr>
    </table>
    
    <br />
    </div>
    </td>
  </tr>
 
  <? }
  
  } else { ?>
  <tr>
    <td colspan="2" align="center" bgcolor="#FFFFFF">No hay Items Para Esta Pregunta</td>
    </tr>
  <? } ?>
  </table>

    <br />

<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="33%">
    <?
    	/* 
		
		*/
		$id_pregunta = $_GET['idpregunta'];
		$id_empresa = $_GET['idcliente'];
		
		/* detarminando el numero de pregunta */
		/* determinando el siguiente numero de pregunta a aplicar */
		
		$p = $mysql->Select("select num from Preguntas_LARC where ID_preguntas='$id_pregunta'");
		$numero_pregunta = $p[0][0];
		$condicion = true;
		//echo "$numero_pregunta";
		
		while($condicion){
			/* buscamos el ID de la pregunta  */
			$numero_pregunta -= 1;
			//echo " - $numero_pregunta";
			$p = $mysql->Select("select ID_preguntas from Preguntas_LARC where num='$numero_pregunta'");
			$id_pregunta_nuevo = $p[0][0];
			
			$p = $mysql->Select("select estado from preguntas_no_aplicables where id_pregunta='$id_pregunta_nuevo' and id_cliente = '$id_empresa'");
			
			if($p[0][0]== "" )
				$estado="1";
			if($p[0][0]=="1")
				$estado="1";
			if($p[0][0]=="0")
				$estado="0";
			//echo "(".$p[0][0]." : $estado)";
				
			if($estado == "0")
				$condicion = true;
			else
				$condicion = false;
			
		}
		
		
		
	?>
    
    
    <? if($id_pregunta_nuevo!=""){ ?>
   <h2> <a href="preguntaLARC.php?idcliente=<?=$id_empresa?>&idpregunta=<?=$id_pregunta_nuevo?>">Pregunta Anterior</a></h2>
    <? } ?>
    
    </td>
    <td width="33%" align="center"><h2><a href="evaluar2LARC.php?id=<?=$empresa[0][0]?>">Lista de Preguntas</a></h2></td>
    <td width="33%" align="right">
    <?
    	/* 
		
		*/
		$id_pregunta = $_GET['idpregunta'];
		$id_pregunta_siguiente = $id_pregunta+1;
		$id_empresa = $_GET['idcliente'];
		
		/* detarminando el numero de pregunta */
		/* determinando el siguiente numero de pregunta a aplicar */
		
		$p = $mysql->Select("select num from Preguntas_LARC where ID_preguntas='$id_pregunta'");
		$numero_pregunta = $p[0][0];
		$condicion = true;
		//echo "$numero_pregunta";
		
		while($condicion){
			/* buscamos el ID de la pregunta  */
			$numero_pregunta += 1;
			//echo " - $numero_pregunta";
			$p = $mysql->Select("select ID_preguntas from Preguntas_LARC where num='$numero_pregunta'");
			$id_pregunta_nuevo = $p[0][0];
			
			$p = $mysql->Select("select estado from preguntas_no_aplicables where id_pregunta='$id_pregunta_nuevo' and id_cliente = '$id_empresa'");
			
			if($p[0][0]== "" )
				$estado="1";
			if($p[0][0]=="1")
				$estado="1";
			if($p[0][0]=="0")
				$estado="0";
			//echo "(".$p[0][0]." : $estado)";
				
			if($estado == "0")
				$condicion = true;
			else
				$condicion = false;
			
		}
		
		
		
	?>
    
    <? if($id_pregunta_nuevo!=""){ ?>
   <h2> <a href="preguntaLARC.php?idcliente=<?=$id_empresa?>&idpregunta=<?=$id_pregunta_nuevo?>">Pregunta Siguiente</a></h2>
    <? } ?>
    </td>
  </tr>
</table>

 <br />
Sistema desarrollado por <a href="http://benzahosting.cl">BenzaHosting </a>
</body>
</html>
