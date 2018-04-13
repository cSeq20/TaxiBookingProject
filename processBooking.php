<?php
/**
Student Info: Chris Sequeira (14863759)
Description: Gets a connection to database on cmslamp14 server.  
			 Gets form input and validates it.  
			 Inserts form input to database table to save the booking
*/	
	require_once ("../../conf/settings.php"); 	// to use db credentials from secure file
	$conn = @mysqli_connect($host, $user, $pswd, $dbnm);	//gets a connection value, otherwise false

	// get form fields passed from client
	$name = $_POST['name'];
	$phn = $_POST['phn'];
	$num = $_POST['strNum'];
	$street = $_POST['strName'];
	$suburb = $_POST['suburb'];
	$dest = $_POST['dest'];
	$pckTime = $_POST['pckTime'];
	$pckDate = $_POST['pckDate'];
	
	$todayDate = date("d/m/y");	// bkDate for db
	$todayTime = date("h:i:sa");	// bkTime for db
	$status = "unassigned";	// field to send to db.
	
	// function to check if cusName and phone passed is valid
	function checkNamePhSet($nm, $ph){
		// check if not empty and set
		if(isset($nm) && !empty($nm) && isset($ph) && !empty($ph)){
			// check if phone is a number
			if(is_numeric($ph)){
				return true;
			} else {
				echo "<p>Phone number is not valid</p>";
			}			
		} else {
			echo "<p>Please enter your name and phone</p>";			
		}
		return false;
	}
	
	// function to check if street num and name passed is valid
	function checkAddressSet($strNum, $strName){
		if(isset($strNum) && !empty($strNum) && isset($strName) && !empty($strName)){
			return true;						
		} else {
			echo "<p>Please set your address</p>";			
		}
		return false;
	}
	
	// Check if time is set
	function checkDateTime($time){
		if(!isset($time) || empty($time)){
			echo "<p>Please enter a pickup time!</p>";
			return false;
		}
		
		return true;
	}
			
	//check connection
	if(!$conn){
		echo "<p>Database connection failure</p>";
	} else {
		// check if all fields are set
		if(checkNamePhSet($name, $phn) && checkAddressSet($num, $street) && checkDateTime($pckDate, $pckTime)){
			// check table exist
			
			$pkAddress = $num . " " . $street; 	// combine address fields to one variable
			$insertdate = Date("Y-m-d", strtotime($pckDate));	// change date format
			
			$chckDate = date("Y/m/d");	// todays date for checking
			$refDate = date("Y/m/d", strtotime($pckDate));	// date entered formated to check for time

			$time = date("H:i");	// current time.				
			$tpick = explode(":", $pckTime);	// split time entered to hours / minutes
			$ctime = explode(":", $time);	// split current time to hours / minutes			
			
			// query to add to db.
			$query = "insert into $assign2Table "
			. " (cusName, phone, pkUpAddress, suburb, destSuburb, pkDate, pkTime, bkDate, bkTime, status)"
			. "values"
			. "('$name', '$phn', '$pkAddress', '$suburb', '$dest', '$insertdate', '$pckTime' , '$todayDate', '$todayTime', '$status')";					
			
			// check if date picked is todays date
			if ($refDate == $chckDate) {												
				// compare times by hour first
				if($tpick[0] > $ctime[0]){	// valid time based on hours
					// execute query
					$result = mysqli_query($conn, $query);
					if(!$result){
						echo "<p>Something wrong with the query.  Please try again at a later time</p>";
					} else {
						$last_id = mysqli_insert_id($conn);							
				
						echo "Thank you! Your booking reference number is " . $last_id . ". You will be picked up from your provided address at " . $pckTime . " on " . $pckDate;
						mysqli_free_result($result);
					}
					
				} elseif ($tpick[0] == $ctime[0]) { // if hours equal
					if($tpick[1] >= $ctime[1]){	// valid time based on minutes 
						// execute query
						$result = mysqli_query($conn, $query);
						// check if its succesfull
						if(!$result){
							echo "<p>Something wrong with the query.  Please try again at a later time</p>";
						} else {
							$last_id = mysqli_insert_id($conn);											
							echo "Thank you! Your booking reference number is " . $last_id . ". You will be picked up from your provided address at " . $pckTime . " on " . $pckDate;
							
							mysqli_free_result($result);
						}
					} else {	// time is not ok
						echo "<p>Time is before current time.  Please pick a differnt time</p>";
					}
					
				} else {	// hours is less then time is not ok
					echo "<p>Time is before current time.  Please pick a differnt time</p>";
				} 
			// if picked date is greater than today add new booking.
			} elseif ($refDate > $chckDate) {				
				$result = mysqli_query($conn, $query);
				if(!$result){
					echo "<p>Something wrong with the query.  Please try again at a later time</p>";
				} else {
					$last_id = mysqli_insert_id($conn);							
					echo "Thank you! Your booking reference number is " . $last_id . ". You will be picked up from your provided address at " . $pckTime . " on " . $pckDate;
					
					mysqli_free_result($result);
				}				
			}				
		}
		mysqli_close($conn);
	}
?>