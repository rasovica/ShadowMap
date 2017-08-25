toastr.options.positionClass = 'toast-top-center';
toastr.options.extendedTimeOut = 0; //1000;
toastr.options.timeOut = 1000;
toastr.options.fadeOut = 250;
toastr.options.fadeIn = 250;

var new_camera_html = `
<div id="new-camera-panel">
    <h1>New camera</h1>
    <p>Camera name:</p>
    <input type="text" value="New camera" id="name"></input>
    <p>Camera angle:</p>
    <input type="number" min="1" max="360" value="90" id="angle"></input>
    <p>Camera online source:</p>
    <input type="text" placeholder="Optional url to camera" id="url"></input>
    <button id="add">Add camera</button>
    <div class="close" id="close-camera"></div>
</div>
`
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
function getTile(lat, lng, zoom) {
    var xtile = parseInt(Math.floor( (lng + 180) / 360 * (1<<zoom) ));
    var ytile = parseInt(Math.floor( (1 - Math.log(Math.tan(toRadian(lat)) + 1 / Math.cos(toRadian(lat))) / Math.PI) / 2 * (1<<zoom) ));
    return {xtile, ytile};
}
$(document).ready(function(){
    $("#panel").on("click", "#close",function() {
        $("#panel").animate({
            right: "-400",
            }, 500, function() {
        });
    });

    $("#about").click(function(){
        $("#help-panel").hide();
        $("#about-panel").show();
        $("#panel").animate({
            right: "-0",
            }, 500, function() {
        });
    });

    $("#api").click(function(){
        $("#panel").animate({
            right: "-0",
            }, 500, function() {
        });
    });

    $("#credits").click(function(){
        $("#panel").animate({
            right: "0",
            }, 500, function() {
        });
    });

    $("#help").click(function(){
        $("#about-panel").hide();
        $("#help-panel").show();
        $("#panel").animate({
            right: "0",
            }, 500, function() {
        });
    });

    window.map = L.map('map', {attributionControl: false});

    $.ajax({
        type: 'POST',
        url: "geoip.php",
        success: function(result){
            if(result.latitude == 0 && result.longitude == 0){
                var pos = [46.5547, 15.6467];
            }else{
                var pos = [result.latitude, result.longitude];
            }
            map.setView(pos, 13);
            map.setZoom(14);
            L.tileLayer('http://a.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}@2x.png', {maxNativeZoom: "18", maxZoom: "18", minZoom: "6"}).addTo(map);

            map.addControl( new L.Control.Search({
                url: 'http://nominatim.openstreetmap.org/search?format=json&q={s}',
                jsonpParam: 'json_callback',
                propertyName: 'display_name',
                propertyLoc: ['lat','lon'],
                autoCollapse: true,
                autoType: false,
                minLength: 2,
                marker: false,
                zoom: 14,
            }) );
        },
        dataType: "json",
    });

    document.getElementById("map").addEventListener('AddCameraStart', function (ev) {
        $("#about-panel").hide();
        $("#api-panel").hide();
        $("#credits-panel").hide();
        $("#help-panel").hide();
        if($("#new-camera-panel").length == 0){
            $("#panel").append(new_camera_html).animate({
                right: "0",
                }, 500, function() {
            });
            var camera = L.semiCircle(ev.detail, {radius: 15, color: '#DC143C'}).setDirection(0, 90).on("click", function (e) {
                console.log(getTile(ev.detail.lat, ev.detail.lng, 14));
            }).addTo(map);

            map.on('mousemove', function (e) {
                if(getDistance([ev.detail.lat, ev.detail.lng], [e.latlng.lat, e.latlng.lng])<=100){
                    camera.setRadius(getDistance([ev.detail.lat, ev.detail.lng], [e.latlng.lat, e.latlng.lng]));
                }
                camera.setDirection(getAngle([ev.detail.lat, ev.detail.lng], [e.latlng.lat, e.latlng.lng]) ,90);
                window.direction = getAngle([ev.detail.lat, ev.detail.lng], [e.latlng.lat, e.latlng.lng]);
            });

            map.on('mouseup',function(e){
                map.removeEventListener('mousemove');
            });
            $("#angle").change(function(){
                camera.setDirection(window.direction, $("#angle").val());
            });
            $("#add").click(function(){
                data = {
                    x: getTile(ev.detail.lat, ev.detail.lng, 14).xtile,
                    y: getTile(ev.detail.lat, ev.detail.lng, 14).ytile,
                    lat: ev.detail.lat,
                    lng: ev.detail.lng,
                    n: $("#name").val(),
                    a: $("#angle").val(),
                    u: $("#url").val(),
                    r: camera.getRadius(),
                    d: window.direction,
                }
                $.ajax({
                    type: "POST",
                    url: "add_camera.php",
                    data: data,
                    success: function(data){
                        if("success" in data){
                            toastr.success(data.success);
                            $("#panel").animate({
                                right: "-400",
                            }, 500, function() {
                                    $("#new-camera-panel").remove();
                            });
                        }else if("error" in data){
                            toastr.error(data.error, 'Server error');
                        }else{
                            toastr.error('Something wierd happned');
                        }
                    },
                    error: function(){
                        toastr.error("Servers are down");
                    },
                });
            });
        }
        $("#close-camera").click(function(){
            map.removeLayer(camera);
            $("#panel").animate({
                right: "-400",
            }, 500, function() {
                    $("#new-camera-panel").remove();
            });
        });
    }, false);
});