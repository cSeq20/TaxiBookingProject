/*
Student Info: Chris Sequeira (14863759)
File used to send data to php files.

Description: Takes the datasource, divId, and form fields from booking.htm.
			 Passes the fields entered to processBooking.php using POST and displays
			 success result in the divId provided on booking.htm.
*/
var xhr = createRequest();
function getData(dataSource, divID, aName, aPhn, aStreetNum, aStrtName, aSuburb, aDest, aPckUpTime, aPckUpDate)  {
    if(xhr) {
	    var obj = document.getElementById(divID);
	    var requestbody = "name="+encodeURIComponent(aName)+"&phn="+encodeURIComponent(aPhn)+"&strNum="+encodeURIComponent(aStreetNum)+"&strName="
				+encodeURIComponent(aStrtName)+"&suburb="+encodeURIComponent(aSuburb)+"&dest="+encodeURIComponent(aDest)+"&pckTime="+encodeURIComponent(aPckUpTime)
				+"&pckDate="+encodeURIComponent(aPckUpDate);
	    xhr.open("POST", dataSource, true);
		xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	    xhr.onreadystatechange = function() {		    
			if (xhr.readyState == 4 && xhr.status == 200) {
				obj.innerHTML = xhr.responseText;
		    } // end if
	    } // end anonymous call-back function
	    xhr.send(requestbody);
	} // end if
} // end function getData()


/*
Description: Takes the divId from admin.htm.
			 Sets a connection to getBookings.php to get current bookings.
			 Displays the result in the divId provided from admin.htm
*/
function getBookings(div){
	var xhr = new XMLHttpRequest();
	xhr.onreadystatechange = function() {					
		if (xhr.readyState == 4 && xhr.status == 200) {
			document.getElementById(div).innerHTML = xhr.responseText;
		} // end if
	} // end anonymous call-back function
	xhr.open("GET", "getBookings.php", true);
	xhr.send();
}// end function