var map;
var marker;


function createMap() {
    var centerPosition = new google.maps.LatLng(54.9, 23.9);
    var options = {
        zoom: 10,
        center: centerPosition,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        disableDefaultUI: true
    };
    map = new google.maps.Map($('#map')[0], options);
    
    
    google.maps.event.addListener(map, 'click', function (evt) {
        placeMarker(evt.latLng);
        var markerlat = marker.getPosition().lat().toFixed(11);
        var markerlng = marker.getPosition().lng().toFixed(11);
        document.getElementById("ilguma").value = markerlat;
        document.getElementById("platuma").value = markerlng;
    });
}
google.maps.event.addDomListener(window, 'load', creatMap);

 function placeMarker(location) {
    if (marker) {
        marker.setPosition(location);
    } 
    else {
        marker = new google.maps.Marker({          
            position: location,
            map: map
        });
    }
} 
