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

   return Response::json(compact('token'));
});

Route::post('/signin', function () {
   $credentials = Input::only('email', 'password');

   if ( ! $token = JWTAuth::attempt($credentials)) {
       return Response::json(false, HttpResponse::HTTP_UNAUTHORIZED);
   }

   return Response::json(compact('token'));
});


Route::post('/receipts', [
   'before' => 'jwt-auth',
   function () {
		$token = JWTAuth::getToken();

		try { 
			$user = JWTAuth::toUser($token);
			$bill_details = Input::only('bill_number','bill_date','b17_debit','description','invoice_no','invoice_date','procurement_certificate','procurement_date','unit_weight','unit_quantity','value','duty','transport_registration','receipt_timestamp','balance_quantity','balance_value');

			$receipt = Receipts::where('bill_number', '=', $bill_details['bill_number'] )->get();

			if( $receipt -> first()){
				return Response::json(['message' => 'Operation Failed: Duplicate Receipt']);
			}else{
				try {
					$receipt = Receipts::create($bill_details);
					
					# Create Audit Record 
					
					return Response::json(['message' => 'Receipt Recorded']);
				} catch (\Illuminate\Database\QueryException $e) {
					return Response::json( ['message' => 'System Error', 'exception' => $e->getMessage()] );
				} 
			}
		} catch (Exception $e) {
			return Response::json(['message' => 'Unauthorized Access']);
		}	
	}
]);

Route::put('/receipts/{bill_number}', [
   'before' => 'jwt-auth',
   function ( $bill_number) {

		$token = JWTAuth::getToken();
		try { 
			$user = JWTAuth::toUser($token);
		} catch (Exception $e) {
			return Response::json(['message' => 'Unauthorized Access']);
		}	

		try { 
			$user = JWTAuth::toUser($token);
			$bill_details = Input::except('bill_number');

			$receipts = Receipts::where('bill_number', '=', $bill_number )->get();

			if( $receipts -> first()){
				$receipts -> first()->update($bill_details);
				return Response::json(['message' => 'Receipt Updated']);
			}else{
				return Response::json(['message' => 'Operation Failed: Receipt with Bill Number: ' + $bill_number + " Not Found"]);
			}
		} catch (Exception $e) {
			return Response::json(['message' => 'Failure Updating Receipt Details for bill_number: ' + $bill_number ]);
		}	
	}
]);

Route::get('/receipts/{bill_number}', [
   'before' => 'jwt-auth',
   function ( $bill_number ) {
		$token = JWTAuth::getToken();
		try { 
			$user = JWTAuth::toUser($token);
		} catch (Exception $e) {
			return Response::json(['message' => 'Unauthorized Access']);
		}	

		try { 
			$receipts = Receipts::where('bill_number', '=', $bill_number )->get();

			if( $receipts -> first()){
				return Response::json($receipts -> first());
			}else{
				return Response::json(['message' => 'Operation Failed: Receipt with Bill Number: ' + $bill_number + " Not Found"]);
			}
		} catch (Exception $e) {
			return Response::json(['message' => 'Failure Getting Receipt Details for bill_number: ' + $bill_number ]);
		}	
	}
]);

Route::get('/audit/{bill_number}', [
   'before' => 'jwt-auth',
   function ( $bill_number ) {
		$token = JWTAuth::getToken();
		try { 
			$user = JWTAuth::toUser($token);
		} catch (Exception $e) {
			return Response::json(['message' => 'Unauthorized Access']);
		}	

		try { 
			$audit = Audit::where('bill_number', '=', $bill_number )->get();

			if( $audit -> isEmpty()){
				return Response::json(['message' => 'Operation Failed: Audit Trail with Bill Number: ' + $bill_number + " Not Found"]);
			}else{
				return Response::json($audit);
			}
		} catch (Exception $e) {
			return Response::json(['message' => 'Failure Getting Audit Trail for bill_number: ' + $bill_number ]);
		}	
	}
]);

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


