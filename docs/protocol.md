#Server: http://34.210.16.40:8000/

## Examine a Queue Status

Simply call this API

`
/api/queue/[Queue Id]

Example

http://34.210.16.40:8000/api/queue/2
`

and you will get something line

`
{"error":false,"id":2,"position":0,"servicestarted":null,"lastupdate":null,"timepercustomer":0,"accepting_appointments":0,"initial_free_slots":0,"recurring_free_slot":0,"next_available_slot":1,"name":"My Queue"}
`

## How to start managing a Queue if you are a new user

* Register to the system and save the token recieved in response

http://ec2-34-210-16-40.us-west-2.compute.amazonaws.com:8000/api/signup

When you use this API, the data needed will be on the API documentation page

and you will get something like

`
{"token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjI2LCJpc3MiOiJodHRwOlwvXC9lYzItMzQtMjEwLTE2LTQwLnVzLXdlc3QtMi5jb21wdXRlLmFtYXpvbmF3cy5jb206ODAwMFwvYXBpXC9zaWdudXAiLCJpYXQiOjE0OTIzNDQ5MDQsImV4cCI6MTQ5MjM0ODUwNCwibmJmIjoxNDkyMzQ0OTA0LCJqdGkiOiI3YmJmNGQ5Y2Y1ZDE3NjY3MDE3M2JlYzdkOWIwYTFlYiJ9.Hv4RyI7PIifxgUG1QY8HGT9088yWkurq8QJBihaQ8-g","id":26,"name":"Aashish Agarwal","email":"aashish1@gmail.com"}
`

* Call API to create a queue

To call this API, you need to send

Token as Authorization header
name as data

API will look like (POST)
`
http://ec2-34-210-16-40.us-west-2.compute.amazonaws.com:8000/api/queue
`

And You will get something like

`
{
  "error": false,
  "id": 7,
  "name": "Ghaziabad",
  "queuelist": [
    {
      "id": 7,
      "user_id": 26
    }
  ]
}
`
Now whne you have got the Queue Id, you can update queue state, to move next or to reset. To do this call API
	Token as Authorization header
	with data
		'action' : movenext
		or
		'action' : reset

		`	
http://ec2-34-210-16-40.us-west-2.compute.amazonaws.com:8000/api/queue/7
`

you will get something like this when you call with action movenext
	
`
{
  "error": false,
  "id": 7,
  "position": 4,
  "name": "Ghaziabad"
}
`

you will get something like this when you call with action reset
	
`
{
  "error": false,
  "id": 7,
  "position": 0,
  "name": "Ghaziabad"
}
`

If your token expires, than calling this API will result in authentication error. If that happens, relogin to the system using Signin API


`
http://ec2-34-210-16-40.us-west-2.compute.amazonaws.com:8000/api/signin
`

and you will get something like

`
{
  "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjI3LCJpc3MiOiJodHRwOlwvXC9lYzItMzQtMjEwLTE2LTQwLnVzLXdlc3QtMi5jb21wdXRlLmFtYXpvbmF3cy5jb206ODAwMFwvYXBpXC9zaWduaW4iLCJpYXQiOjE0OTIzNDU3MjEsImV4cCI6MTQ5MjM0OTMyMSwibmJmIjoxNDkyMzQ1NzIxLCJqdGkiOiJmNGMyMDgzNGVlOTU1YTEzYmM1ZTg0ZmE0M2MxYzg0ZCJ9.stTgXJzXhsrr_PzOKgw0lUOZdyNC4V273u1HSOXXo6s",
  "id": 27,
  "name": "Aashish",
  "email": "ash@gmail.com"
}
`

rest of the flow remains the same

## How to ge the queues that you own

use Authorization header with token
use GET 

`
http://ec2-34-210-16-40.us-west-2.compute.amazonaws.com:8000/api/queue
`

`
and you will get something like
{
  "error": false,
  "queues": [
    {
      "id": 8,
      "user_id": 27
    },
    {
      "id": 9,
      "user_id": 27
    }
  ]
}
`
