<html>
<head>
    <meta name="viewport" content="initial-scale=1,maximum-scale=1,user-scalable=no">
    <link href="https://api.mapbox.com/mapbox-gl-js/v2.14.1/mapbox-gl.css" rel="stylesheet">
    <script src="https://api.mapbox.com/mapbox-gl-js/v2.14.1/mapbox-gl.js"></script>
    <style>
        body { margin: 0; padding: 0; }
        #map { position: absolute; bottom: 10px; right: 10px; width: 500px; height: 500px;
            background-color: #f5f5f5;;
        }
        .mapboxgl-popup-content{
            color: black;
        }
        .mapboxgl-popup-close-button{
            opacity: 0.1;
        }
    </style>
</head>
<body>
<div id="map"></div>
<script>
    mapboxgl.accessToken = 'pk.eyJ1IjoiY2xlYW5za3kxIiwiYSI6ImNsaGdtZ3I0MjAyM2kzZXJ6NXFpdWo4a3cifQ.8yFg-lyDUMVs9KqajQ5qDA';
    const map = new mapboxgl.Map({
        container: 'map',
        style: 'mapbox://styles/mapbox/dark-v11',
        center: [-3.7038, 40.4168],
        zoom: 9
    });

</script>
</body>
</html>
