/*
Student Info: Chris Sequeira (14863759)
Description: Takes the datasource, divId, and booking number from admin.htm.
			 Passes the booking num to assignBooking.php using POST and displays
			 result in the divId on admin.htm.
*/

var xhr = createRequest();
function assignTaxi(dataSource, divID, aNum){
	if(xhr){
		var obj = document.getElementById(divID);
		var requestbody = "num="+encodeURIComponent(aNum);
		xhr.open("POST", dataSource, true);
		xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
		xhr.onreadystatechange = function() {		    
			if (xhr.readyState == 4 && xhr.status == 200) {
				obj.innerHTML = xhr.responseText;
		    } // end if
	    } // end anonymous call-back function
		xhr.send(requestbody);
	}	
}