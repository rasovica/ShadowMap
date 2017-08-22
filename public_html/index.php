<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ShadowMap</title>
    <link rel="stylesheet" href="css/leaflet.css"/>
    <link rel="stylesheet" href="css/style.css"/>
    <link rel="stylesheet" href="css/leaflet-search.css" />
    <script src="js/jquery.min.js"></script>
    <script src="js/leaflet.js"></script>
    <script src="js/leaflet.edgebuffer.js"></script>
    <script src="js/leaflet-search.js"></script>
    <script src="js/Semicircle.js"></script>
</head>
<body>
    <div id="map"></div>
    <div class="sidepanel" id="panel">
        <div id="about-panel" class="hidden">
            <h1>About</h1>
            <p>This is a map of all security cameras so you can avoid them and shit</p>
            <div class="close" id="close"></div>
        </div>
        <div id="api-panel">

        </div>
        <div id="credits-panel">

        </div>
        <div id="help-panel">
            <h1>Help</h1>
            <p>Add a new camera by pressing shift and click draging into the direction it is pointing.</p>
            <div class="close" id="close"></div>
        </div>
    </div>
    <div class="footer">
        <ul>
            <li><a id="about">About</a></li>
            <li><a id="api">Api</a></li>
            <li><a id="credits">Credits</a></li>
            <li><a id="help">Help</a></li>
        </ul>
    </div>
    <script>
        function getAngle(p1, p2){
            return Math.atan2(p2[1] - p1[1], p2[0] - p1[0]) * 180 / Math.PI
        }
        function getDistance(origin, destination) {
            // return distance in meters
            var lon1 = toRadian(origin[1]),
            lat1 = toRadian(origin[0]),
            lon2 = toRadian(destination[1]),
            lat2 = toRadian(destination[0]);

            var deltaLat = lat2 - lat1;
            var deltaLon = lon2 - lon1;

            var a = Math.pow(Math.sin(deltaLat/2), 2) + Math.cos(lat1) * Math.cos(lat2) * Math.pow(Math.sin(deltaLon/2), 2);
            var c = 2 * Math.asin(Math.sqrt(a));
            var EARTH_RADIUS = 6371;
            return c * EARTH_RADIUS * 1000;
        }
        function toRadian(degree) {
            return degree*Math.PI/180;
        }
        $(document).ready(function(){
            $("#close").click(function() {
                $("#panel").animate({
                    right: "-400",
                    }, 500, function() {
                });
            });

            $("#about").click()

            var map = L.map('map');
            $.ajax({
                type: 'POST',
                url: "geoip.php",
                success: function(result){
                    var pos = [result.latitude, result.longitude];
                    map.setView(pos, 13);
                    L.tileLayer('http://a.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}@2x.png', {maxNativeZoom: "18", maxZoom: "20"}).addTo(map);

                    map.addControl( new L.Control.Search({
                        url: 'http://nominatim.openstreetmap.org/search?format=json&q={s}',
                        jsonpParam: 'json_callback',
                        propertyName: 'display_name',
                        propertyLoc: ['lat','lon'],
                        autoCollapse: true,
                        autoType: false,
                        minLength: 2,
                        marker: false,
                        zoom: 13,
                    }) );
                },
                dataType: "json",
            });
            document.getElementById("map").addEventListener('AddCameraStart', function (ev) {
                var camera = L.semiCircle(ev.detail, {radius: 15, color: '#DC143C'}).setDirection(0, 90).on("click", function (e) {
                    alert("aa");
                }).addTo(map);

                map.on('mousemove', function (e) {
                    if(getDistance([ev.detail.lat, ev.detail.lng], [e.latlng.lat, e.latlng.lng])<=100){
                        camera.setRadius(getDistance([ev.detail.lat, ev.detail.lng], [e.latlng.lat, e.latlng.lng]));
                    }
                    camera.setDirection(getAngle([ev.detail.lat, ev.detail.lng], [e.latlng.lat, e.latlng.lng]) ,90);
                });

                map.on('mouseup',function(e){
                    map.removeEventListener('mousemove');
                })
            }, false);
        });

    </script>
</body>
</html>