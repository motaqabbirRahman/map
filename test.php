<!DOCTYPE html>
<html>
   <head>
      <title>Map Marker Form</title>
      <!-- Add map library -->
      <script src="https://maps.googleapis.com/maps/api/js?language=en&key=AIzaSyD93cHVgv78v7---19ZQtimRvVpqi7t_M0"></script>
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
      <script>
         // Define global variables to keep track of the markers and map
         var map;
         var markers = [];
         
     function initMap() {
         // Set default map center
         map = new google.maps.Map(document.getElementById("map"), {
             center: { lat: 37.7739, lng: -122.4194 },
             zoom: 8,
         });
         
         // Retrieve markers from the server and add them to the map
         var xhr = new XMLHttpRequest();
         xhr.open("GET", "get_markers.php");
         xhr.onload = function () {
             if (xhr.status === 200) {
                 var markersData = JSON.parse(xhr.responseText);
                 markersData.forEach(function (markerData) {
                     // Create a new marker object for each marker data and add it to the map
                     var marker = createMarker(markerData);
                     markers.push(marker);
                 });
             } else {
                 console.error("Error retrieving markers from server");
            filterControlDiv }
         };
         xhr.send();
         
         // Create a custom control for filtering the markers based on marker_type
         var filterControlDiv = document.createElement("div");
         var filterControl = new FilterControl(filterControlDiv, markers);
         map.controls[google.maps.ControlPosition.TOP_RIGHT].push(filterControlDiv);
         
         // Create the buttons
        var fireButton = document.createElement("button");
        fireButton.innerHTML = "Fire";
        fireButton.style.backgroundColor = "#fff";
        fireButton.style.border = "1px solid #ccc";
        fireButton.style.padding = "10px";
        fireButton.style.marginRight = "5px";
        fireButton.style.marginTop = "5px";
        fireButton.style.fontSize = "16px";
        fireButton.addEventListener("mouseover", function() {
            fireButton.style.backgroundColor = "#eee";
        });
        fireButton.addEventListener("mouseout", function() {
            fireButton.style.backgroundColor = "#fff";
        });

        var stationButton = document.createElement("button");
        stationButton.innerHTML = "Fire Station";
        stationButton.style.backgroundColor = "#fff";
        stationButton.style.border = "1px solid #ccc";
        stationButton.style.padding = "10px";
        stationButton.style.marginRight = "5px";
        stationButton.style.marginTop = "5px";
        stationButton.style.fontSize= "16px";
        stationButton.addEventListener("mouseover", function() {
            stationButton.style.backgroundColor = "#eee";
        });
        stationButton.addEventListener("mouseout", function() {
            stationButton.style.backgroundColor = "#fff";
        });

        var extinguisherButton = document.createElement("button");
        extinguisherButton.innerHTML = "Fire Extinguisher";
        extinguisherButton.style.backgroundColor = "#fff";
        extinguisherButton.style.border = "1px solid #ccc";
        extinguisherButton.style.padding = "10px";
        extinguisherButton.style.marginRight = "5px";
        extinguisherButton.style.marginTop = "5px";
        extinguisherButton.style.fontSize= "16px";
        extinguisherButton.style.fontFamily = "'Sofia', sans-serif";
        extinguisherButton.addEventListener("mouseover", function() {
            extinguisherButton.style.backgroundColor = "#eee";
        });
        extinguisherButton.addEventListener("mouseout", function() {
            extinguisherButton.style.backgroundColor = "#fff";
        });

        var showAllButton = document.createElement("button");
        showAllButton.innerHTML = "Show All";
        showAllButton.style.backgroundColor = "#fff";
        showAllButton.style.border = "1px solid #ccc";
        showAllButton.style.padding = "10px";
        showAllButton.style.marginRight = "5px";
        showAllButton.style.fontSize= "16px";
        showAllButton.style.fontWeight = "bold";
        showAllButton.addEventListener("mouseover", function() {
            showAllButton.style.backgroundColor = "#eee";
        });
        showAllButton.addEventListener("mouseout", function() {
            showAllButton.style.backgroundColor = "#fff";
        });

         
         // Add the buttons to the filter control
         filterControlDiv.appendChild(fireButton);
         filterControlDiv.appendChild(stationButton);
         filterControlDiv.appendChild(extinguisherButton);
         filterControlDiv.appendChild(showAllButton);
         
         // Set up the click event listener for the filter buttons
         fireButton.addEventListener("click", function () {
             filterMarkers("🔥");
         });
         
         stationButton.addEventListener("click", function () {
             filterMarkers("🚒");
         });
         
         extinguisherButton.addEventListener("click", function () {
             filterMarkers("🧯");
         });
         
         showAllButton.addEventListener("click", function () {
             showAllMarkers();
         });
     }
         
         // Create a marker object based on the marker data and add it to the map
        function createMarker(markerData) {
         var iconUrl;
         var iconSize = new google.maps.Size(40, 40);
         
         if (markerData.status == "approved") {
             if (markerData.marker_type == "🔥") {
                 iconUrl = "img/fire.png";
             } else if (markerData.marker_type == "🧯") {
                 iconUrl = "img/fire-extinguisher.png";
             } else if (markerData.marker_type == "🚒") {
                 iconUrl = "img/fire-station.png";
             } else {
                 console.error("Invalid marker type:", markerData.marker_type);
             }
             var marker = new google.maps.Marker({
                 position: {
                     lat: parseFloat(markerData.latitude),
                     lng: parseFloat(markerData.longitude),
                 },
                 map: map,
                 icon: {
                     url: iconUrl,
                     scaledSize: iconSize,
                 },
                 title: markerData.title,
                 description: markerData.description,
                 marker_type: markerData.marker_type,
             });
         
             // Add a click event listener to the marker to display the info window
             marker.addListener("click", function () {
                 var infoWindow = new google.maps.InfoWindow({
                     content:
                         "<h4>" +
                         marker.getTitle() +
                         "</h4>" +
                         "<p>" +
                         marker.description +
                         "</p>",
                 });
                 infoWindow.open(map, marker);
             });
         
             return marker;
         } else {
             console.error("Invalid marker status:", markerData.status);
         }
         }
         
         // Filter the markers based on marker_type and show only the filtered markers
     function filterMarkers(markerType) {
         markers.forEach(function (marker) {
             if (marker.marker_type === markerType) {
                 marker.setVisible(true);
             } else {
                 marker.setVisible(false);
             }
         });
         }
         
         // Show all markers on the map
         function showAllMarkers() {
         markers.forEach(function (marker) {
             marker.setVisible(true);
         });
         }
         
         // Custom control for filtering the markers based on marker_type
         function FilterControl(controlDiv, markers) {
         // Set up the div to contain the control
         var filterControlUI = document.createElement("div");
         controlDiv.appendChild(filterControlUI);
         }


        



         
      </script>
   </head>
   <body onload="initMap()">
      <!-- Add map container -->
      <div id="map" style="height: 500px;"></div>
      <div id="flex-container">
         <div class="flex-item">
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