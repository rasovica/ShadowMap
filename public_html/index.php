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
    <div class="footer">
        <ul>
            <li><a href="#home">About</a></li>
            <li><a href="#api">Api</a></li>
            <li><a href="#credits">Credits</a></li>
            <li><a href="#help">Help</a></li>
        </ul>
    </div>
    <script>
        $(document).ready(function(){
            $.ajax({
              type: 'POST',
              url: "geoip.php",
              success: function(result){
                    var pos = [result.latitude, result.longitude];
                    var map = L.map('map').setView(pos, 13);
                    L.tileLayer('http://a.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}.png', {}).addTo(map);

                    L.semiCircle(pos, {radius: 15, color: '#ff0000'}).setDirection(0, 90).on("click", function (e) {
                        alert("aa");
                    }).addTo(map);

                    map.addControl( new L.Control.Search({
                        url: 'http://nominatim.openstreetmap.org/search?format=json&q={s}',
                        jsonpParam: 'json_callback',
                        propertyName: 'display_name',
                        propertyLoc: ['lat','lon'],
                        autoCollapse: true,
                        autoType: false,
                        minLength: 2,
                        marker: false,
                        zoom: 13
                    }) );
              },
              dataType: "json",
            });
        });


    </script>
</body>
</html>