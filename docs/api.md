## REST API authentication and headers

To make a REST API call, you must include request headers including the Authorization header with an OAuth 2.0 access token.

----
----
----

**Sign Up**

This is required to sign up a new user. When successful API returns an access token. This token must be used as an authentication token before with API's that require authenticated users.

----
/api/signup


* **Method:**
  
  `POST`
  
*  **URL Params**

   None 

* **Data Paramsbefoe**

   **Required:**
 
   `name=[string]`
   `email=[string]`
   `password=[string]`

* **Success Response:**
  
  * **Code:** 200 <br />
    **Content:** `{ token : "SU282865982.GTisis82sf.jsgiug29...",  id : 12, name : "creative automations", email : "creative@creative.com"}`
 
* **Error Response:**

  * **Code:** 401 UNAUTHORIZED <br />
    **Content:** `{ error : "User already exists" }`
  
* **Sample Call:**

  ```javascript
	    var url = 'api/signup' ;
		return $http({
			url: url,
			method: "POST",
			data: { 'name': name, 'email' : email,  'password' : pwd}
		})
		.then(function(response) {
				// success
				return response;
		}, 
		function(response) { // optional
				// failed
				return response;
		});
  ```
* **Notes:**

	To be updated after the API has been implemented 
----
----
----

**Sign In**

This is required to sign in a previously registered user. When successful API returns an access token. This token must be used as an authentication token before with API's that require authenticated users.

----
/api/signin


* **Method:**
  
  `POST`
  
*  **URL Params**

   None 

* **Data Params**

   **Required:**
 
   `email=[string]`
   `password=[string]`

* **Success Response:**
  
  * **Code:** 200 <br />
    **Content:** `{ token : "SU282865982.GTisis82sf.jsgiug29...",  id : 12, name : "creative automations", email : "creative@creative.com" , queuelist : [ {id: 110, name : "Queue 1"} , {id: 124, name : "Queue 2"}] }`
 
* **Error Response:**

  * **Code:** 401 UNAUTHORIZED <br />
    **Content:** `{ error : "Invalid Credentials" }`
  
* **Sample Call:**

  ```javascript
	    var url = 'api/signin' ;
		return $http({
			url: url,
			method: "POST",
			data: { 'email' : email,  'password' : pwd}
		})
		.then(function(response) {
				// success
				return response;
		}, 
		function(response) { // optional
				// failed
				return response;
		});
  ```
* **Notes:**

	To be updated after the API has been implemented 
----
----
----
  
----
----
----
**Create A New Queue**

Users can create a new *Queue* using this API call. This API requires *Authorization Header*

----
/api/queue


* **Method:**
  
  `POST` 
  
*  **URL Params**

	None

* **Data Params**

	**Required:**

	name=[string] 
 
* **Success Response:**
  
  * **Code:** 200 <br />
    **Content:** `{ id : 124 , name : "Queue 2" , queuelist : [ {id: 110, name : "Queue 1"} , {id: 124, name : "Queue 2"}] }`
 
* **Error Response:**

  * **Code:** 401 UNAUTHORIZED <br />
    **Content:** `{ error : "Authentication required to perform this call" }`
  
* **Sample Call:**

  ```javascript
	    var url = 'api/queue' ;
		return $http({
			url: url,
			method: "POST",
			data: { 'name': name},
			headers: {
				'Authorization': "Bearer ".concat(token)
			}

		})
		.then(function(response) {
				// success
				return response;
		}, 
		function(response) { // optional
				// failed
				return response;
		});
  ```
* **Notes:**

	To be updated after the API has been implemented 
  
----
----
----
  
**Move to next position in the queue**

Move to the next position in queue, specified in the URL. This API requires *Authorization Header*

----
/api/queue/:queue


* **Method:**
  
  `POST` 
  
*  **URL Params**

	None

* **Data Params**

  * **Required:** <br />
	action='movenext'
 
* **Success Response:**
  
  * **Code:** 200 <br />
    **Content:** `{ id : 124 , position : 24}`
 
* **Error Response:**

  * **Code:** 401 UNAUTHORIZED <br />
    **Content:** `{ error : "Authentication required to perform this call" }`
  
* **Sample Call:**

  ```javascript
	    var url = 'api/queue/' + queueid;
		return $http({
			url: url,
			method: "POST",
			data: { 'action': 'movenext'},
			headers: {
				'Authorization': "Bearer ".concat(token)
			}
		})
		.then(function(response) {
				// success
				return response;
		}, 
		function(response) { // optional
				// failed
				return response;
		});
  ```
* **Notes:**

	To be updated after the API has been implemented 
  
----
----
----
  
**Reset the queue position**

Reset the Queue position to 0, indicating the queue is inactive to its consumers, queue id is specified in the URL. This API requires *Authorization Header*

----
/api/queue/:queue


* **Method:**
  
  `POST` 
  
*  **URL Params**

	None

* **Data Params**

  * **Required:** <br />
	action='reset'

 
* **Success Response:**
  
  * **Code:** 200 <br />
    **Content:** `{ id : 124 , position : 0}`
 
* **Error Response:**

  * **Code:** 401 UNAUTHORIZED <br />
    **Content:** `{ error : "Authentication required to perform this call" }`
  
* **Sample Call:**

  ```javascript
	    var url = 'api/queue/' + queueid;
		return $http({
			url: url,
			method: "POST",
			data: { 'action': 'reset'},
			headers: {
				'Authorization': "Bearer ".concat(token)
			}
		})
		.then(function(response) {
				// success
				return response;
		}, 
		function(response) { // optional
				// failed
				return response;
		});
  ```
* **Notes:**

	To be updated after the API has been implemented 
  
----
----
----
  
**Get the current state of a queue**

Get the current state of a queue. Current position of the queue, appointment status \& next available slot, average time to serve each customer, start time and current time.  

----
/api/queue/:queue


* **Method:**
  
  `GET` 
  
*  **URL Params**

	None

* **Data Params**

  * **Required:** <br />
	action='reset'

 
* **Success Response:**
  
  * **Code:** 200 <br />
    **Content:** `{ id : 124 , position : 78 , servicestarted : 67258298629629 , timenow : 288529858258, timepercustomer : 187 , appointments : {status : "open" , availableposition : 94}}`
 
* **Error Response:**

  * **Code:** 404 Not Found <br />
    **Content:** `{ error : "Requested queue could not be found" }`
  
* **Sample Call:**

  ```javascript
	    var url = 'api/queue/' + queueid;
		return $http({
			url: url,
			method: "GET"
		})
		.then(function(response) {
				// success
				return response;
		}, 
		function(response) { // optional
				// failed
				return response;
		});
  ```
* **Notes:**

	To be updated after the API has been implemented 
  
----
----
----
 
**Appointment Administration**

Start or stop accepting *Appointment* requestus, clear all Appointments. This API requires *Authorization Header*

----
/api/:queue/appointment


* **Method:**
  
  `POST` 
  
*  **URL Params**

	None

* **Data Params**

  * **Required:** <br />
	action='open' | action='close' | action='reset'   

 
* **Success Response:**
  
  * **Code:** 200 <br />
    **Content:** `{ id : 124 , appointments : {status : "open" , availableposition : 14}}`
	
	OR
	
  * **Code:** 200 <br />
    **Content:** `{ id : 124 , appointments : {status : "open" , availableposition : 1}}`
 
* **Error Response:**

  * **Code:** 404 Not Found <br />
    **Content:** `{ error : "Requested queue could not be found" }`
  
* **Sample Call:**

  ```javascript
	    var url = 'api/' + queueid + '/appointment';
		return $http({
			url: url,
			method: "GET",
	  		data: { 'action': action},
	  		headers: {
				'Authorization': "Bearer ".concat(token)
			}

		})
		.then(function(response) {
				// success
				return response;
		}, 
		function(response) { // optional
				// failed
				return response;
		});
  ```
* **Notes:**

	To be updated after the API has been implemented 
  
----
----
----
  
**Booking an Appointment**

Authorized users should be able to book an appointment with a reference id. This API requires *Authorization Header*

----
/api/:queue/appointment


* **Method:**
  
  `POST` 
  
*  **URL Params**

	None

* **Data Params**

  * **Required:** <br />
	action='book'   | reference=[string]

 
* **Success Response:**
  
  * **Code:** 200 <br />
    **Content:** `{ id : 124 , position : 65 , reference : "Amit - AY 182 2016/11" }`
	
 
* **Error Response:**
	
	when the request made without signin 

	* **Code:** 401 UNAUTHORIZED <br />
    **Content:** `{ error : "Authentication required to perform this call" }`


	OR when the request was made on a queue that is not available

	* **Code:** 404 Not Found <br />
    **Content:** `{ error : "Requested queue could not be found" }`

	OR when the request made while appointments were not accepted
	
  * **Code:** 403 Forbidden<br />
    **Content:** `{ id : 124 , error : 'Appointments Closed' }`
	
	
* **Sample Call:**

  ```javascript
	    var url = 'api/' + queueid + '/appointment';
		return $http({
			url: url,
			method: "GET",
	  		data: { 'action': action , 'reference' : reference},
	  		headers: {
				'Authorization': "Bearer ".concat(token)
			}

		})
		.then(function(response) {
				// success
				return response;
		}, 
		function(response) { // optional
				// failed
				return response;
		});
  ```
* **Notes:**

	To be updated after the API has been implemented 
  
----
----
----
  
    
**Retrieve Appointments**

Authenticated users should be able to retrieve an appointment with a reference id. This API requires Authorization Header. When request is made by queue owner all appointments for this queue are returned. Else the appointments made by signed in user to the requested queue is returned

----
/api/:queue/appointment


* **Method:**
  
  `GET` 
  
*  **URL Params**

	None

* **Data Params**

	None
 
* **Success Response:**
  
  * **Code:** 200 <br />
    **Content:** `{ id : 124 , appointments : [{position : 23 , reference : "Amit - AY 182 2016/11" }, {position : 27 , reference : "Jatin - BS 04 2016/11" } ] }`
	
 
* **Error Response:**
	
	when the request made without signin 

	* **Code:** 401 UNAUTHORIZED <br />
    **Content:** `{ error : "Authentication required to perform this call" }`


	OR when the request was made on a queue that is not available

	* **Code:** 404 Not Found <br />
    **Content:** `{ error : "Requested queue could not be found" }`

	
	
* **Sample Call:**

  ```javascript
	    var url = 'api/' + queueid + '/appointment';
		return $http({
			url: url,
			method: "GET",
	  		headers: {
				'Authorization': "Bearer ".concat(token)
			}

		})
		.then(function(response) {
				// success
				return response;
		}, 
		function(response) { // optional
				// failed
				return response;
		});
  ```
* **Notes:**

	To be updated after the API has been implemented 
  
----
----
----
  
  
