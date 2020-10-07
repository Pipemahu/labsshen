<?
include "php/Configuracion.php";

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin t&iacute;tulo</title>
<script type="text/javascript" src="js/jquery-1.4.2.js"></script>
<script type="text/javascript">
<!--
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}

function aceptar_pregunta(IDCliente,IDPregunta){
	$.ajax({
		url: "ajax/validar-pregunta.php?cliente="+IDCliente+"&pregunta="+IDPregunta+"&accion=acepta",
		beforeSend:
			function(objeto){
        		document.getElementById("cargando_pregunta_minima_"+IDPregunta).innerHTML = "<img src='images/ajax-loader.gif' />";
        	},
		complete:
			function(objeto, exito){
            	if(exito=="success"){
               		document.getElementById("cargando_pregunta_minima_"+IDPregunta).innerHTML = ""; 
					$("#pregunta_minima_"+IDPregunta).slideToggle("slow");
					$("#pregunta_extendida_"+IDPregunta).slideToggle("slow");
            	}
        	},
		error:
			function(objeto, quepaso, otroobj){
            	
        	},
        success: 
			function(datos){
			}
       });
}

function rechazar_pregunta(IDCliente,IDPregunta){
		$.ajax({
		url: "ajax/validar-pregunta.php?cliente="+IDCliente+"&pregunta="+IDPregunta+"&accion=rechaza",
		beforeSend:
			function(objeto){
        		document.getElementById("cargando2_pregunta_minima_"+IDPregunta).innerHTML = "<img src='images/ajax-loader.gif' />";
        	},
		complete:
			function(objeto, exito){
            	if(exito=="success"){
               		document.getElementById("cargando2_pregunta_minima_"+IDPregunta).innerHTML = ""; 
					$("#pregunta_minima_"+IDPregunta).slideToggle("slow");
					$("#pregunta_extendida_"+IDPregunta).slideToggle("slow");
            	}
        	},
		error:
			function(objeto, quepaso, otroobj){
            	
        	},
        success: 
			function(datos){
			}
       })
}




//-->
</script>
</head>

<body>
<? include "menuempresa.php" ?>

<? 
/* empresa a evaluar */
$empresa = $_GET['id'];
if(isset($empresa)){
	$empresa = $mysql->Select("select * from clientes where ID_clientes=$empresa");
}
?>

<h1>Evaluacion para <?=$_SESSION['admin']?>

</h1>

  

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


    <table width="90%" border="0" align="center" cellpadding="0" cellspacing="2" bgcolor="#E8E8E8">
  <tr>
    <td width="1220"><strong>Lista de Preguntas</strong></td>
    </tr>
  
  <? $empresas = $mysql->Select("select * from Preguntas_ISO order by num asc");
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
    <td bgcolor="#FFFFFF">
    <div id="pregunta_minima_<?=$Valor[0]?>" <? if($estado=="1"){ echo "style=\"display:none\""; } ?> >
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><?= utf8_encode($Valor[7])?></td>
    <td width="50" align="right"><div id="cargando_pregunta_minima_<?=$Valor[0]?>" style="width:20px; float:left"></div>
      <? $id_empresa = $_GET['id']; ?>
      <a href="#" onclick="aceptar_pregunta(<?=$id_empresa?>,<?=$Valor[0]?>); return false;"><img src="images/up.gif" width="14" height="14" border="0" /></a></td>
  </tr>
</table>
    </div>
    <div id="pregunta_extendida_<?=$Valor[0]?>" <? if($estado=="0"){ echo "style=\"display:none\""; } ?>>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="138" align="left">Pregunta Nro:
          <?=$Valor[1]?></td>
        <td width="541" align="left">Tipo: 
          <?=utf8_encode($Valor[2])?></td>
        <td width="151" rowspan="6" align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td align="right">
            <? $id_empresa = $_GET['id']; ?>
            <div id="cargando2_pregunta_minima_<?=$Valor[0]?>" style="width:20px; float:left"></div><a href="#" onclick="rechazar_pregunta(<?=$id_empresa?>,<?=$Valor[0]?>); return false;"><img src="images/down.gif" width="14" height="14" border="0" /></a></td>
          </tr>
          <tr>
            <td align="center"><h3>Estado de Avance:</h3>
              <? 
			  /* calculo de estado de avance */
			  
			  /* primero ver los item de esta pregunta */
			  $Items = $mysql->Select("select COUNT(*) from items_ISO where ID_preguntas='".$Valor[0]."'");
			  echo "Numero de Items: ".$Items[0][0]."<br>";
			  
			  /* determinar cuentos iten fueron respondidos de esta pregunta */
			  $id_empresa = $_GET['id'];
			  
			  $Respuestas = $mysql->Select("
										   	SELECT COUNT(*) 
											FROM notas_ISO, items_ISO
											WHERE notas_ISO.ID_clientes = '$id_empresa'
											and items_ISO.ID_Preguntas = '$Valor[0]'
											AND notas_ISO.ID_items = items_ISO.ID_items");
			  
			  echo "Items Respondidos: ".$Respuestas[0][0]."<br>";
			  if($Items[0][0]!=0){
			  	$porcentaje = (float)((float)(100 * $Respuestas[0][0]) / (float)$Items[0][0]);
				echo "<h2>". number_format($porcentaje,2)." %</h2>"; 
			  } else {
			  	echo "<h2>0 %</h2>"; 
			  }
			  
			  
			  ?>
              
             
            </td>
          </tr>
          <tr>
            <td align="center"><h2><a href="preguntaempresaiso.php?idcliente=<?=$empresa[0][0]?>&idpregunta=<?=$Valor[0]?>">Responder</a></h2></td>
          </tr>
        </table></td>
        </tr>
      <tr>
        <td align="left">Elemento:</td>
        <td align="left"><?=utf8_encode($Valor[3])?></td>
        </tr>
      <tr>
        <td align="left">Sub Elemento:</td>
        <td align="left"><?= utf8_encode($Valor[4])?></td>
        </tr>
      <tr>
        <td align="left">Practica:</td>
        <td align="left"><?= utf8_encode($Valor[5])?></td>
        </tr>
      <tr>
        <td align="left">Pregunta:</td>
        <td align="left"><?=utf8_encode($Valor[6])?></td>
        </tr>
      <tr>
        <td align="left">Intencion de la Pregunta:</td>
        <td align="left"><?=utf8_encode( $Valor[7])?></td>
        </tr>
    </table>
    </div>
    </td>
  </tr>

  <? }} ?>
  </table>
 <br />
Sistema desarrollado por <a href="http://benzahosting.cl">BenzaHosting </a>
</body>
</html>
