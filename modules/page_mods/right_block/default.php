<?	
$file="_rightblock-rightPRO"; if (RetCache($file, "cacheblock")=="true") { list($Page["RightContent"], $cap)=GetCache($file, 0); } else { list($Page["RightContent"], $cap)=CreateRightBlock(); SetCache($file, $Page["RightContent"], "", "cacheblock"); }

function CreateRightBlock() {
	global $Domains, $SubDomain, $GLOBAL, $C20, $C25, $C, $C10, $C15, $yandex1, $yandex2; $ban10=2; $text=''; $src="";
	$text="<div class='banner' id='Banner-1-1'></div>";
	// --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
	
	/*PodSurikat 1 */
	$q="SELECT `[table]`.`id`, `[table]`.`name`, `[table]`.`lid`, `[table]`.`tavto`,`[table]`.`data`, `[table]`.`pic`, '[link]' as `link` FROM `[table]` WHERE (`[table]`.`stat`='1' && `[table]`.`data`>'".(time()-6*24*60*60)."' && (`[table]`.`promo`=1 || `[table]`.`spromo`=1) [used])";
	$endq="ORDER BY `data` DESC LIMIT 6"; $data=getNewsFromLentas($q, $endq); $list=array(); $cnt=1; if ((int)$data["total"]>0) { for ($i=0; $i<$data["total"]; $i++) { @mysql_data_seek($data["result"], $i); $ar=@mysql_fetch_array($data["result"]); $list[]=$ar; }
	foreach($list as $ar) { if ($ar["link"]!="ls") { if (strpos($ar["link"], "ls")!==false || strpos($ar["link"], "bubr")!==false) { $rel="target='_blank' rel='nofollow'"; } else { $rel=""; }
	$text.="<div class='OCNew ReTwoOrder'>"; $text.="<a href='/".$ar["link"]."/view/".$ar["id"]."' $rel>"; if ($ar["tavto"]==1 && $ar["pic"]!="") { $text.="<img src='$src/userfiles/pictavto/".$ar["pic"]."'>"; } $text.=$ar["name"]."</a>"; $text.="</div>".$C25; $cnt++; }}} else { $text.=$yandex1; }
	
	$text.=$C10."<div class='banner3' id='Banner-10-".$ban10."'></div>"; $ban10=$ban10+2;
	
	/*PodSurikat 2 */
	$q="SELECT `[table]`.`id`, `[table]`.`name`,`[table]`.`data`, '1' as `tavto`, `[table]`.`lid`, `[table]`.`pic`, '[link]' as `link` FROM `[table]` WHERE (`[table]`.`stat`='1' && `[table]`.`data`>'".(time()-6*24*60*60)."' && (`[table]`.`promo`=1 || `[table]`.`spromo`=1) [used])";
	$endq="ORDER BY `data` DESC LIMIT 6,6"; $data=getNewsFromLentas($q, $endq); $list=array(); $cnt=1; if ((int)$data["total"]>0) { for ($i=0; $i<$data["total"]; $i++) { @mysql_data_seek($data["result"], $i); $ar=@mysql_fetch_array($data["result"]); $list[]=$ar; }
	foreach($list as $ar) { if ($ar["link"]!="ls") { if (strpos($ar["link"], "ls")!==false || strpos($ar["link"], "bubr")!==false) { $rel="target='_blank' rel='nofollow'"; } else { $rel=""; }
	$text.="<div class='OCNew ReTwoOrder'>"; $text.="<a href='/".$ar["link"]."/view/".$ar["id"]."' $rel>"; if ($ar["tavto"]==1 && $ar["pic"]!="") { $text.="<img src='$src/userfiles/pictavto/".$ar["pic"]."'>"; } $text.=$ar["name"]."</a>"; $text.="</div>".$C25; $cnt++; }}} else { $text.=$yandex2; }		

		
	$text.=$C10."<div class='banner3' id='Banner-10-".$ban10."'></div>"; $ban10=$ban10+2;
	$text.="<h3>Самое популярное</h3>".getMaxSeens();
	$text.=$C10."<div class='banner3' id='Banner-10-".$ban10."'></div>"; $ban10=$ban10+2;
	$text.="<h3>Самое комментируемое</h3>".getMaxComments();
	$text.=$C10."<div class='banner3' id='Banner-10-".$ban10."'></div>"; $ban10=$ban10+2;
	$text.=$C10."<div class='banner3' id='Banner-10-".$ban10."'></div>"; $ban10=$ban10+2;
	$text.="<h3>Выбор читателей</h3>".getMaxLikes();
	
	// --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
	return(array($text, ""));
}
?>