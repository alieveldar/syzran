function GetItemVoting(qid) { JsHttpRequest.query('/modules/lenta/GetVoting-JSReq.php',{'qid':qid },function(result,errors){ $("#ItemVotingDiv").html(result.text); },true); }
function voteSavelenta(qid, nodeId, link){ var vid = $('#ItemVotingDiv input:checked').val(); if(vid){ $("a, span", $("#ItemVotingDiv .votingButton")).toggle(); JsHttpRequest.query('/modules/lenta/VoteSave-JSReq.php',{'vid':vid, 'qid':qid, 'pid':nodeId, 'link':link},function(result,errors){ $("#ItemVotingDiv").html(result.text);  },true); } else { alert('Вы не выбрали ответ'); }}
function GetItemLikes(nodeId, link) { JsHttpRequest.query('/modules/lenta/GetLikes-JSReq.php',{'pid':nodeId,'link':link},function(result,errors){ $("#ItemLikesDiv").html(result.text); },true); }
function likeSavelenta(qid, nodeId, link){ $("#ItemLikesDiv").html("<img src='/template/standart/loader.gif' style='margin:15px 40px;'>"); JsHttpRequest.query('/modules/lenta/LikeSave-JSReq.php',{'qid':qid,'pid':nodeId,'link':link},function(result,errors){ $("#ItemLikesDiv").html(result.text); console.log(result.log); },true); }
function showmorelis() { $("#morelibtn").slideUp(300); $(".hiddenlis").slideDown(300); }
function initMap(ev){
	center = ev[2].split(','); var Map = new DG.Map('Map'); Map.setCenter(new DG.GeoPoint(center[0],center[1]), 14); Map.controls.add(new DG.Controls.Zoom());
	var id = ev[0]; var name = ev[1]; var icon = ev[3];
	var marker = new DG.Markers.Common({
    	geoPoint: new DG.GeoPoint(center[0],center[1]),	icon : icon ? new DG.Icon(icon, new DG.Size(35, 35), function() { return new DG.Point(-7, -51)} ) : null, hint : name,	    	
    	clickCallback : function(clickEvent, marker){
    		var this_ = this; var iconurl = this_.getIcon().url; var markerId = this_.getContainerId(); var icon = $('#'+markerId).attr('data-icon'); var eventId = $('#'+markerId).attr('data-event');
			if(icon){ $('#'+markerId).parent().addClass('custom'); this_.setIcon(new DG.Icon('/template/standart/loader00.gif', new DG.Size(35, 35), function() { return new DG.Point(-7, -51)} ));	}
    		JsHttpRequest.query('/modules/eventmap/GetEvent-JSReq.php',{'id':eventId},function(result,errors){ 					
				if(icon) this_.setIcon(new DG.Icon(iconurl, new DG.Size(35, 35), function() { return new DG.Point(-7, -51)} ));
				else $('#'+markerId).parent().removeClass('custom');
				ViewBlank(result['name'], result['text']);
			},true);
    	}
    });
	Map.markers.add(marker); var markerId = marker.getContainerId(); $('#'+markerId).attr('data-event', id); if(icon){ $('#'+markerId).parent().addClass('custom'); $('#'+markerId).attr('data-icon', 1); }            
}

function totwetter() { $('.totwitter').each(function(){ var text=$(this).html(); var link=document.location;	var image = "<span class='tweet' onclick=\"socialtweet('"+text+"','"+link+"');\"><img src='/template/standart/totwitter.png'> Ретвит</span>";	$(this).html("<span>"+text+"</span>"+image); }); }
$(function() {  if ($(".ArticleContent")) { totwetter(); } }); function socialtweet(text, link) { link = encodeURIComponent(link); text.replace(/\s{2,}/g, ' '); text = encodeURIComponent($.trim(text)); var top = parseInt($(window).height()/2-225); var left = parseInt($(window).width()/2 - 225);
var url='https://twitter.com/intent/tweet?text='+text+'&url='+link; window.open(url, "displayWindow", "width=450, height=450, top=" + top + ", left=" + left + " location=0, status=0, toolbar=0, menubar=0, scrollbars=0, resizable=0"); return false; }
