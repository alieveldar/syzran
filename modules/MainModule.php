<?
$_SESSION['onsite']=1; $SafeMode=0; $IsIndex=0; $capcache=''; if ($SafeMode==1) { error_reporting(E_ALL & ~E_NOTICE); } // 0-рабочий режим, 1-режим отладки с выводом логов

// Переменные шаблона (В HTML файле шаблона подставляются значения этих переменных) ========================================================================
// Например: если в index.html поместить "$Caption" эта строка заменится на значение переменной $Page["Caption"] ===========================================
// Так же такое же замещение идет с переменными, определенными через систему управления: "Основные настройки" и "Параметры сайта" (массив VARS) ============
$Page=array();  # В этом массиве хранится весь контент (например: $Page["Caption"], $Page["Content"], $Page["Title"] массив должен быть доступен в функциях модулей)

$VarsToHtml = array(
	// Стандартные переменные - должны определяться в модулях, вывод в "Заполнение шаблона сайта"
	"Заголовок страницы (H1)"						=> "Caption", 		// формируется в модулях
	"Заголовок страницы (title)"					=> "Title", 		// формируется ниже, перед выводом дизайна
	"Содержание верхней колонки"					=> "TopContent", 	// формируется в модулях
	"Содержание страницы"							=> "Content", 		// формируется в модулях
	"Содержание левой колонки"						=> "LeftContent", 	// формируется в модулях
	"Содержание правой колонки"						=> "RightContent", 	// формируется в модулях
	"Содержание нижний колонки"						=> "BottomContent", // формируется в модулях
	"Ключевые слова (keywords)"						=> "KeyWords", 		// формируется ниже, перед выводом дизайна
	"Описание страницы (description)"				=> "Description", 	// формируется ниже, перед выводом дизайна
	"Дочерние страницы"								=> "ChildPages", 	// формируется ниже, только для статичных страниц.
	"Авторизация пользователей"						=> "UserAuth", 		// формируется в UserLogin.php
	"Сапа"											=> "sape", 			// Сапа
	"Кнопка Про Город Объявления"					=> "SocialGroups",	// Социальные группы /// заменено на кнопку добавления объявления
);

// Список необходимых файлов и модулей =====================================================================================================================
// $JSmodules и $CSSmodules можно пополнять в модулях сайта ================================================================================================
// $JSmodules и $CSSmodules дополняются автоматически при запросе модуля (запрашиваются соответствующие js и css файлы) ====================================
$PHPmodules = array(
	"Работа с кэшем"					=> "modules/standart/Cache.php",
	"Общие функции"						=> "modules/standart/Settings.php",
	"Навигация сайта"					=> "modules/standart/CreateMenu.php",
	"Социальные иллюстрации"			=> "modules/standart/ImageMaster.php",
	"Отправка E-mail"					=> "modules/standart/MailSend.php",
	"Авторизация и данные"				=> "modules/standart/UsersLogin.php",	
	"Комментарии пользователей"			=> "modules/standart/UsersComments.php",
);

$JSmodules = array(	
	"Библиотека JQuery"					=> "/modules/standart/js/JQuery.js",
	"Передача данных JsHttpRequest"		=> "/modules/standart/js/JsHttpRequest.js",	
	"Основной JS сайта"  				=> "/modules/standart/js/MainModule.js",
	"Авторизация ULogin"				=> "http://ulogin.ru/js/ulogin.js",
"API Vkontakte Groups"			=> "http://userapi.com/js/api/openapi.js",
);

$CSSmodules = array(
	"Стандартный Pro.CMS"	=> "/template/standart/prostandart.css",
	"Тонкий Шрифт Google"	=> "http://fonts.googleapis.com/css?family=Roboto+Condensed:400,300,700&subset=latin,cyrillic",
);


// Подключение БД ==========================================================================================================================================
$GLOBAL=array(); $GLOBAL["sitekey"]=1;													// Глобальный массив сайта
@require("modules/standart/DataBase.php"); 												// подключение БД
$GLOBAL["StartTime"]=GetMicroTime(); 													// начало работы скриптов
$RealPage = trim($_SERVER["REQUEST_URI"], "/");											// Текущая страница
$RealHost = str_replace(array('http://', 'www.'), '', $_SERVER['HTTP_HOST']);			// Текущий адрес сайта
$ar=explode("?", $RealPage); $RealPage=$ar[0]; $nocache=$ar[1];  
// Oпределение данных из URL ===============================================================================================================================
$dir=explode("/", $RealPage);

$link	= $dir[0];
$start	= $dir[1];
$page	= $dir[2];
$id		= $dir[3];
$part	= $dir[4];
$sel	= $dir[5];

## ПЕРЕХВАТ СТАРЫХ URL ##############################################################
if ($link=="newsv2" || $link=="node") { $Perehvat=1; $nid_old=str_replace(".html", "", $start); }
if ($link=="all") { $RealPage="all"; } if ($link=="oney") { $link="ls"; $Perehvat=2; $dir[0]="ls"; }

## ПЕРЕХВАТ СТАРЫХ URL ##############################################################

if (!isset($link) || $link=="" || $link=="/") { $link = ""; $IsIndex=1; }
if (!isset($start) || $start=="") $s = 0;
if (!isset($page) || $page=="")	$page = 0;
if (!isset($id) || $id=="")		$id = 0;
if (!isset($part) || $part=="")	$part = 0;
if (!isset($sel) || $sel=="")	$sel = 0;
if (!isset($fd) || $fd=="")		$fd = 0;

// Запрос стандартных модулей PHP ====================================================================================================================================
foreach ($PHPmodules as $name=>$module) {
	if (is_file(trim($module,"/"))) { $GLOBAL["log"].="<i>Подключение PHP</i>: модуль &laquo;".$name."&raquo; подключен<hr>";
	@require($module); } else { $GLOBAL["log"].="<u>Подключение PHP</u>: модуль &laquo;".$name."&raquo; не найден (<b>".$module."</b>)<hr>"; }
}

if ($nocache=="nocache") { $VARS["cachemenu"]=0; $VARS["cacheblock"]=0; $VARS["cachepages"]=0; $capcache=" <span style='color:red;'>[Отключен кэш]</span>"; }

// Содержание страницы =======================================================================================================================================
### Массив доменов и субдоменов и Определяем текущий поддомен
$Domains = GetDomains(); $tmp=array(); $tmp=array_flip($Domains); 
$sd=explode(".", $RealHost); $SubDomain = $tmp[$sd[(sizeof($sd)-3)]];

# Запрос стандартного модуля со общими данными (контент для всего сайта, а не определенного раздела)
if (is_file("modules/StaticBlocks.php")) { @require("modules/StaticBlocks.php"); $GLOBAL["log"].="<i>Подключение PHP</i>: общий модуль &laquo;StaticBlocks.php&raquo; подключен<hr>"; }

if ($IsIndex==1) {
	# Если это главная страница сайта
	$data=DB("SELECT * FROM `_pages` WHERE (`isindex`='1' && `stat`='1' && domain='".(int)$SubDomain."') LIMIT 1");
} else {
	# Если НЕ главная страница сайта 
	if (Dbsel($RealPage)!=Dbsel($link)) { $q="((`link`='".Dbsel($RealPage)."' && `module`='') || (`link`='".Dbsel($link)."' && `module`!=''))"; } else { $q="`link`='".Dbsel($link)."'"; }
	$data=DB("SELECT * FROM `_pages` WHERE (".$q." && `stat`='1') LIMIT 1");
}

if ($data["total"]==0){
	if ($link=="7ya") {
		@require("modules/7yalenta/7yalenta.php");
		$node["design"]="semyaindex";
		
	} else {
	@header("HTTP/1.1 404 Not Found"); $Robots='<meta name="robots" content="noindex" />'; $Page404=1;
	$Page["Content"]=@file_get_contents($ROOT."/template/404.html"); $Page["Caption"]="Страница не найдена - 404";
	$GLOBAL["log"].="<u>Содержание</u>: страница &laquo;".$RealPage."&raquo; не найдена<hr>";
	}
} else {
	@mysql_data_seek($data["result"], 0); $node=@mysql_fetch_array($data["result"]);
	if ($IsIndex==1) { $link=$node["link"]; }

	# Если данный раздел принадлежит другому поддомену
	if (trim($Domains[$node["domain"]].".".$VARS["mdomain"], ".")!=$RealHost && $Perehvat!=2) { @header("location: http://".trim($Domains[$node["domain"]].".".$VARS["mdomain"], ".")."/".$RealPage); exit(); }
	
	# Генерация контента
	$Page["Node"]		 = $node;
	$Page["Link"]	 	 = $node["link"];
	$Page["KeyWords"]	 = $node["kw"];
	$Page["Description"] = $node["ds"];
	$Page["Data"]		 = $node["data"];
	$Page["Caption"]	 = $node["name"];
	$Page["ShortName"]	 = $node["shortname"];
	$Page["Content"]	 = $node["text"];

	if ($node["module"]=="") {
		#Если найдена статичная страница
		@header ("HTTP/1.0 200 Ok"); $Robots='<meta name="robots" content="index, follow" />'; $Page404=0;
		$GLOBAL["log"].="<i>Содержание</i>: вывод статичной страницы &laquo;<b>".$Page["Link"]."</b>&raquo;<hr>";
	} else {
		
	$GLOBAL["log"].="<h1>Работа основного модуля: ".$node["module"]."</h1>";
	#Если запрошенная страница выводится через модуль
	if (is_file("modules/page_mods/".$node["module"]."-".$node["link"].".php")) { 
			/* PHP */ @header ("HTTP/1.0 200 Ok"); $Robots='<meta name="robots" content="index, follow" />'; @require ("modules/page_mods/".$node["module"]."-".$node["link"].".php");
			/* JS */  if (is_file("modules/page_mods/".$node["module"]."-".$node["link"].".js")) { $JSmodules[$node["name"]]="/modules/page_mods/".$node["module"]."-".$node["link"].".js"; }
			/* CSS */ if (is_file("modules/page_mods/".$node["module"]."-".$node["link"].".css")) { $CSSmodules[$node["name"]]="/modules/page_mods/".$node["module"]."-".$node["link"].".css"; }
			$GLOBAL["log"].="<i>Модификатор PHP</i>: файл &laquo;".$node["module"]."_".$node["link"].".php&raquo; раздела &laquo;".$link."&raquo; подключен<hr>";
		
	#Ищем основной файл php (пример: /modules/lenta/lenta.php)
	} elseif (is_file("modules/".$node["module"]."/".$node["module"].".php")) { 
			/* PHP */ @header ("HTTP/1.0 200 Ok"); $Robots='<meta name="robots" content="index, follow" />'; @require ("modules/".$node["module"]."/".$node["module"].".php");
			/* JS */  if (is_file("modules/".$node["module"]."/".$node["module"].".js")) { $JSmodules[$node["name"]]="/modules/".$node["module"]."/".$node["module"].".js"; }
			/* CSS */ if (is_file("modules/".$node["module"]."/".$node["module"].".css")) { $CSSmodules[$node["name"]]="/modules/".$node["module"]."/".$node["module"].".css"; }
			$GLOBAL["log"].="<i>Подключение PHP</i>: модуль &laquo;".$node["module"]."&raquo; раздела &laquo;".$link."&raquo; подключен<hr>";
	} else {
	#Раздел на модуле, но файлы не найдены
			@header("HTTP/1.1 404 Not Found"); $Robots='<meta name="robots" content="noindex" />'; $Page404=1;
			$Page["Content"]=@file_get_contents($ROOT."/template/404.html"); $Page["Caption"]="Страница не найдена - 404";
			$GLOBAL["log"].="<u>Подключение PHP</u>: модуль &laquo;".$node["module"]."&raquo; раздела &laquo;".$link."&raquo; не найден<hr>";
	}
	$GLOBAL["log"].="<h1>Работа дополнительных модулей</h1>";	
	}


	if (is_file("modules/page_mods/".$node["link"].".php") && $Page404!=1) { 
        @require("modules/page_mods/".$node["link"].".php"); }
		if (is_file("modules/page_mods/".$node["link"].".js") && $Page404!=1) { $JSmodules["modules/page_mods/".$node["link"].".js"]="/modules/page_mods/".$node["link"].".js"; }
		if (is_file("modules/page_mods/".$node["link"].".css") && $Page404!=1) { $CSSmodules["modules/page_mods/".$node["link"].".css"]="/modules/page_mods/".$node["link"].".css"; }

}

############################################################################################################################################
############################################################################################################################################
############################################################################################################################################
if ($Perehvat==1) {
	$Page404=0; $node["design"]="0"; @header ("HTTP/1.0 200 Ok"); $Robots='<meta name="robots" content="index, follow" />'; 
	$CSSmodules["Старый формат новостей NEWSV2"]="/modules/old_news/old_news.css"; @require("modules/old_news/old_news.php");
	$GLOBAL["log"].="<i>ОПРЕДЕЛЕНА СТАРАЯ НОВОСТЬ</i>: модуль &laquo;modules/old_news.php&raquo; подключен<hr>"; 	
}
############################################################################################################################################
############################################################################################################################################
############################################################################################################################################

// Временно здесь постоит скрипт создания сапы ==================================================================================================================================================
#if (!defined('_SAPE_USER')){     define('_SAPE_USER', 'edbced77aec02198c113fd9319093618');   }
#require_once(realpath($_SERVER['DOCUMENT_ROOT'].'/'._SAPE_USER.'/sape.php'));
#$o['charset'] = 'UTF-8'; $sape = new SAPE_client($o); $Page['sape']=$sape->return_links();

$Page['sape']="";

############################################################################################################################################

// Определение шаблона сайта ==================================================================================================================================================
if ($node["design"]=="0" || $node["design"]=="" || $Page404==1) {
	$data=DB("SELECT `folder` FROM `_designs` WHERE (`stat`='1') LIMIT 1");
	if ($data["total"]==1) { @mysql_data_seek($data["result"], 0); $tmp=@mysql_fetch_array($data["result"]); $design=$tmp["folder"]; } else { $design="index"; }
	$GLOBAL["log"].="<i>Шаблон дизайна</i>: определена папка по умолчанию &laquo;".$design."&raquo;<hr>";
} else {
	$design=$node["design"];
	$GLOBAL["log"].="<i>Шаблон дизайна</i>: определена папка раздела &laquo;".$design."&raquo;<hr>";
}

// Загрузка шаблона сайта =====================================================================================================================================================
$ICON='<link rel="shortcut icon" href="/favicon.png" type="image/x-icon" />'.$r;
$fortwocolumns=array("forum", "strochki", "users", "eventmap", "2giscatalog", "companies");
if ($node["link"]!="index" && $node["link"]!="all" && ($node["module"]=="" || in_array($node["module"], $fortwocolumns))) { $design="twocolumns"; }
if ($node["link"]=="mysamara") { $design="instasamara";  }


if (is_file("template/".$design."/".$design.".html")) { $DesignHtml=@file_get_contents("template/".$design."/".$design.".html"); $GLOBAL["log"].="<i>Шаблон дизайна</i>: загружен шаблон &laquo;"."template/".$design."/".$design.".html"."&raquo;<hr>";
} else { $DesignHtml="<h1>Не подключен шаблон дизайна</h1>"; $GLOBAL["log"].="<u>Шаблон дизайна</u>: не найден шаблон &laquo;"."template/".$design."/".$design.".html"."&raquo;<hr>"; }
if (is_file("template/".$design."/".$design.".css")) { $CSSmodules["template/".$design."/".$design.".css"]="/template/".$design."/".$design.".css"; }
if (is_file("template/".$design."/".$design.".js")) { $JSmodules["template/".$design."/".$design.".js"]="/template/".$design."/".$design.".js"; }

// Заполнение шаблона сайта ===================================================================================================================================================
if ($node["isindex"]==1) { $Page["Title"]=$VARS["sitename"]; } else { if ($Page["Caption"]!="") { $Page["Title"]=strip_tags($Page["Caption"])." ".$VARS["splitter"]." ".$VARS["sitename"]; } else { $Page["Title"]=$Page["Title"]." ".$VARS["splitter"]." ".$VARS["sitename"]; }}
if ($Page["KeyWords"]=="") { $Page["KeyWords"]=$Page["Caption"].", ".$VARS["keywords"]; } else { $Page["KeyWords"]=$Page["KeyWords"]/*.", ".$VARS["keywords"]*/; }
if ($Page["Description"]=="") { $Page["Description"]=$Page["Caption"].", ".$VARS["description"]; } else { $Page["Description"]=$Page["Description"]/*.", ".$VARS["description"]*/; }
if ($Page["Caption"]!="" && (int)$ShowCap==0) { $Page["Caption"]="<h1>".$Page["Caption"].$capcache."</h1>"; } else { if ($Page["Caption"]!="") { $Page["Caption"]="<h1 class='CatL'>".$Page["Caption"].$capcache."</h1>"; }}
$Page["Caption"]=Hsc($Page["Caption"]); $Page["Title"]=Hsc($Page["Title"]); if ($node["isindex"]==1) { $Page["Caption"]=$capcache; } 

foreach ($VarsToHtml as $key=>$value) { $DesignHtml=str_replace('$'.$value, $Page[$value], $DesignHtml); } # Переменные шаблона дизайна (определяются в начале этого файла)
foreach ($VARS as $key=>$value) { $DesignHtml=str_replace('$'.$key, $value, $DesignHtml); } # Параметры и настройки сайта (определяются в панели администрирования)
foreach ($MENU as $key=>$value) { $DesignHtml=str_replace('$'.$key, $value, $DesignHtml); } # Меню сайта (определяются в панели администрирования)

// Запрос вспомогательных модулей JS ====================================================================================================================================
$GLOBAL["log"].="<h1>Запрос дополнительных скриптов</h1>";
foreach ($JSmodules as $name=>$module) {
	if (strpos($module, "http:")===false) {
		if (is_file(trim($module,"/"))) { $GLOBAL["log"].="<i>Подключение JS</i>: скрипт &laquo;".$name."&raquo; подключен<hr>";
		$GLOBAL["JSModules"].="<script src='".$module."' type='text/javascript'></script>"."\r\n";
		} else { $GLOBAL["log"].="<u>Подключение JS</u>: скрипт &laquo;".$name."&raquo; не найден (<b>".$module."</b>)<hr>"; }
	} else {
		$GLOBAL["log"].="<i>Подключение JS</i>: внешний скрипт &laquo;".$name."&raquo; подключен<hr>";
		$GLOBAL["JSModules"].="<script src='".$module."' type='text/javascript'></script>"."\r\n";
	}
}

// Запрос CSS для вспомогательных модулей =====================================================================================================================================
$GLOBAL["log"].="<h1>Запрос дополнительных стилей</h1>";
foreach ($CSSmodules as $name=>$module) {
	if (strpos($module, "http")===false) {
		if (is_file(trim($module,"/"))) { $GLOBAL["log"].="<i>Подключение CSS</i>: стиль &laquo;".$name."&raquo; подключен<hr>";
		$GLOBAL["CSSModules"].="<link rel='stylesheet' type='text/css' href='".$module."' media='all' />"."\r\n";
		} else { $GLOBAL["log"].="<u>Подключение CSS</u>: стиль &laquo;".$name."&raquo; не найден (<b>".$module."</b>)<hr>"; }
	} else {
		$GLOBAL["log"].="<i>Подключение CSS</i>: внешний стиль &laquo;".$name."&raquo; подключен<hr>";
		$GLOBAL["CSSModules"].="<link rel='stylesheet' type='text/css' href='".$module."' media='all' />"."\r\n";
	}
}
@mysql_close();

// Вывод шаблона сайта ========================================================================================================================================================
$RENDER='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">'.$r;
$RENDER.='<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru" dir="ltr">'.$r.'<head>'.$r;
$RENDER.='<title>'.$Page["Title"].'</title>'.$r;
$RENDER.=$Robots.$r;
$RENDER.='<meta name="google-site-verification" content="9n1L1i-msAou-A6y68djZmm4Xchzb4zC7Nvj_VQTJ6Q" />';
$RENDER.='<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'.$r;
$RENDER.='<meta name="keywords" content=\''.trim($Page["KeyWords"], ",").'\' /><meta name="description" content=\''.trim($Page["Description"], ",").'\' />'.$r;
$RENDER.=$ICON;
$RENDER.='<link rel="alternate" type="application/rss+xml" title=\''.$VARS["sitename"].'\' href="http://'.$VARS["mdomain"].'/rss.xml" />'.$r;
$RENDER.=$GLOBAL["CSSModules"];
$RENDER.=$GLOBAL["JSModules"];
$RENDER.='</head>'.$r.'<body>'.$r;
$RENDER.=$DesignHtml.$r;
$RENDER.='<input type="hidden" id="BoxCount" value="0" /><input type="hidden" id="gidvk" value="0" /><input type="hidden" id="DomainId" value="'.(int)$SubDomain.'" /><input type="hidden" id="UserId" value="'.(int)$_SESSION["userid"].'" />';
$RENDER.='</body>
'.$r.'</html>';

echo $RENDER; if ($HardCacheFile!="" && $HardCacheFile!=NULL && (int)$_SESSION['userid']==0) { @file_put_contents($HardCacheFile, $RENDER); }

// Вывод логов сайта ===========================================================================================================================================================
$GLOBAL["StopTime"]=GetMicroTime(); 
$GLOBAL["RunTime"]=$GLOBAL["StopTime"]-$GLOBAL["StartTime"];


if ($SafeMode==1 && $_SESSION["userid"]==1) { 
	echo "<div id='SystemLogs'>";
		echo "<h1>Лог выполнения скриптов</h1>".$GLOBAL["log"];
		if (isset($_SESSION)) {
			echo "<h1>Значения  в ".'$_SESSION'."</h1>";
			foreach ($_SESSION as $key=>$value) { echo "<b>$key</b> -> &laquo;<i>$value</i>&raquo;<hr>"; }
		}
		echo "<h1>Время выполнения и количество запросов</h1>";
		echo "<i>Количество запросов SQL:</i> <b>".round($GLOBAL["sqlcount"], 3)."</b><hr>";
		echo "<i>Время выполнения SQL:</i> <b>".round($GLOBAL["sqltime"], 3)."</b> с.<hr>";
		echo "<i>Время выполнения PHP:</i> <b>".round($GLOBAL["RunTime"], 3)."</b> с.";
	echo "</div>";
} else {
	echo "<!-- CountSQL: ".round($GLOBAL["sqlcount"], 3)." | TimeSQL: ".round($GLOBAL["sqltime"], 3)."c. | TotalTime: ".round($GLOBAL["RunTime"], 3)."c. -->";
}
//}
// =============================================================================================================================================================================
?>