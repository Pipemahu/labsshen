<?
session_start();

if(!isset($_SESSION['admin'])){
	header("location:login.php");	
}

/* 

sistema desarrollado por: 

			|----------------------------------|
			|    Juan Carlos Gonzalez Ulloa    |
			|	jcgonzalez@benzahosting.cl     |
			|		 +56 9 89723240            |
			|	   www.benzahosting.cl         |
		    |----------------------------------|
*/
include "php/ManejoArchivos.class.php";
include "php/Utiles.class.php";
include "php/MySQL.class.php";
include "php/SistemaEvaluador.class.php";

/* instanciando las clases */
$mysql = new mysql("localhost", "labsshe_db", "labsshe_db", "123456");
$util = new utiles();
$sistema_evaluador = new SistemaEvaluador();




?>