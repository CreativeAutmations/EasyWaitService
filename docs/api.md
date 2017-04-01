# REST API authentication and headers

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
    **Content:** `{ token : "SU282865982.GTisis82sf.jsgiug29...",  id : 12, name : "creative automations", email : "creative@creative.com"}`
 
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

	**Required:**

	name=[string] 

* **Data Params**

	None
 
* **Success Response:**
  
  * **Code:** 200 <br />
    **Content:** `{ id : 124 , name : "My Queue"}`
 
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
  
  
