<?php

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Response as HttpResponse;
use App\Receipts as Receipts;
use App\Audit as Audit;
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

Route::post('/signupcrap', function () {
   $credentials = Input::only('name','email', 'password');
 	return response('OK', 200)
	  ->header('Content-Type', 'application/json')
	  ->setContent(['name' => $credentials['name'],
					'email' => $credentials['email'],
					'password' => $credentials['password']]);

});


Route::post('/signup', function () {
   $credentials = Input::only('name','email', 'password');
   $credentials['password'] = Hash::make($credentials['password']);
   try {
	
       $user = User::create($credentials);
   } catch (Exception $e) {
		return response('Sign Up Failed', 401)
		  ->header('Content-Type', 'application/json')
		  ->setContent(['exception' => $e ]);
   }

   $token = JWTAuth::fromUser($user);
   $user = JWTAuth::toUser($token);

	return response('OK', 200)
	  ->header('Content-Type', 'application/json')
	  ->setContent(['token' => $token,
					'id' => $user->id,
					'name' => $user->name,
					'email' => $user->email]);

});

Route::post('/signin', function () {
   $credentials = Input::only('email', 'password');

   if ( ! $token = JWTAuth::attempt($credentials)) {
       return Response::json(false, HttpResponse::HTTP_UNAUTHORIZED);
   }
   $user = JWTAuth::toUser($token);

	return response('OK', 200)
	  ->header('Content-Type', 'application/json')
	  ->setContent(['token' => $token,
					'id' => $user->id,
					'name' => $user->name,
					'email' => $user->email]);
});

## Logging out of the server
Route::get('/signout', [
   'before' => 'jwt-auth',
   function () {
      try {
	JWTAuth::invalidate(JWTAuth::getToken());	  
	return Response::json(['status' => 'User Logged Out']);
     } catch (Exception $e) {
	       return Response::json(false, HttpResponse::HTTP_UNAUTHORIZED);
     }
   }
]);

Route::post('/receipts', ['before' => 'jwt-auth', 'uses' => 'ReceiptsController@CreateReceipt']);
Route::put('/receipts/{bill_number}', ['before' => 'jwt-auth', 'uses' => 'ReceiptsController@UpdateReceipt']);
Route::get('/receipts/{bill_number}', ['before' => 'jwt-auth', 'uses' => 'ReceiptsController@GetReceiptByBillNumber']);
Route::post('/receipts/search', ['before' => 'jwt-auth', 'uses' => 'ReceiptsController@SearchReceipts']);
Route::get('/audit/{bill_number}', ['before' => 'jwt-auth', 'uses' => 'ReceiptsController@GetAuditTrailForABill']);

Route::post('/organizations', ['before' => 'jwt-auth', 'uses' => 'OrganizationController@CreateOrganization']);
Route::put('/organizations', ['before' => 'jwt-auth', 'uses' => 'OrganizationController@UpdateOrganization']);
Route::get('/organizations', ['before' => 'jwt-auth', 'uses' => 'OrganizationController@GetOrganization']);
Route::post('/organizations/membership', ['before' => 'jwt-auth', 'uses' => 'OrganizationController@MembershipRequest']);
Route::get('/organizations/membership', ['before' => 'jwt-auth', 'uses' => 'OrganizationController@GetMembershipStatus']);

Route::post('/queue', ['before' => 'jwt-auth', 'uses' => 'QueueController@CreateQueue']);
Route::get('/queue', ['before' => 'jwt-auth', 'uses' => 'QueueController@GetQueues']);
Route::post('/queue/{queueid}', ['before' => 'jwt-auth', 'uses' => 'QueueController@UpdateQueueStatus']);
Route::get('/queue/{queueid}', ['uses' => 'QueueController@GetQueueStatus']);

Route::post('/queue/{queueid}/preferences', ['before' => 'jwt-auth', 'uses' => 'QueueController@SetPreferences']);
Route::get('/queue/{queueid}/preferences', ['before' => 'jwt-auth', 'uses' => 'QueueController@GetPreferences']);

Route::post('/queue/{queueid}/preferences', ['before' => 'jwt-auth', 'uses' => 'QueueController@SetPreferences']);
Route::post('/queue/{queueid}/appointment', ['before' => 'jwt-auth', 'uses' => 'QueueController@ManageAppointments']);



Route::get('/queue/{queueid1}', [
   function ( $queueid ) {
		return response('OK', 200)
	  ->header('Content-Type', 'application/json')
	  ->setContent(['id' => $queueid,
					'type' => 'GET',
					'position' => 125]);

   }
]);


