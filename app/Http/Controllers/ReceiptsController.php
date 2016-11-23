<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;

use App\Http\Requests;
use Illuminate\Http\Response as HttpResponse;
use App\Receipts as Receipts;
use App\Audit as Audit;
use Illuminate\Support\Facades\Input;
use Response;

class ReceiptsController extends Controller
{

    public function checkToken()
    {
        try 
        {
            if (! $user = JWTAuth::parseToken()->authenticate())
            {
                return [
                    'error' => true,
                    'code'  => 10,
                    'details'  => [
                        'message'   => 'User not found by given token'
                    ]
                ];
            }

        } catch (TokenExpiredException $e) {

            return [
                'error' => true,
                'code'  => 11,
                'details'  => [
                    'message'   => 'Token Expired'
                ]
            ];

        } catch (TokenInvalidException $e) {

            return [
                'error' => true,
                'code'  => 12,
                'details'  => [
                    'message'   => 'Invalid Token'
                ]
            ];

        } catch (JWTException $e) {

            return [
                'error' => true,
                'code'  => 13,
                'details'  => [
                    'message'   => 'Token absent'
                ]
            ];

        }

        return ['error' => false, 'token' => JWTAuth::getToken()];
    }

	


	/**
	 * Create Receipt
	 *
	 * @return Response
	 */
   public function CreateReceipt()    {
		$JWTValidationResult = $this->checkToken();
		if ( $JWTValidationResult['error'] ) {
				return response('Unauthorized', 401)
                  ->header('Content-Type', 'application/json')
				  ->setContent($JWTValidationResult);
		}

		$token = $JWTValidationResult['token'];
		$user = JWTAuth::toUser($token);

		try { 
			$bill_details = Input::only('bill_number','bill_date','b17_debit','description','unit','customs_station','warehouse_details','eou_details','other_procurement_source','invoice_no','invoice_date','procurement_certificate','procurement_date','weight','quantity','value','duty','transport_registration','receipt_timestamp','balance_quantity','balance_value');

			$receipt = Receipts::where('bill_number', '=', $bill_details['bill_number'] )->get();

			if( $receipt -> first()){
				return Response::json(['message' => 'Operation Failed: Duplicate Receipt']);
			}else{
				try {
					$receipt = Receipts::create($bill_details);
					
					# Create Audit Record
					$audit_change_log = json_encode($bill_details);
					$audit_email = $user->email;
					$category = 'Receipt';
					$action = 'Added';
					
					$audit_record_data = array ('bill_number' => $bill_details['bill_number'], 
												'email' => $audit_email, 
												'action' => $action, 
												'change_log' => $audit_change_log, 
												'category' => $category);
					$audit = Audit::create($audit_record_data);
					
					
					return Response::json(['message' => 'Receipt Recorded']);
				} catch (\Illuminate\Database\QueryException $e) {
					return Response::json( ['message' => 'System Error', 'exception' => $e->getMessage()] );
				} 
			}
		} catch (Exception $e) {
		       return Response::json(false, HttpResponse::HTTP_UNAUTHORIZED);
		}	
	}


   public function UpdateReceipt( $bill_number) {
		$JWTValidationResult = $this->checkToken();
		if ( $JWTValidationResult['error'] ) {
				return response('Unauthorized', 401)
                  ->header('Content-Type', 'application/json')
				  ->setContent($JWTValidationResult);
		}

		$token = $JWTValidationResult['token'];
		$user = JWTAuth::toUser($token);

		try { 
			$bill_details = Input::except('bill_number');

			$receipts = Receipts::where('bill_number', '=', $bill_number )->get();

			if( $receipts -> first()){
				$receipts -> first()->update($bill_details);
				
					# Create Audit Record
					$audit_change_log = json_encode($bill_details);
					$audit_email = $user->email;
					$category = 'Receipt';
					$action = 'Updated';
					
					$audit_record_data = array ('bill_number' => $bill_number, 
												'email' => $audit_email, 
												'action' => $action, 
												'change_log' => $audit_change_log, 
												'category' => $category);
					$audit = Audit::create($audit_record_data);

					
				return Response::json(['message' => 'Receipt Updated']);
			}else{
				return Response::json(['message' => 'Operation Failed: Receipt with Bill Number: ' . $bill_number . ' Not Found']);
			}
		} catch (Exception $e) {
			return Response::json(['message' => 'Failure Updating Receipt Details for bill_number: ' . $bill_number ]);
		}	
	}
	
   public function GetReceiptByBillNumber ( $bill_number ) {
		$JWTValidationResult = $this->checkToken();
		if ( $JWTValidationResult['error'] ) {
				return response('Unauthorized', 401)
                  ->header('Content-Type', 'application/json')
				  ->setContent($JWTValidationResult);
		}

		try { 
			$receipts = Receipts::where('bill_number', '=', $bill_number )->get();

			if( $receipts -> first()){
				return Response::json($receipts -> first());
			}else{
				return Response::json(['message' => 'Operation Failed: Receipt with Bill Number: ' . $bill_number . ' Not Found']);
			}
		} catch (Exception $e) {
			return Response::json(['message' => 'Failure Getting Receipt Details for bill_number: ' . $bill_number ]);
		}	
	}

   public function SearchReceipts ( ) {
		$JWTValidationResult = $this->checkToken();
		if ( $JWTValidationResult['error'] ) {
				return response('Unauthorized', 401)
                  ->header('Content-Type', 'application/json')
				  ->setContent($JWTValidationResult);
		}

		$filter_conditions = Input::all();
		$field = array_keys($filter_conditions)[0];
		$field_value = $filter_conditions[$field]["value"];
		$operator = $filter_conditions[$field]["operation"];

		try { 
			$receipts = Receipts::where($field, $operator , $field_value )->get();

			if( $receipts->isEmpty()){
				return response('Not Found', 404)
                  ->header('Content-Type', 'application/json')
				  ->setContent([
                'error' => true,
                'code'  => 20,
                'details'  => [
                    'message'   => 'No Matching Records Found'
                ]]);
				
			} else {
				return response('OK', 200)
                  ->header('Content-Type', 'application/json')
				  ->setContent($receipts);
			}
		} catch (Exception $e) {
				return response('Internal Server Error', 500)
                  ->header('Content-Type', 'application/json')
				  ->setContent(	[
                'error' => true,
                'code'  => 21,
                'details'  => [
                    'message'   => 'System Error',
					'exception' => $e->getMessage()
                ]]);
		}	
	}

   public function GetAuditTrailForABill ( $bill_number ) {
		$JWTValidationResult = $this->checkToken();
		if ( $JWTValidationResult['error'] ) {
				return response('Unauthorized', 401)
                  ->header('Content-Type', 'application/json')
				  ->setContent($JWTValidationResult);
		}

		try { 
			$audit = Audit::where('bill_number', '=', $bill_number )->get();

			if( $audit->isEmpty()){
				return Response::json(['message' => 'Operation Failed: Audit Trail with Bill Number: ' . $bill_number . " Not Found"]);
			} else {
				foreach ( $audit as $audit_record ) {
					$audit_record['change_log'] = json_decode($audit_record['change_log']);
				}
				return Response::json($audit);
			}
		} catch (Exception $e) {
			return Response::json(['message' => 'Failure Getting Audit Trail for bill_number: ' . $bill_number ]);
		}	
	}
	
}
