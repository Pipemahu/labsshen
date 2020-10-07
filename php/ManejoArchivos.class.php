<?php
/*
 * Juan Carlos gonzalez Ulloa
 * benzahosting.cl
 * jcgonzalez@benzahosting.cl
 *
 * SubirArchivo('imagen',"upload","Archivosubirdo.txt")
 */
class ManejoArchivos {
    
    function SubirArchivo($NombreCampoArchivo,$CarpetaDestino,$NombreArchivo){
        
        if($_FILES[$NombreCampoArchivo]['name']!=''){

            if(!is_dir($CarpetaDestino)){
                mkdir($CarpetaDestino, 0777);
            }

            $RutaTotal = $CarpetaDestino . '/' . $NombreArchivo;

            if(move_uploaded_file($_FILES[$NombreCampoArchivo]['tmp_name'], $RutaTotal))
                return true;
            else
		return false;

	} else {
            return false;
        }
    }
    
}
?>
