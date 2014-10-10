/**
* license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
* author Tingxi Tan, Grid Research Centre [txtan@cpsc.ucalgary.ca]
* link http://grc.ucalgary.ca/
*/


var map,batch,batchinfo,panInterval;
var panindex = 0;

function load_map(mapdiv,centre_lat,centre_lng,zoom,mapcontrol){
	//usual google maps start up stuff
	if(GBrowserIsCompatible()) {
        	map = new GMap2(document.getElementById(mapdiv));
		map.setCenter(new GLatLng(centre_lat,centre_lng), zoom);
		map.setMapType(G_HYBRID_MAP);
		map.enableScrollWheelZoom();
		if(mapcontrol == true) map.addControl(new GLargeMapControl());
	
	}
}

function plot_content(json_url,timer,panning){
	//plot markers
	if(panning == null){
	//default for panning is true
		panning = true;
	}
	$.ajax({
	url:json_url,
	dataType:'json',
	success:function(json){
		//fetched json, now add markers 
		batch = [];
		batchinfo = [];
		var users = json.content;
		for(var i = 0; i < users.length; i++){
			name = users[i].name;
			point = new GLatLng(parseFloat(users[i].lat),parseFloat(users[i].lng));
			infowindowhtml = users[i].info;
			iconurl = users[i].icon;
			cmarker = create_marker(name, point, infowindowhtml,iconurl);
			if(infowindowhtml != null){
				batchinfo.push(infowindowhtml);
				batch.push(cmarker);
			}
			map.addOverlay(cmarker);
		}
		if(panning == true && batch.length > 0){
			//set panning function if pan
			pan();
			panInterval = setInterval("pan()",timer);
		}
	},
	})
}


function pan(){
	//panning function 
	if(panindex == batch.length){panindex = 0;}
	map.panTo(batch[panindex].getLatLng());
	html = batchinfo[panindex];
	batch[panindex].openInfoWindowHtml(html);		
	panindex++;
}

function create_marker(name, point, infowindowhtml,icon){
	var colIcon = new GIcon(G_DEFAULT_ICON);
	if(icon != null){
		colIcon.image = icon;
		colIcon.iconSize = new GSize(30,30);
	}
		
	var markerOptions = {icon:colIcon};
	var marker = new GMarker(point, markerOptions);
	if(infowindowhtml != null){
		GEvent.addListener(marker, "click", function(){
		map.panTo(point);
		//after a user clicks, toggle panning off
		clearInterval(panInterval);
		marker.openInfoWindowHtml(infowindowhtml);
		});
	}
	return marker;
}
