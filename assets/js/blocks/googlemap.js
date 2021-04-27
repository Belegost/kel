
if($('#cotactus-map').length) {
    var map;
    var markers = [];

    function initMap() {
        var contacts = [
            {
                location: {lat: 51.510363, lng: -0.130464},
                title: 'London'
            },
            {
                location: {lat: 52.524667, lng: 13.417772},
                title: 'Berlin'
            },
            {
                location: {lat: 50.463807, lng: 30.462619},
                title: 'Kyiv'
            }
        ];
        var map_center = {lat: 52.524667, lng: 13.417772};
        var image = {
            url: "/images/map_marker.png",
            size: new google.maps.Size(41, 52),
            origin: new google.maps.Point(0, 0),
            anchor: new google.maps.Point(20, 52)
        };
        var styledMapType = new google.maps.StyledMapType([
            {
                featureType: "all",
                elementType: "all",
                stylers: [
                    {invert_lightness: true},
                    {saturation: -100}
                ]
            }
        ]);
        map = new google.maps.Map(document.getElementById('cotactus-map'), {
            center: map_center,
            zoom: 5,
            streetViewControl: false,
            mapTypeControl: false,
            fullscreenControl: false
        });
        contacts.forEach(function (contact, i) {
            var j = i;
            markers[j] = new google.maps.Marker({
                position: contact.location,
                icon: image,
                map: map
            });
            var infowindow = new google.maps.InfoWindow({
                content: '<h3>' + contact.title + '</h3>'
            });
            markers[j].addListener('click', function () {
                infowindow.open(map, markers[j]);
            });
        });

        //Associate the styled map with the MapTypeId and set it to display.
        map.mapTypes.set('styled_map', styledMapType);
        map.setMapTypeId('styled_map');
    }

}