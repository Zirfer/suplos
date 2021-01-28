<?php
/* 
--- © Juan David Avellaneda Molina - 2021 ---
--- david@avellaneda.co
Inicia el 28 de enero de 2021
*/
error_reporting(E_ALL);
set_time_limit(0);
require './app.php';
require './clsdbad.php';
if (isset($APP->db_servidor)==0){
	echo 'No se ha definido el servidor de base de datos';
	die();
	}
$objDB=new clsdbad_v2($APP->db_servidor, $APP->db_usuario, $APP->db_clave, $APP->db_nombre);
if ($APP->db_puerto!=''){$objDB->dbPuerto=$APP->db_puerto;}
$versionejecutable=9;
$procesos=0;
$suspende=0;
$iError=0;
echo 'Iniciando proceso de revision de la base de datos <b>'.$APP->db_nombre.'</b>';
$sSQL='SHOW TABLES;';
$result=$objDB->ejecutasql($sSQL);
$cant=$objDB->nf($result);
if ($cant<1){
	// Se crea un archivo de configuracion para llevar versionado de la base de datos
	$sSQL="CREATE TABLE config (codclave varchar(30), nombreclave varchar(50), valor varchar (20), PRIMARY KEY (codclave));";
	$result=$objDB->ejecutasql($sSQL);
	$sSQL="INSERT INTO config (codclave, nombreclave, valor) VALUES('dbversion','Version de la Base de Datos', 0);";
	$result=$objDB->ejecutasql($sSQL);
	$dbversion=0;
	echo '<br>Deberia crear las tablas';
	}else{
	$sSQL="SELECT valor FROM config WHERE codclave='dbversion';";
	$result=$objDB->ejecutasql($sSQL);
	$row=$objDB->sf($result);
	$dbversion=$row['valor'];
	$bbloquea=false;
	if ($dbversion>1000){$bbloquea=true;}
	if ($bbloquea){
		echo '<br>Debe correr el upddb que corresponda a la version {'.$dbversion.'}...';
		die();		
		}
	}
echo "<br>Version Actual de la base de datos ".$dbversion;
while ($dbversion<$versionejecutable){
$sSQL='';
if (($dbversion>0)&&($dbversion<101)){
	if ($dbversion==1){$sSQL="CREATE TABLE sys01ciudad (sys01id int NOT NULL, sys01nombre varchar(100) NULL)";}
	if ($dbversion==2){$sSQL="ALTER TABLE sys01ciudad ADD PRIMARY KEY(sys01id)";}
	if ($dbversion==3){$sSQL="CREATE TABLE sys02tipobien (sys02id int NOT NULL, sys02nombre varchar(100) NULL)";}
	if ($dbversion==4){$sSQL="ALTER TABLE sys02tipobien ADD PRIMARY KEY(sys02id)";}
	if ($dbversion==5){$sSQL="CREATE TABLE sys03bienes (sys03idtipo int NOT NULL, sys03consec int NOT NULL, sys03id int NULL DEFAULT 0, sys03direccion varchar(100) NULL, sys03idciudad int NULL DEFAULT 0, sys03telefono varchar(50) NULL, sys03codpostal varchar(50) NULL, sys03precio Decimal(15,2) NULL DEFAULT 0)";}
	if ($dbversion==6){$sSQL="ALTER TABLE sys03bienes ADD PRIMARY KEY(sys03id)";}
	if ($dbversion==7){$sSQL="ALTER TABLE sys03bienes ADD UNIQUE INDEX sys03bienes_id(sys03idtipo, sys03consec)";}
	if ($dbversion==8){$sSQL="ALTER TABLE sys03bienes ADD (sys03guardado varchar(1) NULL DEFAULT 'N')";}


	}
if (($dbversion>101)&&($dbversion<201)){}
if (($dbversion>201)&&($dbversion<301)){}
if (($dbversion>301)&&($dbversion<401)){}
if (($dbversion>401)&&($dbversion<501)){}
if (($dbversion>501)&&($dbversion<601)){}
if (($dbversion>601)&&($dbversion<701)){}
if (($dbversion>701)&&($dbversion<801)){}
if (($dbversion>801)&&($dbversion<901)){}
if (($dbversion>901)&&($dbversion<1001)){}
	echo "<br>".$sSQL;
	$bHayError=false;
	$result=$objDB->ejecutasql($sSQL);
	if ($result==false){
		$bHayError=true;
		//Si viene un DROP INDEX no hay error.
		if (strpos($sSQL, 'DROP INDEX')>0){$bHayError=false;}
		if (strpos($sSQL, 'DROP PRIMARY KEY')>0){$bHayError=false;}
		}
	if ($bHayError){
		echo '<br><font color="#FF0000"><b>Error </b>'.$objDB->sError.'</font>';
		$iError++;
		$suspende=1;
		}
	// Aumentar la version de base de datos	
	$sSQL="UPDATE config SET valor=".($dbversion+1)." WHERE codclave='dbversion';";
	$result=$objDB->ejecutasql($sSQL);
	$dbversion++;
	$procesos++;
	// El proceso se hace por bloques
	if ($procesos>14){
		$suspende=1;
		break;
		}
	}//termina de ejecutar sentencia por sentenca.
$objDB->CerrarConexion();
?>
<br>Base de Datos Actualizada <?php echo $dbversion; ?>;
<?php
$bConFormulario=false;
if($suspende==1){$bConFormulario=true;}
if ($bConFormulario){
?><br>
<form id="frmsigue" name="frmsigue" method="post" action="">
El Proceso A&uacute;n No Ha Concluido
<input type="submit" name="Submit" value="Continuar" />
</form>
<?php
if ($iError==0){
?>
<script language="javascript">
function recargar(){
	frmsigue.submit();
	}
setInterval ("recargar();", 1000); 
</script>
<?php 
	}//fin de si no hay errores...
}
?>