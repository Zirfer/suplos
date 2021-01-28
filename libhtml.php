<?php
/*
--- © Juan David Avellaneda Molina - 2021 ---
--- david@avellaneda.co
--- Modelo 1 jueves, 28 de enero de 2021
*/
class clsHtmlCombos{
	var $bConVacio=true;
	var $aItem=array();
	var $iItems=0;
	var $iAncho=0;
	var $sAccion='';
	var $sClaseCombo='';
	var $sEtiVacio='';
	var $sNombre='';
	var $sVrVacio='';
	var $sValorCombo='';
	function addArreglo($aDatos, $iCantidad, $sEstilo=''){
		for ($k=1;$k<=$iCantidad;$k++){
			$bAdiciona=true;
			if (isset($aDatos[$k])==0){$aDatos[$k]='';}
			if ($aDatos[$k]==''){$bAdiciona=false;}
			if ($bAdiciona){
				$this->iItems++;
				$i=$this->iItems;
				$this->aItem[$i]['v']=$k;
				$this->aItem[$i]['e']=cadena_notildes($aDatos[$k]);
				$this->aItem[$i]['c']=$sEstilo;
				}
			}
		}
	function addItem($sValor, $sEtiqueta, $sEstilo=''){
		$this->iItems++;
		$i=$this->iItems;
		$this->aItem[$i]['v']=$sValor;
		$this->aItem[$i]['e']=$sEtiqueta;
		$this->aItem[$i]['c']=$sEstilo;
		}
	function html($sConsulta='', $objDB=NULL){
		$sRes='';
		$sAccion='';
		$sEstilos='';
		$sClaseC='';
		if ($this->sAccion!=''){$sAccion=' onChange="'.$this->sAccion.'"';}
		if ($this->sClaseCombo!=''){$sClaseC=' class="'.$this->sClaseCombo.'"';}
		if ($this->iAncho!=0){$sEstilos='width:'.$this->iAncho.'px;';}
		if ($sEstilos!=''){$sEstilos=' style="'.$sEstilos.'"';}	
		$sRes='<select id="'.$this->sNombre.'" name="'.$this->sNombre.'"'.$sAccion.$sClaseC.$sEstilos.'>';
		if ($this->bConVacio){
			$sEstilo='';
			if ($this->sVrVacio===''){$sEstilo=' style="color:#FF0000"';}
			$sRes=$sRes.'<option value="'.$this->sVrVacio.'"'.$sEstilo.'>'.$this->sEtiVacio.'</option>';
			}
		for ($k=1;$k<=$this->iItems;$k++){
			$sSel='';
			$sEstilo='';
			if ($this->aItem[$k]['v']==$this->sValorCombo){$sSel=' selected';}
			if ($this->aItem[$k]['c']!=''){$sEstilo=' style="'.$sEstilo.'"';}
			$sRes=$sRes.'<option value="'.$this->aItem[$k]['v'].'"'.$sSel.$sEstilo.'>'.cadena_notildes($this->aItem[$k]['e']).'</option>';
			}
		if ($sConsulta!=''){
			$sEstilo='';
			$tablac=$objDB->ejecutasql($sConsulta);
			while ($fila=$objDB->sf($tablac)){
				$sSel='';
				if ($fila['id']==$this->sValorCombo){$sSel=' selected';}
				$sRes=$sRes.'<option value="'.$fila['id'].'"'.$sSel.$sEstilo.'>'.cadena_notildes($fila['nombre']).'</option>';
				}
			}
		$sRes=$sRes.'</select>';
		return utf8_encode($sRes);
		}
	function nuevo($sNombre, $sValorCombo='', $bConVacio=true, $sEtiVacio='{Seleccione Uno}', $sVrVacio=''){
		$this->bConVacio=$bConVacio;
		$this->aItem=array();
		$this->iAncho=0;
		$this->iItems=0;
		$this->sAccion='';
		$this->sClaseCombo='';
		$this->sEtiVacio=$sEtiVacio;
		$this->sNombre=$sNombre;
		$this->sVrVacio=$sVrVacio;
		$this->sValorCombo=$sValorCombo;
		}
	function __construct($iItems){
		$this->iItems=0;
		}	
	/*function __construct($sNombre='', $sValorCombo='', $bConVacio=true, $sEtiVacio='{Seleccione Uno}', $sVrVacio=''){
		$this->nuevo($sNombre, $sValorCombo, $bConVacio, $sEtiVacio);
		}*/
	}
?>