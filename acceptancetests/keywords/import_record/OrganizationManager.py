__version__ = '0.1'
import requests
import json

class OrganizationManager(object):

    def __init__(self, host, port=8000):
        self._host = host
        self._port = port
        self._hostURL = 'http://' + host + ':' + port


    def organization_membership_request(self, access_token, org_id):
		url = self._hostURL +'/api/user/organization'
        json_payload_list = {}
		json_payload_list['id'] = org_id;
        call_headers = {'Authorization': 'Bearer '+ access_token}
        r = requests.post(url, json=json_payload_list, headers=call_headers)
        r.raise_for_status()


    def create_organization(self, access_token,org_name,address,tax_registration,tax_commissionar):
        url = self._hostURL +'/api/organizations'
        json_payload_list = {}
		json_payload_list['org_name'] = org_name;
		json_payload_list['address'] = address;
		json_payload_list['tax_registration'] = tax_registration;
		json_payload_list['tax_commissionar'] = tax_commissionar;
		
        call_headers = {'Authorization': 'Bearer '+ access_token}
        r = requests.post(url, json=json_payload_list, headers=call_headers)
        r.raise_for_status()

		server_response = r.json()
		print server_response
		return server_response["id"]


    def approve_organization_membership(self, access_token,email):
		url = self._hostURL +'/api/user/organization'
        json_payload_list = {}
		json_payload_list['email'] = email;
		json_payload_list['action'] = 'approve';
        call_headers = {'Authorization': 'Bearer '+ access_token}
        r = requests.put(url, json=json_payload_list, headers=call_headers)
        r.raise_for_status()

