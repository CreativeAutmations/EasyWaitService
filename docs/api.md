**REST API authentication and headers**

To make a REST API call, you must include request headers including the Authorization header with an OAuth 2.0 access token.

----
----
----

**Sign Up**
----
/api/signup


* **Method:**
  
  `POST`
  
*  **URL Params**

   None 

* **Data Params**

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

