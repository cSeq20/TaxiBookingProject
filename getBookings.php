<?php
/**
Student Info: Chris Sequeira (14863759)
Description: Gets a connection to database on cmslamp14 server.  
			 Displays bookings that are unassigned, and within two hours
			 of current time using todays date.
*/

	require_once ("../../conf/settings.php"); 	// to be able to use db credentials from secure file
	$conn = @mysqli_connect($host, $user, $pswd, $dbnm);	//gets a connection value, otherwise false
	
	//check connection
	if(!$conn){
		echo "<p>Database connection failure</p>";
	} else {
		
		$today = date("Y-m-d");	// todays date
		$currTime = date("H:i");	// current time
		$twoHour = date("H:i", time()+7200);	// time in two hours

		$query = "SELECT * FROM " . $assign2Table . " WHERE status = 'unassigned' AND pkDate = '$today' AND pkTime >= '$currTime' AND pkTime <= '$twoHour'";
		
		// executes the query and store result into the result pointer
		$result = mysqli_query($conn, $query);
		
		// check if its succesfull
		if(!$result){
			echo "<p>Something wrong with the query.  Please try again at a later time</p>";
		} else {
			// check if records return more than 0
			$rowcount = mysqli_num_rows($result);
			if($rowcount > 0){
				// display records
				echo "<table border=\"1\">";
				echo "<tr>\n"
				 ."<th scope=\"col\">Booking Number</th>\n"
			     ."<th scope=\"col\">Booking Information</th>\n"				 
				 ."</tr>\n";
				while($row = $result->fetch_assoc()) {					
					echo "<tr>";
					echo "<td>",$row["bkNum"],"</td>";
					echo "<td>",$row["cusName"]," (",$row["phone"],") will be picked up from ",$row["suburb"],". <br>Destination: ",$row["destSuburb"],
					" <br>Pickup Date: ",$row["pkDate"],"<br>Pickup Time: ",$row["pkTime"],"</td>";
					echo "</tr>";
				}
			} else {
				echo "<label>No Records to display</label>";
			}
			mysqli_free_result($result);
		}
		mysqli_close($conn);
	}
?>