  // Define global variables to keep track of the markers and map
  var map;
  var markers = [];
   
   function name() {

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
                  }
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


        function approveMarker(markerId, button) {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'approve_marker.php');
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                if (xhr.status === 200) {
                // The marker was approved successfully
                button.style.display = 'none';
                alert('Marker approved!');
                location.reload(); 
                document.getElementById("approvedText").style.display = "inline";
                } else {
                console.error('Error approving marker');
                }
            };
            xhr.send('marker_id=' + encodeURIComponent(markerId));
}


function deleteMarker(id) {
    if (confirm("Are you sure you want to delete this marker?")) {
        // send a DELETE request to the server to delete the marker
        var xhr = new XMLHttpRequest();
        xhr.open('DELETE', 'delete_marker.php');
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                // The marker was deleted successfully
                // Reload the page to update the markers
                alert('Deleted Successfully!');
                location.reload();
            } else {
                alert('Failed to delete marker');
            }
        };
        xhr.send('marker_id=' + id);
    }
}