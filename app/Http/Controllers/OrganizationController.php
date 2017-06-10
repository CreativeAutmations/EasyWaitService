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
	

   public function GetOrganization()    {
		$JWTValidationResult = $this->checkToken();
		if ( $JWTValidationResult['error'] ) {
				return response('Unauthorized', 401)
                  ->header('Content-Type', 'application/json')
				  ->setContent($JWTValidationResult);
		}

		$token = $JWTValidationResult['token'];
		$user = JWTAuth::toUser($token);
		
		$userOrganizations = UserOrganization::where('user_id', '=', $user->id )
												->get();
		if ( ! $userOrganizations->isEmpty()) {
			$userorganization = $userOrganizations->first();
			
			$organization = Organization::where('id', '=', $userorganization->org_id )
				->get()
				->first();

			
			return response('OK', 200)
				->header('Content-Type', 'application/json')
				->setContent(['organization' => $organization,
							  'error' => false,
							  'status' => $userorganization->status,
							  'isadmin' => $userorganization->isadmin]);
		} else {
			return response('Not Found', 404)
				->header('Content-Type', 'application/json')
				->setContent([
					'error' => true,
					'code'  => 21,
					'details'  => ['message'   => 'Not a member of any organization']]);
		}
	}
	
   public function GetMembershipStatus()    {
		$JWTValidationResult = $this->checkToken();
		if ( $JWTValidationResult['error'] ) {
				return response('Unauthorized', 401)
                  ->header('Content-Type', 'application/json')
				  ->setContent($JWTValidationResult);
		}

		$token = $JWTValidationResult['token'];
		$user = JWTAuth::toUser($token);
		
		# If user is already associated with any organization retrurn an error, with details about the organization user is associated with
		#	$userorg->isadmin = 1;
		$userOrganizations = UserOrganization::where('user_id', '=', $user->id )
												->where('isadmin', 1)
												->get();
		if ( ! $userOrganizations->isEmpty()) {
			$userorganization = $userOrganizations->first();
			$organizations = UserOrganization::where('org_id', '=', $userorganization->org_id )
												->where('status', 0)
												->get();

			return response('OK', 200)
				->header('Content-Type', 'application/json')
				->setContent([
					'error' => false,
					'code'  => 10,
					'pending'  => $organizations,
					'details'  => ['message'   => 'Membership Already Requested']]);
		} else {
			return response('Not Found', 404)
				->header('Content-Type', 'application/json')
				->setContent([
					'error' => true,
					'code'  => 10,
					'details'  => ['message'   => 'No Membership Requests Pending']]);
		}
	}

   public function RecordMembershipRequest( $user, $org_id)    {
		# If user is already associated with any organization retrurn an error, with details about the organization user is associated with
		$userOrganizations = UserOrganization::where('user_id', '=', $user->id )->get();
		if ( ! $userOrganizations->isEmpty()) {
			return response('Membership Already Requested', 412)
				->header('Content-Type', 'application/json')
				->setContent([
					'error' => true,
					'code'  => 10,
					'details'  => ['message'   => 'Membership Already Requested']]);
		}


		try {
			# Associate user with the organization
			$userorg = new UserOrganization();
			$userorg->user_id = $user->id;
			$userorg->org_id = $org_id;
			$userorg->status = 0;
			$userorg->isadmin = 0;
			$userorg->save();

			return response('OK', 200)
				->header('Content-Type', 'application/json')
				->setContent([
					'error' => false,
					'code'  => 10,
					'userorganization' => $userorg,
					'details'  => ['message'   => 'Membership Requested']]);
		} catch (\Illuminate\Database\QueryException $e) {
			return response('Internal Server Error', 500)
				->header('Content-Type', 'application/json')
				->setContent([
					'error' => true,
					'code'  => 12,
					'details'  => ['message'   => 'Database Exception']]);
		} 
   }
   public function isAdministrator( $user)    {
		$userOrganizations = UserOrganization::where('user_id', '=', $user->id )
												->where('isadmin',1)
												->get();
		if ( ! $userOrganizations->isEmpty()) {
			return true;
		}
		return false;
   }

   public function getUserOrganization( $user_id )    {
		$userOrganizations = UserOrganization::where('user_id', '=', $user_id )
												->get();
		if ( ! $userOrganizations->isEmpty()) {
			return $userOrganizations->first()->org_id;
		}
		return false;
   }

   public function isMembershipRequestForSameOrganization( $user1_id, $user2_id)    {
		$user1_org = $this->getUserOrganization($user1_id);
		$user2_org = $this->getUserOrganization($user2_id);
		
		if ( $user1_org  && 
				$user2_org && 
					( $user1_org == $user2_org)) {
			return true;
		}
		return false;
   }	

   public function activateUser( $user_id )    {
		
		UserOrganization::where('user_id', $user_id )
          ->update(['status' => 1]);
   }	
   
   public function ApproveMembershipRequest( $user, $user_id)    {
		# exit if the user is not an administrator
		if  ( ! $this->isAdministrator( $user) ) {
			return response('Operation allowed for administrators only', 412)
				->header('Content-Type', 'application/json')
				->setContent([
					'error' => true,
					'code'  => 10,
					'details'  => ['message'   => 'Operation allowed for administrators only']]);
		} 
		
		# Exit if the requesting user belong to some other organisation
		if( ! $this->isMembershipRequestForSameOrganization( $user->id, $user_id)) {
			return response('Membership Request Is For Different Organization', 412)
				->header('Content-Type', 'application/json')
				->setContent([
					'error' => true,
					'code'  => 10,
					'details'  => ['message'   => 'Membership Request Is For Different Organization']]);
		}
		
		# Activate the user
		try {
			$this->activateUser($user_id);

			return response('OK', 200)
				->header('Content-Type', 'application/json')
				->setContent([
					'error' => false,
					'code'  => 10,
					'details'  => ['message'   => 'Membership Approved']]);
		} catch (\Illuminate\Database\QueryException $e) {
			return response('Internal Server Error', 500)
				->header('Content-Type', 'application/json')
				->setContent([
					'error' => true,
					'code'  => 12,
					'details'  => ['message'   => 'Database Exception']]);
		} 
   }
	
	
   public function MembershipRequest()    {
		$JWTValidationResult = $this->checkToken();
		if ( $JWTValidationResult['error'] ) {
				return response('Unauthorized', 401)
                  ->header('Content-Type', 'application/json')
				  ->setContent($JWTValidationResult);
		}

		$token = $JWTValidationResult['token'];
		$user = JWTAuth::toUser($token);
		
		$organization_details = Input::only('org_id','action','user_id');
		if ( $organization_details['action'] == 'addrequest') {
			return $this->RecordMembershipRequest( $user, $organization_details['org_id']);
		} elseif ( $organization_details['action'] == 'approve') {
			return $this->ApproveMembershipRequest( $user, $organization_details['user_id']);
		} else {
			return response('Unsupported Action', 412)
				->header('Content-Type', 'application/json')
				->setContent([
					'error' => true,
					'code'  => 11,
					'details'  => ['message'   => 'Unsupported Action']]);
		}
	}

	
   public function UpdateOrganization()    {
		$JWTValidationResult = $this->checkToken();
		if ( $JWTValidationResult['error'] ) {
				return response('Unauthorized', 401)
                  ->header('Content-Type', 'application/json')
				  ->setContent($JWTValidationResult);
		}

		$token = $JWTValidationResult['token'];
		$user = JWTAuth::toUser($token);

		$userOrganizations = UserOrganization::where('user_id', '=', $user->id )->get();
		if ( ! $userOrganizations->isEmpty()) {
			# exit if the user is not an administrator
			if  ( ! $this->isAdministrator( $user) ) {
				return response('Operation allowed for administrators only', 412)
					->header('Content-Type', 'application/json')
					->setContent([
						'error' => true,
						'code'  => 10,
						'details'  => ['message'   => 'Operation allowed for administrators only']]);
			} 

			$userorganization = $userOrganizations->first();
			$organization = Organization::where('id', '=', $userorganization->org_id )
							->get()
							->first();
			$organization_details = Input::only('name','address','tax_registration','tax_commissionar');
			try {
				$organization['name'] = $organization_details['name'];
				$organization['address'] = $organization_details['address'];
				$organization['tax_registration'] = $organization_details['tax_registration'];
				$organization['tax_commissionar'] = $organization_details['tax_commissionar'];
				$organization->save();

				return response('OK', 200)
					->header('Content-Type', 'application/json')
					->setContent(['organization' => $organization]);
			} catch (\Illuminate\Database\QueryException $e) {
				return response('Database Error', 401)
					->header('Content-Type', 'application/json')
					->setContent([
						'error' => true,
						'code'  => 12,
						'details'  => ['message'   => 'Database Error']]);
			} 
			
			
		} else {
			return response('User is not a member of any organization', 412)
				->header('Content-Type', 'application/json')
				->setContent([
					'error' => true,
					'code'  => 10,
					'details'  => ['message'   => 'User is not a member of any organization']]);
		}
		
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
		$userOrganizations = UserOrganization::where('user_id', '=', $user->id )->get();
		if ( ! $userOrganizations->isEmpty()) {
			$userorganization = $userOrganizations->first();
			$organizations = Organization::where('id', '=', $userorganization->org_id )->get();
			$org = $organizations->first();
			return response('Precondition Failed', 412)
				->header('Content-Type', 'application/json')
				->setContent([
					'error' => true,
					'code'  => 10,
					'organization' => $org,
					'details'  => ['message'   => 'Organization Already Created']]);
		}
		
		
		# Create Organization
		$organization_details = Input::only('name','address','tax_registration','tax_commissionar');
		try {
			$organization = new Organization();
			$organization['name'] = $organization_details['name'];
			$organization['address'] = $organization_details['address'];
			$organization['tax_registration'] = $organization_details['tax_registration'];
			$organization['tax_commissionar'] = $organization_details['tax_commissionar'];
			$organization->save();

			# Associate user with the organization
			$userorg = new UserOrganization();
			$userorg->user_id = $user->id;
			$userorg->org_id = $organization->id;
			$userorg->status = 1;
			$userorg->isadmin = 1;
			$userorg->save();

			return response('OK', 200)
				->header('Content-Type', 'application/json')
				->setContent([
					'error' => false,
					'organization' => $organization]);
		} catch (\Illuminate\Database\QueryException $e) {
			return response('Unauthorized', 401)
				->header('Content-Type', 'application/json')
				->setContent([
					'error' => true,
					'code'  => 12,
					'details'  => ['message'   => 'Invalid Token']]);
		} 
	}
	
	   public function CreateQueue()    {
		
		return response('OK', 200)
			->header('Content-Type', 'application/json')
			->setContent([
			'id' => 124,
			'name' => "Queue 2" ]);
	}

}
