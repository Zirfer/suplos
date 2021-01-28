<?php
/*
--- © Juan David Avellaneda Molina - 2021 ---
--- david@avellaneda.co
--- Modelo 1 jueves, 28 de enero de 2021
*/
/*
error_reporting(E_ALL);
ini_set("display_errors", 1);
*/
if (!file_exists('./app.php')){
	echo 'No se ha establecido un archivo de configuraci&oacute;n.';
	die();
	}
mb_internal_encoding('UTF-8');
require './app.php';
require './clsdbad.php';
require './resultados.php';
require './librerias.php';
require './libhtml.php';
require './excel/PHPExcel.php';
require './excel/PHPExcel/Writer/Excel2007.php';
$sError='';
$bEntra=true;
if (isset($_REQUEST['v3'])==0){$_REQUEST['v3']=0;}
if (isset($_REQUEST['v4'])==0){$_REQUEST['v4']=0;}
if ($sError!=''){$bEntra=false;}
if ($bEntra){
	$bEntra=false;
	$sTituloRpt='Bienes';
	$sFormato='blanco.xlsx';
	if ($sError==''){
		if (!file_exists($sFormato)){
			$sError='Formato no encontrado {'.$sFormato.'}';
			}
		}
	$idCiudad=($_REQUEST['v3']);
	$idTipo=($_REQUEST['v4']);
	$sSQLadd='';
	if ($sError==''){
		$objReader=PHPExcel_IOFactory::createReader('Excel2007');
		$objPHPExcel=$objReader->load($sFormato);
		$objPHPExcel->getProperties()->setCreator('David Avellaneda - http://www.ideasw.com');
		$objPHPExcel->getProperties()->setLastModifiedBy('David Avellaneda - http://www.ideasw.com');
		$objPHPExcel->getProperties()->setTitle($sTituloRpt);
		$objPHPExcel->getProperties()->setSubject($sTituloRpt);
		$objHoja=$objPHPExcel->getActiveSheet();
		$objHoja->setTitle($sTituloRpt);
		$iFila=2;
		$objDB=new clsdbad_v2($APP->db_servidor, $APP->db_usuario, $APP->db_clave, $APP->db_nombre);
		if ($APP->db_puerto!=''){$objDB->dbPuerto=$APP->db_puerto;}
		// Inicia filtros
		if ((int)$idCiudad!=0){
			$sSQLadd=$sSQLadd.'TB.sys03idciudad='.$idCiudad.' AND ';
			}
		if ((int)$idTipo!=0){
			$sSQLadd=$sSQLadd.'TB.sys03idtipo='.$idTipo.' AND ';
			}
		// Fin filtros
		$sSQL='SELECT TB.sys03consec, TB.sys03id, TB.sys03direccion, TB.sys03telefono, TB.sys03codpostal, TB.sys03precio, TB.sys03guardado, T1.sys01nombre, T2.sys02nombre
FROM sys03bienes AS TB, sys01ciudad AS T1, sys02tipobien AS T2
WHERE '.$sSQLadd.' TB.sys03guardado="S" AND TB.sys03idciudad=T1.sys01id AND TB.sys03idtipo=T2.sys02id';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$objHoja->setCellValueByColumnAndRow(0, $iFila, 'Id');
			$objHoja->setCellValueByColumnAndRow(1, $iFila, 'Tipo bien');
			$objHoja->setCellValueByColumnAndRow(2, $iFila, 'Ciudad');
			$objHoja->setCellValueByColumnAndRow(3, $iFila, 'Direccion');
			$objHoja->setCellValueByColumnAndRow(4, $iFila, 'Telefono');
			$objHoja->setCellValueByColumnAndRow(5, $iFila, 'Código Postal');
			$objHoja->setCellValueByColumnAndRow(6, $iFila, 'Precio');
			$objHoja->getStyle('A'.$iFila.':G'.$iFila.'')->getFont()->setBold(true);
			//
			$iFila++;
			while ($fila=$objDB->sf($tabla)){
				$objHoja->setCellValueByColumnAndRow(0, $iFila, $fila['sys03id']);
				$objHoja->setCellValueByColumnAndRow(1, $iFila, $fila['sys02nombre']);
				$objHoja->setCellValueByColumnAndRow(2, $iFila, $fila['sys01nombre']);
				$objHoja->setCellValueByColumnAndRow(3, $iFila, $fila['sys03direccion']);
				$objHoja->setCellValueByColumnAndRow(4, $iFila, $fila['sys03telefono']);
				$objHoja->setCellValueByColumnAndRow(5, $iFila, $fila['sys03codpostal']);
				$objHoja->setCellValueByColumnAndRow(6, $iFila, formato_moneda($fila['sys03precio'], 0));
				$iFila++;
				}
			}
		//Tamaño de las celdas
		for($i='A'; $i<='G'; $i++){
			$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setAutoSize(TRUE);
			}	
		$objDB->CerrarConexion();
		/* descargar el resultado */
		header('Expires: Thu, 27 Mar 1980 23:59:00 GMT'); /* la pagina expira en una fecha pasada */
		header('Last-Modified: '.gmdate("D, d M Y H:i:s").' GMT'); /* ultima actualizacion ahora cuando la cargamos */
		header('Cache-Control: no-cache, must-revalidate'); /* no guardar en CACHE */
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.$sTituloRpt.'.xlsx"');
		header('Cache-Control: max-age=0');
		$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->setPreCalculateFormulas(false);
		$objWriter->save('php://output');
		die();
		}else{
		echo $sError;
		}
	}else{
	echo $sError;
	}
?>