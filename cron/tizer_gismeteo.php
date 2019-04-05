<?php 

$filecache="gismeteosamara.cache";
$picpath="http://progorodsamara.ru/sites/default/files/imagecache/tizer_75_56/images/";
$dbloc="localhost"; $dbname="admin_samara"; $dbuser="admin_samara"; $dbpass="Sk8xQkn";

if (is_file($filecache)) { $nowt=time(); $filet=filemtime($filecache); if (($nowt-$filet)>1800) { unlink($filecache); Create(); }else{ echo(file_get_contents($filecache)); }}else{ Create(); } 

function Create() {
	global $filecache, $picpath, $dbloc, $dbname, $dbuser, $dbpass;
	$dbcnx = @mysql_connect($dbloc,$dbuser,$dbpass); if (!$dbcnx) { echo("<P>BD error 1</P>" ); exit();} if (!@mysql_select_db($dbname,$dbcnx)) { echo("<P>BD Error 2.</P>" ); exit(); } 

	$sql="SELECT n.nid AS nid, n.title AS title,n. created AS cda, f.filename AS ifile
	FROM node n INNER JOIN node_revisions nr INNER JOIN content_field_image i INNER JOIN files f ON n.nid = nr.nid AND n.nid = i.nid AND i.field_image_fid = f.fid 
	LEFT JOIN  content_type_news_v_2 ctn ON ctn.nid = n.nid 
	WHERE (n.STATUS=1) 
	AND n.type='news_v_2'
	AND (ctn.field_gismeteo2_value='on')
	ORDER BY created DESC LIMIT 5"; 

	$newsList=mysql_query($sql); $totm=@mysql_num_rows($newsList);
	$Content='<html><head><meta http-equiv="Content-Type" content="text/html; charset=windows-1251" /><style>* { margin:0; padding:0; } .progorodsamara_title { color:#000; font-size:10px; text-decoration:none; font-family:Verdana; } .progorodsamara img { border:2px solid #EEE; padding:0; width:70px; height:50px; float:left; margin:0 12px 7px 3px; } .progorodsamara { width:240px; height:50px; } .progorodsamaral { clear:both; font-size:3px; line-height:3px; height:4px; margin-bottom:0px; }</style></head><body><a href="http://progorodsamara.ru/" style="float:none;" target="_blank"><img style="border:none; margin-bottom:9px; width:240px; height:35px;" src="http://progorodsamara.ru/export/topimagesamara.jpg" title="��������� ������ ������ ProgorodSamara.ru"/></a>';
	for ($i=0; $i<$totm; $i++) { $node=mysql_fetch_object($newsList); 
		$Content.='<div class="progorodsamara"><a href="http://progorodsamara.ru/node/'.$node->nid.'" target="_blank"><img src="'.$picpath.$node->ifile.'" alt="'.$node->title.'" title="'.$node->title.'"/></a><a href="http://progorodsamara.ru/node/'.$node->nid.'" target="_blank" class="progorodsamara_title">'.$node->title.'</a></div>';
		if ($i!=($totm-1)) { $Content.='<div class="progorodsamaral"></div>'; }
	};
	$Content.='<div style="clear:both;"></div><a href="http://progorodsamara.ru/" style="float:none;" target="_blank"><img style="border:none; margin-top:8px; width:240px; height:26px;" src="http://progorodsamara.ru/export/bottomimagesamara.jpg" alt="��������� ������ ������ ProgorodSamara.ru" title="��������� ������ ������ ProgorodSamara.ru" /></a></body></html>';
	echo $Content; $fs=fopen($filecache,"w"); fwrite($fs, $Content); fclose($fs);
}
?>




