function initialize() {
    var myLatlng = new google.maps.LatLng(51.202557,3.210614);
    var mapOptions = {
        zoom: 13,
        center: myLatlng
    };
    var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
    $.getJSON("http://localhost:8080/TRYHARDS/data.json")
        .done(function (data) {
            $.each( data.delicten, function(i, value) {

                var myLatlng = new google.maps.LatLng(value.latitude, value.longitude);
                var marker = new google.maps.Marker({
                    position: myLatlng,
                    map: map,
                    title: "text "+value.titel
                });
                var infowindow = new google.maps.InfoWindow({
                    content: value.omschrijving
                });
                google.maps.event.addListener(marker, 'click', function() {
                    infowindow.open(map,marker);
                });

            });
            console.log(data);
        })
        .fail(function (jqXHR, textStatus, err) {
            console.log('Data ophalen mislukt');
        });
}

google.maps.event.addDomListener(window, 'load', initialize);