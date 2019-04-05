<?
### Запрашиваемый файл должен определять переменную $rsstext 

$rsstext='<?xml version="1.0" encoding="UTF-8" ?>
<rss version="2.0" xmlns:yandex="http://news.yandex.ru">
<channel>
<title>'.htmlspecialchars($VARS["sitename"]).'</title>
<link>http://'.$GLOBAL["host"].'</link>
<description>'.htmlspecialchars($VARS["sitename"]).'</description>
<lastBuildDate>'.date("r").'</lastBuildDate>
<yandex:logo>http://'.$GLOBAL["host"].'/template/yalogo.png</yandex:logo>
<yandex:logo type="square">http://'.$GLOBAL["host"].'/template/sqyalogo.png</yandex:logo>
';

$q=""; foreach($tables as $table) { $tmp=explode("_", $table); $link=$tmp[0]; $q.="(SELECT `$table`.`id`, `$table`.`name`, `$table`.`text`, `$table`.`data`, `$table`.`pic`, `_pages`.`link` FROM `$table` LEFT JOIN `_pages` ON `_pages`.`link`='$link' WHERE (`$table`.`stat`='1' && `$table`.`promo`!='1' && `$table`.`yarss`='1') GROUP BY 1) UNION ";}
$datat=DB(trim($q, "UNION ")." ORDER BY `data` DESC LIMIT 20"); for($it=0; $it<$datat["total"]; $it++) { @mysql_data_seek($datat["result"], $it); $at=@mysql_fetch_array($datat["result"]);
$rsstext.='
<item>
	<title>'.htmlspecialchars($at["name"]).'</title>
	<link>http://'.$GLOBAL["host"].'/'.$at["link"].'/view/'.$at["id"].'</link>   
	<yandex:full-text>'.htmlspecialchars(strip_tags($at["text"])).'</yandex:full-text>   
	<pubDate>'.date("r", $at["data"]).'</pubDate>';
if ($at["pic"]!="") { $rsstext.='
	<enclosure url="http://'.$GLOBAL["host"].'/userfiles/picpreview/'.$at["pic"].'" type="image/jpeg" />'; }
$rsstext.='
</item>';
}

$rsstext.='
</channel>
</rss>';
?>