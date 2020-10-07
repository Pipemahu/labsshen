<?

function ArbolDependencia($IDDependencia,$Control,$Periodo,$Pagina){
	
	if($Control==0){
		/* obtenemos el ID del nodo padre */
		$IDNodoPadre = $IDDependencia;
		$NombreNodoPadre = MySQL_ObtenerDato("select Nombre from item where IDItem='$IDNodoPadre'");
		/* obtenemos el nombre del nodo padre */
		echo "<ul id='browser' class='filetree'>";
		echo 	"<li> <span class='folder'> <a href='$Pagina?op=MostrarDependencia&IDDependencia=$IDNodoPadre&Periodo=$Periodo'>$NombreNodoPadre</a> </span> "; ArbolDependencia($IDNodoPadre,1,$Periodo,$Pagina); echo "</li>";
		echo "</ul>";
	} else {
		
		/* buscamos todos los Item del IDDependencia */
		$Item = MySQL_Select("select * from item where IDDependencia='$IDDependencia'");
		/* determinamos cuantos item tiene pa ver si agregamos mas niveles o nop */
		$NumeroElementos = count($Item);
		if($NumeroElementos != 0){
			echo "<ul>";
			foreach ($Item as $Valor){
				echo "<li> <span class='folder'> <a href='$Pagina?op=MostrarDependencia&IDDependencia=$Valor[0]&Periodo=$Periodo'>".$Valor[3]."</a> </span> "; ArbolDependencia($Valor[0],1,$Periodo,$Pagina); echo "</li>";
			}
			echo "</ul>";
		}
	}	
}

function AgregarItem($IDNivel, $IDDependencia , $Nombre){
	
	if(MySQL_Insert("insert into item values ( '0' , '$IDDependencia' , '$IDNivel' , '$Nombre' ,'0')")){
		return true;	
	} else {
		echo mysql_error();
		return false;	
	}
	
}

function AgregarTarea($IDItem,$FechaInicio,$FechaFin,$TipoTarea,$IDItemGasto,$IDComision,$Denominacion,$IDPrestador,$Observaciones,$MECE, $IES,$NroCuotas,$NombreCampoImagen,$NroDecreto,$PjteImportancia,$MontoProgramadoMECE,$MontoProgramadoIES,$Responsable,$Periodo){
	
	/* guardar el archivo adjunto en la base de datos respectiva */
	/* si no hay imagen solo guardar en la db */
		/* si guardo retornar true */
		/* si no guardo retornar false */
	/* si hay imagen */
		/* subirla al servidor */
		/* guardarla en la base de datos */
		/* si guardo el archivo y guardo en la db retirnar true */
		/* cualquiera de los otroas alternativas retornar false */	
	
	if($_FILES[''.$NombreCampoImagen]['name']==""){
		
		if(MySQL_Insert("INSERT INTO tareas values ('0', '$IDItem', '$FechaInicio', '$FechaFin', '$TipoTarea', '$IDItemGasto', '$IDComision', '$Denominacion', '$IDPrestador', '$Observaciones', '$MECE', '$IES', '$NroCuotas','$NroDecreto','$PjteImportancia','$MontoProgramadoMECE','$MontoProgramadoIES','$Responsable','$Periodo');"))
			return mysql_insert_id();
		else
			return false;
	} else {
		
		
		
		return false;	
	}
		

}

function AgregarPrestador($RutPrestador,$NombrePrestador,$DireccionPrestador,$TelefonoPrestador,$EmailPrestador){
	if(MySQL_Insert("insert into prestadores values ( '0' , '$RutPrestador' , '$NombrePrestador' , '$DireccionPrestador' ,'$TelefonoPrestador' ,'$EmailPrestador')")){
		echo "confirmado";
		return mysql_insert_id();
	} else {
		echo mysql_error();
		return false;	
	}
	
		
}

function EditarTarea($IDTarea, $FechaInicio,$FechaFin,$TipoTarea,$IDItemGasto,$IDComision,$Denominacion,$IDPrestador,$Observaciones,$MECE, $IES,$NroDecreto,$PtjeImportancia,$MontoProgramadoMECE,$MontoProgramadoIES,$Responsable){
	if(MySQL_Insert("update tareas set FechaInicio = '$FechaInicio', FechaFin= '$FechaFin', TipoTarea='$TipoTarea', IDItemGasto='$IDItemGasto', IDComision='$IDComision', Denominacion='$Denominacion', IDPrestador='$IDPrestador', Observaciones='$Observaciones', MECE='$MECE', IES='$IES', NroDecreto='$NroDecreto', PtjeImportancia = '$PtjeImportancia', ProgramadoMECE = '$MontoProgramadoMECE', ProgramadoIES = '$MontoProgramadoIES', IDResponsable = '$Responsable' where IDTarea = '$IDTarea';"))
			return true;
		else
			{
				echo mysql_error();
				return false;
			}
	
	
}

function AgregarCuota($IDTarea,$Monto){
	if(MySQL_Insert("insert into tareas_cuotas values ('0' , '$IDTarea' , '$Monto' , '' , '','0')"))
			return true;
		else
			return false;	
}

function PorcentajeCorrectoItem($IDItem){
	
	$Importancia = MySQL_ObtenerDato("select SUM(Importancia) from item where IDDependencia = '$IDItem'");
	if($Importancia != 100)
		return false;
	else
		return true;	
}

function PorcentajeAvanceTarea($IDTarea){
	/* el porcentaje de ab¿vance de la tarea se mide segun sus cuotas cumplidas para el caso de las valorizadas en el caso de las no valorizadas se extrae directamente desde la base de datos */
	$MontoMECE = MySQL_ObtenerDato("select MECE from tareas where IDTarea='$IDTarea'");
	$MontoIES = MySQL_ObtenerDato("select IES from tareas where IDTarea='$IDTarea'");
	$Monto = $MontoMECE + $MontoIES;
	
	$SumaCuotasPagadas = MySQL_ObtenerDato("select SUM(Monto) from tareas_cuotas where IDTarea='$IDTarea' and Estado = '1'");
	
	if($Monto!=0){
		$PorcentajeAvance = (100*$SumaCuotasPagadas)/$Monto;
	} else {
		$PorcentajeAvance = 0;	
	}
	return round($PorcentajeAvance);
}

function PorcentajeCorrectoTareas($IDItem){
	
	$SumaPtje = MySQL_ObtenerDato("select SUM(PtjeImportancia) from tareas where IDItem='$IDItem'");
	//echo $SumaPtje;
	if($SumaPtje != 100)
		return false;
	else
		return true;
	
}

function EditarCuotaTarea($IDCuota,$Monto,$NroBoF,$Estado,$Fecha){
	if(MySQL_Insert("update tareas_cuotas set Monto = '$Monto', NroBoletaOFactura = '$NroBoF', Estado = '$Estado', FechaVencimiento='$Fecha' where IDCuota = '$IDCuota';"))
			return true;
		else
			return false;
}

function SituacionBanderaTarea($IDTarea){
	
	$PorcentajeAvance = PorcentajeAvanceTarea($IDTarea);
	$FechaTermino = MySQL_ObtenerDato("select FechaFin from tareas where IDTarea='$IDTarea'");
	$FechaTermino = strtotime($FechaTermino, 0);

	$FechaHoy = date("Y-m-j");
	$FechaHoy = strtotime($FechaHoy,0);
	
	
	if($PorcentajeAvance==100){
		return 1;	
	} else {
		if($PorcentajeAvance<100 && $FechaTermino>$FechaHoy){
			return 2;	
		} else {
			if($PorcentajeAvance<100 && $FechaTermino<=$FechaHoy){
				return 3;
			}	
		}
	}
	
	
}

function AgregarResponsable($ResponsableNombre,$ResponsableEmail){
	
	if(MySQL_Insert("insert into responsables values ( '0' , '$ResponsableNombre' , '$ResponsableEmail')")){
		return mysql_insert_id();
	} else {
		echo mysql_error();
		return false;	
	}
}

function ListaTareas($IDItem,$Control,$url,$Criterio,$Periodo,$ItemGasto,$P){
	
	if($Control==0){
		if($ItemGasto=="0"){
			if($Periodo=="Todo")
				$Tareas = MySQL_Select("select * from tareas where IDItem='".$IDItem."' and Denominacion like  '%$Criterio%'");
			else 
				$Tareas = MySQL_Select("select * from tareas where IDItem='".$IDItem."' and Denominacion like  '%$Criterio%' and Periodo='$Periodo'");
		}
		else{
			
			if($Periodo=="Todo")
				$Tareas = MySQL_Select("select * from tareas where IDItem='".$IDItem."' and Denominacion like  '%$Criterio%' and IDItemGasto='$ItemGasto'");
			else 
				$Tareas = MySQL_Select("select * from tareas where IDItem='".$IDItem."' and Denominacion like  '%$Criterio%' and Periodo='$Periodo' and IDItemGasto='$ItemGasto'");
			
		}
		$NroElem = count($Tareas);
			
		if($NroElem != 0){				
				?>
                 
                <?
				
			foreach($Tareas as $ValorT){
				               
			$Util = new Utiles();
			?>
             
             <tr>
               <td width="15"><?
               $SB = SituacionBanderaTarea($ValorT[0]);
			   if( $SB == 1){
				?>
                 <img src="../images/archivo_verde.gif" width="16" height="16" />
                <?   
			   }
			   if( $SB == 2){
				?>
                 <img src="../images/archivo_amarillo.gif" width="16" height="16" />
                <?   
			   }
			    if( $SB == 3){
				?>
                 <img src="../images/archivo_rojo.gif" width="16" height="16" />
                <?   
			   }
			   
			   
			   ?></td>
               <td width="25" class="Mostrar-Ocultar"><?=PorcentajeAvanceTarea($ValorT[0])." %"?></td>
               <td width=""><a href='#' onclick="Boxy.DEFAULTS.title = 'Detalle Tarea'; Boxy.load('DetalleTarea.php?ID=<?=$ValorT[0]?>');"><?=$ValorT[7]?></a></td>
               <td width="65" align="center" valign="middle"><?=$Util->FormatoMoneda($ValorT[15], "pesos")?></td>
               <td width="65" align="center" valign="middle"><?=$Util->FormatoMoneda($ValorT[16], "pesos")?></td>
               <td width="65" align="center" valign="middle"><strong><?=$Util->FormatoMoneda($ValorT[16] + $ValorT[15], "pesos")?></strong></td>
               <td width="65" align="center" valign="middle"><?=$Util->FormatoMoneda($ValorT[10], "pesos")?></td>
               <td width="65" align="center" valign="middle"><?=$Util->FormatoMoneda($ValorT[11], "pesos")?></td>
               <td width="65" align="center" valign="middle"><strong><?=$Util->FormatoMoneda($ValorT[10] + $ValorT[11], "pesos")?></strong></td>
               <?
			   
               $P[0] = $P[0] + $ValorT[15];
               $P[1] = $P[1] + $ValorT[16];
               $P[2] = $P[2] + $ValorT[10];
               $P[3] = $P[3] + $ValorT[11];
               
			   ?>
			   <td width="35" align="right" valign="middle">
               
               <? if($_SESSION['Tipo']=="1"){ ?>
               <form action="" name="FormularioBorrarTarea<?=$ValorT[0]?>" method="post">
                <input name="BorrarTarea" type="hidden" value="<?=$ValorT[0]?>" />
                </form>
               <a href="#" onclick="Boxy.DEFAULTS.title = 'Editar Tarea'; Boxy.load('EditarTarea.php?ID=<?=$ValorT[0]?>&URL=<?=$url?>');"><img src="../images/edit.gif" width="16" height="16" border="0" /></a> <a href="#" onclick="  Boxy.confirm('¿Desea borrar esta tarea?', function() { document.FormularioBorrarTarea<?=$ValorT[0]?>.submit(); });"> <img src="../images/delete.gif" width="16" height="16" border="0" /></a>
              <? } ?>
               </td>
             </tr>
            <? } ?>
         
                <?
			}
		
		
		$Control = 1;
	} if($Control == 1) {
		$Item = MySQL_Select("select * from item where IDDependencia='$IDItem'");
		$NroElemItem = count($Item);
	
		if($NroElemItem != 0){
		
			foreach ($Item as $Valor){
			
				if($ItemGasto=="0"){
					if($Periodo=="Todo")
						$Tareas = MySQL_Select("select * from tareas where IDItem='".$Valor[0]."' and Denominacion like  '%$Criterio%'");
					else 
						$Tareas = MySQL_Select("select * from tareas where IDItem='".$Valor[0]."' and Denominacion like  '%$Criterio%' and Periodo='$Periodo'");
				}
				else{
			
					if($Periodo=="Todo")
						$Tareas = MySQL_Select("select * from tareas where IDItem='".$Valor[0]."' and Denominacion like  '%$Criterio%' and IDItemGasto='$ItemGasto'");
					else 
						$Tareas = MySQL_Select("select * from tareas where IDItem='".$Valor[0]."' and Denominacion like  '%$Criterio%' and Periodo='$Periodo' and IDItemGasto='$ItemGasto'");
			
		}
				
						
				
				$NroElem = count($Tareas);
			
				if($NroElem != 0){				
				
					foreach($Tareas as $ValorT){
						$Util = new Utiles();
			?>
             
             <tr>
               <td width="15"><?
               $SB = SituacionBanderaTarea($ValorT[0]);
			   if( $SB == 1){
				?>
                 <img src="../images/archivo_verde.gif" width="16" height="16" />
                <?   
			   }
			   if( $SB == 2){
				?>
                 <img src="../images/archivo_amarillo.gif" width="16" height="16" />
                <?   
			   }
			    if( $SB == 3){
				?>
                 <img src="../images/archivo_rojo.gif" width="16" height="16" />
                <?   
			   }
			   
			   
			   ?></td>
               <td width="25" class="Mostrar-Ocultar"><?=PorcentajeAvanceTarea($ValorT[0])." %"?></td>
               <td width=""><a href='#' onclick="Boxy.DEFAULTS.title = 'Detalle Tarea'; Boxy.load('DetalleTarea.php?ID=<?=$ValorT[0]?>');"><?=$ValorT[7]?></a></td>
               <td width="65" align="center" valign="middle"><?=$Util->FormatoMoneda($ValorT[15], "pesos")?></td>
               <td width="65" align="center" valign="middle"><?=$Util->FormatoMoneda($ValorT[16], "pesos")?></td>
               <td width="65" align="center" valign="middle"><strong><?=$Util->FormatoMoneda($ValorT[16] + $ValorT[15], "pesos")?></strong></td>
               <td width="65" align="center" valign="middle"><?=$Util->FormatoMoneda($ValorT[10], "pesos")?></td>
               <td width="65" align="center" valign="middle"><?=$Util->FormatoMoneda($ValorT[11], "pesos")?></td>
               <td width="65" align="center" valign="middle"><strong><?=$Util->FormatoMoneda($ValorT[10] + $ValorT[11], "pesos")?></strong></td>
                <?
			   
               $P[0] = $P[0] + $ValorT[15];
               $P[1] = $P[1] + $ValorT[16];
               $P[2] = $P[2] + $ValorT[10];
               $P[3] = $P[3] + $ValorT[11];
               
			   ?>
               <td width="35" align="right" valign="middle">
               <? if($_SESSION['Tipo']=="1"){ ?>
               <form action="" name="FormularioBorrarTarea<?=$ValorT[0]?>" method="post">
                <input name="BorrarTarea" type="hidden" value="<?=$ValorT[0]?>" />
                </form>
               <a href="#" onclick="Boxy.DEFAULTS.title = 'Editar Tarea'; Boxy.load('EditarTarea.php?ID=<?=$ValorT[0]?>&URL=<?=$url?>');"><img src="../images/edit.gif" width="16" height="16" border="0" /></a> <a href="#" onclick="  Boxy.confirm('¿Desea borrar esta tarea?', function() { document.FormularioBorrarTarea<?=$ValorT[0]?>.submit(); });"> <img src="../images/delete.gif" width="16" height="16" border="0" /></a>
               <? } ?>
               </td>
             </tr>
					<? }
				}
			
				ListaTareas($Valor[0],1,$url,$Criterio,$Periodo,$ItemGasto,$P);
			}
		}
	}
	
	return $P;
}


/* funcion que se encragra de agregar una nueva actividad al sistema y ademas retorna un ID quew puede
   ser usado para cualquier elemento relacionado a una actividad
*/

function AgregarActividad($IDItem, $NombreReunion, $Comision, $Fecha, $Lugar, $Horario, $Participantes, $TemasTratadosPu, $TemasTratadosPr , $AcuerdosPu, $AcuerdosPr, $TemasProximaReunionPu, $TemasProximaReunionPr, $Documentos, $Observaciones){
	
	if(MySQL_Insert("insert into actividades values ( '0' , '$IDItem', '$NombreReunion', '$Comision', '$Fecha', '$Lugar', '$Horario', '$Participantes', '$TemasTratadosPu', '$TemasTratadosPr' , '$AcuerdosPu', '$AcuerdosPr', '$TemasProximaReunionPu', '$TemasProximaReunionPr', '$Documentos', '$Observaciones')")){
		return mysql_insert_id();
	} else {
		echo mysql_error();
		return false;	
	}
	
	
}

/* funcion que se encragra de editar una nueva actividad al sistema y ademas retorna un ID quew puede
   ser usado para cualquier elemento relacionado a una actividad
*/

function EditarActividad($ID, $NombreReunion, $Comision, $Fecha, $Lugar, $Horario, $Participantes, $TemasTratadosPu, $TemasTratadosPr , $AcuerdosPu, $AcuerdosPr, $TemasProximaReunionPu, $TemasProximaReunionPr, $Documentos, $Observaciones){
	
	if(MySQL_Insert("update actividades set NombreReunion = '$NombreReunion', Comision = '$Comision', Fecha = '$Fecha', Lugar = '$Lugar', Horario= '$Horario', Participantes= '$Participantes', TemasTratadosPu= '$TemasTratadosPu', TemasTratadosPr= '$TemasTratadosPr' , AcuerdosPu='$AcuerdosPu', AcuerdosPr='$AcuerdosPr', TemasProximaReunionPu='$TemasProximaReunionPu', TemasProximaReunionPr='$TemasProximaReunionPr', Documentos='$Documentos', Observaciones='$Observaciones' where ID='$ID'")){
		return mysql_insert_id();
	} else {
		echo mysql_error();
		return false;	
	}
	
	
}


function SubirArchivoTarea($IDTarea,$Campo){
	
	$ManejoArchivos = new ManejoArchivos();
	$ManejoArchivos->SubirArchivo($Campo,"../upload",$IDTarea."_".$_FILES[$Campo]['name']);
	
	if(MySQL_Insert("insert into tareas_archivos_adjuntos values ( '0' , '$IDTarea', '".$IDTarea."_".$_FILES[$Campo]['name']."', '".$_FILES[$Campo]['size']."', '0', 'upload/".$IDTarea."_".$_FILES[$Campo]['name']."')")){
		return mysql_insert_id();
	} else {
		echo mysql_error();
		return false;	
	}
}

function AgregarDocumento($IDItem, $Visible, $Documento, $Descripcion){
	
	/* subiendo archivo al servidor */
	$ManejoArchivos = new ManejoArchivos();
	$ManejoArchivos->SubirArchivo($Documento,"../upload","DOC.".$IDItem.".".$_FILES[''.$Documento]['name']);
	
	if(MySQL_Insert("insert into documentos values ( '0' , '$IDItem', 'upload/DOC.".$IDItem.".".$_FILES[$Documento]['name']."', '$Descripcion' , '".$_FILES[$Documento]['name']."' , '$Visible' , '".$_FILES[$Documento]['size']."')")){
		return mysql_insert_id();
	} else {
		echo mysql_error();
		return false;	
	}
}

function BorrarArchivoAdjunto($ID){
	if(MySQL_Insert("delete from tareas_archivos_adjuntos where IDArchivo = '$ID'")){
		return true;	
	} else {
		echo mysql_error();
		return false;	
	}
}


function GeneraGrafico1PHP($IDItem,$Control,$url,$Criterio,$Periodo,$ItemGasto,$P){
	
	if($Control==0){
		if($Periodo=="Todo")
			$Tareas = MySQL_Select("select * from tareas where IDItem='".$IDItem."' and Denominacion like  '%$Criterio%'");
		else
			$Tareas = MySQL_Select("select * from tareas where IDItem='".$IDItem."' and Denominacion like  '%$Criterio%' and Periodo='$Periodo'");
		$NroElem = count($Tareas);
			
		if($NroElem != 0){				
			foreach($Tareas as $ValorT){
				if($ValorT[5]==1){
					echo "c1+=".$ValorT[10]."\n";
					echo "i1+=".$ValorT[11]."\n";
				}
				if($ValorT[5]==2){
					echo "c2+=".$ValorT[10]."\n";
					echo "i2+=".$ValorT[11]."\n";
				}
				if($ValorT[5]==3){
					echo "c3+=".$ValorT[10]."\n";
					echo "i3+=".$ValorT[11]."\n";
				}
				if($ValorT[5]==4){
					echo "c4+=".$ValorT[10]."\n";
					echo "i4+=".$ValorT[11]."\n";
				}
				if($ValorT[5]==5){
					echo "c5+=".$ValorT[10]."\n";
					echo "i5+=".$ValorT[11]."\n";
				}
				
			} 
		}
				
		$Control = 1;
	} if($Control == 1) {
		$Item = MySQL_Select("select * from item where IDDependencia='$IDItem'");
		$NroElemItem = count($Item);
	
		if($NroElemItem != 0){
		
			foreach ($Item as $Valor){
			
				if($Periodo=="Todo")
					$Tareas = MySQL_Select("select * from tareas where IDItem='".$Valor[0]."' and Denominacion like  '%$Criterio%'");
				else
					$Tareas = MySQL_Select("select * from tareas where IDItem='".$Valor[0]."' and Denominacion like  '%$Criterio%' and Periodo='$Periodo'");
				
				$NroElem = count($Tareas);
			
				if($NroElem != 0){				
				
					foreach($Tareas as $ValorT){
						if($ValorT[5]==1){
					echo "c1+=".$ValorT[10]."\n";
					echo "i1+=".$ValorT[11]."\n";
				}
				if($ValorT[5]==2){
					echo "c2+=".$ValorT[10]."\n";
					echo "i2+=".$ValorT[11]."\n";
				}
				if($ValorT[5]==3){
					echo "c3+=".$ValorT[10]."\n";
					echo "i3+=".$ValorT[11]."\n";
				}
				if($ValorT[5]==4){
					echo "c4+=".$ValorT[10]."\n";
					echo "i4+=".$ValorT[11]."\n";
				}
				if($ValorT[5]==5){
					echo "c5+=".$ValorT[10]."\n";
					echo "i5+=".$ValorT[11]."\n";
				}
					}
				}
			
				GeneraGrafico1PHP($Valor[0],1,$url,$Criterio,$Periodo,$ItemGasto,$P);
			}
		}
	}
	
	
}




function GeneraGraficoItemPHP($IDItem,$Control,$url,$Criterio,$Periodo,$ItemGasto,$P){
	
	if($Control==0){
		if($Periodo=="Todo")
			$Tareas = MySQL_Select("select * from tareas where IDItem='".$IDItem."' and Denominacion like  '%$Criterio%' and IDItemGasto='$ItemGasto'");
		else
			$Tareas = MySQL_Select("select * from tareas where IDItem='".$IDItem."' and Denominacion like  '%$Criterio%' and Periodo='$Periodo' and IDItemGasto='$ItemGasto'");
		$NroElem = count($Tareas);
			
		if($NroElem != 0){				
			foreach($Tareas as $ValorT){
				
					echo "c1+=".$ValorT[10]."\n";
					echo "i1+=".$ValorT[11]."\n";
				
				
				
			} 
		}
				
		$Control = 1;
	} if($Control == 1) {
		$Item = MySQL_Select("select * from item where IDDependencia='$IDItem'");
		$NroElemItem = count($Item);
	
		if($NroElemItem != 0){
		
			foreach ($Item as $Valor){
			
				if($Periodo=="Todo")
					$Tareas = MySQL_Select("select * from tareas where IDItem='".$Valor[0]."' and Denominacion like  '%$Criterio%' and IDItemGasto='$ItemGasto'");
				else
					$Tareas = MySQL_Select("select * from tareas where IDItem='".$Valor[0]."' and Denominacion like  '%$Criterio%' and Periodo='$Periodo' and IDItemGasto='$ItemGasto'");
				
				$NroElem = count($Tareas);
			
				if($NroElem != 0){				
				
					foreach($Tareas as $ValorT){
						
					echo "c1+=".$ValorT[10]."\n";
					echo "i1+=".$ValorT[11]."\n";
				
				
					}
				}
			
				GeneraGraficoItemPHP($Valor[0],1,$url,$Criterio,$Periodo,$ItemGasto,$P);
			}
		}
	}
	
	
}




/* funciones que se encargaran de retornar el valor progrmado segun un item de gasto asiciado */
function getPorcentajes($IDItemGasto,$Periodo,$Modo){
	
	/* calculamos lo programado */
	switch($Modo){
		
		case 'MECE':
			$ValorProgramadoMECE = MySQL_ObtenerDato("select SUM(ProgramadoMECE) from tareas where IDItemGasto='$IDItemGasto' and Periodo='$Periodo'");
			$ValorComprometidoMECE = MySQL_ObtenerDato("select SUM(MECE) from tareas where IDItemGasto='$IDItemGasto'  and Periodo='$Periodo'");
			$PorcentajeMECE =  ((float)($ValorComprometidoMECE/$ValorProgramadoMECE))*100;
			return $PorcentajeMECE;
			break;
		
		case 'IES':
			$ValorProgramadoIES = MySQL_ObtenerDato("select SUM(ProgramadoIES) from tareas where IDItemGasto='$IDItemGasto'  and Periodo='$Periodo'");
			$ValoComprometidoIES = MySQL_ObtenerDato("select SUM(IES) from tareas where IDItemGasto='$IDItemGasto'  and Periodo='$Periodo'");
			$PorcentajeIES =  ((float)($ValoComprometidoIES/$ValorProgramadoIES))*100;
			return $PorcentajeIES;
			break;
			
		case 'TOTAL':
			$ValorProgramadoMECE = MySQL_ObtenerDato("select SUM(ProgramadoMECE) from tareas where IDItemGasto='$IDItemGasto' and Periodo='$Periodo'");
			$ValorProgramadoIES = MySQL_ObtenerDato("select SUM(ProgramadoIES) from tareas where IDItemGasto='$IDItemGasto'  and Periodo='$Periodo'");
					
			$TotalProgramado = $ValorProgramadoMECE + $ValorProgramadoIES;
			
			$ValorComprometidoMECE = MySQL_ObtenerDato("select SUM(MECE) from tareas where IDItemGasto='$IDItemGasto'  and Periodo='$Periodo'");
			$ValorComprometidoIES = MySQL_ObtenerDato("select SUM(IES) from tareas where IDItemGasto='$IDItemGasto'  and Periodo='$Periodo'");
					
			$TotalComprometido = $ValorComprometidoMECE + $ValorComprometidoIES;
					
					/* calculamos el % relacionado para los totales */
			$PorcentajeTotal =  ((float)($TotalComprometido/$TotalProgramado))*100;
			return $PorcentajeTotal;
			break;
		
	}
	
	
}

?>