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
					'details'  => ['message'   => 'Error Createing Queue', 'exception' => $e]]);
		} 
	}

	public function isAdmin( $user_id, $queue_id)
	{
		# if the authenticatd user is not an administrator for the queue, return with exception
		$qadmin = QueueAdmin::where([['user_id',$user_id], ['id',$queue_id]])->get();
		if ( $qadmin->isEmpty() ) 
		{
				return false;
		}
		
		return true;
	}

    //
   public function UpdateQueueStatus($queue_id)    
   {
		$JWTValidationResult = $this->checkToken();
		if ( $JWTValidationResult['error'] ) {
				return response('Unauthorized', 401)
                  ->header('Content-Type', 'application/json')
				  ->setContent($JWTValidationResult);
		}

		$token = $JWTValidationResult['token'];
		$user = JWTAuth::toUser($token);
		
		# if the authenticatd user is not an administrator for the queue, return with exception
		$qadmin = QueueAdmin::where([['user_id',$user->id], ['id',$queue_id]])->get();
		if ( $qadmin->isEmpty() ) {
				return response('Unauthorized', 401)
                  ->header('Content-Type', 'application/json')
					->setContent([
						'error' => true,
						'details'  => ['message'   => 'Invalid Queue or Unauthorized Access']]);
		}
		 
		
		# If caller is queue administrator, take required action and return queue state
	    $callparams = Input::only('action');
		$action = $callparams['action'];
		
		if ( $action != "movenext"  && $action != "reset" ) {
				return response('Unauthorized', 401)
                  ->header('Content-Type', 'application/json')
					->setContent([
						'error' => true,
						'details'  => ['message'   => 'Unsupported Action']]);
		}

		try {
			$queue = Queue::find($queue_id);
			
			if ( $action == "movenext" ) {
				$queue->current_position = $queue->current_position + 1;
			}

			if ( $action == "reset" ) {
				$queue->current_position = 0;
			}
			
			$queue->save();

			# Content: { id : 124 , position : 0}
			return response('OK', 200)
				->header('Content-Type', 'application/json')
				->setContent([
					'error' => false,
					'id' => $queue->id,
					'position' => $queue->current_position,
					'name' => $queue->name]);
		} catch (\Illuminate\Database\QueryException $e) {
			return response('Unauthorized', 401)
				->header('Content-Type', 'application/json')
				->setContent([
					'error' => true,
					'code'  => 12,
					'details'  => ['message'   => 'Queue Status Update Failed', 'exception' => $e ]]);
		} 
	}
    //
	
	
   public function GetQueues()    
   {
		$JWTValidationResult = $this->checkToken();
		if ( $JWTValidationResult['error'] ) {
				return response('Unauthorized', 401)
                  ->header('Content-Type', 'application/json')
				  ->setContent($JWTValidationResult);
		}

		$token = $JWTValidationResult['token'];
		$user = JWTAuth::toUser($token);
		
		# if the authenticatd user is not an administrator for the queue, return with exception
		$qadmin = QueueAdmin::where('user_id',$user->id)->get();
		if ( $qadmin->isEmpty() ) {
			return response('OK', 200)
				->header('Content-Type', 'application/json')
				->setContent([
					'error' => true,
					'code' => 101,
					'message' => 'no queues available']);
		} else 
		{
			return response('OK', 200)
				->header('Content-Type', 'application/json')
				->setContent([
					'error' => false,
					'queues' => $qadmin]);
		}
		 
	}

	public function GetPreferences($queue_id)    
   {
		
		$JWTValidationResult = $this->checkToken();
		if ( $JWTValidationResult['error'] ) {
				return response('Unauthorized', 401)
                  ->header('Content-Type', 'application/json')
				  ->setContent($JWTValidationResult);
		}

		$token = $JWTValidationResult['token'];
		$user = JWTAuth::toUser($token);
		
		# Retrurn if the user is not an administrator of the queue
		# if the authenticatd user is not an administrator for the queue, return with exception
		if ( ! $this->isAdmin() ) {
				return response('Unauthorized', 401)
                  ->header('Content-Type', 'application/json')
					->setContent([
						'error' => true,
						'details'  => ['message'   => 'Invalid Queue or Unauthorized Access']]);
		}
		

		try {
			$queue = Queue::find($queue_id);
			return response('OK', 200)
				->header('Content-Type', 'application/json')
				->setContent([
					'error' => false,
					'id' => $queue->id,
					'initial_free_slots' => $queue->initial_free_slots,
					'recurring_free_slot' => $queue->recurring_free_slot,
					'name' => $queue->name]);
		} catch (\Illuminate\Database\QueryException $e) {
			return response('Unauthorized', 401)
				->header('Content-Type', 'application/json')
				->setContent([
					'error' => true,
					'code'  => 12,
					'details'  => ['message'   => 'Queue Preference Update Failed', 'exception' => $e ]]);
		} 
   }

	public function SetPreferences($queue_id)    
   {
		
		$JWTValidationResult = $this->checkToken();
		if ( $JWTValidationResult['error'] ) {
				return response('Unauthorized', 401)
                  ->header('Content-Type', 'application/json')
				  ->setContent($JWTValidationResult);
		}

		$token = $JWTValidationResult['token'];
		$user = JWTAuth::toUser($token);
		
		# Retrurn if the user is not an administrator of the queue
		# if the authenticatd user is not an administrator for the queue, return with exception
		if ( ! $this->isAdmin() ) {
				return response('Unauthorized', 401)
                  ->header('Content-Type', 'application/json')
					->setContent([
						'error' => true,
						'details'  => ['message'   => 'Invalid Queue or Unauthorized Access']]);
		}
		
		# Retrieve Queue Record
	    $preferences = Input::only('initial_free_slots','recurring_free_slot');

		try {
			$queue = Queue::find($queue_id);
			
			if ( ! empty($preferences['initial_free_slots'])) 
			{
				$queue->initial_free_slots = $preferences['initial_free_slots'];
			}
			if ( ! empty($preferences['recurring_free_slot'])) 
			{
				$queue->recurring_free_slot = $preferences['recurring_free_slot'];
			}

			$queue->save();

			return response('OK', 200)
				->header('Content-Type', 'application/json')
				->setContent([
					'error' => false,
					'id' => $queue->id,
					'initial_free_slots' => $queue->initial_free_slots,
					'recurring_free_slot' => $queue->recurring_free_slot,
					'name' => $queue->name]);
		} catch (\Illuminate\Database\QueryException $e) {
			return response('Unauthorized', 401)
				->header('Content-Type', 'application/json')
				->setContent([
					'error' => true,
					'code'  => 12,
					'details'  => ['message'   => 'Queue Preference Update Failed', 'exception' => $e ]]);
		} 
   }
   
	public function GetQueueStatus($queue_id)    
   {
		
		# if the authenticatd user is not an administrator for the queue, return with exception
		$queue = Queue::where('id',$queue_id)->get();
		if ( $queue->isEmpty() ) {
				return response('Unauthorized', 401)
                  ->header('Content-Type', 'application/json')
					->setContent([
						'error' => true,
						'details'  => ['message'   => 'Queue not found']]);
		}
	  $queue = $queue->first();
		return response('OK', 200)
			->header('Content-Type', 'application/json')
			->setContent([
				'error' => false,
				'id' => $queue->id,
				'position' => $queue->current_position,
				'servicestarted' => $queue->start_time,
				'lastupdate' => $queue->update_time,
				'timepercustomer' => 0,
				'accepting_appointments' => $queue->accepting_appointments,
				'initial_free_slots' => $queue->initial_free_slots,
				'recurring_free_slot' => $queue->recurring_free_slot,
				'next_available_slot' => $queue->next_available_slot,
				'name' => $queue->name]);
	}	
}
