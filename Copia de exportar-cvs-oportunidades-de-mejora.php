<?php
include "php/MySQL.class.php";
$mysql = new mysql("localhost", "sistemae_db", "sistemae_usuario", "usuario");

$id_empresa = $_GET['id'];
$empresa = $mysql->Select("select * from clientes where ID_clientes='$id_empresa'");

header("Content-type: application/vnd.ms-excel"); 
header("Content-Disposition: attachment; filename=areas_".$empresa[0][2].".xls"); 
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
    <td colspan="3"><strong>Listado  de Areas de Intervencion</strong></td>
  </tr>
  <tr>
    <td>Area</td>
    <td width="100" align="center">Promedio</td>
    <td width="10" align="center">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" bgcolor="#FFFFFF">Administraci&oacute;n de SSMC (Salud Seguridad Medio Ambiente y Comunidad)</td>
    <td align="center" bgcolor="#FFFFFF"><? calcula_promedio_para_area("num='16' or num='17' or num='18' or num='19' or num = '20' or num='21' or num='22' or num='23'") ?></td>
    <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" bgcolor="#FFFFFF">Alineaci&oacute;n con la visi&oacute;n y el negocio</td>
    <td align="center" bgcolor="#FFFFFF"><? calcula_promedio_para_area("num='1' or num='2' or num='9' or num='14' or num = '24' or num='26' or num='28'") ?></td>
    <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" bgcolor="#FFFFFF">Desarrollo y Capacitaci&oacute;n del empleado</td>
    <td align="center" bgcolor="#FFFFFF"><? calcula_promedio_para_area("num='6' or num='11'") ?></td>
    <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" bgcolor="#FFFFFF">Organizaci&oacute;n</td>
    <td align="center" bgcolor="#FFFFFF"><? calcula_promedio_para_area("num='3' or num='4' or num='7' or num='8'") ?></td>
    <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" bgcolor="#FFFFFF">Administraci&oacute;n de la condici&oacute;n de los equipos</td>
    <td align="center" bgcolor="#FFFFFF"><? calcula_promedio_para_area("num='1' or num='2' or num='9' or num='14' or num = '24' or num='26' or num='28'") ?></td>
    <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" bgcolor="#FFFFFF">Administraci&oacute;n de Trabajos</td>
    <td align="center" bgcolor="#FFFFFF"><? calcula_promedio_para_area("num='32' or num='38' or num='49' or num='50' or num = '41' or num='52' or num='53' or num='54' or num='55' or num='56' or num='58' or num='59' or num='60' or num='61' or num='62' or num='63' or num='64'") ?></td>
    <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" bgcolor="#FFFFFF">Administraci&oacute;n de paradas:</td>
    <td align="center" bgcolor="#FFFFFF"><? calcula_promedio_para_area("num='88' or num='89' or num='90' or num='91' or num = '92'") ?></td>
    <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" bgcolor="#FFFFFF">Administraci&oacute;n de contratos</td>
    <td align="center" bgcolor="#FFFFFF"><? calcula_promedio_para_area("num='77' or num='78' or num='79' or num='80'") ?></td>
    <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" bgcolor="#FFFFFF">Administraci&oacute;n de materiales</td>
    <td align="center" bgcolor="#FFFFFF"><? calcula_promedio_para_area("num='65' or num='66' or num='67' or num='68' or num = '69' or num='70' or num='71' or num='72' or num='73' or num='74' or num='75' or num='76'") ?></td>
    <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" bgcolor="#FFFFFF">Presupuestos y control de costos</td>
    <td align="center" bgcolor="#FFFFFF"><? calcula_promedio_para_area("num='93' or num='94' or num='95' or num='96' or num = '97'") ?></td>
    <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" bgcolor="#FFFFFF">Medici&oacute;n de rendimiento:</td>
    <td align="center" bgcolor="#FFFFFF"><? calcula_promedio_para_area("num='5' or num='12' or num='13'") ?></td>
    <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" bgcolor="#FFFFFF">Soluci&oacute;n de problemas y mejoramiento de negocios</td>
    <td align="center" bgcolor="#FFFFFF"><? calcula_promedio_para_area("num='9' or num='15' or num='25' or num='29' or num = '31' or num='39' or num='44'  or num='45' or num='46' or num='47' or num='48'") ?></td>
    <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" bgcolor="#FFFFFF">Administraci&oacute;n de sistemas de informaci&oacute;n de mantenimiento</td>
    <td align="center" bgcolor="#FFFFFF"><? calcula_promedio_para_area("num='81' or num='82' or num='84' or num='85' or num = '86'") ?></td>
    <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" bgcolor="#FFFFFF">Instalaciones, equipos y herramientas</td>
    <td align="center" bgcolor="#FFFFFF"><? calcula_promedio_para_area("num='42' or num='57'") ?></td>
    <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" bgcolor="#FFFFFF">Administraci&oacute;n de planos y documentos</td>
    <td align="center" bgcolor="#FFFFFF"><? calcula_promedio_para_area("num='27' or num='83'") ?></td>
    <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" bgcolor="#FFFFFF">Modificaci&oacute;n y Control de los Equipos</td>
    <td align="center" bgcolor="#FFFFFF"><? calcula_promedio_para_area("num='36' or num='87'") ?></td>
    <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
</table>
