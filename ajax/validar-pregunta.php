<?
include "../php/MySQL.class.php";
$mysql = new mysql("localhost", "sistemae_db", "sistemae_usuario", "usuario");

$accion = $_GET['accion'];
$pregunta = $_GET['pregunta'];
$cliente = $_GET['cliente'];

if(isset($accion)){
	$verificador = $mysql->Select("select estado 
								  from preguntas_no_aplicables 
								  where id_cliente='$cliente' 
								  and id_pregunta='$pregunta'
								  ");	
	
	if($verificador[0][0]==""){
		if($accion=="acepta" )
			$mysql->Insert("insert into preguntas_no_aplicables 
					        values ('$pregunta','$cliente','1','')
					       ");
		if($accion=="rechaza" )
			$mysql->Insert("insert into preguntas_no_aplicables 
					        values ('$pregunta','$cliente','0','')
					       ");
	} else {
		if($accion=="acepta" ){
			$mysql->Insert("update preguntas_no_aplicables 
						    set estado='1' 
						    where id_cliente='$cliente' 
						    and id_pregunta='$pregunta' ");
		}
		if($accion=="rechaza"){
			$mysql->Insert("update preguntas_no_aplicables 
						    set estado='0' 
							where id_cliente='$cliente' 
							and id_pregunta='$pregunta'
							");
		}
	} 
}


?>