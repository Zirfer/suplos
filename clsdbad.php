<?php
/*
--- © Juan David Avellaneda Molina - 2021 ---
--- david@avellaneda.co
--- Modelo 1 jueves, 28 de enero de 2021
*/
function clsdb_warning_handler($errno, $errstr, $errfile, $errline, $errcontext){
	throw new Exception($errstr);
	}
class clsdbad_v2{
	var $objMYSQL=NULL;
	var $bConectada=false;
	var $bXajax=false;
	var $dbClave='';
	var $dbNombre='';
	var $dbServidor='';
	var $dbUsuario='';
	var $dbPuerto='';
	var $sError='';
	var $bUTF8=false;
	// Funciones propias de la clase de la base de datos
	function xajax(){
		$this->bxajax=true;
		$this->bconectada=false;
		}
	
	function conectar(){
		$bRes=false;
		$this->sError='';
		$bHayServicio=false;
		try{
			$this->objMYSQL=new mysqli();
			$bHayServicio=true;
			}
		catch (Exception $e){
			$this->sError='Error al intentar conectar el servicio MySQL';
			}
		if ($bHayServicio){
			if ($this->dbPuerto==''){
				@$this->objMYSQL->connect($this->dbServidor, $this->dbUsuario, $this->dbClave, $this->dbNombre);
				}else{
				@$this->objMYSQL->connect($this->dbServidor, $this->dbUsuario, $this->dbClave, $this->dbNombre, $this->dbPuerto);
				}
			if ($this->objMYSQL->connect_error){
				$this->sError='Error conectando al servidor de datos <br><b>'.$this->objMYSQL->connect_error.'</b>';
				}else{
				$bRes=true;
				}
			}
		if (!$this->bXajax){$this->bConectada=true;}
		return $bRes;
		}
	
	function CerrarConexion(){
		if ($this->objMYSQL!=NULL){
			@mysqli_close($this->objMYSQL);
			}
		}
	
	function ejecutasql($sSentencia){
		$res=false;
		$this->sError='';
		if (trim($sSentencia)==''){$this->sError='No se ha definido una consulta a ejecutar';}
		if (!$this->bConectada){$this->conectar();}
		if ($this->sError==''){
			$res=@$this->objMYSQL->query($sSentencia);
			if ($res==false){
				$this->sError='Error al ejecutar sentencia: '.$sSentencia.'<br>'.@$this->objMYSQL->error;
				}
			}
		return $res;
		}
	
	function liberar($tabla){
		@mysqli_free_result($tabla);
		}
	
	function nf($tabla){ // Numero de filas
		$iRes=0;
		if ($tabla!=false){
			if (is_object($tabla)){
				$iRes=$tabla->num_rows;
				}
			}
		return $iRes;
		}
		
	function sf($tabla){
		$res=false;
		if ($tabla!=false){
			if (is_object($tabla)){
				$res=$tabla->fetch_array();
				}
			}
		return $res;
		}
	
	function __construct($servidor, $usuario, $clave, $db){
		$this->dbClave=$clave;
		$this->dbNombre=$db;
		$this->dbServidor=$servidor;
		$this->dbUsuario=$usuario;
		if ($servidor==''){
			$this->sError='No se ha definido el servidor de base de datos';
			}
		}
		
	function __destruct(){
		if ($this->objMYSQL!=NULL){
			//$this->objMYSQL->close();
			$this->CerrarConexion();
			unset($this->objMYSQL);
			}
		}
	
	}	
?>