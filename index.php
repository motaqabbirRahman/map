  <!DOCTYPE html>
  <html>
  <head>
    <title>Map Marker Form</title>
    <!-- Add map library -->
    <script src="https://maps.googleapis.com/maps/api/js?language=en&key=AIzaSyD93cHVgv78v7---19ZQtimRvVpqi7t_M0"></script>
    <script src="test.php"></script>
    <link rel="stylesheet" href="styles.css" class="style">
<script>
        // Wait for the DOM to load
        document.addEventListener("DOMContentLoaded", function() {
          // Get the success message element
        var successMsg = document.getElementById("success-msg");
          // If the success message exists
        if (successMsg) {
            // Set a timeout to hide the success message after 5 seconds (5000 milliseconds)
            setTimeout(function() {
              successMsg.style.display = "none";
            }, 2000);
          }
        });
</script>

    </script>
    <script>
      // Initialize map and marker variable
      var map;
      var marker; 

      function initMap() {
        // Set default map center
        var map = new google.maps.Map(document.getElementById('map'), {
              center: {lat: 37.7739, lng: -122.4194},
              zoom: 8
              });
              var yellowMarkerIcon = 'http://maps.google.com/mapfiles/ms/icons/yellow-dot.png';
              var iconSize = new google.maps.Size(15, 15);
              
              // Retrieve markers from the server and add them to the map
              var xhr = new XMLHttpRequest();
              xhr.open('GET', 'get_markers.php');
              xhr.onload = function() {
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

                  //starts
               

                      




                  ///ends

                  
                  newMarker.id = marker.id;
                  newMarker.marker_info = marker.marker_info; 
                  newMarker.marker_type = marker.marker_type;
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

         // Keep track of the currently open info window
            var openInfoWindow = null;
            function createInfoWindow(marker) {
                console.log('Marker status:', marker.info);
                var contentString = '<div><p style="color:black;">Marker type: ' + marker.marker_type +'</p>'+
                '<div><p style="color:black;">Marker info: ' + marker.marker_info+'</p>';
                
                // Close the currently open info window before opening a new one
                if (openInfoWindow) {
                    openInfoWindow.close();
                }
                
                var infoWindow = new google.maps.InfoWindow({
                    content: contentString
                });
                
                // Set the current info window to the newly opened one
                openInfoWindow = infoWindow;
                
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
            var contentString = '<div><p>Marker info goes here</p></div>';
            var infoWindow = new google.maps.InfoWindow({
              content: contentString
            });
            infoWindow.open(map, marker);
          });
          
          // Set form values to clicked location
          document.getElementById('latitude').value = event.latLng.lat();
          document.getElementById('longitude').value = event.latLng.lng();
          
          // Show form
          document.getElementById('flex-item-mid').style.display = 'flex';

          document.getElementById('success-msg').style.display = 'none';
        });

      
      }
    </script>

  </head>
  <body onload="initMap()">
    <!-- Add map container -->
    <div id="map" style="height: 500px;"></div>
    <div id="flex-container">
        <div class="flex-item">
             <button id="fire-marker-button">Show Fire Markers</button>
              <button id="extinguisher-marker-button">Show Fire Extinguisher Markers</button>
              <button id="station-marker-button">Show Fire Station Markers</button>

        </div>

      <div class="flex-item" id="flex-item-mid" style="display:none;">
        <form method="post" action="save_marker.php" class="add-marker-form">
          <!-- <h3>Add Marker</h3> -->
          <input type="hidden" id="latitude" name="latitude" required>
          <input type="hidden" id="status" name="status" value="pending" required>
          <input type="hidden" id="longitude" name="longitude" required>

          <label for="marker_type">Marker type:</label>
          <select id="marker_type" name="marker_type" required>
            <option value="">Select Type</option>
            <option value="🚒">🚒</option>
            <option value="🧯">🧯</option>
            <option value="🔥">🔥</option>
          </select>

          <label for="marker_info">Marker info:</label>
          <textarea id="marker_info" name="marker_info"></textarea>

          <input type="submit" value="Save marker">
        </form>
      </div>

 
  </div>




          <div id="nav-box">
                    <span id="add-marker-msg"> Click on the map to add new marker! </span>;
         </div>
    <?php
      if (isset($_GET['msg'])) {
      echo "<div id='success-msg'>
      Success! Waiting for Approval.
      </div>";
      }
      ?>
 
  </body>
  </html>
