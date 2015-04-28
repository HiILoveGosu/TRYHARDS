var map;
function initialize() {
    var mapOptions = {
        zoom: 8,
        center: new google.maps.LatLng(51.209348, 3.224700)
    };
    map = new google.maps.Map(document.getElementById('map-canvas'),
        mapOptions);
}

google.maps.event.addDomListener(window, 'load', initialize);