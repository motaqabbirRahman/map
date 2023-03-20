<?php
session_start();

if(!isset($_SESSION['adminLoggedin']) || $_SESSION['adminLoggedin']!=true){
    header("location: admin_login.php");
    exit;
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Map Marker Form</title>
    <!-- Add map library -->
<script src="https://maps.googleapis.com/maps/api/js?language=en&key=AIzaSyD93cHVgv78v7---19ZQtimRvVpqi7t_M0"></script>
    <link rel="stylesheet" href="styles.css" class="style">
    <script src="test.js"></script>

    <script>
        // Initialize map and marker variable
        var map;
        var marker; 
        var markerArray = []; // store all markers in this array

        function initMap() {
            // Set default map center
            var map = new google.maps.Map(document.getElementById('map'), {
                center: {lat: 37.7739, lng: -122.4194},
                zoom: 8
            });
            var yellowMarkerIcon = 'http://maps.google.com/mapfiles/ms/icons/yellow-dot.png';
            var iconSize = new google.maps.Size(15, 15);
                        if (xhr.status === 200) {
                var markers = JSON.parse(xhr.responseText);
                markers.forEach(function(marker) {
                //   console.log('Marker type:', marker.marker_type);
                //   console.log('Status:', marker.status);
                
                  var iconUrl;
                    var iconSize = new google.maps.Size(40, 40);
                    if (marker.status == 'approved') {
                        if (marker.marker_type == '🔥') {
                            iconUrl = 'img/fire.png';
                        } else if (marker.marker_type == '🧯') {
                            iconUrl = 'img/fire-extinguisher.png';
                        } else if (marker.marker_type == '🚒') {
                            iconUrl = 'img/fire-station.png';
                        } else {
                            iconUrl = 'http://maps.google.com/mapfiles/ms/icons/blue-dot.png';
                        }
                    } else {
                        iconUrl = 'http://maps.google.com/mapfiles/ms/icons/yellow-dot.png';
                    }
                    var newMarker = new google.maps.Marker({
                        position: {lat: parseFloat(marker.latitude), lng: parseFloat(marker.longitude)},
                        map: map,
                        icon: {
                            url: iconUrl,
                            scaledSize: iconSize
                        }
                  });



                
                newMarker.id = marker.id;
                newMarker.status = marker.status;
                newMarker.marker_info = marker.marker_info; 
                newMarker.addListener('click', function() {
                    var infoWindow = createInfoWindow(newMarker);
                    infoWindow.open(map, newMarker);
                });
                });
            } else {
                console.error('Error retrieving markers from server');
            }
            };
            xhr.send();
            // Retrieve markers from the
      
    
    
    // Filter markers based on their marker type
    function filterMarkers(makerType) {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'get_markers.php?marker_type=' + encodeURIComponent(makerType));
        xhr.onload = function() {
            if (xhr.status === 200) {
                var markers = JSON.parse(xhr.responseText);
                // Clear the existing markers from the map
                marker.setMap(null);
                // Add the filtered markers to the map
                markers.forEach(function(marker) {
                    var iconUrl;
                    var iconSize = new google.maps.Size(40, 40);
                    if (marker.status == 'approved') {
                        if (marker.marker_type == '🔥') {
                            iconUrl = 'img/fire.png';
                        } else if (marker.marker_type == '🧯') {
                            iconUrl = 'img/fire-extinguisher.png';
                        } else if (marker.marker_type == '🚒') {
                            iconUrl = 'img/fire-station.png';
                        } else {
                            iconUrl = 'http://maps.google.com/mapfiles/ms/icons/blue-dot.png';
                        }
                    } else {
                        iconUrl = 'http://maps.google.com/mapfiles/ms/icons/yellow-dot.png';
                    }
                    var newMarker = new google.maps.Marker({
                        position: {lat: parseFloat(marker.latitude), lng: parseFloat(marker.longitude)},
                        map: map,
                        icon: {
                            url: iconUrl,
                            scaledSize: iconSize
                        }
                    });
                    newMarker.id = marker.id;
                    newMarker.status = marker.status;
                    newMarker.marker_info = marker.marker_info; 
                    newMarker.addListener('click', function() {
                        var infoWindow = createInfoWindow(newMarker);
                        infoWindow.open(map, newMarker);
                    });
                });
            } else {
                console.error('Error filtering markers by marker type');
            }
        };
        xhr.send();
    }
</script>
</head>
<body onload="initMap()">
  <div id="map"></div>
  <div class="filter-option">
      <label>Filter by marker type:</label>
      <select id="marker-type-select" onchange="filterMarkers(this.value)">
          <option value="">All markers</option>
          <option value="🔥">Fire</option>
          <option value="🧯">Fire extinguisher</option>
          <option value="🚒">Fire station</option>
      </select>
  </div>
  <div id="approvedText" style="display:none;"><p>Approved!</p></div>
</body>
</html>