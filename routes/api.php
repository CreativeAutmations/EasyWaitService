<?php

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Response as HttpResponse;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/signup', function () {
   $credentials = Input::only('name','email', 'password');
   $credentials['password'] = Hash::make($credentials['password']);
   try {
	
       $user = User::create($credentials);
   } catch (Exception $e) {
     return Response::json($e); 
	//	return Response::json(['error' => 'User already exists.'], HttpResponse::HTTP_CONFLICT);
   }

   $token = JWTAuth::fromUser($user);

   return Response::json(compact('token'));
});

Route::post('/signin', function () {
   $credentials = Input::only('email', 'password');

   if ( ! $token = JWTAuth::attempt($credentials)) {
       return Response::json(false, HttpResponse::HTTP_UNAUTHORIZED);
   }

   return Response::json(compact('token'));
});


Route::post('/receipts', 
   function () {
	$bill_details = Input::only('bill_number','bill_date','b17_debit','description','invoice_no','invoice_date','procurement_certificate','procurement_date','unit_weight','unit_quantity','value','duty','transport_registration','receipt_timestamp','balance_quantity','balance_value');
	return Response::json(['message' => 'Unauthorized Access',
				'count' => count($bill_details),
				'bill_number' =>  implode(" ",$bill_details) ]);
						
});


Route::get('/restricted', [
   'before' => 'jwt-auth',
   function () {
       $token = JWTAuth::getToken();
      try { 
	
	$user = JWTAuth::toUser($token);

       return Response::json([
           'data' => [
               'email' => $user->email,
               'registered_at' => $user->created_at->toDateTimeString()
           ]
       ]);
	} catch (Exception $e) {
      		 return Response::json(['error' => 'Unauthorized Access']);
   	}	
   }
]);

Route::get('/signout', [
   'before' => 'jwt-auth',
   function () {
      try {
	JWTAuth::invalidate(JWTAuth::getToken());	  
	return Response::json(['status' => 'User Logged Out']);
     } catch (Exception $e) {
	return Response::json(['error' => 'Unauthorized Access']);
     }
   }
]);


