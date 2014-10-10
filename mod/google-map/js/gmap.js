$.gmap = {
    debug: false,
    maps: {},
    markers: {},
    scan_markers: function() {
     $('.gmapped').each(function() {
         for (var key in $.gmap.maps) {
           $.gmap.mark($.gmap.maps[key], this);
         }
       });
     },
    mark: function(map, m) {
        if (!map || !m) {
            // FIXME: map should never be null 
            // is null after editing widget settings
            return;
        } 
        if (!m.latlng) {
            if (!(m = $.gmap.readLocation(map, m))) {
                return;
            }
        } 
        var marker = new GMarker(m.latlng);
        var old = $.gmap.markers[m.latlng];
        if (old) {
          map.removeOverlay(old);
        }
        $.gmap.markers[m.latlng] = marker;
        map.addOverlay(marker);
        if (m.txt) {
            GEvent.addListener(marker, "click", function() {
                marker.openInfoWindowHtml(m.txt);
            });
            if (m.visible) {
              marker.openInfoWindowHtml(m.txt);
            }
        }
    },
    readLocation: function(map, elem) {
        var latlng = $(elem).attr("latlng");
        var txt = $(elem).html();
        if (latlng) {
            return { latlng:latlng, txt:txt };
        } 
        var address = $(elem).attr("address");
        if (address) {
          $.gmap.geocoder.getLatLng(address, function(point) {
              if (point) {
                  // TODO: cache address lookup results
                  var origin = $(elem).attr('origin');
                  if (origin) {
                      $('#' + origin).click(function() {
                          map.setCenter(point);
                          $.gmap.markers[point].openInfoWindowHtml(txt);
                      }); 
                  }
                  var obj = {latlng:point, txt:txt };
                  $.gmap.mark(map, obj);
              }
              else {
                // TODO: notify app to clean up the address?
                // TODO: maybe try a different service?  allow user fix?
                if ($.gmap.debug) {
                  alert("Can't find address: " + address);
                }
              }
          });
        }
        return null;
    },
    mapCount: 1
};

$.fn.gmap = function(address, zoom, options) {
    // If we aren't supported, we're done
    if (!window.GBrowserIsCompatible || !GBrowserIsCompatible()) {
        return this;
    }

    if (!address) {
        address = "<?php echo GMAP_DEFAULT_LOCATION; ?>";
    }
    if (zoom === undefined) {
        zoom = <?php echo GMAP_DEFAULT_ZOOM; ?>;
    }
    if (!$.gmap.geocoder) {
        $.gmap.geocoder = new GClientGeocoder();
    }

    // Sanitize options
    if (!options || typeof options != 'object') {
        options = {};
    }
    options.mapOptions = options.mapOptions || {};
    options.markers = options.markers || [];
    options.controls = options.controls || {};

    // Map all our elements
    return this.each(function() {
        // Make sure we have a valid id
        if (!this.id) {
            this.id = GMAP_DEFAULT_ID + $.gmap.mapCount++;
        }
        // Create a map and a shortcut to it at the same time
        $.gmap.maps[this.id] = new GMap2(this, options.mapOptions);
        var map = $.gmap.maps[this.id];
        
        if (typeof(address) == 'object') {
            var lat = ((typeof(address.lat) == 'function')
                ? address.lat() : address.lat);
            var lng = ((typeof(address.lng) == 'function')
                ? address.lng() : address.lng);
            map.setCenter(new GLatLng(lat, lng), zoom);
        }
        else {
            map.setCenter(new GLatLng(0, 0), 0);
            var id = this.id;
            $.gmap.geocoder.getLatLng(address, function(point) {
                if (point) {
                    var map = $.gmap.maps[id];
                    map.setCenter(point, zoom);
                }
                else {
                    // TODO: notify app of error 
                    if ($.gmap.debug) {
                        alert("<?php echo sprintf(elgg_echo('gmap:notfound'), "--LOC--"); ?>".replace("--LOC--", address));
                    }
                }
            });
        }
        // Add controls to our map
        var i;
        for (i = 0; i < options.controls.length; i++) {
            var c = options.controls[i];
            eval("map.addControl(new " + c + "());");
        }
        // If we have markers, put them on the map
        for (i = 0; i < options.markers.length; i++) {
            $.gmap.mark(map, options.markers[i]);
        }
        // Scan the rest of the page for markers
        $.gmap.scan_markers();
    });
};

$(window).unload(function() { GUnload(); });
