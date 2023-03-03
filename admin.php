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

        // function createInfoWindow(marker) {
        //     var contentString = '<div><p style="color:black;">Marker info: ' + marker.id + '</p><button class="popBtn" onclick="approveMarker(\'' + marker.id + '\')">Approve</button></div>';
        //     var infoWindow = new google.maps.InfoWindow({
        //         content: contentString
        //     });
        //     return infoWindow;
        // }
        //  function createInfoWindow(marker) {
        //     var contentString = '<div><p style="color:black;">Marker info: ' + marker.id + '</p>';
        //         if (marker.status !== 'approved') {
        //             contentString += '<button class="popBtn" onclick="approveMarker(\'' + marker.id + '\')">Approve</button>';
        //         }
        //         contentString += '</div>';
        //         var infoWindow = new google.maps.InfoWindow({
        //             content: contentString
        //         });
        //         return infoWindow;
        //     }
       // Keep track of the currently open info window
            var openInfoWindow = null;
            function createInfoWindow(marker) {
                var contentString = '<div><p style="color:black;">Marker info: ' + marker.marker_info + '</p>';
          

                if (marker.status === 'pending') {
                   
                    contentString += '<button class="popBtn" onclick="approveMarker(\'' + marker.id + '\', this)">Approve</button>';

                } else {
                    contentString += '<button class="dltBtn" onclick="deleteMarker(\'' + marker.id + '\')">Delete</button>';
                }
                contentString += '</div>';

          
            
                
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
	  
    }
  </script>
  <script>           
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


          <div id="nav-box">
            <?php
                if(isset($_SESSION['username'])){
                    // display the username and logout option inside a box
                    // echo '<div id="welcome-msg">' . $_SESSION['username'] . '</div>';
                    // echo '<div><a id="logout-btn" href="admin_logout.php">Logout</a></div>';
                    // echo '<div id="nav-box">'
                echo '<div>';
                    echo '<span id="welcome-msg">' . $_SESSION['username'] . '</span>';
                     echo '<span id="logo">' . "🔥" . '</span>';
                    echo '<a href="admin_logout.php" id="logout-btn">Logout</a>';
                echo '</div>';

                }
            ?>
        </div>
        <div style="margin-left: 220px; margin-top: 50px;">
            <!-- the rest of your website content goes here -->
        </div>


  
</body>
</html>
