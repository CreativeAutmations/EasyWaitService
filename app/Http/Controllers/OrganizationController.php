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


class OrganizationController extends Controller
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
	 * Create Organization
	 *
	 * @return Response
	 */
   public function CreateOrganization()    {
		$JWTValidationResult = $this->checkToken();
		if ( $JWTValidationResult['error'] ) {
				return response('Unauthorized', 401)
                  ->header('Content-Type', 'application/json')
				  ->setContent($JWTValidationResult);
		}

		$token = $JWTValidationResult['token'];
		$user = JWTAuth::toUser($token);
		
		# If user is already associated with any organization retrurn an error, with details about the organization user is associated with
		
		# Create Organization
		$organization_details = Input::only('name','address','tax_registration','tax_commissionar');
		try {
			$organization = new Organization();
			$organization['name'] = $organization_details['name'];
			$organization['address'] = $organization_details['address'];
			$organization['tax_registration'] = $organization_details['tax_registration'];
			$organization['tax_commissionar'] = $organization_details['tax_commissionar'];
			$organization->save();
			return response('OK', 200)
				->header('Content-Type', 'application/json')
				->setContent($organization);
			return Response::json(['message' => 'Organization Created']);
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
