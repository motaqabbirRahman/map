<!DOCTYPE html>
<html>
<head>
  <title>Map Marker Form</title>
  <!-- Add map library -->
  <script src="https://maps.googleapis.com/maps/api/js?language=en&key=AIzaSyD93cHVgv78v7---19ZQtimRvVpqi7t_M0"></script>
  <link rel="stylesheet" href="styles.css" class="style">

  <script>
    // Initialize map and marker variable
    var map;
    var marker; 

    function initMap() {
      // Set default map center
     // Define the map and default marker icon
            var map = new google.maps.Map(document.getElementById('map'), {
            center: {lat: 37.7739, lng: -122.4194},
            zoom: 8
            });
            var yellowMarkerIcon = 'http://maps.google.com/mapfiles/ms/icons/yellow-dot.png';

            // Retrieve markers from the server and add them to the map
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'get_markers.php');
            xhr.onload = function() {
            if (xhr.status === 200) {
                var markers = JSON.parse(xhr.responseText);
                markers.forEach(function(marker) {
                var newMarker = new google.maps.Marker({
                    position: {lat: parseFloat(marker.latitude), lng: parseFloat(marker.longitude)},
                    map: map,
                    icon: yellowMarkerIcon
                });
                newMarker.id = marker.id;
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

            function createInfoWindow(marker) {
            var contentString = '<div><p style="color:black;">Marker info: ' + marker.id + '</p><button class="popBtn" onclick="approveMarker(\'' + marker.id + '\')">Approve</button></div>';
            var infoWindow = new google.maps.InfoWindow({
                content: contentString
            });
            return infoWindow;
            }


      // Add click event listener to map
      map.addListener('click', function(event) {
        // If marker already exists, remove it from the map
        if (marker) {
          marker.setMap(null);
        }
        
        // Create new marker at clicked location
        marker = new google.maps.Marker({
          position: event.latLng,
          map: map,
          title: 'New marker'
        });

        marker.setMap(map);

   
	
        
        // Show info window when marker is clicked
        marker.addListener('click', function() {
        var contentString = '<div><p>Marker info: ' + marker.marker_info + '</p><button onclick="approveMarker(\'' + marker.id + '\')">Approve</button></div>';
        var infoWindow = new google.maps.InfoWindow({
            content: contentString
        });
        infoWindow.open(map, marker);
        });
        // Set form values to clicked location
        document.getElementById('latitude').value = event.latLng.lat();
        document.getElementById('longitude').value = event.latLng.lng();
        
        // Show form
        document.getElementById('marker-form').style.display = 'block';
		document.getElementById('success-msg').style.display = 'none';
      });

	  
    }
  </script>
  <script>           
        function approveMarker(markerId) {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'approve_marker.php');
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                    if (xhr.status === 200) {
                    // The marker was approved successfully
                    alert('Marker approved!');
                    } else {
                    console.error('Error approving marker');
                    }
                };
                xhr.send('marker_id=' + encodeURIComponent(markerId));
            }
 </script>

</head>
<body onload="initMap()">
  <!-- Add map container -->
  <div id="map" style="height: 500px;"></div>
  <?php
		if (isset($_GET['msg'])) {
		echo "<div id='success-msg'>
		 Success! Waiting for Approval.
		 </div>";
		}
    ?>
  <!-- Add marker form -->
  <div id="marker-form">
    <form method="post" action="save_marker.php">
      <input type="hidden" id="latitude" name="latitude" required>
      <input type="hidden" id="status" name="status" value="pending" required>
      <input type="hidden" id="longitude" name="longitude" required>

	  <label for="marker_type" style="color:#003049;">Marker type:</label>
		<select id="marker_type" name="marker_type">
			<option value="default">Select Type</option>
			<option value="🚒">🚒</option>
			<option value="🧯">🧯</option>
			<option value="🔥">previous 🔥</option>
		</select>

      <label for="marker_info" style="color:#003049;">Marker info:</label>
      <textarea id="marker_info" name="marker_info"></textarea>

      <input type="submit" value="Save marker">
    </form>
  </div>
</body>
</html>
