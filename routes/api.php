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

Route::post('/signup', function () {
   $credentials = Input::only('name','email', 'password');
   $credentials['password'] = Hash::make($credentials['password']);
   try {
	
       $user = User::create($credentials);
   } catch (Exception $e) {
     return Response::json($e); 
   }

   $token = JWTAuth::fromUser($user);

	return response('OK', 200)
	  ->header('Content-Type', 'application/json')
	  ->setContent(compact('token'));
});

Route::post('/signin', function () {
   $credentials = Input::only('email', 'password');

   if ( ! $token = JWTAuth::attempt($credentials)) {
       return Response::json(false, HttpResponse::HTTP_UNAUTHORIZED);
   }

	return response('OK', 200)
	  ->header('Content-Type', 'application/json')
	  ->setContent(compact('token'));
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

