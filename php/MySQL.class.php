<?php
/*###########################################################################
# Clase: Utiles
# Escrito por: Juan Carlos Gonzalez Ulloa
# Contacto: jcgonzalez@benzahosting.cl | www.benzahosting.cl
# Version 1.0
############################################################################# */

class MySQL
{
	private $Servidor;
	private $BaseDatos;
	private $Usuario;
	private $Contrasena;
	private $Coneccion;	
	protected $TotalRegistro = 0;
	protected $NuevoIDInsertado = '';
	protected $Error= '';
	
	function MySQL($_Servidor, $_BaseDatos, $_Usuario, $_Contrasena) {
		$this->Servidor = $_Servidor;
		$this->BaseDatos = $_BaseDatos;
		$this->Usuario = $_Usuario;
		$this->Contrasena = $_Contrasena;
		$this->TotalRegistro = 0;
		$this->NuevoIDInsertado = '';
		$this->Error = '';
   	}	
	function UltimoID(){
			return $this->NuevoIDInsertado;
	}
	function MostrarError(){
		return $this->Error;	
	}
	function Conectar() {
		$this->Coneccion = mysql_connect($this->Servidor,$this->Usuario,$this->Contrasena);
		mysql_select_db($this->BaseDatos);
	}	
	function Desconectar() {
		mysql_close($this->Coneccion);
	}	
	function Select($SQL) {
		
		$this->TotalRegistro = 0;
		$this->NuevoIDInsertado = '';
		$this->Error = '';
		
		$this->Conectar();
		
		$Resultado = mysql_query($SQL,$this->Coneccion);
		$this->TotalRegistro = mysql_num_rows($Resultado);		
		$this->Desconectar();
	
		if($Resultado){
			$ContadorI = 0;
			while($row = mysql_fetch_row($Resultado)){
				$ContadorJ = 0;
				foreach ($row as $Valor){
					$ArrayRetorno[$ContadorI][$ContadorJ] = $Valor;
					$ContadorJ++;
				}
				$ContadorI++;
			}
			$this->ArrayResultado = $ArrayRetorno;
		}
		else {
			$this->Error = mysql_error();	
		}
	
		return $ArrayRetorno;
		
	}
	function Insert($SQL){

            $this->TotalRegistro = 0;
	    $this->NuevoIDInsertado = '';
            $this->Error = '';
	    $this->Conectar();

            if(mysql_query($SQL,$this->Coneccion)){
                $this->NuevoIDInsertado = mysql_insert_id();
                $this->TotalRegistro = mysql_affected_rows();
                $this->Desconectar();
                return true;
            }
            else{
                $this->Error = mysql_error();
                $this->Desconectar();
                return false;               
            }
        }
	function Update($SQL){

            $this->TotalRegistro = 0;
	    $this->NuevoIDInsertado = '';
            $this->Error = '';
	    $this->Conectar();

            if(mysql_query($SQL,$this->Coneccion)){
                $this->NuevoIDInsertado = mysql_insert_id();
                $this->TotalRegistro = mysql_affected_rows();
                $this->Desconectar();
                return true;
            }
            else{
                $this->Error = mysql_error();
                $this->Desconectar();
                return false;
            }
        }
        function Delete($SQL){

            $this->TotalRegistro = 0;
	    $this->NuevoIDInsertado = '';
            $this->Error = '';
	    $this->Conectar();

            if(mysql_query($SQL,$this->Coneccion)){
                $this->NuevoIDInsertado = mysql_insert_id();
                $this->TotalRegistro = mysql_affected_rows();
                $this->Desconectar();
                return true;
            }
            else{
                $this->Error = mysql_error();
                $this->Desconectar();
                return false;
            }
        }
}

?>
