<?php
/*
--- © Juan David Avellaneda Molina - 2021 ---
--- david@avellaneda.co
--- Modelo 1 jueves, 28 de enero de 2021
*/
function f9001_MostrarMisBienes($aParametros, $objDB){
	$sError='';
	$sRes='';
	// Recibir variables
	if (isset($aParametros[1])==0){$aParametros[1]='';} // Ciudad
	if (isset($aParametros[2])==0){$aParametros[2]='';} // Tipo
	if (isset($aParametros[3])==0){$aParametros[3]='';} // Rango
	// Filtros
	$sSQLadd='';
	if ($aParametros[1]!=''){$sSQLadd=$sSQLadd.'TB.sys03idciudad='.$aParametros[1].' AND ';}
	if ($aParametros[2]!=''){$sSQLadd=$sSQLadd.'TB.sys03idtipo='.$aParametros[2].' AND ';}
	if ($aParametros[3]!=''){
		$aBase=explode(';', $aParametros[3]);
		$iMin=$aBase[0];
		$iMax=$aBase[1];
		$sSQLadd=$sSQLadd.'TB.sys03precio>='.$iMin.' AND ';
		$sSQLadd=$sSQLadd.'TB.sys03precio<='.$iMax.' AND ';
		}
	// Iniciar todo
	$sSQL='SELECT TB.sys03consec, TB.sys03id, TB.sys03direccion, TB.sys03telefono, TB.sys03codpostal, TB.sys03precio, TB.sys03guardado, T1.sys01nombre, T2.sys02nombre
FROM sys03bienes AS TB, sys01ciudad AS T1, sys02tipobien AS T2
WHERE '.$sSQLadd.' TB.sys03guardado="S" AND TB.sys03idciudad=T1.sys01id AND TB.sys03idtipo=T2.sys02id';
	$tabla=$objDB->ejecutasql($sSQL);
	$iCant=$objDB->nf($tabla);
	if ($objDB->nf($tabla)>0){
		$sRes='<table border="0" align="center" cellpadding="0" cellspacing="2">
		<thead><tr>
		<td colspan="2">Bienes encontrados '.$iCant.'</td>
		</tr></thead>';
		while($fila=$objDB->sf($tabla)){
			$sBoton='<input class="btn green" value="Eliminar" id="Eliminar" onclick="eliminar('.$fila['sys03id'].')">';
			$sImagen='<img src="./img/home.jpg" width="150" height="150" />';
			$sDatos='<b>Direcci&oacute;n:</b> '.$fila['sys03direccion'].'<br>
<b>Ciudad:</b> '.$fila['sys01nombre'].'<br>
<b>Telefono:</b> '.$fila['sys03telefono'].'<br>
<b>C&oacute;digo postal:</b> '.$fila['sys03codpostal'].'<br>
<b>Tipo:</b> '.$fila['sys02nombre'].'<br>
<b>Precio:</b> '.formato_moneda($fila['sys03precio'], 0).'<br>
'.$sBoton.'';
			$sRes=$sRes.'<tr>
<td>'.$sImagen.'</td>	
<td>'.$sDatos.'</td>			
			';
			}
		$sRes=$sRes.'</table>';	
		}	
	return array($sError, $sRes);
	}
function f9001_MostrarBienesDisp($aParametros, $objDB){
	$sError='';
	$sRes='';
	// Recibir variables
	if (isset($aParametros[1])==0){$aParametros[1]='';} // Ciudad
	if (isset($aParametros[2])==0){$aParametros[2]='';} // Tipo
	if (isset($aParametros[3])==0){$aParametros[3]='';} // Rango
	// Filtros
	$sSQLadd='';
	if ($aParametros[1]!=''){$sSQLadd=$sSQLadd.'TB.sys03idciudad='.$aParametros[1].' AND ';}
	if ($aParametros[2]!=''){$sSQLadd=$sSQLadd.'TB.sys03idtipo='.$aParametros[2].' AND ';}
	if ($aParametros[3]!=''){
		$aBase=explode(';', $aParametros[3]);
		$iMin=$aBase[0];
		$iMax=$aBase[1];
		$sSQLadd=$sSQLadd.'TB.sys03precio>='.$iMin.' AND ';
		$sSQLadd=$sSQLadd.'TB.sys03precio<='.$iMax.' AND ';
		}
	// Iniciar todo
	$sSQL='SELECT TB.sys03consec, TB.sys03id, TB.sys03direccion, TB.sys03telefono, TB.sys03codpostal, TB.sys03precio, TB.sys03guardado, T1.sys01nombre, T2.sys02nombre
FROM sys03bienes AS TB, sys01ciudad AS T1, sys02tipobien AS T2
WHERE '.$sSQLadd.' TB.sys03idciudad=T1.sys01id AND TB.sys03idtipo=T2.sys02id';
	$tabla=$objDB->ejecutasql($sSQL);
	$iCant=$objDB->nf($tabla);
	if ($objDB->nf($tabla)>0){
		$sRes='<table border="0" align="center" cellpadding="0" cellspacing="2">
		<thead><tr>
		<td colspan="2">Bienes encontrados '.$iCant.'</td>
		</tr></thead>';
		while($fila=$objDB->sf($tabla)){
			if ($fila['sys03guardado']=='N'){
				$sBoton='<input class="btn green" value="Guardar" id="Guardar" onclick="guardar('.$fila['sys03id'].')">';
				}else{
				$sBoton='<input class="btn green" value="Guardar" id="Guardar" disabled="true">';
				}
			$sImagen='<img src="./img/home.jpg" width="150" height="150" />';
			$sDatos='<b>Direcci&oacute;n:</b> '.$fila['sys03direccion'].'<br>
<b>Ciudad:</b> '.$fila['sys01nombre'].'<br>
<b>Telefono:</b> '.$fila['sys03telefono'].'<br>
<b>C&oacute;digo postal:</b> '.$fila['sys03codpostal'].'<br>
<b>Tipo:</b> '.$fila['sys02nombre'].'<br>
<b>Precio:</b> '.formato_moneda($fila['sys03precio'], 0).'<br>
'.$sBoton.'';
			$sRes=$sRes.'<tr>
<td>'.$sImagen.'</td>	
<td>'.$sDatos.'</td>			
			';
			}
		$sRes=$sRes.'</table>';	
		}	
	return array($sError, $sRes);
	}
function f9001_RevisarDatos($objDB){
	$data=file_get_contents('data-1.json');
	$aItems=json_decode($data, true);
	// listar los items
	$iCant=0;
	$sCampos03='(sys03idtipo, sys03consec, sys03id, sys03direccion, sys03idciudad, sys03telefono, sys03codpostal, sys03precio)';
	foreach($aItems as $aBienes){
		$sValores03='';
		// Usamos la funcion htmlspecialchars para evitar que nos inyecten codigo
		$sId=htmlspecialchars($aBienes['Id']);
		$sDireccion=htmlspecialchars($aBienes['Direccion']);
		$sCiudad=htmlspecialchars($aBienes['Ciudad']);
		$sTelefono=htmlspecialchars($aBienes['Telefono']);
		$sCodigo=htmlspecialchars($aBienes['Codigo_Postal']);
		$sTipo=htmlspecialchars($aBienes['Tipo']);
		$dPrecio=htmlspecialchars($aBienes['Precio']);
		// Formatear el precio, porque viene en formato texto
		$dPrecio=str_replace('$',' ',$dPrecio);
		$dPrecio=str_replace(',','',$dPrecio); 
		// Agregar toda esta informacion a la base de datos
		// Empezamos con la ciudad
		$idCiudad=f9001_AgregarCiudad($sCiudad, $objDB);
		// Ahora los tipos de bien
		$idTipoBien=f9001_AgregarTipoBien($sTipo, $objDB);
		// Verificar primero si ese Id ya existe
		$sSQL='SELECT 1 FROM sys03bienes WHERE sys03id='.$sId.'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)==0){
			// Significa que no encontre ese id, por tanto, agregarlo
			$sys03consec=tabla_consecutivo('sys03bienes', 'sys03consec', ' sys03idtipo='.$idTipoBien.'', $objDB);
			$sys03id=tabla_consecutivo('sys03bienes', 'sys03id', '', $objDB);
			$sValores03='('.$idTipoBien.', '.$sys03consec.', '.$sys03id.', "'.$sDireccion.'", '.$idCiudad.', "'.$sTelefono.'", "'.$sCodigo.'", "'.$dPrecio.'")';
			// Hacemos el insert dps de superar un tope de 10
			$sSQL='INSERT INTO sys03bienes '.$sCampos03.' VALUES '.$sValores03.'';
			$result=$objDB->ejecutasql($sSQL);
			}
		}
	// Retornar un error en caso dado de que el archivo no exista
	// Hacer la validacion despues.
	}
function f9001_AgregarTipoBien($sNomTipo, $objDB){
	$idTipoBien=0;
	// Validamos si esta ciudad existe
	$sSQL='SELECT sys02id FROM sys02tipobien WHERE sys02nombre="'.$sNomTipo.'"';
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$idTipoBien=$fila['sys02id'];
		}else{
		// Si no encontramos la ciudad, crearla
		$sys02id=tabla_consecutivo('sys02tipobien', 'sys02id', '', $objDB);
		$sys02nombre=$sNomTipo;
		$sCampos02='sys02id, sys02nombre';
		$sValores02=''.$sys02id.', "'.$sys02nombre.'"';
		// Hacer el insert
		$sSQL='INSERT INTO sys02tipobien ('.$sCampos02.') VALUES ('.$sValores02.')';
		$result=$objDB->ejecutasql($sSQL);
		//
		$idTipoBien=$sys02id;
		}
	return $idTipoBien;
	}	
function f9001_AgregarCiudad($sNomCiudad, $objDB){
	$idCiudad=0;
	// Validamos si esta ciudad existe
	$sSQL='SELECT sys01id FROM sys01ciudad WHERE sys01nombre="'.$sNomCiudad.'"';
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$idCiudad=$fila['sys01id'];
		}else{
		// Si no encontramos la ciudad, crearla
		$sys01id=tabla_consecutivo('sys01ciudad', 'sys01id', '', $objDB);
		$sys01nombre=$sNomCiudad;
		$sCampos01='sys01id, sys01nombre';
		$sValores01=''.$sys01id.', "'.$sys01nombre.'"';
		// Hacer el insert
		$sSQL='INSERT INTO sys01ciudad ('.$sCampos01.') VALUES ('.$sValores01.')';
		$result=$objDB->ejecutasql($sSQL);
		//
		$idCiudad=$sys01id;
		}
	return $idCiudad;
	}
?>