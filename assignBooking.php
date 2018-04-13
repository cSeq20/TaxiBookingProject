<?php
/**
Student Info: Chris Sequeira (14863759)
Description: Gets a connection to database on cmslamp14 server.  
Gets booking id user entered in admin.htm form using Post and querys database with it.
If row exists, changes the status field to 'assigned'.
*/
	require_once ("../../conf/settings.php"); 	// to be able to use db details from a secure file
	$conn = @mysqli_connect($host, $user, $pswd, $dbnm);	//gets a connection value, otherwise false
		
		// Checks if connection is successful
		if (!$conn) {
			// Displays an error message
			echo "<p>Database connection failure</p>";
		} else {
			// check if booking number entered
			if(isset($_POST['num']) && !empty($_POST['num'])){
				// get field value
				$bkNum = $_POST['num'];
						
				// sql command to get row based on bk number inputed.
				$query = "SELECT * FROM taxibooking WHERE bkNum = " .intval($bkNum);
				
				// executes the query and stores result into the result pointer
				$result = mysqli_query($conn, $query);
				
				// check if its succesfull
				if(!$result){
					echo "<p>Something wrong with the query.  Please try again at a later time</p>";
				} else {
					$rowcount = mysqli_num_rows($result);	// num rows
					if($rowcount > 0){
						// update the query
						$updateQuery = "UPDATE taxibooking SET status='assigned' WHERE bkNum = " .intval($bkNum);
									
						// check if its succesfull
						if(!mysqli_query($conn, $updateQuery)){
							echo "<p>Something wrong with the query. ". mysqli_error($conn) ."</p>";
						} else {
							echo "<p>The booking request " . $bkNum . " has been properly assigned</p>";
						}
						
					} else {
						echo "No Records found with that Booking Number";
					}
				} 				
			} else {
				echo "Enter a booking number";
			}
			// close db connection
			mysqli_close($conn);
		}
?>