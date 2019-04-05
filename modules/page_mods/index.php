<?
$Page["Caption"]=""; $Page["Content"]=""; $Page["LeftContent"]=""; $Page["RightContent"]=""; $src=""; 
$file="_index-indexsmpage"; if (RetCache($file,'cacheblock')=="true") { list($text,$cap)=GetCache($file,0); }else{ list($text,$cap)=NewIndexPage(); SetCache($file,$text); }

$Page["TopContent"].=$C15."<h1>Новости Сызрани".$capcache."</h1>".$C5;
if ($GLOBAL["USER"]["role"]>1) { $Page["TopContent"].="<div id='AdminEditItem'><a href='".$BP."?nocache'>Обновить кэш. Не злоупотреблять! =)</a></div>".$C25; }
$Page["TopContent"].=$text;

### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### 

function NewIndexPage(){
	global $VARS, $GLOBAL, $Page, $C10, $C20, $C25, $C, $used, $CommerceBlock, $lentas, $src;
	//--- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- -
	$text="<div id='ONLEFT'><div id='TV'>".TV()."</div><div id='LEFT'>".LEFT()."</div><div id='CENTER'>".CENTER()."</div></div><div id='RIGHT'>".RIGHT()."</div>";
	//--- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- -
	return array($text,$cap);
}

### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ###

function TV() {
	global $used, $C, $C5, $C10, $C20, $C25, $lentas, $src; $text="";
	$q="SELECT `[table]`.`id`, `[table]`.`name`, `[table]`.`lid`, `[table]`.`data`, `[table]`.`comcount`, `[table]`.`pic`, '[link]' as `link` FROM `[table]` WHERE (`[table]`.`stat`='1' && `[table]`.`onind`=1 [used])";
	$endq="ORDER BY `data` DESC LIMIT 1"; $data=getNewsFromLentas($q, $endq); if ($data["total"]==1) { @mysql_data_seek($data["result"], 0); $ar=@mysql_fetch_array($data["result"]); $used[$ar["link"]][]=$ar["id"];
	$text.="<a href='/".$ar["link"]."/view/".$ar["id"]."'><img src='$src/userfiles/picintv/".$ar["pic"]."' title='".$ar["name"]."' alt='".$ar["name"]."' class='TvPic'/></a>";
	$text.="<a href='/".$ar["link"]."/view/".$ar["id"]."' class='TvLink'>".$ar["name"]."</a><div class='TvSpan'>".$ar["lid"]."</div>".Dater($ar); } return $text;
}

### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ###

function LEFT() {
	global $used, $C, $C10, $C20, $C25, $lentas, $src;
	$adv=array(); $news=array(); $list=array(); $advid=0; $cnt=1; $ban10=1;
	
	$q="SELECT `[table]`.`id`, `[table]`.`name`, `[table]`.`tavto`, `[table]`.`lid`, `[table]`.`data`, `[table]`.`comcount`, `[table]`.`pic`, '[link]' as `link` FROM `[table]` WHERE (`[table]`.`stat`='1' && `[table]`.`redak`!=1 && (`[table]`.`promo`=1 || `[table]`.`spromo`=1) && `[table]`.`data`<'".(time()-5*24*60*60)."' && `[table]`.`data`>'".(time()-7*24*60*60)."')"; $endq="ORDER BY `data` DESC"; $data=getNewsFromLentas($q, $endq);
	for ($i=0; $i<$data["total"]; $i++) { @mysql_data_seek($data["result"], $i); $ar=@mysql_fetch_array($data["result"]); $ar["pic"]=""; $ar["data"]=''; if ($ar["link"]!="ls") { $adv[]=$ar; }}
	
	$q="SELECT `[table]`.`id`, `[table]`.`name`, `[table]`.`tavto`, `[table]`.`lid`, `[table]`.`data`, `[table]`.`comcount`, `[table]`.`pic`, '[link]' as `link` FROM `[table]` WHERE (`[table]`.`stat`='1' && `[table]`.`redak`!=1 && (`[table]`.`promo`!=1 || `[table]`.`spromo`!=1) [used])"; $endq="ORDER BY `data` DESC LIMIT 110"; $data=getNewsFromLentas($q, $endq);
	for ($i=0; $i<$data["total"]; $i++) { @mysql_data_seek($data["result"], $i); $ar=@mysql_fetch_array($data["result"]);if (($i+1)%4==0 && $adv[$advid]["name"]!="") { $list[]=$adv[$advid]; $advid++; } $list[]=$ar; }
	
	foreach($list as $ar) {
		$text.="<div class='ONew'>";
			$text.="<a href='/".$ar["link"]."/view/".$ar["id"]."'>";
			if ($ar["tavto"]==1 && $ar["pic"]!="") { $text.="<img src='$src/userfiles/pictavto/".$ar["pic"]."'>"; }
			$text.=$ar["name"]."</a>".$C.Dater($ar);
		$text.="</div>".$C20;
		if ($cnt%4==0) {
			if ($cnt==4) { $text.='<script type="text/javascript" src="//vk.com/js/api/openapi.js?105"></script><div id="vk_groupsVK"></div><script type="text/javascript">VK.Widgets.Group("vk_groupsVK", {mode: 1, width: "200", height: "140", color1: "FFFFFF", color2: "2B587A", color3: "5B7FA6"}, 99860777);</script>'.$C25; }
			if ($ban10<10) { $text.="<div class='banner3' id='Banner-10-".$ban10."'></div>"; $ban10=$ban10+2; }
		} 
	$cnt++; } return $text;
}

### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ###

function CENTER() {
	global $used, $C, $C10, $C20, $C25, $C30, $lentas, $src;	
	$adv=array(); $advs=array(); $news=array(); $list=array(); $tmplist=array(); $redlist=array(); $advid=0; $advsid=0; $cnt=1; $ban6=1;	
	
	/*Surikat*/ 
	$q="SELECT `[table]`.`id`, `[table]`.`name`, `[table]`.`lid`, `[table]`.`tavto`, `[table]`.`pic`,`[table]`.`data`, '[link]' as `link` FROM `[table]` WHERE (`[table]`.`stat`='1' && `[table]`.`data`>'".(time()-1*24*60*60)."' && `[table]`.`spromo`=1 [used])"; $endq="ORDER BY `data` DESC LIMIT 1"; $data=getNewsFromLentas($q, $endq);
	if ($data["total"]==1) { @mysql_data_seek($data["result"], 0); $ar=@mysql_fetch_array($data["result"]); $ar["style"]="NorN"; $cnt++; $ar["linked"]="/".$ar["link"]."/view/".$ar["id"]; $ar["data"]=''; if ($ar["pic"]!="" && $ar["tavto"]==1) { $ar["pic"]=$src."/userfiles/pictavto/".$ar["pic"]; } else { $ar["pic"]=""; } if ($ar["link"]!="ls") { $list[]=$ar; }}

	/*PodSurikat*/
	$q="SELECT `[table]`.`id`, `[table]`.`name`, `[table]`.`lid`, `[table]`.`tavto`,`[table]`.`data`, `[table]`.`pic`, '[link]' as `link` FROM `[table]` WHERE (`[table]`.`stat`='1' && `[table]`.`data`>'".(time()-1*24*60*60)."' && `[table]`.`promo`=1 [used])"; $endq="ORDER BY `data` DESC"; $data=getNewsFromLentas($q, $endq);
	for ($i=0; $i<$data["total"]; $i++) { @mysql_data_seek($data["result"], $i); $ar=@mysql_fetch_array($data["result"]); $ar["style"]="ReOneOrder"; $ar["data"]=''; $ar["linked"]="/".$ar["link"]."/view/".$ar["id"]; if ($ar["pic"]!="" && $ar["tavto"]==1) { $ar["pic"]=$src."/userfiles/pictavto/".$ar["pic"]; } else { $ar["pic"]=""; } if ($ar["link"]!="ls") { $avd[]=$ar; }} 
	
	/*Staruhi*/
	$q="SELECT `[table]`.`id`, `[table]`.`name`, `[table]`.`lid`, `[table]`.`tavto`,`[table]`.`data`, `[table]`.`pic`, '[link]' as `link` FROM `[table]` WHERE (`[table]`.`stat`='1' && `[table]`.`data`<'".(time()-7*24*60*60)."' && `[table]`.`data`>'".(time()-11*24*60*60)."' && (`[table]`.`promo`=1 || `[table]`.`spromo`=1) [used])"; $endq="ORDER BY `data` DESC LIMIT 30"; $data=getNewsFromLentas($q, $endq);
	for ($i=0; $i<$data["total"]; $i++) { @mysql_data_seek($data["result"], $i); $ar=@mysql_fetch_array($data["result"]); $ar["style"]="Oldest"; $ar["data"]=''; $ar["linked"]="/".$ar["link"]."/view/".$ar["id"]; if ($ar["pic"]!="" && $ar["tavto"]==1) { $ar["pic"]=$src."/userfiles/pictavto/".$ar["pic"]; } else { $ar["pic"]=""; } if ($ar["link"]!="ls") { $avds[]=$ar; }}
	
	$q="SELECT `[table]`.`id`, `[table]`.`name`, `[table]`.`lid`, `[table]`.`tavto`,`[table]`.`data`, `[table]`.`comcount`, `[table]`.`pic`, '[link]' as `link` FROM `[table]` WHERE (`[table]`.`stat`='1' && `[table]`.`redak`=1 [used])"; $endq="ORDER BY `data` DESC LIMIT 60"; $data=getNewsFromLentas($q, $endq);
	$sc=0; for ($i=0; $i<$data["total"]; $i++) { @mysql_data_seek($data["result"], $i); $ar=@mysql_fetch_array($data["result"]); $ar["style"]="Editors"; $ar["linked"]="/".$ar["link"]."/view/".$ar["id"]; if ($ar["pic"]!="" && $ar["tavto"]==1) { $ar["pic"]=$src."/userfiles/pictavto/".$ar["pic"]; } else { $ar["pic"]=""; }
	if ($sc<4 && $ar["link"]!="ls") { $redlist[]=$ar; $sc++; } else { $tmplist[]=$ar; }} 
		
	#$xml = simplexml_load_file("http://bubr.ru/prokazan_news.xml"); $bubr=array(); if (!empty($xml)) { $count_str = count($xml->channel->item); $i=0; while ($i <= ($count_str-1)): $item=$xml->channel->item[$i];
	#if ((int)$bubr["adv"]!=1) { $bubr["name"]=(string)$item->title; $bubr["link"]=(string)$item->link; $bubr["linked"]=(string)$item->link; $bubr["pic"]=(string)$item->picmiddle; $bubr["data"]=(string)$item->data; $bubr["lid"]=(string)$item->ttwo; $ar["style"]="Bubr"; $tmplist[]=$bubr; } $i++; endwhile; }

	usort($tmplist, ArraySort);
	
	foreach ($redlist as $ar) { $list[]=$ar;
		if (($cnt+1)%4==0) {
			if ($avd[$advid]["name"]!="") { $list[]=$avd[$advid]; $advid++; $cnt++; /*PodSurikat*/
			} else { if ($avds[$advsid]["name"]!="") { $list[]=$avds[$advsid]; $advsid++; $cnt++; /*Staruhi*/ }}
		}
	$cnt++; } 
	
	foreach ($tmplist as $ar) { $list[]=$ar;
		if (($cnt+1)%4==0) {
			if ($avd[$advid]["name"]!="") { $list[]=$avd[$advid]; $advid++; $cnt++; /*PodSurikat*/
			} else { if ($avds[$advsid]["name"]!="") { $list[]=$avds[$advsid]; $advsid++; $cnt++; /*Staruhi*/ }}
		}
	$cnt++; }
	
	$cnt=1; foreach($list as $ar) {
		if (strpos($ar["link"], "ls")!==false || strpos($ar["link"], "bubr")!==false) { $rel="target='_blank' rel='nofollow'"; } else { $rel=""; }
		$text.="<div class='RedNews ".$ar["style"]."'>";
			if ($ar["pic"]!="") { $text.="<div class='img'><a href='".$ar["linked"]."' $rel><img src='".$ar["pic"]."'></a></div><div class='stext'>"; } else { $text.="<div class='ftext'>"; }
			$text.="<a href='".$ar["linked"]."' class='caption' $rel>".$ar["name"]."</a>"; if ($ar["lid"]!="") { $text.="<div class='lid'>".$ar["lid"]."</div>"; } $text.=$C.Dater($ar);
		$text.="</div></div>".$C30;
		if ($cnt%4==0) {
			if ($ban6<10) { $text.="<div class='banner2' id='Banner-6-".$ban6."'></div>"; $ban6++; }
		}
	$cnt++; } return $text;
}

### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ### --- ###

function RIGHT() {
	global $used, $C, $C10, $C20, $C25, $lentas, $src, $yandex1, $yandex2; $ban10=2;
	$text="<div class='banner' id='Banner-1-1'></div>";
	// --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---

	/*PodSurikat*/
	$q="SELECT `[table]`.`id`, `[table]`.`name`, `[table]`.`lid`, `[table]`.`tavto`,`[table]`.`data`, `[table]`.`pic`, '[link]' as `link` FROM `[table]` WHERE (`[table]`.`stat`='1' && `[table]`.`data`<'".(time()-2*24*60*60)."' && `[table]`.`data`>'".(time()-5*24*60*60)."' && (`[table]`.`promo`=1 || `[table]`.`spromo`=1) [used])";
	$endq="ORDER BY `data` DESC LIMIT 6"; $data=getNewsFromLentas($q, $endq); $list=array(); $cnt=1; if ((int)$data["total"]>0) { for ($i=0; $i<$data["total"]; $i++) { @mysql_data_seek($data["result"], $i); $ar=@mysql_fetch_array($data["result"]); $list[]=$ar; }
	foreach($list as $ar) { if ($ar["link"]!="ls") { $text.="<div class='OCNew ReTwoOrder'>"; $text.="<a href='/".$ar["link"]."/view/".$ar["id"]."'>"; if ($ar["tavto"]==1 && $ar["pic"]!="") { $text.="<img src='$src/userfiles/picsquare/".$ar["pic"]."'>"; } $text.=$ar["name"]."</a>"; $text.="</div>".$C25; $cnt++; }}} else { $text.=$yandex1; }
	
	$text.=$C10."<div class='banner3' id='Banner-10-".$ban10."'></div>"; $ban10=$ban10+2;
	
	$q="SELECT `[table]`.`id`, `[table]`.`name`, `[table]`.`data`, '1' as `tavto`, `[table]`.`lid`, `[table]`.`pic`, '[link]' as `link` FROM `[table]` WHERE (`[table]`.`stat`='1' && `[table]`.`data`<'".(time()-2*24*60*60)."' && `[table]`.`data`>'".(time()-5*24*60*60)."' && `[table]`.`promo`=1 [used])";
	$endq="ORDER BY `data` DESC LIMIT 6, 6"; $data=getNewsFromLentas($q, $endq); $list=array(); $cnt=1; if ((int)$data["total"]>0) { for ($i=0; $i<$data["total"]; $i++) { @mysql_data_seek($data["result"], $i); $ar=@mysql_fetch_array($data["result"]); $list[]=$ar; }
	foreach($list as $ar) { if ($ar["link"]!="ls") { if (strpos($ar["link"], "ls")!==false || strpos($ar["link"], "bubr")!==false) { $rel="target='_blank' rel='nofollow'"; } else { $rel=""; } $text.="<div class='OCNew ReTwoOrder'>"; $text.="<a href='/".$ar["link"]."/view/".$ar["id"]."' $rel>"; if ($ar["tavto"]==1 && $ar["pic"]!="") { $text.="<img src='$src/userfiles/picsquare/".$ar["pic"]."'>"; } $text.=$ar["name"]."</a>"; $text.="</div>".$C25; $cnt++; }}} else { $text.=$yandex2; }		
	
	$text.=$C10."<div class='banner3' id='Banner-10-".$ban10."'></div>"; $ban10=$ban10+2;
	$text.="<h3>Самое популярное</h3>".getMaxSeens();
	$text.=$C10."<div class='banner3' id='Banner-10-".$ban10."'></div>"; $ban10=$ban10+2;
	
	
	$text.="<h3>Самое комментируемое</h3>".getMaxComments();
	$text.=$C10."<div class='banner3' id='Banner-10-".$ban10."'></div>"; $ban10=$ban10+2;
	$text.=$C10."<div class='banner3' id='Banner-10-".$ban10."'></div>"; $ban10=$ban10+2;
	$text.="<h3>Выбор читателей</h3>".getMaxLikes();
			
	// --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
	return $text;
}
?>