<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ShadowMap</title>
    <link rel="stylesheet" href="css/leaflet.css"/>
    <link rel="stylesheet" href="css/style.css"/>
    <link rel="stylesheet" href="css/leaflet-search.css" />
    <link rel="stylesheet" href="css/toastr.min.css" />
    <script src="js/leaflet.js"></script>
    <script src="js/leaflet.edgebuffer.js"></script>
    <script src="js/leaflet-search.js"></script>
    <script src="js/Semicircle.js"></script>
    <script src="js/jquery.min.js"></script>
    <script src="js/toastr.min.js"></script>
    <script src="js/index.js"></script>
</head>
<body>
    <div id="map"></div>
    <div class="sidepanel" id="panel">
        <div id="about-panel" class="hidden">
            <h1>About</h1>
            <p>This is a map of all security cameras so you can avoid them and shit</p>
            <div class="close" id="close"></div>
        </div>
        <div id="api-panel" class="hidden">

        </div>
        <div id="credits-panel" class="hidden">

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
</body>
</html>