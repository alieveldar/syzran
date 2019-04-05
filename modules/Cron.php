<?
$GLOBAL=array(); $GLOBAL["sitekey"]=1; $now=time(); $ROOT=$_SERVER['DOCUMENT_ROOT']; 
@require_once($ROOT."/modules/standart/DataBase.php"); 
@require_once($ROOT."/modules/standart/Settings.php");

$crondata=DB("SELECT * FROM `_cron` WHERE (`runtime`+`lasttime`<'".$now."' && stat='1') ORDER BY `rate` DESC");
if ($crondata["total"]>0) { for ($croni=0; $croni<$crondata["total"]; $croni++): @mysql_data_seek($crondata["result"],$croni); $ar=@mysql_fetch_array($crondata["result"]);
	$start=GetMicroTime(); $cronlog=""; $act.=$ar["link"].", ";
	$cronlog.="Запуск крона: <b>".$ar["link"]."</b>, время: <b>".date("H:i:s, d.m.Y")."</b><br>"; UPD($ar["id"], $cronlog);
	if (is_file($ROOT.$ar["link"])) {
		$cronlog.="Файл загружен: <b>".$ar["link"]."</b>, время: <b>".date("H:i:s, d.m.Y")."</b><br><br>"; UPD($ar["id"], $cronlog);
		#################################################################################################################
		@require_once($ROOT.$ar["link"]);
		#################################################################################################################
		$cronlog.="<br>Файл выполнен: <b>".$ar["link"]."</b>, время: <b>".date("H:i:s, d.m.Y")."</b><br>"; UPD($ar["id"], $cronlog);
	} else {
		$cronlog.="Файл не найден: <b>".$ar["link"]."</b>, время: <b>".date("H:i:s, d.m.Y")."</b><br>"; UPD($ar["id"], $cronlog);
	}
	$end=GetMicroTime(); $cronlog.="<hr>Время выполнения: <b>".round($end-$start, 2)."</b> сек.";  UPD($ar["id"], $cronlog); endfor;
}
echo "<hr>Cron end @ ".date("H:i:s d.m.Y")." runned: ".trim($act, ", ");

function UPD($i, $t) { DB("UPDATE `_cron` SET log='$t', lasttime='".time()."' WHERE (`id`='$i')"); }



?>