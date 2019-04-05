<?
session_start(); if (!$_SESSION["full"]) { $_SESSION["full"]=false; }
if (!$_SESSION["mobile"] || $_SESSION["mobile"]=="") { $_SESSION["mobile"]="auto"; } mb_internal_encoding("UTF-8");
	
	if (!preg_match('/^m\./', $_SERVER['HTTP_HOST']) && !$_SESSION["full"] && !preg_match('/live/', $_SERVER['REQUEST_URI']) && !preg_match('/^live\./', $_SERVER['HTTP_HOST'])) {
		$iphone = mb_strpos($_SERVER['HTTP_USER_AGENT'],"iPhone");
		$android = mb_strpos($_SERVER['HTTP_USER_AGENT'],"Android");
		$palmpre = mb_strpos($_SERVER['HTTP_USER_AGENT'],"webOS");
		$berry = mb_strpos($_SERVER['HTTP_USER_AGENT'],"BlackBerry");
		$ipod = mb_strpos($_SERVER['HTTP_USER_AGENT'],"iPod");
		$ipad = mb_strpos($_SERVER['HTTP_USER_AGENT'],"iPad");
		$mobile = mb_strpos($_SERVER['HTTP_USER_AGENT'],"Mobile");
		$symb = mb_strpos($_SERVER['HTTP_USER_AGENT'],"Symbian");
		$operam = mb_strpos($_SERVER['HTTP_USER_AGENT'],"Opera M");
		$htc = mb_strpos($_SERVER['HTTP_USER_AGENT'],"HTC_");
		$fennec = mb_strpos($_SERVER['HTTP_USER_AGENT'],"Fennec/");
		$winphone = mb_strpos($_SERVER['HTTP_USER_AGENT'],"WindowsPhone"); 
		$wp7 = mb_strpos($_SERVER['HTTP_USER_AGENT'],"WP7");
		$wp8 = mb_strpos($_SERVER['HTTP_USER_AGENT'],"WP8");
		if ($ipad || $iphone || $android || $palmpre || $ipod || $berry || $mobile || $symb || $operam || $htc || $fennec || $winphone || $wp7 || $wp8 === true) {
			$r=explode(".", $_SERVER['HTTP_HOST']); $t=count($r); $domain=trim("http://m.".$r[$t-2].".".$r[$t-1]."/".$_SERVER['REQUEST_URI'], "/"); @header('Location: '.$domain); exit();  
		}
	}
if(preg_match('/^m\./', $_SERVER['HTTP_HOST'])) { unset($_SESSION["full"]); require ("modules/mobile.MainModule.php"); } else { require ("modules/MainModule.php"); }
?>