<?	
$file="_leftblock-SMddefault"; if (RetCache($file, "cacheblock")=="true") { list($Page["LeftContent"], $cap)=GetCache($file, 0); } else { list($Page["LeftContent"], $cap)=CreateLeftBlock(); SetCache($file, $Page["LeftContent"], "", "cacheblock"); }

function CreateLeftBlock() {
	global $Domains, $SubDomain, $GLOBAL, $C20, $C10, $C25, $C, $used; $text=''; $src=""; $advs=array(); $news=array(); $list=array(); $tmplist=array(); $advid=0; $advsid=0; $cnt=1;	
	// --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
		
		/*TV*/ $q="SELECT `[table]`.`id`, `[table]`.`name`, `[table]`.`tavto`, `[table]`.`lid`, `[table]`.`data`, `[table]`.`comcount`, `[table]`.`pic`, '[link]' as `link` FROM `[table]` WHERE (`[table]`.`stat`='1' && `[table]`.`onind`=1 [used])";
		$endq="ORDER BY `data` DESC LIMIT 1"; $data=getNewsFromLentas($q, $endq); for ($i=0; $i<$data["total"]; $i++): @mysql_data_seek($data["result"], $i); $ar=@mysql_fetch_array($data["result"]); $used[$ar["link"]][]=$ar["id"];
		$text.="<div class='ONew'><a href='/".$ar["link"]."/view/".$ar["id"]."'>"; if ($i==0 && $ar["pic"]!="") { $text.="<img src='$src/userfiles/pictavto/".$ar["pic"]."'>"; } $text.=$ar["name"]."</a>".$C.Dater($ar)."</div>".$C20; endfor;
		
		/*OLD PROMO*/ $q="SELECT `[table]`.`id`, `[table]`.`name`, `[table]`.`lid`, `[table]`.`tavto`,`[table]`.`data`, `[table]`.`pic`, '[link]' as `link` FROM `[table]` WHERE (`[table]`.`stat`='1' && `[table]`.`data`<'".(time()-6*24*60*60)."' && `[table]`.`data`>'".(time()-11*24*60*60)."' && (`[table]`.`promo`=1 || `[table]`.`spromo`=1) [used])";
		$endq="ORDER BY `data` DESC"; $data=getNewsFromLentas($q, $endq); for ($i=0; $i<$data["total"]; $i++) { @mysql_data_seek($data["result"], $i); $ar=@mysql_fetch_array($data["result"]); 
		if ($ar["link"]!="ls") { $ar["style"]="Oldest"; $ar["data"]=''; $ar["link"]="/".$ar["link"]."/view/".$ar["id"]; if ($ar["pic"]!="" && $ar["tavto"]==1) { $ar["pic"]=$src."/userfiles/pictavto/".$ar["pic"]; } else { $ar["pic"]=""; } $avds[]=$ar; }}

		/*NEWS*/$q="SELECT `[table]`.`id`, `[table]`.`name`, `[table]`.`lid`, `[table]`.`tavto`,`[table]`.`data`, `[table]`.`comcount`, `[table]`.`pic`, '[link]' as `link` FROM `[table]` WHERE (`[table]`.`stat`='1' [used])"; $endq="ORDER BY `data` DESC LIMIT 50"; $data=getNewsFromLentas($q, $endq); for ($i=0; $i<$data["total"]; $i++) { @mysql_data_seek($data["result"], $i); $ar=@mysql_fetch_array($data["result"]); $ar["style"]="Editors"; $ar["link"]="/".$ar["link"]."/view/".$ar["id"]; if ($ar["pic"]!="" && $ar["tavto"]==1) { $ar["pic"]=$src."/userfiles/pictavto/".$ar["pic"]; } else { $ar["pic"]=""; } $tmplist[]=$ar; } 
			
		#$xml = simplexml_load_file("http://bubr.ru/prokazan_news.xml"); $bubr=array(); if (!empty($xml)) { $count_str = count($xml->channel->item); $i=0; while ($i <= ($count_str-1)): $item=$xml->channel->item[$i];
		#if ((int)$bubr["adv"]!=1) { $bubr["name"]=(string)$item->title; $bubr["link"]=(string)$item->link; $bubr["pic"]=(string)$item->picmiddle; $bubr["data"]=(string)$item->data;
		#$bubr["lid"]=(string)$item->ttwo; $ar["style"]="Bubr"; $tmplist[]=$bubr; } $i++; endwhile; }

		usort($tmplist, ArraySort); foreach ($tmplist as $ar) { $list[]=$ar; if (($cnt+1)%4==0) { if ($avds[$advsid]["name"]!="") { $list[]=$avds[$advsid]; $advsid++; $cnt++; /*Staruhi*/ }} $cnt++; }
		
		$cnt=1; $ban10=1; foreach($list as $ar) {
		if (strpos($ar["link"], "ls")!==false || strpos($ar["link"], "bubr")!==false) { $rel="target='_blank' rel='nofollow'"; } else { $rel=""; }
		$text.="<div class='ONew'>";
			$text.="<a href='".$ar["link"]."' $rel>";
			$text.=$ar["name"]."</a>".$C.Dater($ar);
		$text.="</div>".$C20;
		if ($cnt%4==0) {
			if ($ban10<10) { $text.="<div class='banner3' id='Banner-10-".$ban10."'></div>"; $ban10=$ban10+2; }
		} 
		$cnt++; }
				
	// --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
	$text.="<div class='C10'></div>"; return(array($text, ""));
}
?>