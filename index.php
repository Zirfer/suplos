<?php
/*
--- © Juan David Avellaneda Molina - 2021 ---
--- david@avellaneda.co
--- Modelo 1 jueves, 28 de enero de 2021
*/
if (!file_exists('./app.php')){
	echo 'No se ha establecido un archivo de configuraci&oacute;n.';
	die();
	}
mb_internal_encoding('UTF-8');
// Librerias
require './app.php';
require './clsdbad.php';
require './resultados.php';
require './librerias.php';
require './libhtml.php';
// Base de datos
$xajax=NULL;
$objDB=new clsdbad_v2($APP->db_servidor, $APP->db_usuario, $APP->db_clave, $APP->db_nombre);
if ($APP->db_puerto!=''){$objDB->dbPuerto=$APP->db_puerto;}
// Iniciar la conexion
if (!$objDB->Conectar()){
	echo 'Error al intentar conectar con la base de datos <b>'.$objDB->sError.'</b><br>';
	}
// Hacer revision de los datos generales incluidos en el archivo data-1.json
f9001_RevisarDatos($objDB);
// Variables
if (isset($_REQUEST['bciudad'])==0){$_REQUEST['bciudad']='';}
if (isset($_REQUEST['btipo'])==0){$_REQUEST['btipo']='';}
if (isset($_REQUEST['rangoPrecio'])==0){$_REQUEST['rangoPrecio']='0;100000';}
if (isset($_REQUEST['sys03id'])==0){$_REQUEST['sys03id']='';}
if (isset($_REQUEST['paso'])==0){$_REQUEST['paso']='';}
if (isset($_REQUEST['fciudad'])==0){$_REQUEST['fciudad']='';}
if (isset($_REQUEST['ftipo'])==0){$_REQUEST['ftipo']='';}
// Guardar
if ($_REQUEST['paso']==20){
	$_REQUEST['paso']='';
	$sSQL='UPDATE sys03bienes SET sys03guardado="S" WHERE sys03id='.$_REQUEST['sys03id'].'';
	$result=$objDB->ejecutasql($sSQL);
	$_REQUEST['sys03id']='';
	}
// Eliminar
if ($_REQUEST['paso']==21){
	$_REQUEST['paso']='';
	$sSQL='UPDATE sys03bienes SET sys03guardado="N" WHERE sys03id='.$_REQUEST['sys03id'].'';
	$result=$objDB->ejecutasql($sSQL);
	$_REQUEST['sys03id']='';
	}
// Acciones que requieres llamado a la base de datos
$objCombos=new clsHtmlCombos(0);
$objCombos->nuevo('bciudad', $_REQUEST['bciudad'], true, 'Elige una ciudad', '');
$sSQL='SELECT sys01id AS id, sys01nombre AS nombre FROM sys01ciudad ORDER BY sys01nombre';
$html_ciudad=$objCombos->html($sSQL, $objDB);
//
$objCombos->nuevo('btipo', $_REQUEST['btipo'], true, 'Elige un tipo', '');
$sSQL='SELECT sys02id AS id, sys02nombre AS nombre FROM sys02tipobien ORDER BY sys02nombre';
$html_tipo=$objCombos->html($sSQL, $objDB);
// Filtros
$objCombos->nuevo('fciudad', $_REQUEST['fciudad'], true, 'Elige una ciudad', '');
$sSQL='SELECT sys01id AS id, sys01nombre AS nombre FROM sys01ciudad ORDER BY sys01nombre';
$html_fciudad=$objCombos->html($sSQL, $objDB);
//
$objCombos->nuevo('ftipo', $_REQUEST['ftipo'], true, 'Elige un tipo', '');
$sSQL='SELECT sys02id AS id, sys02nombre AS nombre FROM sys02tipobien ORDER BY sys02nombre';
$html_ftipo=$objCombos->html($sSQL, $objDB);
// Acciones que requieran llamado a base de datos
$aParametros[1]=$_REQUEST['bciudad'];
$aParametros[2]=$_REQUEST['btipo'];
$aParametros[3]=$_REQUEST['rangoPrecio'];
list($sError, $sBienesDisp)=f9001_MostrarBienesDisp($aParametros, $objDB);
// Bienes guardados
list($sError, $sBienes)=f9001_MostrarMisBienes($aParametros, $objDB);
if ($sError!=''){echo $sError;}
?>
<script language="javascript">
<!--
function actualizar(){
	window.document.formulario.submit();
	}
function guardar(id03){
	window.document.formulario.sys03id.value=id03;
	window.document.formulario.rangoPrecio.value='0;100000';
	window.document.formulario.paso.value=20;
	window.document.formulario.submit();
	}
function eliminar(id03){
	window.document.formulario.sys03id.value=id03;
	window.document.formulario.rangoPrecio.value='0;100000';
	window.document.formulario.paso.value=21;
	window.document.formulario.submit();
	}
function imprimeexcel(){
	window.document.frmimprime.v3.value=window.document.frmfiltros.fciudad.value;
	window.document.frmimprime.v4.value=window.document.frmfiltros.ftipo.value;
	window.document.frmimprime.submit();
	}	
// -->
</script>
<form id="frmimprime" name="frmimprime" method="post" action="e9001.php" target="_blank">
<input id="v3" name="v3" type="hidden" value="" />
<input id="v4" name="v4" type="hidden" value="" />
</form>
<?php
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link type="text/css" rel="stylesheet" href="css/materialize.min.css"  media="screen,projection"/>
  <link type="text/css" rel="stylesheet" href="css/customColors.css"  media="screen,projection"/>
  <link type="text/css" rel="stylesheet" href="css/ion.rangeSlider.css"  media="screen,projection"/>
  <link type="text/css" rel="stylesheet" href="css/ion.rangeSlider.skinFlat.css"  media="screen,projection"/>
  <link type="text/css" rel="stylesheet" href="css/index.css"  media="screen,projection"/>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Formulario</title>
</head>

<body>
<!-- <video src="img/video.mp4" id="vidFondo"></video> -->

<div class="contenedor">
<div class="card rowTitulo">
<h1>Bienes Intelcost</h1>
</div>
<div class="colFiltros">
<form id="formulario" name="formulario" action="" method="post">
<input id="sys03id" name="sys03id" type="hidden" value="" />
<input id="paso" name="paso" type="hidden" value="" />
<div class="filtrosContenido">
<div class="tituloFiltros">
<h5>Filtros</h5>
</div>
<div class="filtroCiudad input-field">
<p><label for="selectCiudad">Ciudad:</label><br></p>
<?php
echo $html_ciudad;
?>	
</div>
<div class="filtroTipo input-field">
<p><label for="selecTipo">Tipo:</label></p>
<br>
<?php
echo $html_tipo;
?>
</div>
<div class="filtroPrecio">
<label for="rangoPrecio">Precio:</label>
<input type="text" id="rangoPrecio" name="rangoPrecio" value="<?php echo $_REQUEST['rangoPrecio']; ?>" />
</div>
<div class="botonField">
<input class="btn white" value="Buscar" id="submitButton" onClick="actualizar()">
</div>
</div>
</form>
</div>
<div id="tabs" style="width: 75%;">
<ul>
<li><a href="#tabs-1">Bienes disponibles</a></li>
<li><a href="#tabs-2">Mis bienes</a></li>
<li><a href="#tabs-3">Reportes</a></li>
</ul>
<div id="tabs-1">
<div class="colContenido" id="divResultadosBusqueda">
<div class="tituloContenido card" style="justify-content: center;">
<h5>Resultados de la búsqueda:</h5>
<div id="bienesdisp">
<?php
echo $sBienesDisp;
?>
</div>
<div class="divider"></div>
</div>
</div>
</div>

<div id="tabs-2" >
<div class="colContenido" id="divResultadosBusqueda">
<div class="tituloContenido card" style="justify-content: center;">
<h5>Bienes guardados:</h5>
<div id="bienes">
<?php
echo $sBienes;
?>
</div>
<div class="divider"></div>
</div>
</div>
</div>

<div id="tabs-3">
<div class="colContenido" id="divResultadosBusqueda">
<div class="tituloContenido card" style="justify-content: center;">
<div class="colFiltros">
<form id="frmfiltros" name="frmfiltros" action="" method="post">
<div class="filtrosContenido">
<div class="tituloFiltros">
<h5>Filtros</h5>
</div>
<div class="filtroCiudad input-field">
<p><label for="selectCiudad">Ciudad:</label><br></p>
<?php
echo $html_fciudad;
?>	
</div>
<div class="filtroTipo input-field">
<p><label for="selecTipo">Tipo:</label></p>
<br>
<?php
echo $html_ftipo;
?>
</div>
<div class="botonField">
<input class="btn white" value="Generar" id="Generar" onClick="imprimeexcel()">
</div>
</div>
</form>
</div>
<div class="divider"></div>
</div>
</div>
</div>  
</div>

<script src="https://code.jquery.com/jquery-1.12.4.js"></script>

<script type="text/javascript" src="js/ion.rangeSlider.min.js"></script>
<script type="text/javascript" src="js/materialize.min.js"></script>
<script type="text/javascript" src="js/index.js"></script>
<script type="text/javascript" src="js/buscador.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript">
$( document ).ready(function() {
$( "#tabs" ).tabs();
});
</script>
</body>
</html>
<?php
?>