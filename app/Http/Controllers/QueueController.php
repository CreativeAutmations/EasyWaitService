<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Queue as Queue;
use App\QueueAdmin as QueueAdmin;

use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;

use App\Http\Requests;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Input;
use Response;



class QueueController extends Controller
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

    //
   public function CreateQueue()    {
		$JWTValidationResult = $this->checkToken();
		if ( $JWTValidationResult['error'] ) {
				return response('Unauthorized', 401)
                  ->header('Content-Type', 'application/json')
				  ->setContent($JWTValidationResult);
		}

		$token = $JWTValidationResult['token'];
		$user = JWTAuth::toUser($token);
		
		# Create a new Queue
		# Get the new Queue Id
		# Create a new Queue Admin Record
		# Get the list of all queues for current user
		# Return JSON
		
		# Create Organization
	    $qdetails = Input::only('name');
		try {
			$queue = new Queue();
			$queue['name'] = $qdetails['name'];
			$queue->save();

			# Associate queue with the creator as admin
			$qadmin = new QueueAdmin();
			$qadmin->user_id = $user->id;
			$qadmin->id = $queue->id;
			$qadmin->save();

			$queuelist = QueueAdmin::where('user_id', '=', $user->id )
												->get();
			# Content: { id : 124 , name : "Queue 2" , queuelist : [ {id: 110, name : "Queue 1"} , {id: 124, name : "Queue 2"}] }
			
			return response('OK', 200)
				->header('Content-Type', 'application/json')
				->setContent([
					'error' => false,
					'id' => $queue->id,
					'name' => $queue->name,
					'queuelist' => $queuelist]);
		} catch (\Illuminate\Database\QueryException $e) {
			return response('Unauthorized', 401)
				->header('Content-Type', 'application/json')
				->setContent([
					'error' => true,
					'code'  => 12,
					'details'  => ['message'   => 'Invalid Token']]);
		} 
	}
}
