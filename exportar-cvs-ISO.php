<?php
include "php/MySQL.class.php";
$mysql = new mysql("localhost", "labsshe_db", "labsshe_db", "123456");

$id_empresa = $_GET['id'];
$empresa = $mysql->Select("select * from clientes where ID_clientes='$id_empresa'");

header("Content-type: application/vnd.ms-excel"); 
header("Content-Disposition: attachment; filename=areas_".$empresa[0][2].".xls"); 

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
                                    


<table width="100%" border="0" align="center" cellpadding="2" cellspacing="2" bgcolor="#F3F4F6">
  <tr>
    <td><strong>Informacion Cliente</strong></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Rut:
      <?=$empresa[0][2]?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Razon Social :
      <?=$empresa[0][3]?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Giro:
      <?=$empresa[0][4]?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <? $id_empresa = $_GET['id']; ?>
    <td colspan="3" align="right">&nbsp;</td>
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