<?php
/*
--- © Juan David Avellaneda Molina - 2021 ---
--- david@avellaneda.co
--- Modelo 1 jueves, 28 de enero de 2021
*/
function cadena_notildes($origen){
	$nuevo=$origen;
	$sT=array('á','é','í','ó','ú', 'è','ì','ò','ñ','Ñ', 
	'Á','É','Í','Ó','Ú', '¿','¬','°','Â','Ç', 
	'©','¡','ª','­','–', '™','ê','ã','ç','â',
	'õ','“','”', '´', 'ü', 'Ü', 'Ã');
	$sH=array('&aacute;','&eacute;','&iacute;','&oacute;','&uacute;', '&egrave;','&igrave;','&ograve;','&ntilde;','&Ntilde;', 
	'&Aacute;','&Eacute;','&Iacute;','&Oacute;','&Uacute;', '&iquest;','&not;','&deg;','&Acirc;','&Ccedil;', 
	'&copy;','&iexcl;','&ordf;','&shy;','&ndash;', '&trade;','&ecirc;','&atilde;','&ccedil;','&acirc;',
	'&otilde;','&ldquo;','&rdquo;','&acute;', '&uuml;', '&Uuml;');
	$iTotal=35;
	for ($k=0;$k<=$iTotal;$k++){
		$nuevo=str_replace($sT[$k],$sH[$k],$nuevo);
		}
	return $nuevo;
	}
function numeros_validar($semilla, $decimal=false, $idecimales=0, $permitircomas=false){
	$cn='';
	$cd='';
	$permitidos='-1234567890';
	//se elimina la coma como factor permitido
	if ($decimal){
		$permitidos=$permitidos.'.';
		if ($permitircomas){$permitidos=$permitidos.',';}
		}
	$signo='';
	$punto='';
	$largo=strlen($semilla);
	for ($k=0 ; $k<$largo; $k++){
		$una=substr($semilla,$k,1);
		$lugar=strpos($permitidos, $una);
		if ($lugar===false){
			}else{
			switch ($una){
				case '-':
				if (($cn=='')&&($cd=='')){$signo='-';}
				break;
				case '.':
				case ',':
				if ($punto==''){
					$punto='.';
					if ($cn==''){$punto='0.';}
					}
				break;
				default:
				if ($punto==''){
					$cn=$cn.$una;
					}else{
					$cd=$cd.$una;
					}
				}
			}
		}
	if (strlen($cd)>0){
		if ($idecimales>0){
			$cd=substr($cd,0,$idecimales);
			}
		if ((int)$cd==0){
			$cd='';
			}else{
			$muestra=substr($cd,strlen($cd)-1);
			while ($muestra=='0'){
				$cd=substr($cd,0,strlen($cd)-1);
				if (strlen($cd)==0){break;}
				$muestra=substr($cd,strlen($cd)-1);
				}
			}
		}
	if ($cd==''){
		$punto='';
		}
	return $signo.$cn.$punto.$cd;
	}
function formato_moneda($dValor,$iDecimales=2){
	$dValor2=numeros_validar($dValor, true, $iDecimales);
	if ($dValor2==''){$dValor2=0;}
	//$sfinal="$ ".number_format($ivalor2,$idecimales,",","."); // Posicion anterior
	$sFinal='$ '.number_format($dValor2, $iDecimales, '.', ',');
	return $sFinal;
	}
function tabla_consecutivo($sTabla, $sCampoConsec, $sWhere, $objDB){
	$res=1;
	$sError='';
	if ($sCampoConsec==''){$sError='Sin campo a consultar';}
	if ($sTabla==''){$sError='Sin tabla';}
	if ($sError==''){
		$sCondi='';
		if ($sWhere!=''){$sCondi=' WHERE '.$sWhere;}
		$sSQL='SELECT MAX('.$sCampoConsec.') FROM '.$sTabla.' '.$sCondi;
		$iPaso=1;
		$tabla=$objDB->ejecutasql($sSQL);
		if ($tabla==false){
			$sError=$sSQL;
			$res=-1;
			}else{
			if ($objDB->nf($tabla)>0){
				$tabla=$objDB->sf($tabla);
				$res=(int)$tabla[0]+$iPaso;
				}else{
				$res=$iPaso;
				}
			if ($res<0){$res=1;}
			}
		}else{
		$res=-1;
		}
	return $res;
	}
?>