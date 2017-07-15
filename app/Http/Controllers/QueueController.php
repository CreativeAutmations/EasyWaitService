<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Queue as Queue;
use App\QueueAdmin as QueueAdmin;
use App\Appointment as Appointment;


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
			$managed_queues = QueueAdmin::where('user_id', '=', $user->id )
												->get();
			
			if ( $managed_queues->count() >= 5 ) 
			{
				return response('OK', 200)
					->header('Content-Type', 'application/json')
					->setContent([
						'error' => true,
						'code'  => 10010,
						'details'  => ['message'   => 'You have exceeded the maximum queues that can be created']]);
			} 



			$queue = new Queue();
			$queue['name'] = $qdetails['name'];
			$queue->save();

			# Associate queue with the creator as admin
			$qadmin = new QueueAdmin();
			$qadmin->user_id = $user->id;
			$qadmin->id = $queue->id;
			$qadmin->save();

			$managed_queues = QueueAdmin::where('user_id', '=', $user->id )
												->get();
			# Content: { id : 124 , name : "Queue 2" , queuelist : [ {id: 110, name : "Queue 1"} , {id: 124, name : "Queue 2"}] }

			$qidlist = array();
			foreach ($managed_queues as $admin_record) {
				$qidlist[] =  $admin_record->id;
			}

			$queuelist = Queue::find($qidlist);
			
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
		$managed_queues = QueueAdmin::where('user_id',$user->id)->get();
		if ( $managed_queues->isEmpty() ) {
			return response('No Content', 204)
				->header('Content-Type', 'application/json')
				->setContent([
					'error' => true,
					'code' => 101,
					'message' => 'no queues available']);
		} else 
		{
			$qidlist = array();
			foreach ($managed_queues as $admin_record) {
				$qidlist[] =  $admin_record->id;
			}

			$queuelist = Queue::find($qidlist);
			
			return response('OK', 200)
				->header('Content-Type', 'application/json')
				->setContent([
					'error' => false,
					'queues' => $queuelist]);
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
		if ( ! $this->isAdmin($user->id, $queue_id) ) {
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

   
   public function setAppointmentStatus($queue_id, $status) 
   {
		try 
		{
			$queue = Queue::find($queue_id);
			$queue->accepting_appointments  = $status;			
			$queue->save();

			return response('OK', 200)
				->header('Content-Type', 'application/json')
				->setContent([
					'error' => false,
					'id' => $queue_id,
					'accepting_appointments' => $queue->accepting_appointments]);
		} 
		catch (\Illuminate\Database\QueryException $e) 
		{
			return response('Unauthorized', 401)
				->header('Content-Type', 'application/json')
				->setContent([
					'error' => true,
					'code'  => 12,
					'details'  => ['message'   => 'Failed to get appointment status', 'exception' => $e ]]);
		} 			
   }
   
   public function removeAllAppointments($queue_id) 
   {
		try 
		{
			Appointment::where('queue_id', $queue_id)->delete();
		    $queue = Queue::find($queue_id);
			$queue->next_available_slot = 1;
			$queue->save();
	
			return response('OK', 200)
				->header('Content-Type', 'application/json')
				->setContent([
					'error' => false,
					'id' => $queue_id]);
		} 
		catch (\Illuminate\Database\QueryException $e) 
		{
			return response('Unauthorized', 401)
				->header('Content-Type', 'application/json')
				->setContent([
					'error' => true,
					'code'  => 12,
					'details'  => ['message'   => 'Failed to delete all appointments', 'exception' => $e ]]);
		} 			
   }

   
   public function areAppointmentsAccepted($queue_id) 
   {
		try 
		{
			$queue = Queue::find($queue_id);
			return $queue->accepting_appointments ; 
		} 
		catch (\Illuminate\Database\QueryException $e) 
		{
			return response('Unauthorized', 401)
				->header('Content-Type', 'application/json')
				->setContent([
					'error' => true,
					'code'  => 12,
					'details'  => ['message'   => 'Failed to get appointment status', 'exception' => $e ]]);
		} 			
   }
   
   
   public function useAvailablePosition($queue_id) 
   {
		try 
		{
			$queue = Queue::find($queue_id);

			# $queue->next_available_slot = $queue->next_available_slot + 1;

			# Minimum alloted position should be higher than the initial free slots
			if ( $queue->next_available_slot <  $queue->initial_free_slots ) 
			{
				$available_position = $queue->initial_free_slots + 1;
				$queue->next_available_slot = $available_position + 1;
				$queue->save();
				return $available_position;
			}	
			else if (  ($queue->recurring_free_slot > 0) && ($queue->next_available_slot % $queue->recurring_free_slot) == 0 ) 
			{
				$available_position = $queue->next_available_slot + 1;
				$queue->next_available_slot = $available_position + 1;
				$queue->save();
				return $available_position;
			} 
			else 
			{
				$available_position = $queue->next_available_slot ;
				$queue->next_available_slot = $available_position + 1;
				$queue->save();
				return $available_position;
			}
		} catch (\Illuminate\Database\QueryException $e) 
		{
			return -1;
		} 			

   }
   public function getAllAppointmentsOfQueue($queue_id)
   {
		try 
		{
			$appointments = Appointment::where('queue_id',$queue_id)->get();
			
			if ( $appointments->isEmpty() ) {
				return response('No Content', 204)
					->header('Content-Type', 'application/json')
					->setContent([
						'error' => true,
						'code' => 101,
						'message' => 'No Appointments Yet']);
			} else {
				return response('OK', 200)
					->header('Content-Type', 'application/json')
					->setContent([
						'error' => false,
						'appointments' => $appointments]);
			}
			
		} 
		catch (\Illuminate\Database\QueryException $e) 
		{
			return response('Unauthorized', 401)
				->header('Content-Type', 'application/json')
				->setContent([
					'error' => true,
					'code'  => 12,
					'details'  => ['message'   => 'Failed to retrieve appointments', 'exception' => $e ]]);
		} 			
   }
   public function getAllAppointmentsByUser($user_id, $queue_id)
   {
		try 
		{
			$appointments = Appointment::where([['queue_id',$queue_id],['user_id',$user_id]])->get();

			if ( $appointments->isEmpty() ) {
				return response('No Content', 204)
					->header('Content-Type', 'application/json')
					->setContent([
						'error' => true,
						'code' => 101,
						'message' => 'No Appointments Yet']);
			} else {
				return response('OK', 200)
					->header('Content-Type', 'application/json')
					->setContent([
						'error' => false,
						'appointments' => $appointments]);
			}
		} 
		catch (\Illuminate\Database\QueryException $e) 
		{
			return response('Unauthorized', 401)
				->header('Content-Type', 'application/json')
				->setContent([
					'error' => true,
					'code'  => 12,
					'details'  => ['message'   => 'Failed to retrieve appointments', 'exception' => $e ]]);
		} 			
	   
   }
   public function RetrieveAppointments($queue_id)
   {
		# return if not authenticated
		$JWTValidationResult = $this->checkToken();
		if ( $JWTValidationResult['error'] ) {
				return response('Unauthorized', 401)
                  ->header('Content-Type', 'application/json')
				  ->setContent($JWTValidationResult);
		}

		$token = $JWTValidationResult['token'];
		$user = JWTAuth::toUser($token);

		if ( $this->isAdmin($user->id, $queue_id) ) {
			return $this->getAllAppointmentsOfQueue($queue_id);
		} 
		else 
		{
			return $this->getAllAppointmentsByUser($user->id,$queue_id);
		}	
	   
   }
   
   public function cancelAppointment($user_id, $queue_id, $position)
   {
		try 
		{
			if ( $this->isAdmin($user_id, $queue_id) ) {
				Appointment::where([['queue_id',$queue_id], ['position',$position]])->delete();
			} 
			else 
			{
				Appointment::where([['user_id',$user_id], ['queue_id',$queue_id], ['position',$position]])->delete();
			}

			return response('OK', 200)
				->header('Content-Type', 'application/json')
				->setContent([
					'error' => false]);
		} 
		catch (\Illuminate\Database\QueryException $e) 
		{
			return response('Unauthorized', 401)
				->header('Content-Type', 'application/json')
				->setContent([
					'error' => true,
					'code'  => 12,
					'details'  => ['message'   => 'Failed to cancel appointments', 'exception' => $e ]]);
		} 			
   }
	public function ManageAppointments($queue_id)    
   {
		# return if not authenticated
		$JWTValidationResult = $this->checkToken();
		if ( $JWTValidationResult['error'] ) {
				return response('Unauthorized', 401)
                  ->header('Content-Type', 'application/json')
				  ->setContent($JWTValidationResult);
		}

		$token = $JWTValidationResult['token'];
		$user = JWTAuth::toUser($token);
		
	    $callparams = Input::only('action','reference','position');
		if ( $callparams['action'] == 'cancel' ) 
		{
			return $this->cancelAppointment($user->id, $queue_id, $callparams['position']);
			
		}

		if ( $callparams['action'] == 'book' ) 
		{
				if ( ! $this->areAppointmentsAccepted($queue_id) ) 
				{
					return response('Unauthorized', 401)
						->header('Content-Type', 'application/json')
						->setContent([
							'error' => true,
							'code'  => 12,
							'details'  => ['message'   => 'Bookings closed', 'exception' => $e ]]);
				}
				
				try {
					$appointment = new Appointment();
					$appointment->user_id = $user->id;
					$appointment->queue_id = $queue_id;
					$appointment->position = $this->useAvailablePosition($queue_id);
					$appointment->reference = $callparams['reference'];
					$appointment->save();

					return response('OK', 200)
						->header('Content-Type', 'application/json')
						->setContent([
							'error' => false,
							'id' => $queue_id,
							'position' => $appointment->position,
							'reference' => $appointment->reference]);
				} catch (\Illuminate\Database\QueryException $e) {
					return response('Unauthorized', 401)
						->header('Content-Type', 'application/json')
						->setContent([
							'error' => true,
							'code'  => 12,
							'details'  => ['message'   => 'Failed to book appointment', 'exception' => $e ]]);
				} 			
		}
		
		
		# If action is either of action='open' | action='close' | action='reset'
		# Retrurn if the user is not an administrator of the queue
		if ( ! $this->isAdmin($user->id, $queue_id) ) {
				return response('Unauthorized', 401)
                  ->header('Content-Type', 'application/json')
					->setContent([
						'error' => true,
						'details'  => ['message'   => 'Invalid Queue or Unauthorized Access']]);
		}
		
		if ( $callparams['action'] == 'open' ) 
		{
			return $this->setAppointmentStatus($queue_id , 1);
		} else if ( $callparams['action'] == 'close') 
		{
			return $this->setAppointmentStatus($queue_id , 0);
		} else if ( $callparams['action'] == 'reset' ) 
		{
			return $this->removeAllAppointments($queue_id );
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
		if ( ! $this->isAdmin($user->id, $queue_id) ) {
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
