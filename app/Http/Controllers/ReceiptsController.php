<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Http\Response as HttpResponse;
use App\Receipts as Receipts;
use App\Audit as Audit;

class ReceiptsController extends Controller
{
	/**
	 * Cancel & Existing Booking
	 *
	 * @return Response
	 */
   public function CreateReceipt() 
   {
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
					return Response::json( false,['message' => 'System Error', 'exception' => $e->getMessage()] );
				} 
			}
		} catch (Exception $e) {
		       return Response::json(false, HttpResponse::HTTP_UNAUTHORIZED);
		}	
	}
}
