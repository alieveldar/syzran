function initMap(center, events){
	center = center.split(',');
	var Map = new DG.Map('Map');
    Map.setCenter(new DG.GeoPoint(center[0],center[1]), 12);
    Map.controls.add(new DG.Controls.Zoom());
    
    for(i = 0; i < events.length; i++){	
    	var id = events[i][0];
    	var name = events[i][1];
    	var coords = events[i][2].split(',');
    	var icon = events[i][3]; 
    	var marker = new DG.Markers.Common({
	    	geoPoint: new DG.GeoPoint(coords[0],coords[1]),
	    	icon : icon ? new DG.Icon(icon, new DG.Size(35, 35), function() { return new DG.Point(-7, -51)} ) : null,
	    	hint : name,	    	
	    	clickCallback : function(clickEvent, marker){
	    		var this_ = this;
	    		var iconurl = this_.getIcon().url;
	    		var markerId = this_.getContainerId();
	    		var icon = $('#'+markerId).attr('data-icon');
	    		var eventId = $('#'+markerId).attr('data-event');
				if(icon){
					$('#'+markerId).parent().addClass('custom'); 
		    		this_.setIcon(new DG.Icon('/template/standart/loader00.gif', new DG.Size(35, 35), function() { return new DG.Point(-7, -51)} ));
		    	}
	    		JsHttpRequest.query('/modules/eventmap/GetEvent-JSReq.php',{'id':eventId, 'readmore' : 1},function(result,errors){ 					
					if(icon) this_.setIcon(new DG.Icon(iconurl, new DG.Size(35, 35), function() { return new DG.Point(-7, -51)} ));
					else $('#'+markerId).parent().removeClass('custom');
					ViewBlank(result['name'], result['text']);
				},true);
	    	}
	    });
		Map.markers.add(marker);
		var markerId = marker.getContainerId();
		$('#'+markerId).attr('data-event', id);
		if(icon){
			$('#'+markerId).parent().addClass('custom');
			$('#'+markerId).attr('data-icon', 1);
		}
    }              
}