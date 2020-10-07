<?
include "php/Configuracion.php";

/* lista de funciones */

function calcula_promedio_para_area($consulta_preguntas)
{
	/* Calcular promedio de notas */
	$mysql = new mysql("localhost", "sistemae_db", "sistemae_usuario", "usuario");
	/* buscamosla lista de preguntas */
	$promedio_elemento = 0;
	$suma_elemento = 0;
	$contador_elemento = 0;
	$error = false;
	$id_cliente = $_GET['id'];
	$error_prom = false;
	
	
	$preguntas = $mysql->Select("Select ID_preguntas from Preguntas_ISO where ".$consulta_preguntas." order by num");
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
   <img src="images/48.png" width="10" height="10" />
   <? } ?>
   <br />	
   <?
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Resultados de Evaluacion</title>
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
<? include "menuadmin.php" ?>

<? 
/* empresa a evaluar */
$empresa = $_GET['id'];
if(isset($empresa)){
	$empresa = $mysql->Select("select * from clientes where ID_clientes=$empresa");
}
?>

<h1>Resultados para  
  <select name="empresas" class="select-empresa" id="empresas" onchange="MM_jumpMenu('parent',this,0)" style="background-color:#B6B8AD">
  <option value="resultadosiso.php">Seleccione Empresa a Evaluar</option>
  
  <? $empresas = $mysql->Select("select * from clientes order by razon_social asc");
 	if(count($empresas)!=0){
		foreach ($empresas as $Valor){
 ?>
  
  <option value="resultadosiso.php?id=<?=$Valor[0]?>" <? if($_GET['id']==$Valor[0]){ ?> selected="selected" <? } ?>><?=utf8_encode($Valor[3])?></option>
  
  <? }} ?>
  
</select></h1>

  

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

                                    <table width="90%" border="0" align="center" cellpadding="5" cellspacing="10">
                                    <tr>
                                    <? $id_empresa = $_GET['id']; ?>
                                    <td width="25%" align="center" bgcolor="#000000"><a href="resultadosiso.php?id=<?=$id_empresa?>&lista=elementos" style=" color:#FFF">Lista de Elementos</a></td>
                                    <td width="25%" align="center" bgcolor="#000000"><a href="resultadosiso.php?id=<?=$id_empresa?>&lista=subelementos" style=" color:#FFF">Lista de Subelementos </a></td>
                                    <td width="25%" align="center" bgcolor="#000000"><a href="resultadosiso.php?id=<?=$id_empresa?>&lista=preguntas" style=" color:#FFF">Lista de Preguntas</a></td>
                                    
                                    
                                    <td width="25%" align="center" bgcolor="#000000"><a href="resultadosiso.php?id=<?=$id_empresa?>&lista=mejoras" style=" color:#FFF">Oportunidades de Mejora</a></td>
                                    
                                    </tr>
                                    </table>
                                    <br />
                                    <?
                                    $lista = $_GET['lista'];
                                    switch($lista){
                                    case 'preguntas':
                                    ?>
                                    <table width="90%" border="0" align="center" cellpadding="2" cellspacing="2" bgcolor="#F3F4F6">
                                    <tr>
                                    <? $id_empresa = $_GET['id']; ?>
                                    <td colspan="4" align="right" bgcolor="#B6B8AD"><a href="exportar-cvs-preguntas-ISO.php?id=<?=$id_empresa?>">Exportar a Excel</a></td>
                                    </tr>
                                    <tr>
                                    <td colspan="4"><strong>Lista de Preguntas</strong></td>
                                    </tr>
                                    <tr>
                                    <td width="77">N&deg; Pregunta</td>
                                    <td>Intencion</td>
                                    <td width="60" align="center">Promedio<br />
                                    Raz&oacute;n</td>
                                    <td width="10" align="center">&nbsp;</td>
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
                                                                      <td align="center" bgcolor="#FFFFFF">
                                                                      <span style=" font-size:14px;">
                                                                      <?=utf8_encode($Valor[1])?>
                                                                      </span>
                                                                      </td>
                                                                      <td bgcolor="#FFFFFF"><span style=" font-size:14px;"><?=  utf8_encode($Valor[6])?> </span>
                                                                      <?
                                                                      $id_pregunta = $Valor[0];
                                                                      $nro_item = $mysql->Select("select ID_items from items_ISO where ID_Preguntas='$id_pregunta'");
                                                                      $nro_item = count($nro_item);
                                                                      ?>
                                                                      <span style="font-size: 10px">(Num Item: <?=$nro_item?>)</span></td>
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
                                                                                              select items_ISO.ID_Items, items_ISO.item, notas_ISO.nota
                                                                                              from items_ISO, notas_ISO
                                                                                              where items_ISO.ID_preguntas='$id_pregunta'
                                                                                              and notas_ISO.ID_clientes='$id_cliente'
                                                                                              and notas_ISO.ID_items = items_ISO.ID_items;");
                                                                                              $nro_item = $mysql->Select("select ID_items from items_ISO where ID_Preguntas='$id_pregunta'");
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
                                                                                              <img src="images/48.png" width="10" height="10" />
                                                                                              <? } ?>
                                                                                              <br />
                                                                                              
                                                                                              </td>
                                                                                              <td align="center" bgcolor="#FFFFFF"><a href="preguntaempresaiso.php?idcliente=<?=$id_cliente?>&idpregunta=<?=$id_pregunta?>"><img src="images/icono_enlace_externo.png" width="12" height="12" border="0" /></a></td>
                                                                                              </tr>
                                                                                              
                                                                                              <? }} ?>
                                                                                              </table>
                                                                                              <?
                                                                                              break;
                                                                                              
                                                                                              case 'elementos':
                                                                                              ?>
                                                                                              <table width="90%" border="0" align="center" cellpadding="2" cellspacing="2" bgcolor="#F3F4F6">
                                                                                              <tr>
                                                                                              <? $id_empresa = $_GET['id']; ?>
                                                                                              <? $promedio_elementos = array(); ?>
                                                                                              <td colspan="3" align="right" bgcolor="#B6B8AD"><a href="exportar-cvs-elementos-ISO.php?id=<?=$id_empresa?>">Exportar a Excel</a></td>
                                                                                              </tr>
                                                                                              <tr>
                                                                                              <td colspan="3"><strong>Lista de Elementos</strong></td>
                                                                                              </tr>
                                                                                              <tr>
                                                                                              <td>Elemento</td>
                                                                                              <td width="66" align="center">Promedio</td>
                                                                                              <td width="10" align="center">&nbsp;</td>
                                                                                              </tr>
                                                                                              
                                                                                              <? $elementos = $mysql->Select("SELECT DISTINCT elemento FROM Preguntas_ISO");
                                                                                              if(count($elementos)!=0){
                                                                                              foreach ($elementos as $Valor){
                                                                                              
                                                                                              ?>
                                                                                              
                                                                                              <tr>
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
                                                                                                                       array_push($promedio_elementos , number_format($promedio_elemento,2));
                                                                                                                      ?>
                                                                                                                      <span style=" font-size:16px; font-weight:bold"><?=number_format($promedio_elemento,2)?></span>
                                                                                                                      <? if($error_prom){
                                                                                                                      ?>
                                                                                                                      <img src="images/48.png" width="10" height="10" />
                                                                                                                      <? } ?>
                                                                                                                      <br />
                                                                                                                      </td>
                                                                                                                      <td align="center" bgcolor="#FFFFFF"><a href="evaluar2empresaiso.php?id=<?=$id_cliente?>"><img src="images/icono_enlace_externo.png" alt="" width="12" height="12" border="0" /></a></td>
                                                                                                                      </tr>
                                                                                                                      
                                                                                                                      <? }} ?>
                                                                                                                      </table>
                                                                                                                      
                                                                                                                      <?
                                                                                                                      $elementos2=array();
                                                                                                                      foreach($elementos as $val){
                                                                                                                      array_push($elementos2, utf8_encode([$val])) ;
                                                                                                                      }
                                                                                                                      $a=count($elementos);
                                                                                                                      
                                                                                                                      
            /* pChart library inclusions */
include("pChart/pData.class.php");
include("pChart/pDraw.class.php");
include("pChart/pRadar.class.php");
include("pChart/pImage.class.php");
                                                                                                                      
            /* Create and populate the pData object */
                                                                                                                      $MyData = new pData();
                                                                                                                      $MyData->addPoints($promedio_elementos,"ScoreA");
                                                                                                                      $MyData->setSerieDescription("ScoreA","Application A");
            /* Define the absissa serie */
                                                                                                                      $MyData->addPoints(array("1","2","3","4","5","6","7"),"Labels");
                                                                                                                      $MyData->setAbscissa("Labels");
                                                                                                                      $MyData->setPalette("ScoreA",array("R"=>4,"G"=>147,"B"=>76));
                                                                                                                      
            /* Create the pChart object */
                                                                                                                      $myPicture = new pImage(1000,500,$MyData);
                                                                                                                      
            /* Draw a solid background */
                                                                                                                      $Settings = array("R"=>251, "G"=>218, "B"=>0, "Dash"=>1, "DashR"=>199, "DashG"=>237, "DashB"=>111);
                                                                                                                      $myPicture->drawFilledRectangle(0,0,1000,500,$Settings);
                                                                                                                      
            /* Overlay some gradient areas */
                                                                                                                      $Settings = array("StartR"=>290, "StartG"=>240, "StartB"=>0, "EndR"=>100, "EndG"=>40, "EndB"=>0, "Alpha"=>50);
                                                                                                                      $myPicture->drawGradientArea(0,0,1000,500,DIRECTION_VERTICAL,$Settings);
                                                                                                                      $myPicture->drawGradientArea(0,0,1000,20,DIRECTION_VERTICAL,array("StartR"=>0,"StartG"=>0,"StartB"=>0,"EndR"=>50,"EndG"=>50,"EndB"=>50,"Alpha"=>100));
                                                                                                                      
            /* Add a border to the picture */
                                                                                                                      $myPicture->drawRectangle(0,0,999,499,array("R"=>0,"G"=>0,"B"=>0));
                                                                                                                      
            /* Write the picture title */
                                                                                                                      $myPicture->setFontProperties(array("FontName"=>"fonts/Silkscreen.ttf","FontSize"=>10));
                                                                                                                      $myPicture->drawText(300,13,"Elementos - Cuestionario ISO 50000:2014",array("R"=>255,"G"=>255,"B"=>255));
                                                                                                                      
            /* Set the default font properties */
                                                                                                                      $myPicture->setFontProperties(array("FontName"=>"fonts/calibri.ttf","FontSize"=>15,"R"=>0,"G"=>0,"B"=>0));
                                                                                                                      
            /* Enable shadow computing */
                                                                                                                      $myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10));
                                                                                                                      
            /* Create the pRadar object */
                                                                                                                      $SplitChart = new pRadar();
                                                                                                                      
            /* Draw a radar chart */
                                                                                                                      $myPicture->setGraphArea(10,25,500,500);
                                                                                                                      $Options = array("Layout"=>RADAR_LAYOUT_STAR,"BackgroundGradient"=>array("StartR"=>255,"StartG"=>255,"StartB"=>255,"StartAlpha"=>100,"EndR"=>207,"EndG"=>227,"EndB"=>125,"EndAlpha"=>50), "FontName"=>"fonts/pf_arma_five.ttf","FontSize"=>6);
                                                                                                                      $SplitChart->drawRadar($myPicture,$MyData,$Options);
                                                                                                                      
                                                                                                                      $TextSettings = array("R"=>0,"G"=>0,"B"=>0,"Angle"=>0,"FontSize"=>20);
                                                                                                                      $myPicture->drawText(550,100,"1.- Contexto de la organización",$TextSettings);
                                                                                                                      $myPicture->drawText(550,150,"2.- Liderazgo",$TextSettings);
                                                                                                                      $myPicture->drawText(550,200,"3.- Planificación",$TextSettings);
                                                                                                                      $myPicture->drawText(550,250,"4.- Soporte",$TextSettings);
                                                                                                                      $myPicture->drawText(550,300,"5.- Operación",$TextSettings);
                                                                                                                      $myPicture->drawText(550,350,"6.- Evaluación de desempeño",$TextSettings);
                                                                                                                      $myPicture->drawText(550,400,"7.- Mejoramiento",$TextSettings);
                                                                                                                      
                                                                                                                      
                                                                                                                      
                                                                                                                      
                                                                                                                      
            /* Render the picture (choose the best way) */
                                                                                                                      $myPicture->Render("radar_elementos_ISO.png");                                       ?>
                                                                                                                      
                                                                                                                      
                                                                                                                      <table width="90%" border="0" align="center" bgcolor="#F3F4F6">
                                                                                                                      <td align="center" bgcolor="#FFFFFF"><img src="radar_elementos_ISO.png" /></td>
                                                                                                                      </table>
                                                                                                                      <?
                                                                                                                      break;
                                                                                                                      
                                                                                                                      case 'subelementos':
                                                                                                                      ?>
                                                                                                                      <table width="90%" border="0" align="center" cellpadding="2" cellspacing="2" bgcolor="#F3F4F6">
                                                                                                                      <tr>
                                                                                                                      <? $id_empresa = $_GET['id']; ?>
                                                                                                                      <td colspan="3" align="right" bgcolor="#B6B8AD"><a href="exportar-cvs-subelementos-ISO.php?id=<?=$id_empresa?>">Exportar a Excel</a></td>
                                                                                                                      </tr>
                                                                                                                      <tr>
                                                                                                                      <td colspan="3"><strong>Lista de SubElementos</strong></td>
                                                                                                                      </tr>
                                                                                                                      <tr>
                                                                                                                      <td>Elemento</td>
                                                                                                                      <td width="66" align="center">Promedio</td>
                                                                                                                      <td width="10" align="center">&nbsp;</td>
                                                                                                                      </tr>
                                                                                                                      
                                                                                                                      <? $elementos = $mysql->Select("	SELECT DISTINCT subelemento
                                                                                                                                                     FROM Preguntas_ISO");
                                                                                                                                                     if(count($elementos)!=0){
                                                                                                                                                     foreach ($elementos as $Valor){
                                                                                                                                                     
                                                                                                                                                     ?>
                                                                                                                                                     
                                                                                                                                                     <tr>
                                                                                                                                                     <td bgcolor="#FFFFFF"><span style=" font-size:14px;"><?= utf8_encode($Valor[0])?></span>
                                                                                                                                                     <?
                                                                                                                                                     $preguntas = $mysql->Select("Select ID_preguntas from Preguntas_ISO where subelemento = '$Valor[0]' order by num");
                                                                                                                                                     $num_preguntas = count($preguntas);
                                                                                                                                                     ?>
                                                                                                                                                     
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
                                                                                                                                                     
                                                                                                                                                     
                                                                                                                                                     $preguntas = $mysql->Select("Select ID_preguntas from Preguntas_ISO where subelemento = '$Valor[0]' order by num");
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
                                                                                                                                                                             <img src="images/48.png" width="10" height="10" />
                                                                                                                                                                             <? } ?>
                                                                                                                                                                             <br />
                                                                                                                                                                             </td>
                                                                                                                                                                             <td align="center" bgcolor="#FFFFFF"><a href="evaluar2empresaiso.php?id=<?=$id_cliente?>"><img src="images/icono_enlace_externo.png" alt="" width="12" height="12" border="0" /></a></td>
                                                                                                                                                                             </tr>
                                                                                                                                                                             
                                                                                                                                                                             <? }} ?>
                                                                                                                                                                             </table>
                                                                                                                                                                             <?
                                                                                                                                                                             break;
                                                                                                                                                                             
                                                                                                                                                                             case 'mejoras':
                                                                                                                                                                             ?>
                                                                                                                                                                             <table width="90%" border="0" align="center" cellpadding="2" cellspacing="2" bgcolor="#F3F4F6">
                                                                                                                                                                             <tr>
                                                                                                                                                                             <? $id_empresa = $_GET['id']; ?>
                                                                                                                                                                             <td colspan="5" align="right" bgcolor="#B6B8AD"><a href="exportar-cvs-oportunidades-de-mejora-ISO.php?id=<?=$id_empresa?>">Exportar a Excel</a></td>
                                                                                                                                                                             </tr>
                                                                                                                                                                             <tr>
                                                                                                                                                                             <td colspan="5"><strong>Listado  de oportunidades de mejora</strong></td>
                                                                                                                                                                             </tr>
                                                                                                                                                                             <tr>
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
                                                                                                                                                                                                           <td align="left" bgcolor="#FFFFFF">
                                                                                                                                                                                                           <span style=" font-size:14px;">
                                                                                                                                                                                                           <?=utf8_encode($Valor[0])?>
                                                                                                                                                                                                           </span>
                                                                                                                                                                                                           </td>
                                                                                                                                                                                                           <td align="center" bgcolor="#FFFFFF"><span style=" font-size:14px;">
                                                                                                                                                                                                           <?=utf8_encode($Valor[1])?>
                                                                                                                                                                                                           </span></td>
                                                                                                                                                                                                           <td align="center" bgcolor="#FFFFFF"><span style=" font-size:14px;">
                                                                                                                                                                                                           <?=utf8_encode($Valor[2])?>
                                                                                                                                                                                                           </span></td>
                                                                                                                                                                                                           <td align="center" bgcolor="#FFFFFF"><span style=" font-size:14px;">
                                                                                                                                                                                                           <?=utf8_encode($Valor[3])?>
                                                                                                                                                                                                           </span><br />
                                                                                                                                                                                                           
                                                                                                                                                                                                           </td>
                                                                                                                                                                                                           <td align="center" bgcolor="#FFFFFF"><a href="preguntaempresaiso.php?idcliente=<?=$id_empresa?>&idpregunta=<?=$Valor[5]?>&item=<?=$Valor[4]?>"><img src="images/icono_enlace_externo.png" width="12" height="12" border="0" /></a></td>
                                                                                                                                                                                                           </tr>
                                                                                                                                                                                                           
                                                                                                                                                                                                           <? }} ?>
                                                                                                                                                                                                           </table>
                                                                                                                                                                                                           <?
                                                                                                                                                                                                           break;
                                                                                                                                                                                                           
                                                                                                                                                                                                           }
                                                                                                                                                                                                           
                                                                                                                                                                                                           ?>
                                                                                                                                                                                                           
                                                                                                                                                                                                           <br />
                                                                                                                                                                                                           Sistema desarrollado por <a href="http://benzahosting.cl">BenzaHosting </a>
                                                                                                                                                                                                           </body>
                                                                                                                                                                                                           </html>

