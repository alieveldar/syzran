<?
$file="_index-traffic"; $VARS["cachepages"] = 0; if (RetCache($file)=="true") { list($text, $cap)=GetCache($file, 0); } else { list($text, $cap)=Traffic(); SetCache($file, $text, ""); }
$Page["Content"]='<div class="WhiteBlock" style="padding:0 !important;">'.$text.'</div>'.$C20.$Page["Content"];

function Traffic() { $C=""; 
	$text='<script src="//api-maps.yandex.ru/2.0/?load=package.full&lang=ru-RU" type="text/javascript"></script><script type="text/javascript">ymaps.ready(init); function init () {
	var myMap = new ymaps.Map("map", { center: [53.161766,48.471910], zoom: 16 }); var actualProvider = new ymaps.traffic.provider.Actual({}, { infoLayerShown: true }); actualProvider.setMap(myMap);
	myMap.controls.add("zoomControl", { left: 5, top: 5 }).add("typeSelector").add("mapTools", { left: 35, top: 5 }); var trafficControl = new ymaps.control.TrafficControl({ providerKey: "traffic#actual", shown: true });
	myMap.controls.add(trafficControl); trafficControl.getProvider("traffic#actual").state.set("infoLayerShown", true); }</script><div id="map" style="width:100%; height:620px"></div>';
	return (array($text, $C));
}
$Page["RightContent"]=$C20.$C20.'';
?>