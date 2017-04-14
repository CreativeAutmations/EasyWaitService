<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Organization as Organization;
use App\UserOrganization as UserOrganization;

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
	

   /**
	 * Create Queue
	 *
	 * @return Response
	 */
   public function CreateQueue()    {
		
		return response('OK', 200)
			->header('Content-Type', 'application/json')
			->setContent([
			'id' => 124,
			'name' => "Queue 2" ,
			'queuelist' => [
				['id' => 110, name =>  "Queue 1"] ,
				['id' => 124, name =>  "Queue 2"]
			]);
	}
}
