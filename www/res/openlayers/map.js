var map;
var thisLayer;
var proj_4326 = new OpenLayers.Projection('EPSG:4326');
var proj_900913 = new OpenLayers.Projection('EPSG:900913');
var vlayer;
var highlightCtrl;
var selectCtrl;
var selectedFeatures = [];
function initEditMap(settings){
    
    var defaultSettings = {
        mapContainer:"mapContainer",
        editContainer:"editContainer",
        fieldName:"location"
    
    };
    settings = jQuery.extend(defaultSettings, settings);		
    
    var options = {
        units: "dd",
        numZoomLevels: 18, 
        controls:[],
        theme: false,
        projection: proj_900913,
        'displayProjection': proj_4326,
        /* eventListeners: {
            "zoomend": incidentZoom
        },*/
        maxExtent: new OpenLayers.Bounds(-20037508.34, -20037508.34, 20037508.34, 20037508.34),
        maxResolution: 156543.0339
    };
   
    // Now initialise the map
    map = new OpenLayers.Map(settings.mapContainer, options);
			
    var google_satellite = new OpenLayers.Layer.Google("Google Maps Satellite", { 
        type: google.maps.MapTypeId.SATELLITE,
        animationEnabled: true,
        sphericalMercator: true,
        maxExtent: new OpenLayers.Bounds(-20037508.34,-20037508.34,20037508.34,20037508.34)
    });

    var google_hybrid = new OpenLayers.Layer.Google("Google Maps Hybrid", { 
        type: google.maps.MapTypeId.HYBRID,
        animationEnabled: true,
        sphericalMercator: true,
        maxExtent: new OpenLayers.Bounds(-20037508.34,-20037508.34,20037508.34,20037508.34)
    });

    var google_normal = new OpenLayers.Layer.Google("Google Maps Normal", { 
        animationEnabled: true,
        sphericalMercator: true,
        maxExtent: new OpenLayers.Bounds(-20037508.34,-20037508.34,20037508.34,20037508.34)
    });

    var google_physical = new OpenLayers.Layer.Google("Google Maps Physical", { 
        type: google.maps.MapTypeId.TERRAIN,
        animationEnabled: true,
        sphericalMercator: true,
        maxExtent: new OpenLayers.Bounds(-20037508.34,-20037508.34,20037508.34,20037508.34)
    });

    map.addLayers([google_normal,google_satellite,google_hybrid,google_physical]);
    map.addControl(new OpenLayers.Control.Navigation());
    map.addControl(new OpenLayers.Control.Zoom());
    map.addControl(new OpenLayers.Control.MousePosition());
    map.addControl(new OpenLayers.Control.ScaleLine());
    map.addControl(new OpenLayers.Control.Scale('mapScale'));
    map.addControl(new OpenLayers.Control.LayerSwitcher());
    
    // Vector/Drawing Layer Styles
    style1 = new OpenLayers.Style({
        pointRadius: "8",
        fillColor: "#ffcc66",
        fillOpacity: "0.7",
        strokeColor: "#CC0000",
        strokeWidth: 2.5,
        graphicZIndex: 1,
        externalGraphic: "res/openlayers/img/marker.png",
        graphicOpacity: 1,
        graphicWidth: 21,
        graphicHeight: 25,
        graphicXOffset: -14,
        graphicYOffset: -27
    });
    style2 = new OpenLayers.Style({
        pointRadius: "8",
        fillColor: "#30E900",
        fillOpacity: "0.7",
        strokeColor: "#197700",
        strokeWidth: 2.5,
        graphicZIndex: 1,
        externalGraphic: "res/openlayers/img/marker-green.png",
        graphicOpacity: 1,
        graphicWidth: 21,
        graphicHeight: 25,
        graphicXOffset: -14,
        graphicYOffset: -27
    });
    style3 = new OpenLayers.Style({
        pointRadius: "8",
        fillColor: "#30E900",
        fillOpacity: "0.7",
        strokeColor: "#197700",
        strokeWidth: 2.5,
        graphicZIndex: 1
    });
			
    var vlayerStyles = new OpenLayers.StyleMap({
        "default": style1,
        "select": style2,
        "temporary": style3
    });
                        
    vlayer = new OpenLayers.Layer.Vector( "Editable", {
        styleMap: vlayerStyles,
        rendererOptions: {
            zIndexing: true
        }
    });
    map.addLayer(vlayer);
			
    var endDragfname  = settings.fieldName+"_endDrag"
    var code = ""
    //code += "function "+endDragfname+"(feature, pixel) {";
    code +="for (f in selectedFeatures) {";
    code +="    f.state = OpenLayers.State.UPDATE;";
    code +="}";
    code +="refreshFeatures(\""+settings.fieldName+"\");";
			
    code +="var latitude = parseFloat(jQuery('input[name=\""+settings.fieldName+"_latitude\"]').val());"
    code +="var longitude = parseFloat(jQuery('input[name=\""+settings.fieldName+"_longitude\"]').val());"
			
    code +="reverseGeocode(latitude, longitude,\""+settings.fieldName+"\");"
    //code +="}";
    var endDragf = new Function(code);
    
    // Drag Control
    var drag = new OpenLayers.Control.DragFeature(vlayer, {
        onStart: startDrag,
        onDrag: doDrag,
        onComplete: endDragf
    });
    map.addControl(drag);
			
    // Vector Layer Events
    vlayer.events.on({
        beforefeaturesadded: function(event) {
        //for(i=0; i < vlayer.features.length; i++) {
        //	if (vlayer.features[i].geometry.CLASS_NAME == "OpenLayers.Geometry.Point") {
        //		vlayer.removeFeatures(vlayer.features);
        //	}
        //}
					
        // Disable this to add multiple points
        // vlayer.removeFeatures(vlayer.features);
        },
        featuresadded: function(event) {
            refreshFeatures(event,settings.fieldName);
        },
        featuremodified: function(event) {
            refreshFeatures(event,settings.fieldName);
        },
        featuresremoved: function(event) {
            refreshFeatures(event,settings.fieldName);
        }
    });
			
    // Vector Layer Highlight Features
    highlightCtrl = new OpenLayers.Control.SelectFeature(vlayer, {
        hover: true,
        highlightOnly: true,
        renderIntent: "temporary"
    });
    selectCtrl = new OpenLayers.Control.SelectFeature(vlayer, {
        clickout: true, 
        toggle: false,
        multiple: false, 
        hover: false,
        renderIntent: "select",
        onSelect: addSelected,
        onUnselect: clearSelected
    });
    map.addControl(highlightCtrl);
    map.addControl(selectCtrl);
			
                        
    // Insert Saved Geometries
    wkt = new OpenLayers.Format.WKT();
    
    if(settings.geometries){
        for(i in settings.geometries){
            wktFeature = wkt.read(settings.geometries[i]);
            wktFeature.geometry.transform(proj_4326,proj_900913);
            vlayer.addFeatures(wktFeature);
	}       			
    }else{
        // Default Point
            point = new OpenLayers.Geometry.Point(settings.longitude, settings.latitude);
            OpenLayers.Projection.transform(point, proj_4326, map.getProjectionObject());
            var origFeature = new OpenLayers.Feature.Vector(point);
            vlayer.addFeatures(origFeature);
				
    }						
			
    // Create a lat/lon object
    var startPoint = new OpenLayers.LonLat(settings.longitude, settings.latitude);
    startPoint.transform(proj_4326, map.getProjectionObject());
			
    // Display the map centered on a latitude and longitude (Google zoom levels)
    map.setCenter(startPoint, 8);
   					

			
    // Create the Editing Toolbar
    var container = document.getElementById(settings.editContainer);
    var panel = new OpenLayers.Control.EditingToolbar(
        vlayer, {
            div: container
        }
        );
    map.addControl(panel);
    panel.activateControl(panel.controls[0]);
    drag.activate();
    highlightCtrl.activate();
    selectCtrl.activate();
    
    jQuery('.'+settings.fieldName+'_locationButtonsLast').on('click', function () {
        if (vlayer.features.length > 0) {
            x = vlayer.features.length - 1;
            vlayer.removeFeatures(vlayer.features[x]);
        }
        /*jQuery('#geometry_color').ColorPickerHide();
				jQuery('#geometryLabelerHolder').hide(400);*/
        selectCtrl.activate();
        return false;
    });
			
    // Delete Selected Features
    jQuery('.'+settings.fieldName+'_locationButtonsDelete').on('click', function () {
        for(var y=0; y < selectedFeatures.length; y++) {
            vlayer.removeFeatures(selectedFeatures);
        }
        /*jQuery('#geometry_color').ColorPickerHide();
				jQuery('#geometryLabelerHolder').hide(400);*/
        selectCtrl.activate();
        return false;
    });
			
    // Clear Map
    jQuery('.'+settings.fieldName+'_locationButtonsClear').on('click', function () {
        vlayer.removeFeatures(vlayer.features);
        jQuery('input[name="'+settings.fieldName+'_geometry[]"]').remove();
        jQuery('input[name="'+settings.fieldName+'_latitude"]').val("");
        jQuery('input[name="'+settings.fieldName+'_longitude"]').val("");
        /*jQuery('#geometry_label').val("");
				jQuery('#geometry_comment').val("");
				jQuery('#geometry_color').val("");
				jQuery('#geometry_lat').val("");
				jQuery('#geometry_lon').val("");
				jQuery('#geometry_color').ColorPickerHide();
				jQuery('#geometryLabelerHolder').hide(400);*/
        selectCtrl.activate();
        return false;
    });
	
        
    // GeoCode
    jQuery('.'+settings.fieldName+'_buttonFind').on('click', function () {
        geoCode(settings.fieldName);
    });
    jQuery('#'+settings.fieldName+'_locationFind').bind('keypress', function(e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if(code == 13) { //Enter keycode
            geoCode(settings.fieldName);
            return false;
        }
    });
			
    // Event on Latitude/Longitude Typing Change
    jQuery('#'+settings.fieldName+'_latitude, #'+settings.fieldName+'_longitude').bind("focusout keyup", function() {
        var newlat = jQuery('input[name="'+settings.fieldName+'_latitude"]').val();
        var newlon = jQuery('input[name="'+settings.fieldName+'_longitude"]').val();
        if (!isNaN(newlat) && !isNaN(newlon))
        {
            // Clear the map first
            vlayer.removeFeatures(vlayer.features);
            jQuery('input[name="'+settings.fieldName+'_geometry[]"]').remove();
					
            point = new OpenLayers.Geometry.Point(newlon, newlat);
            OpenLayers.Projection.transform(point, proj_4326,proj_900913);
					
            f = new OpenLayers.Feature.Vector(point);
            vlayer.addFeatures(f);
					
            // create a new lat/lon object
            myPoint = new OpenLayers.LonLat(newlon, newlat);
            myPoint.transform(proj_4326, map.getProjectionObject());

            // display the map centered on a latitude and longitude
            map.panTo(myPoint);
        }
        else
        {
        // Commenting this out as its horribly annoying
        //alert('Invalid value!');
        }
    });
}

function initViewMap(settings){
    
    var defaultSettings = {
        mapContainer:"mapContainer",
        editContainer:"editContainer",
        fieldName:"location"
    
    };
    settings = jQuery.extend(defaultSettings, settings);		
    
    
    var options = {
        units: "dd",
        numZoomLevels: 18, 
        controls:[],
        theme: false,
        projection: proj_900913,
        'displayProjection': proj_4326,
        /* eventListeners: {
            "zoomend": incidentZoom
        },*/
        maxExtent: new OpenLayers.Bounds(-20037508.34, -20037508.34, 20037508.34, 20037508.34),
        maxResolution: 156543.0339
    };
   
    // Now initialise the map
    map = new OpenLayers.Map(settings.mapContainer, options);
			
    var google_satellite = new OpenLayers.Layer.Google("Google Maps Satellite", { 
        type: google.maps.MapTypeId.SATELLITE,
        animationEnabled: true,
        sphericalMercator: true,
        maxExtent: new OpenLayers.Bounds(-20037508.34,-20037508.34,20037508.34,20037508.34)
    });

    var google_hybrid = new OpenLayers.Layer.Google("Google Maps Hybrid", { 
        type: google.maps.MapTypeId.HYBRID,
        animationEnabled: true,
        sphericalMercator: true,
        maxExtent: new OpenLayers.Bounds(-20037508.34,-20037508.34,20037508.34,20037508.34)
    });

    var google_normal = new OpenLayers.Layer.Google("Google Maps Normal", { 
        animationEnabled: true,
        sphericalMercator: true,
        maxExtent: new OpenLayers.Bounds(-20037508.34,-20037508.34,20037508.34,20037508.34)
    });

    var google_physical = new OpenLayers.Layer.Google("Google Maps Physical", { 
        type: google.maps.MapTypeId.TERRAIN,
        animationEnabled: true,
        sphericalMercator: true,
        maxExtent: new OpenLayers.Bounds(-20037508.34,-20037508.34,20037508.34,20037508.34)
    });

    map.addLayers([google_normal,google_satellite,google_hybrid,google_physical]);
    map.addControl(new OpenLayers.Control.Navigation());
    map.addControl(new OpenLayers.Control.Zoom());
    map.addControl(new OpenLayers.Control.MousePosition());
    map.addControl(new OpenLayers.Control.ScaleLine());
    map.addControl(new OpenLayers.Control.Scale('mapScale'));
    map.addControl(new OpenLayers.Control.LayerSwitcher());
    
    // Vector/Drawing Layer Styles
    style1 = new OpenLayers.Style({
        pointRadius: "8",
        fillColor: "#ffcc66",
        fillOpacity: "0.7",
        strokeColor: "#CC0000",
        strokeWidth: 2.5,
        graphicZIndex: 1,
        externalGraphic: "res/openlayers/img/marker.png",
        graphicOpacity: 1,
        graphicWidth: 21,
        graphicHeight: 25,
        graphicXOffset: -14,
        graphicYOffset: -27
    });
    style2 = new OpenLayers.Style({
        pointRadius: "8",
        fillColor: "#30E900",
        fillOpacity: "0.7",
        strokeColor: "#197700",
        strokeWidth: 2.5,
        graphicZIndex: 1,
        externalGraphic: "res/openlayers/img/marker-green.png",
        graphicOpacity: 1,
        graphicWidth: 21,
        graphicHeight: 25,
        graphicXOffset: -14,
        graphicYOffset: -27
    });
    style3 = new OpenLayers.Style({
        pointRadius: "8",
        fillColor: "#30E900",
        fillOpacity: "0.7",
        strokeColor: "#197700",
        strokeWidth: 2.5,
        graphicZIndex: 1
    });
			
    var vlayerStyles = new OpenLayers.StyleMap({
        "default": style1,
        "select": style2,
        "temporary": style3
    });
                        
    vlayer = new OpenLayers.Layer.Vector( "Editable", {
        styleMap: vlayerStyles,
        rendererOptions: {
            zIndexing: true
        }
    });
    map.addLayer(vlayer);
			
	
			
    			
                        
    // Insert Saved Geometries
    wkt = new OpenLayers.Format.WKT();
    if(settings.geometries){
        for(i in settings.geometries){
             wktFeature = wkt.read(settings.geometries[i]);
            wktFeature.geometry.transform(proj_4326,proj_900913);
            vlayer.addFeatures(wktFeature);
	}       			
    }else{
        // Default Point
            point = new OpenLayers.Geometry.Point(settings.longitude, settings.latitude);
            OpenLayers.Projection.transform(point, proj_4326, map.getProjectionObject());
            var origFeature = new OpenLayers.Feature.Vector(point);
            vlayer.addFeatures(origFeature);
				
    }                 							
			
    // Create a lat/lon object
    var startPoint = new OpenLayers.LonLat(settings.longitude, settings.latitude);
    startPoint.transform(proj_4326, map.getProjectionObject());
			
    // Display the map centered on a latitude and longitude (Google zoom levels)
    map.setCenter(startPoint, 8);
			
    // Create the Editing Toolbar
    //var container = document.getElementById(settings.editContainer);
    //refreshFeatures(settings.fieldName);
    
}

function geoCode(fieldName)
{
    jQuery('#'+fieldName+'_findLoading').html('<img src="res/openlayers/img/loading_g.gif">');
    address = jQuery("#"+fieldName+"_locationFind").val();
    jQuery.post("index.php?mod=events&act=geocode", {
        address: address
    },
    function(data){
        if (data.status == 'success'){
            // Clear the map first
            vlayer.removeFeatures(vlayer.features);
            jQuery('input[name="'+fieldName+'_geometry[]"]').remove();
						
            point = new OpenLayers.Geometry.Point(data.longitude, data.latitude);
            OpenLayers.Projection.transform(point, proj_4326,proj_900913);
						
            f = new OpenLayers.Feature.Vector(point);
            vlayer.addFeatures(f);
						
            // create a new lat/lon object
            myPoint = new OpenLayers.LonLat(data.longitude, data.latitude);
            myPoint.transform(proj_4326, map.getProjectionObject());

            // display the map centered on a latitude and longitude
            map.panTo(myPoint);
												
            // Update form values
            jQuery("#"+fieldName+"_country_name").val(data.country);
            jQuery('input[name="'+fieldName+'_latitude"]').val(data.latitude);
            jQuery('input[name="'+fieldName+'_longitude"]').val(data.longitude);
            jQuery("#"+fieldName+"_location_name").val(data.location_name);
        } else {
            // Alert message to be displayed
            var alertMessage = address + " not found!\n\n***************************\n" + 
            "Enter more details like city, town, country\nor find a city or town " +
            "close by and zoom in\nto find your precise location";

            alert(alertMessage)
        }
        jQuery('div#'+fieldName+'_findLoading').html('');
    }, "json");
    return false;
}
		
/* Keep track of the selected features */
function addSelected(feature) {
    selectedFeatures.push(feature);
    selectCtrl.activate();
    if (vlayer.features.length == 1 && feature.geometry.CLASS_NAME == "OpenLayers.Geometry.Point") {
    // This is a single point, no need for geometry metadata
    } else {
        //jQuery('#geometryLabelerHolder').show(400);
        if (feature.geometry.CLASS_NAME == "OpenLayers.Geometry.Point") {
            /*jQuery('#geometryLat').show();
					jQuery('#geometryLon').show();
					jQuery('#geometryColor').hide();
					jQuery('#geometryStrokewidth').hide();*/
            thisPoint = feature.clone();
            thisPoint.geometry.transform(proj_900913,proj_4326);
        /*jQuery('#geometry_lat').val(thisPoint.geometry.y);
					jQuery('#geometry_lon').val(thisPoint.geometry.x);*/
        } else {
        /*jQuery('#geometryLat').hide();
					jQuery('#geometryLon').hide();
					jQuery('#geometryColor').show();
					jQuery('#geometryStrokewidth').show();*/
        }
    /*if ( typeof(feature.label) != 'undefined') {
					jQuery('#geometry_label').val(feature.label);
				}
				if ( typeof(feature.comment) != 'undefined') {
					jQuery('#geometry_comment').val(feature.comment);
				}
				if ( typeof(feature.lon) != 'undefined') {
					jQuery('#geometry_lon').val(feature.lon);
				}
				if ( typeof(feature.lat) != 'undefined') {
					jQuery('#geometry_lat').val(feature.lat);
				}
				if ( typeof(feature.color) != 'undefined') {
					jQuery('#geometry_color').val(feature.color);
				}
				if ( typeof(feature.strokewidth) != 'undefined' && feature.strokewidth != '') {
					jQuery('#geometry_strokewidth').val(feature.strokewidth);
				} else {
					jQuery('#geometry_strokewidth').val("2.5");
				}*/
    }
}

/* Clear the list of selected features */
function clearSelected(feature) {
    selectedFeatures = [];
    /*jQuery('#geometryLabelerHolder').hide(400);
			jQuery('#geometry_label').val("");
			jQuery('#geometry_comment').val("");
			jQuery('#geometry_color').val("");
			jQuery('#geometry_lat').val("");
			jQuery('#geometry_lon').val("");*/
    selectCtrl.deactivate();
    selectCtrl.activate();
//jQuery('#geometry_color').ColorPickerHide();
}

		
/* Feature starting to move */
function startDrag(feature, pixel) {
    lastPixel = pixel;
}

/* Feature moving */
function doDrag(feature, pixel) {
    for (f in selectedFeatures) {
        if (feature != selectedFeatures[f]) {
            var res = map.getResolution();
            selectedFeatures[f].geometry.move(res * (pixel.x - lastPixel.x), res * (lastPixel.y - pixel.y));
            vlayer.drawFeature(selectedFeatures[f]);
        }
    }
    lastPixel = pixel;
}

/* Featrue stopped moving */
/*function endDrag(feature, pixel) {
    for (f in selectedFeatures) {
        f.state = OpenLayers.State.UPDATE;
    }
    refreshFeatures(fieldName);
			
    // Fetching Lat Lon Values
    var latitude = parseFloat(jQuery('input[name="latitude"]').val());
    var longitude = parseFloat(jQuery('input[name="longitude"]').val());
			
    // Looking up country name using reverse geocoding
    reverseGeocode(latitude, longitude);
}*/
		
function refreshFeatures(event,fieldName) {
    var geoCollection = new OpenLayers.Geometry.Collection;
    jQuery('input[name="'+fieldName+'_geometry[]"]').remove();
    for(i=0; i < vlayer.features.length; i++) {
        newFeature = vlayer.features[i].clone();
        newFeature.geometry.transform(proj_900913,proj_4326);
        geoCollection.addComponents(newFeature.geometry);
        if (vlayer.features.length == 1 && vlayer.features[i].geometry.CLASS_NAME == "OpenLayers.Geometry.Point") {
        // If feature is a Single Point - save as lat/lon
        } else {
            // Otherwise, save geometry values
            // Convert to Well Known Text
            var format = new OpenLayers.Format.WKT();
            var geometry = format.write(newFeature);
            var label = '';
            var comment = '';
            var lon = '';
            var lat = '';
            var color = '';
            var strokewidth = '';
            if ( typeof(vlayer.features[i].label) != 'undefined') {
                label = vlayer.features[i].label;
            }
            if ( typeof(vlayer.features[i].comment) != 'undefined') {
                comment = vlayer.features[i].comment;
            }
            if ( typeof(vlayer.features[i].lon) != 'undefined') {
                lon = vlayer.features[i].lon;
            }
            if ( typeof(vlayer.features[i].lat) != 'undefined') {
                lat = vlayer.features[i].lat;
            }
            if ( typeof(vlayer.features[i].color) != 'undefined') {
                color = vlayer.features[i].color;
            }
            if ( typeof(vlayer.features[i].strokewidth) != 'undefined') {
                strokewidth = vlayer.features[i].strokewidth;
            }
            geometryAttributes = JSON.stringify({
                geometry: geometry, 
                label: label, 
                comment: comment,
                lat: lat, 
                lon: lon, 
                color: color, 
                strokewidth: strokewidth
            });
            jQuery('form').append(jQuery('<input></input>').attr('name',fieldName+'_geometry[]').attr('type','hidden').attr('value',geometryAttributes));
        }
    }
    // Centroid of location will constitute the Location
    // if its not a point
    centroid = geoCollection.getCentroid(true);
    jQuery('input[name="'+fieldName+'_latitude"]').val(centroid.y);
    jQuery('input[name="'+fieldName+'_longitude"]').val(centroid.x);
}
			
                        
function incidentZoom(event) {
    jQuery("#incident_zoom").val(map.getZoom());
}
		
function updateFeature(feature, color, strokeWidth){
		
    // Create a symbolizer from exiting stylemap
    var symbolizer = feature.layer.styleMap.createSymbolizer(feature);
			
    // Color available?
    if (color) {
        symbolizer['fillColor'] = "#"+color;
        symbolizer['strokeColor'] = "#"+color;
        symbolizer['fillOpacity'] = "0.7";
    } else {
        if ( typeof(feature.color) != 'undefined' && feature.color != '' ) {
            symbolizer['fillColor'] = "#"+feature.color;
            symbolizer['strokeColor'] = "#"+feature.color;
            symbolizer['fillOpacity'] = "0.7";
        }
    }
			
    // Stroke available?
    if (parseFloat(strokeWidth)) {
        symbolizer['strokeWidth'] = parseFloat(strokeWidth);
    } else if ( typeof(feature.strokewidth) != 'undefined' && feature.strokewidth !='' ) {
        symbolizer['strokeWidth'] = feature.strokewidth;
    } else {
        symbolizer['strokeWidth'] = "2.5";
    }
			
    // Set the unique style to the feature
    feature.style = symbolizer;

    // Redraw the feature with its new style
    feature.layer.drawFeature(feature);
}
		
// Reverse GeoCoder
function reverseGeocode(latitude, longitude,fieldName) {		
    var latlng = new google.maps.LatLng(latitude, longitude);
    var geocoder = new google.maps.Geocoder();
    geocoder.geocode({
        'latLng': latlng
    }, function(results, status){
        if (status == google.maps.GeocoderStatus.OK) {
            var country = results[results.length - 1].formatted_address;
            jQuery("#"+fieldName+"_country_name").val(country);
        } else {
            console.log("Geocoder failed due to: " + status);
        }
    });
}