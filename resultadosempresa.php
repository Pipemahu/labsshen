<?
include "php/Configuracion.php";

/* lista de funciones */
function calcula_promedio_para_area($consulta_preguntas)
{
    /* Calcular promedio de notas */
    $mysql = new mysql("localhost", "labsshe_db", "labsshe_db", "123456");
    /* buscamosla lista de preguntas */
    $promedio_elemento = 0;
    $suma_elemento = 0;
    $contador_elemento = 0;
    $error = false;
    $id_cliente = $_GET['id'];
    $error_prom = false;
    
    
    $preguntas = $mysql->Select("Select ID_preguntas from preguntas where ".$consulta_preguntas." order by num");
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
                                    select items.ID_Items, items.item, notas.nota
                                    from items, notas
                                    where items.ID_preguntas='$id_pregunta'
                                    and notas.ID_clientes='$id_cliente'
                                    and notas.ID_items = items.ID_items;");
                                    $nro_item = $mysql->Select("select ID_items from items where ID_Preguntas='$id_pregunta'");
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
                                    return $promedio_elemento
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
                                    <?
                                    function calcula_promedio_para_items($consulta_items)
                                    {
                                    /* Calcular promedio de notas */
                                    $mysql = new mysql("localhost", "labsshe_db", "labsshe_db", "123456");
                                    /* buscamosla lista de preguntas */
                                    $promedio_elemento = 0;
                                    $suma_elemento = 0;
                                    $contador_elemento = 0;
                                    $error = false;
                                    $id_cliente = $_GET['id'];
                                    $error_prom = false;
                                    
                                    
                                    $preguntas = array($consulta_items);
                                    $num_preguntas = count($preguntas);
                                    
                                    $items = $mysql->Select("select nota from notas where ID_items='$preguntas' and ID_clientes='$id_cliente'");
                                    
                                    
                                    
                                    
                                    foreach ($preguntas as $Valor2){
                                    
                                    //echo "pre: ".$Valor2[0]."<br>";
                                    
                                    $id_pregunta = $Valor2;
                                    $contador = 0;
                                    $suma = 0;
                                    $error = false;
                                    $estado_pregunta=1;
                                    
                                    
                                    if($estado_pregunta=="0"){
                                    $promedio = 0;
                                    } else{
                                    $items = $mysql->Select("
                                                            select items.ID_Items, items.item, notas.nota
                                                            from items, notas
                                                            where items.ID_items='$id_pregunta'
                                                            and notas.ID_clientes='$id_cliente'
                                                            and notas.ID_items = items.ID_items;");
                                                            $nro_item = $id_pregunta;
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
                                                            }
                                                            }
                                                            return $promedio
                                                            ?>
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
<? include "menuempresa.php" ?>

<? 
/* empresa a evaluar */
$empresa = $_GET['id'];
if(isset($empresa)){
	$empresa = $mysql->Select("select * from clientes where ID_clientes=$empresa");
}
?>

<h1>Resultados para  <?=$_SESSION['admin']?>

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

<table width="90%" border="0" align="center" cellpadding="5" cellspacing="10">
  <tr>
    <? $id_empresa = $_GET['id']; ?>
    <td width="15%" align="center" bgcolor="#000000"><a href="resultadosempresa.php?id=<?=$id_empresa?>&lista=elementos" style=" color:#FFF">Lista de Elementos</a></td>
     <td width="15%" align="center" bgcolor="#000000"><a href="resultadosempresa.php?id=<?=$id_empresa?>&lista=subelementos" style=" color:#FFF">Lista de Subelementos </a></td>
    <td width="15%" align="center" bgcolor="#000000"><a href="resultadosempresa.php?id=<?=$id_empresa?>&lista=preguntas" style=" color:#FFF">Lista de Preguntas</a></td>
   
    
    <td width="15%" align="center" bgcolor="#000000"><a href="resultadosempresa.php?id=<?=$id_empresa?>&lista=mejoras" style=" color:#FFF">Oportunidades de Mejora</a></td>
    <td width="15%" align="center" bgcolor="#000000"><a href="resultadosempresa.php?id=<?=$id_empresa?>&amp;lista=area" style=" color:#FFF">Promedio por Area</a></td>
     <td width="15%" align="center" bgcolor="#000000"><a href="resultadosempresa.php?id=<?=$id_empresa?>&amp;lista=ISO" style=" color:#FFF">Promedio 24 areas ISO 55000</a></td>
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
    <td colspan="4" align="right" bgcolor="#B6B8AD"><a href="exportar-cvs-preguntas.php?id=<?=$id_empresa?>">Exportar a Excel</a></td>
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
    <td align="center" bgcolor="#FFFFFF">
    <span style=" font-size:14px;">
      <?=utf8_encode($Valor[1])?>
   </span>
    </td>
    <td bgcolor="#FFFFFF"><span style=" font-size:14px;"><?= utf8_encode($Valor[6])?> </span>
    <? 
	$id_pregunta = $Valor[0];
	$nro_item = $mysql->Select("select ID_items from items where ID_Preguntas='$id_pregunta'");
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
   <img src="images/48.png" width="10" height="10" />
   <? } ?>
   <br />

    </td>
    <td align="center" bgcolor="#FFFFFF"><a href="preguntaempresa.php?idcliente=<?=$id_cliente?>&idpregunta=<?=$id_pregunta?>"><img src="images/icono_enlace_externo.png" width="12" height="12" border="0" /></a></td>
  </tr>

  <? }} ?>
  </table>
    <?
	break;
                                case 'ISO':
                                ?>
                                <table width="90%" border="0" align="center" cellpadding="2" cellspacing="2" bgcolor="#F3F4F6">
                                <tr>
                                <? $id_empresa = $_GET['id']; ?>
                                <? $promedio_elementos = array(); ?>
                                
                                <td colspan="3" align="right" bgcolor="#B6B8AD"><a href="exportar-cvs-ISO.php?id=<?=$id_empresa?>">Exportar a Excel</a></td>
                                
                                </tr>
                                <tr>
                                <td colspan="3"><strong>Lista de Elementos</strong></td>
                                </tr>
                                <tr>
                                <td>Area ISO 55000:2014</td>
                                <td width="66" align="center">Promedio</td>
                                <td width="10" align="center">&nbsp;</td>
                                </tr>
                                
                                <tr>
                                <td align="left" bgcolor="#FFFFFF">Entendimiento de la organizaci&oacute;n y su contexto</td>
                                <td align="center" bgcolor="#FFFFFF">
                                <span style=" font-size:16px; font-weight:bold"><?=number_format(calcula_promedio_para_area("num='1' or num='2'"),2)?></span>
                                </td>
                                
                                <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
                                </tr>
                                
                                
                                <tr>
                                <td align="left" bgcolor="#FFFFFF">Entendimiento de las necesidades y expectativas de los involucrados</td>
                                <td align="center" bgcolor="#FFFFFF">
                                <span style=" font-size:16px; font-weight:bold"><?=number_format(calcula_promedio_para_area("num='9' or num='11' or num='20' or num='65' or num = '66' or num='72'"),2)?></span>
                                </td>
                                <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
                                </tr>
                                
                                <? $promedio_alcances=(calcula_promedio_para_items('1')+calcula_promedio_para_items('2')+calcula_promedio_para_items('3')+calcula_promedio_para_items('4')+calcula_promedio_para_items('5')+calcula_promedio_para_items('6')+calcula_promedio_para_items('7')+calcula_promedio_para_items('8')+calcula_promedio_para_items('9')+calcula_promedio_para_items('10')+calcula_promedio_para_items('328')+calcula_promedio_para_items('329')+calcula_promedio_para_items('330'))/13; ?>
                                <tr>
                                <td align="left" bgcolor="#FFFFFF">Determinar el alcance del sistema de gesti&oacute;n de activos</td>
                                <td align="center" bgcolor="#FFFFFF">
                                <span style=" font-size:16px; font-weight:bold"><?=number_format($promedio_alcances,2)?></span>
                                </td>
                                <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
                                </tr>
                                <? $promedio_definicion=(calcula_promedio_para_items('155')+calcula_promedio_para_items('117')+calcula_promedio_para_items('118')+calcula_promedio_para_items('119')+calcula_promedio_para_items('120')+calcula_promedio_para_items('121')+calcula_promedio_para_items('122')+calcula_promedio_para_items('123'))/8; ?>
                                <tr>
                                <td align="left" bgcolor="#FFFFFF">Definici&oacute;n del sistema de gesti&oacute;n de activos.</td>
                                <td align="center" bgcolor="#FFFFFF">
                                <span style=" font-size:16px; font-weight:bold"><?=number_format($promedio_definicion,2)?></span>
                                </td>
                                <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
                                </tr>
                                
                                <tr>
                                <td align="left" bgcolor="#FFFFFF">Liderazgo y compromiso</td>
                                <td align="center" bgcolor="#FFFFFF">
                                <span style=" font-size:16px; font-weight:bold"><?=number_format(calcula_promedio_para_area("num='4' or num='7' or num='8'"),2)?></span>
                                
                                </td>
                                <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
                                </tr>
                                
                                <? $promedio_normativa=(calcula_promedio_para_items('95')+calcula_promedio_para_items('78'))/2; ?>
                                <tr>
                                <td align="left" bgcolor="#FFFFFF">Normativa y leyes</td>
                                <td align="center" bgcolor="#FFFFFF">
                                <span style=" font-size:16px; font-weight:bold"><?=number_format($promedio_normativa,2)?></span>
                                
                                </td>
                                <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
                                </tr>
                                
                                <tr>
                                <td align="left" bgcolor="#FFFFFF">Roles organizacionales, responsabilidades y autoridades</td>
                                <td align="center" bgcolor="#FFFFFF">
                                <span style=" font-size:16px; font-weight:bold"><?=number_format(calcula_promedio_para_area("num='3' or num='94'"),2)?></span>
                                </td>
                                <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
                                </tr>
                                
                                
                                <tr>
                                <td align="left" bgcolor="#FFFFFF">Acciones para abordar los riesgo y oportunidades del sistema de gesti&oacute;n de activos</td>
                                <td align="center" bgcolor="#FFFFFF">
                                <span style=" font-size:16px; font-weight:bold"><?=number_format(calcula_promedio_para_area("num='10' or num='50'"),2)?></span>
                                </td>
                                <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
                                </tr>
                                
                                
                                <tr>
                                <td align="left" bgcolor="#FFFFFF">Objetivos de la gesti&oacute;n de activos y su planifica&oacute;n para alcanzarlos</td>
                                <td align="center" bgcolor="#FFFFFF">
                                <span style=" font-size:16px; font-weight:bold"><?=number_format(calcula_promedio_para_area("num='24' or num='25' or num='88' or num='89' or num='96'"),2)?></span>
                                </td>
                                <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
                                </tr>
                                
                                
                                <tr>
                                <td align="left" bgcolor="#FFFFFF">Recursos</td>
                                <td align="center" bgcolor="#FFFFFF">
                                <span style=" font-size:16px; font-weight:bold"><?=number_format(calcula_promedio_para_area("num='13' or num='93'") ,2)?></span>
                                </td>
                                <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
                                </tr>
                                
                                
                                <tr>
                                <td align="left" bgcolor="#FFFFFF">Competencias</td>
                                <td align="center" bgcolor="#FFFFFF">
                                <span style=" font-size:16px; font-weight:bold"><?=number_format(calcula_promedio_para_area("num='6' or num='40' or num='52'"),2)?></span>
                                </td>
                                <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
                                </tr>
                                
                                <? $promedio_conciencia=(calcula_promedio_para_items('15')+calcula_promedio_para_items('17')+calcula_promedio_para_items('22')+calcula_promedio_para_items('23')+calcula_promedio_para_items('24')+calcula_promedio_para_items('27')+calcula_promedio_para_items('28')+calcula_promedio_para_items('37')+calcula_promedio_para_items('39')+calcula_promedio_para_items('55')+calcula_promedio_para_items('56')+calcula_promedio_para_items('84')+calcula_promedio_para_items('85')+calcula_promedio_para_items('124')+calcula_promedio_para_items('161'))/15; ?>
                                <tr>
                                <td align="left" bgcolor="#FFFFFF">Conciencia</td>
                                <td align="center" bgcolor="#FFFFFF">
                                <span style=" font-size:16px; font-weight:bold"><?=number_format($promedio_conciencia,2)?></span>
                                </td>
                                <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
                                </tr>
                                <? $promedio_comunicacion=(calcula_promedio_para_items('3')+calcula_promedio_para_items('4')+calcula_promedio_para_items('8')+calcula_promedio_para_items('15')+calcula_promedio_para_items('102')+calcula_promedio_para_items('131')+calcula_promedio_para_items('140')+calcula_promedio_para_items('339')+calcula_promedio_para_items('280')+calcula_promedio_para_items('281')+calcula_promedio_para_items('282')+calcula_promedio_para_items('283')+calcula_promedio_para_items('284'))/13; ?>
                                <tr>
                                <td align="left" bgcolor="#FFFFFF">Comunicaci&oacute;n</td>
                                <td align="center" bgcolor="#FFFFFF">
                                <span style=" font-size:16px; font-weight:bold"><?=number_format($promedio_comunicacion,2)?></span>
                                </td>
                                <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
                                </tr>
                                <tr>
                                <td align="left" bgcolor="#FFFFFF">Requerimientos de informaci&oacute;n</td>
                                <td align="center" bgcolor="#FFFFFF">
                                <span style=" font-size:16px; font-weight:bold"><?=number_format(calcula_promedio_para_area("num='51' or num='62' or num='74'"),2)?></span>
                                </td>
                                <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
                                </tr>
                                <tr>
                                <td align="left" bgcolor="#FFFFFF">Informaci&oacute;n documentada</td>
                                <td align="center" bgcolor="#FFFFFF">
                                <span style=" font-size:16px; font-weight:bold"><?=number_format(calcula_promedio_para_area("num='27' or num='81' or num='82' or num='83' or num='84' or num='85' or num='86'"),2)?></span>
                                </td>
                                <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
                                </tr>
                                <tr>
                                <td align="left" bgcolor="#FFFFFF">Planifica&oacute;n y control operacional</td>
                                <td align="center" bgcolor="#FFFFFF">
                                <span style=" font-size:16px; font-weight:bold"><?=number_format(calcula_promedio_para_area("num='26' or num='34' or num='43' or num='53' or num='54' or num='55' or num='56' or num='57' or num='60' or num='61' or num='68' or num='69' or num='71' or num='73' or num='75'"),2)?></span>
                                </td>
                                <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
                                </tr>
                                
                                <tr>
                                <td align="left" bgcolor="#FFFFFF">Gesti&oacute;n del cambio</td>
                                <td align="center" bgcolor="#FFFFFF">
                                <span style=" font-size:16px; font-weight:bold"><?=number_format(calcula_promedio_para_area("num='21' or num='44' or num='45' or num='59'"),2)?></span>
                                </td>
                                <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
                                </tr>
                                
                                <tr>
                                <td align="left" bgcolor="#FFFFFF">Subcontrataci&oacute;n</td>
                                <td align="center" bgcolor="#FFFFFF">
                                <span style=" font-size:16px; font-weight:bold"><?=number_format(calcula_promedio_para_area("num='77' or num='78' or num='79'"),2)?></span>
                                </td>
                                <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
                                </tr>
                                
                                <tr>
                                <td align="left" bgcolor="#FFFFFF">Monitores, medici&oacute;n, an&aacute;lisis, y evaluaci&oacute;n</td>
                                <td align="center" bgcolor="#FFFFFF">
                                <span style=" font-size:16px; font-weight:bold"><?=number_format(calcula_promedio_para_area("num='5' or num='12' or num='29' or num='30' or num='33' or num='37' or num='42' or num='67' or num='70' or num='76' or num='80' or num='90' or num='95' or num='97'"),2)?></span>
                                </td>
                                <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
                                </tr>
                                
                                <? $promedio_auditoria=(calcula_promedio_para_items('40')+calcula_promedio_para_items('81')+calcula_promedio_para_items('82')+calcula_promedio_para_items('111')+calcula_promedio_para_items('167')+calcula_promedio_para_items('451'))/6; ?>
                                <tr>
                                <td align="left" bgcolor="#FFFFFF">Auditor&iacute;as internas</td>
                                <td align="center" bgcolor="#FFFFFF">
                                <span style=" font-size:16px; font-weight:bold"><?=number_format($promedio_auditoria,2)?></span>
                                </td>
                                <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
                                </tr>
                                
                                <tr>
                                <td align="left" bgcolor="#FFFFFF">Revisi&oacute;n de la gesti&oacute;n</td>
                                <td align="center" bgcolor="#FFFFFF">
                                <span style=" font-size:16px; font-weight:bold"><?=number_format(calcula_promedio_para_area("num='23' or num='31' or num='35' or num='39' or num='64' or num='87' or num='91'"),2)?></span>
                                </td>
                                <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
                                </tr>
                                
                                <tr>
                                <td align="left" bgcolor="#FFFFFF">Defectos y acci&oacute;n correctiva</td>
                                <td align="center" bgcolor="#FFFFFF">
                                <span style=" font-size:16px; font-weight:bold"><?=number_format(calcula_promedio_para_area("num='16' or num='17' or num='22' or num='46' or num='48' or num='49'"),2)?></span>
                                </td>
                                <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
                                </tr>
                                
                                <tr>
                                <td align="left" bgcolor="#FFFFFF">Acci&oacute;n preventiva</td>
                                <td align="center" bgcolor="#FFFFFF">
                                <span style=" font-size:16px; font-weight:bold"><?=number_format(calcula_promedio_para_area("num='28' or num='32' or num='36' or num='38' or num='41'"),2)?></span>
                                </td>
                                <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
                                </tr>
                                
                                <tr>
                                <td align="left" bgcolor="#FFFFFF">Mejoramiento continuo</td>
                                <td align="center" bgcolor="#FFFFFF">
                                <span style=" font-size:16px; font-weight:bold"><?=number_format(calcula_promedio_para_area("num='14' or num='15' or num='63' or num='92'"),2)?></span>
                                </td>
                                <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
                                </tr>
                                
                                </table>
                                <?
                                $promedio_elementos=array(number_format(calcula_promedio_para_area("num='1' or num='2'"),2),
                                                          number_format(calcula_promedio_para_area("num='9' or num='11' or num='20' or num='65' or num = '66' or num='72'"),2),
                                                          number_format($promedio_alcances,2),
                                                          number_format($promedio_definicion,2),
                                                          number_format(calcula_promedio_para_area("num='4' or num='7' or num='8'"),2),
                                                          number_format($promedio_normativa,2),
                                                          number_format(calcula_promedio_para_area("num='3' or num='94'"),2),
                                                          number_format(calcula_promedio_para_area("num='10' or num='50'"),2),
                                                          number_format(calcula_promedio_para_area("num='24' or num='25' or num='88' or num='89' or num='96'"),2),
                                                          number_format(calcula_promedio_para_area("num='13' or num='93'") ,2),
                                                          number_format(calcula_promedio_para_area("num='6' or num='40' or num='52'"),2),
                                                          number_format($promedio_conciencia,2),
                                                          number_format($promedio_comunicacion,2),
                                                          number_format(calcula_promedio_para_area("num='51' or num='62' or num='74'"),2),
                                                          number_format(calcula_promedio_para_area("num='27' or num='81' or num='82' or num='83' or num='84' or num='85' or num='86'"),2),
                                                          number_format(calcula_promedio_para_area("num='26' or num='34' or num='43' or num='53' or num='54' or num='55' or num='56' or num='57' or num='60' or num='61' or num='68' or num='69' or num='71' or num='73' or num='75'"),2),
                                                          number_format(calcula_promedio_para_area("num='21' or num='44' or num='45' or num='59'"),2),
                                                          number_format(calcula_promedio_para_area("num='77' or num='78' or num='79'"),2),
                                                          number_format(calcula_promedio_para_area("num='5' or num='12' or num='29' or num='30' or num='33' or num='37' or num='42' or num='67' or num='70' or num='76' or num='80' or num='90' or num='95' or num='97'"),2),
                                                          number_format($promedio_auditoria,2),
                                                          number_format(calcula_promedio_para_area("num='23' or num='31' or num='35' or num='39' or num='64' or num='87' or num='91'"),2),
                                                          number_format(calcula_promedio_para_area("num='16' or num='17' or num='22' or num='46' or num='48' or num='49'"),2),
                                                          number_format(calcula_promedio_para_area("num='28' or num='32' or num='36' or num='38' or num='41'"),2),
                                                          number_format(calcula_promedio_para_area("num='14' or num='15' or num='63' or num='92'"),2));
                                
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
                                $MyData->addPoints(array("1","2","3","4","5","6","7","8","9","10","11","12","13","14","15","16","17","18","19","20","21","22","23","24"),"Labels");
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
                                $myPicture->drawText(380,13,"24 Areas ISO 55000:2014",array("R"=>255,"G"=>255,"B"=>255));
                                
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
                                
                                
                                
                                
                                
                                $TextSettings = array("R"=>0,"G"=>0,"B"=>0,"Angle"=>0,"FontSize"=>13);
                                $myPicture->drawText(550,50,"1.- Entendimiento de la organización y su contexto.",$TextSettings);
                                $myPicture->drawText(550,68,"2.- Entendimiento de las necesidades de los involucrados",$TextSettings);
                                $myPicture->drawText(550,86,"3.- Determinar el alcance del sistema de gestión de activos.",$TextSettings);
                                $myPicture->drawText(550,104,"4.- Definición del sistema de gestión de activos",$TextSettings);
                                $myPicture->drawText(550,122,"5.- Liderazgo y compromiso",$TextSettings);
                                $myPicture->drawText(550,140,"6.- Normativa y leyes",$TextSettings);
                                $myPicture->drawText(550,158,"7.- Roles organizacionales, responsabilidades y autoridades.",$TextSettings);
                                $myPicture->drawText(550,176,"8.- Riesgos y oportunidades del sistema de gestión de activos ",$TextSettings);
                                $myPicture->drawText(550,194,"9.- Objetivos de la gestión de activos.",$TextSettings);
                                $myPicture->drawText(550,212,"10.- Recursos",$TextSettings);
                                $myPicture->drawText(550,230,"11.- Competencias",$TextSettings);
                                $myPicture->drawText(550,248,"12.- Conciencia",$TextSettings);
                                $myPicture->drawText(550,266,"13.- Comunicación",$TextSettings);
                                $myPicture->drawText(550,284,"14.- Requerimientos de información. ",$TextSettings);
                                $myPicture->drawText(550,302,"15.- Información documentada.",$TextSettings);
                                $myPicture->drawText(550,320,"16.- Planificación y control operacional.",$TextSettings);
                                $myPicture->drawText(550,338,"17.- Gestión del cambio",$TextSettings);
                                $myPicture->drawText(550,356,"18.- Subcontratación ",$TextSettings);
                                $myPicture->drawText(550,374,"19.- Monitoreo, medición, analisis y evaluación. ",$TextSettings);
                                $myPicture->drawText(550,392,"20.- Auditorías internas ",$TextSettings);
                                $myPicture->drawText(550,410,"21.- Revisión de la gestión",$TextSettings);
                                $myPicture->drawText(550,428,"22.- Defectos y acción corretiva.",$TextSettings);
                                $myPicture->drawText(550,446,"23.- Acción Preventiva ",$TextSettings);
                                $myPicture->drawText(550,464,"24.- Mejoramiento Continuo",$TextSettings);
                                
                                
                                
                                
                                
                                                                                       /* Render the picture (choose the best way) */
                                                                                       $myPicture->Render("radar_elementos_16_areas.png");                                       ?>
                                
                                
                                                                                       <table width="90%" border="0" align="center" bgcolor="#F3F4F6">
                                                                                       <td align="center" bgcolor="#FFFFFF"><img src="radar_elementos_16_areas.png" /></td>
                                                                                       </table>
                                
                                
                                                                                       <?
                                                                                       break;
	case 'elementos':
	?>
    <table width="90%" border="0" align="center" cellpadding="2" cellspacing="2" bgcolor="#F3F4F6">
  <tr>
  <? $id_empresa = $_GET['id']; ?>
  <? $promedio_elementos = array(); ?>
    <td colspan="3" align="right" bgcolor="#B6B8AD"><a href="exportar-cvs-elementos.php?id=<?=$id_empresa?>">Exportar a Excel</a></td>
    </tr>
  <tr>
    <td colspan="3"><strong>Lista de Elementos</strong></td>
    </tr>
  <tr>
    <td>Elemento</td>
    <td width="66" align="center">Promedio</td>
    <td width="10" align="center">&nbsp;</td>
    </tr>
  
  <? $elementos = $mysql->Select("	SELECT DISTINCT elemento
									FROM preguntas");
 	if(count($elementos)!=0){
		foreach ($elementos as $Valor){
			
 ?>
   
  <tr>
    <td bgcolor="#FFFFFF"><span style=" font-size:14px;"><?= utf8_encode($Valor[0])?>
    </span>
    <? $preguntas = $mysql->Select("Select ID_preguntas from preguntas where elemento = '$Valor[0]' order by num");
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
	
	
	$preguntas = $mysql->Select("Select ID_preguntas from preguntas where elemento = '$Valor[0]' order by num");
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
										select items.ID_Items, items.item, notas.nota
										from items, notas
										where items.ID_preguntas='$id_pregunta'
										and notas.ID_clientes='$id_cliente'
										and notas.ID_items = items.ID_items;");
			$nro_item = $mysql->Select("select ID_items from items where ID_Preguntas='$id_pregunta'");
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
    <td align="center" bgcolor="#FFFFFF"><a href="evaluar2.php?id=<?=$id_cliente?>"><img src="images/icono_enlace_externo.png" alt="" width="12" height="12" border="0" /></a></td>
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
                                    $MyData->addPoints(array("1","2","3","4","5","6","7","8","9","10","11","12","13","14"),"Labels");
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
                                    $myPicture->drawText(380,13,"Elementos - 16 areas clave",array("R"=>255,"G"=>255,"B"=>255));
                                    
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
                                    
                                    $TextSettings = array("R"=>0,"G"=>0,"B"=>0,"Angle"=>0,"FontSize"=>15);
                                    $myPicture->drawText(550,100,"1.- Administración y uso de la información",$TextSettings);
                                    $myPicture->drawText(550,125,"2.- Entrega del proyecto",$TextSettings);
                                    $myPicture->drawText(550,150,"3.- Gestión de Suministros",$TextSettings);
                                    $myPicture->drawText(550,175,"4.- Mantenimiento (garantía de funcionamiento)",$TextSettings);
                                    $myPicture->drawText(550,200,"5.- Producción",$TextSettings);
                                    $myPicture->drawText(550,225,"6.- Gestión SSMC",$TextSettings);
                                    $myPicture->drawText(550,250,"7.- Metodología de mejoramiento",$TextSettings);
                                    $myPicture->drawText(550,275,"8.- Métrica y presentación de informes",$TextSettings);
                                    $myPicture->drawText(550,300,"9.- Intercambio de conocimientos e innovación",$TextSettings);
                                    $myPicture->drawText(550,325,"10.- Enfoque en el mercado – clientes / proveedores",$TextSettings);
                                    $myPicture->drawText(550,350,"11.- Liderazgo y alineamiento ",$TextSettings);
                                    $myPicture->drawText(550,375,"12.- Administración del desempeño (contribución)",$TextSettings);
                                    $myPicture->drawText(550,400,"13.- Valores, Misión y Visión ",$TextSettings);
                                    $myPicture->drawText(550,425,"14.- Preparación de presupuestos y control de costos ",$TextSettings);
                                    
                                    
                                    
                                    
            /* Render the picture (choose the best way) */
                                    $myPicture->Render("radar_elementos_16_areas.png");                                       ?>
                                    
                                    
                                    <table width="90%" border="0" align="center" bgcolor="#F3F4F6">
                                    <td align="center" bgcolor="#FFFFFF"><img src="radar_elementos_16_areas.png" /></td>
                                    </table>

    <?
	break;
	
	case 'subelementos':
	?>
<table width="90%" border="0" align="center" cellpadding="2" cellspacing="2" bgcolor="#F3F4F6">
  <tr>
  <? $id_empresa = $_GET['id']; ?>
    <td colspan="3" align="right" bgcolor="#B6B8AD"><a href="exportar-cvs-subelementos.php?id=<?=$id_empresa?>">Exportar a Excel</a></td>
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
									FROM preguntas");
 	if(count($elementos)!=0){
		foreach ($elementos as $Valor){
			
 ?>
   
  <tr>
    <td bgcolor="#FFFFFF"><span style=" font-size:14px;"><?= utf8_encode($Valor[0])?></span>
    <?
    $preguntas = $mysql->Select("Select ID_preguntas from preguntas where subelemento = '$Valor[0]' order by num");
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
	
	
	$preguntas = $mysql->Select("Select ID_preguntas from preguntas where subelemento = '$Valor[0]' order by num");
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
										select items.ID_Items, items.item, notas.nota
										from items, notas
										where items.ID_preguntas='$id_pregunta'
										and notas.ID_clientes='$id_cliente'
										and notas.ID_items = items.ID_items;");
			$nro_item = $mysql->Select("select ID_items from items where ID_Preguntas='$id_pregunta'");
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
    <td align="center" bgcolor="#FFFFFF"><a href="evaluar2.php?id=<?=$id_cliente?>"><img src="images/icono_enlace_externo.png" alt="" width="12" height="12" border="0" /></a></td>
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
    <td colspan="5" align="right" bgcolor="#B6B8AD"><a href="exportar-cvs-oportunidades-de-mejora.php?id=<?=$id_empresa?>">Exportar a Excel</a></td>
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
								SELECT mejoras.mejora, mejoras.complejidad, mejoras.beneficio, mejoras.complejidad *  mejoras.beneficio AS indice, mejoras.ID_items, items.ID_preguntas
								FROM mejoras, items
								WHERE mejoras.ID_clientes = '$id_empresa'
								AND items.ID_items = mejoras.ID_items
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
    <td align="center" bgcolor="#FFFFFF"><a href="preguntaempresa.php?idcliente=<?=$id_empresa?>&idpregunta=<?=$Valor[5]?>&item=<?=$Valor[4]?>"><img src="images/icono_enlace_externo.png" width="12" height="12" border="0" /></a></td>
  </tr>

  <? }} ?>
  </table>
	<?
	break;
                                case 'area':
                                ?>
                                <table width="90%" border="0" align="center" cellpadding="2" cellspacing="2" bgcolor="#F3F4F6">
                                <tr>
                                <? $id_empresa = $_GET['id']; ?>
                                <td colspan="3" align="right" bgcolor="#B6B8AD"><a href="exportar-cvs-areas.php?id=<?=$id_empresa?>">Exportar a Excel</a></td>
                                </tr>
                                <tr>
                                <td colspan="3"><strong>Listado  de Areas de Intervencion</strong></td>
                                </tr>
                                <tr>
                                <td>Area</td>
                                <td width="100" align="center">Promedio</td>
                                <td width="10" align="center">&nbsp;</td>
                                </tr>
                                
                                
                                
                                <tr>
                                <td align="left" bgcolor="#FFFFFF">Administraci&oacute;n de SSMC (Salud Seguridad Medio Ambiente y Comunidad)</td>
                                <td align="center" bgcolor="#FFFFFF">
                                <span style=" font-size:16px; font-weight:bold"><?=number_format(calcula_promedio_para_area("num='16' or num='17' or num='18' or num='19' or num = '20' or num='21' or num='22' or num='23'"),2)?></span>
                                </td>
                                
                                <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
                                </tr>
                                
                                
                                <tr>
                                <td align="left" bgcolor="#FFFFFF">Alineaci&oacute;n con la visi&oacute;n y el negocio</td>
                                <td align="center" bgcolor="#FFFFFF">
                                <span style=" font-size:16px; font-weight:bold"><?=number_format(calcula_promedio_para_area("num='1' or num='2' or num='9' or num='14' or num = '24' or num='26' or num='28'"),2)?></span>
                                </td>
                                <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
                                </tr>
                                
                                
                                <tr>
                                <td align="left" bgcolor="#FFFFFF">Desarrollo y Capacitaci&oacute;n del empleado</td>
                                <td align="center" bgcolor="#FFFFFF">
                                <span style=" font-size:16px; font-weight:bold"><?=number_format(calcula_promedio_para_area("num='6' or num='11'"),2)?></span>
                                </td>
                                <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
                                </tr>
                                
                                
                                <tr>
                                <td align="left" bgcolor="#FFFFFF">Organizaci&oacute;n</td>
                                <td align="center" bgcolor="#FFFFFF">
                                <span style=" font-size:16px; font-weight:bold"><?=number_format(calcula_promedio_para_area("num='3' or num='4' or num='7' or num='8'"),2)?></span>
                                
                                </td>
                                <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
                                </tr>
                                
                                
                                <tr>
                                <td align="left" bgcolor="#FFFFFF">Administraci&oacute;n de la condici&oacute;n de los equipos</td>
                                <td align="center" bgcolor="#FFFFFF">
                                <span style=" font-size:16px; font-weight:bold"><?=number_format(calcula_promedio_para_area("num='1' or num='2' or num='9' or num='14' or num = '24' or num='26' or num='28'"),2)?></span>
                                
                                </td>
                                <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
                                </tr>
                                
                                <tr>
                                <td align="left" bgcolor="#FFFFFF">Administraci&oacute;n de Trabajos</td>
                                <td align="center" bgcolor="#FFFFFF">
                                <span style=" font-size:16px; font-weight:bold"><?=number_format(calcula_promedio_para_area("num='32' or num='38' or num='49' or num='50' or num = '41' or num='52' or num='53' or num='54' or num='55' or num='56' or num='58' or num='59' or num='60' or num='61' or num='62' or num='63' or num='64'"),2)?></span>
                                </td>
                                <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
                                </tr>
                                
                                
                                <tr>
                                <td align="left" bgcolor="#FFFFFF">Administraci&oacute;n de paradas</td>
                                <td align="center" bgcolor="#FFFFFF">
                                <span style=" font-size:16px; font-weight:bold"><?=number_format(calcula_promedio_para_area("num='88' or num='89' or num='90' or num='91' or num = '92'"),2)?></span>
                                </td>
                                <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
                                </tr>
                                
                                
                                <tr>
                                <td align="left" bgcolor="#FFFFFF">Administraci&oacute;n de contratos</td>
                                <td align="center" bgcolor="#FFFFFF">
                                <span style=" font-size:16px; font-weight:bold"><?=number_format(calcula_promedio_para_area("num='77' or num='78' or num='79' or num='80'"),2)?></span>
                                </td>
                                <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
                                </tr>
                                
                                
                                <tr>
                                <td align="left" bgcolor="#FFFFFF">Administraci&oacute;n de materiales</td>
                                <td align="center" bgcolor="#FFFFFF">
                                <span style=" font-size:16px; font-weight:bold"><?=number_format(calcula_promedio_para_area("num='65' or num='66' or num='67' or num='68' or num = '69' or num='70' or num='71' or num='72' or num='73' or num='74' or num='75' or num='76'") ,2)?></span>
                                </td>
                                <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
                                </tr>
                                
                                
                                <tr>
                                <td align="left" bgcolor="#FFFFFF">Presupuestos y control de costos</td>
                                <td align="center" bgcolor="#FFFFFF">
                                <span style=" font-size:16px; font-weight:bold"><?=number_format(calcula_promedio_para_area("num='93' or num='94' or num='95' or num='96' or num = '97'"),2)?></span>
                                </td>
                                <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
                                </tr>
                                
                                
                                <tr>
                                <td align="left" bgcolor="#FFFFFF">Medici&oacute;n de rendimiento</td>
                                <td align="center" bgcolor="#FFFFFF">
                                <span style=" font-size:16px; font-weight:bold"><?=number_format(calcula_promedio_para_area("num='5' or num='12' or num='13'"),2)?></span>
                                </td>
                                <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
                                </tr>
                                <tr>
                                <td align="left" bgcolor="#FFFFFF">Soluci&oacute;n de problemas y mejoramiento de negocios</td>
                                <td align="center" bgcolor="#FFFFFF">
                                <span style=" font-size:16px; font-weight:bold"><?=number_format(calcula_promedio_para_area("num='9' or num='15' or num='25' or num='29' or num = '31' or num='39' or num='44'  or num='45' or num='46' or num='47' or num='48'"),2)?></span>
                                </td>
                                <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
                                </tr>
                                <tr>
                                <td align="left" bgcolor="#FFFFFF">Administraci&oacute;n de sistemas de informaci&oacute;n de mantenimiento</td>
                                <td align="center" bgcolor="#FFFFFF">
                                <span style=" font-size:16px; font-weight:bold"><?=number_format(calcula_promedio_para_area("num='81' or num='82' or num='84' or num='85' or num = '86'"),2)?></span>
                                </td>
                                <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
                                </tr>
                                <tr>
                                <td align="left" bgcolor="#FFFFFF">Instalaciones, equipos y herramientas</td>
                                <td align="center" bgcolor="#FFFFFF">
                                <span style=" font-size:16px; font-weight:bold"><?=number_format(calcula_promedio_para_area("num='42' or num='57'"),2)?></span>
                                </td>
                                <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
                                </tr>
                                <tr>
                                <td align="left" bgcolor="#FFFFFF">Administraci&oacute;n de planos y documentos</td>
                                <td align="center" bgcolor="#FFFFFF">
                                <span style=" font-size:16px; font-weight:bold"><?=number_format(calcula_promedio_para_area("num='27' or num='83'"),2)?></span>
                                </td>
                                <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
                                </tr>
                                
                                <tr>
                                <td align="left" bgcolor="#FFFFFF">Modificaci&oacute;n y Control de los Equipos</td>
                                <td align="center" bgcolor="#FFFFFF">
                                <span style=" font-size:16px; font-weight:bold"><?=number_format(calcula_promedio_para_area("num='36' or num='87'"),2)?></span>
                                </td>
                                <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
                                </tr>
                                
                                </table>
                                <?
                                $promedio_elementos=array(number_format(calcula_promedio_para_area("num='16' or num='17' or num='18' or num='19' or num = '20' or num='21' or num='22' or num='23'"),2),number_format(calcula_promedio_para_area("num='1' or num='2' or num='9' or num='14' or num = '24' or num='26' or num='28'"),2),number_format(calcula_promedio_para_area("num='6' or num='11'"),2),
                                                          number_format(calcula_promedio_para_area("num='3' or num='4' or num='7' or num='8'"),2),
                                                          number_format(calcula_promedio_para_area("num='1' or num='2' or num='9' or num='14' or num = '24' or num='26' or num='28'"),2),
                                                          number_format(calcula_promedio_para_area("num='32' or num='38' or num='49' or num='50' or num = '41' or num='52' or num='53' or num='54' or num='55' or num='56' or num='58' or num='59' or num='60' or num='61' or num='62' or num='63' or num='64'"),2),
                                                          number_format(calcula_promedio_para_area("num='88' or num='89' or num='90' or num='91' or num = '92'"),2),
                                                          number_format(calcula_promedio_para_area("num='77' or num='78' or num='79' or num='80'"),2),
                                                          number_format(calcula_promedio_para_area("num='65' or num='66' or num='67' or num='68' or num = '69' or num='70' or num='71' or num='72' or num='73' or num='74' or num='75' or num='76'") ,2),
                                                          number_format(calcula_promedio_para_area("num='93' or num='94' or num='95' or num='96' or num = '97'"),2),
                                                          number_format(calcula_promedio_para_area("num='5' or num='12' or num='13'"),2),
                                                          number_format(calcula_promedio_para_area("num='9' or num='15' or num='25' or num='29' or num = '31' or num='39' or num='44'  or num='45' or num='46' or num='47' or num='48'"),2),
                                                          number_format(calcula_promedio_para_area("num='81' or num='82' or num='84' or num='85' or num = '86'"),2),
                                                          number_format(calcula_promedio_para_area("num='42' or num='57'"),2),
                                                          number_format(calcula_promedio_para_area("num='27' or num='83'"),2),
                                                          number_format(calcula_promedio_para_area("num='36' or num='87'"),2));
                                
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
                                $MyData->addPoints(array("1","2","3","4","5","6","7","8","9","10","11","12","13","14","15","16"),"Labels");
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
                                $myPicture->drawText(420,13,"16 areas clave",array("R"=>255,"G"=>255,"B"=>255));
                                
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
                                
                                $TextSettings = array("R"=>0,"G"=>0,"B"=>0,"Angle"=>0,"FontSize"=>14);
                                $myPicture->drawText(550,75,"1.- Administración de SSMC ",$TextSettings);
                                $myPicture->drawText(550,100,"2.- Alineación con la visión y el negocio",$TextSettings);
                                $myPicture->drawText(550,125,"3.- Desarrollo y Capacitación del empleado",$TextSettings);
                                $myPicture->drawText(550,150,"4.- Organización",$TextSettings);
                                $myPicture->drawText(550,175,"5.- Administración de la condición de los equipos",$TextSettings);
                                $myPicture->drawText(550,200,"6.- Administración de Trabajos",$TextSettings);
                                $myPicture->drawText(550,225,"7.- Administración de paradas",$TextSettings);
                                $myPicture->drawText(550,250,"8.- Administración de contratos",$TextSettings);
                                $myPicture->drawText(550,275,"9.- Administración de materiales",$TextSettings);
                                $myPicture->drawText(550,300,"10.- Presupuestos y control de costos",$TextSettings);
                                $myPicture->drawText(550,325,"11.- Medición de rendimiento",$TextSettings);
                                $myPicture->drawText(550,350,"12.- Solución de problemas y mejoramiento de negocios",$TextSettings);
                                $myPicture->drawText(550,375,"13.- Sistemas de información de mantenimiento ",$TextSettings);
                                $myPicture->drawText(550,400,"14.- Instalaciones, equipos y herramientas",$TextSettings);
                                $myPicture->drawText(550,425,"15.- Administración de planos y documentos",$TextSettings);
                                $myPicture->drawText(550,450,"16.- Modificación y Control de los Equipos",$TextSettings);
                                
                                
                                
                                
                                /* Render the picture (choose the best way) */
                                $myPicture->Render("radar_elementos_16_areas.png");                                       ?>
                                
                                
                                <table width="90%" border="0" align="center" bgcolor="#F3F4F6">
                                <td align="center" bgcolor="#FFFFFF"><img src="radar_elementos_16_areas.png" /></td>
                                </table>
                                
                                <?
                                break;

}

?>
    
 <br />
Sistema desarrollado por <a href="http://benzahosting.cl">BenzaHosting </a>
</body>
</html>
