function initialize() {
    var myLatlng = new google.maps.LatLng(51.202557,3.210614);
    var mapOptions = {
        zoom: 13,
        center: myLatlng
    }
    var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

    var marker = new google.maps.Marker({
        position: myLatlng,
        map: map,
        title: 'Hello World!'
    });
}

google.maps.event.addDomListener(window, 'load', initialize);