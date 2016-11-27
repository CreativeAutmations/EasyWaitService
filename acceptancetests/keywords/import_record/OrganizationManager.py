__version__ = '0.1'
import requests
import json

class OrganizationManager(object):

	def __init__(self, host, port=8000):
		self._host = host
		self._port = port
		self._hostURL = 'http://' + host + ':' + port

	def create_dictionary_from_input_string(self, inputstring):
		input_array = inputstring.split(',')
		json_payload_list = {}
		for keyval in input_array:
			keyval_array = keyval.split(':')
			if len(keyval_array) == 2:
				key = keyval_array[0]
				key = key.lower().strip().replace(" ","_")
				value = keyval_array[1]
				json_payload_list[key] = value.strip()
		return json_payload_list

	def create_organization(self, access_token,name,address,tax_registration,tax_commissionar):
		url = self._hostURL +'/api/organizations'
		json_payload_list = {}
		json_payload_list['name'] = name
		json_payload_list['address'] = address
		json_payload_list['tax_registration'] = tax_registration
		json_payload_list['tax_commissionar'] = tax_commissionar
		print(json_payload_list)
		call_headers = {'Authorization': 'Bearer '+ access_token}
		r = requests.post(url, json=json_payload_list, headers=call_headers)
		r.raise_for_status()
		server_response = r.json()
		print server_response

	def get_organization_id(self, access_token):
		url = self._hostURL +'/api/organizations'
		call_headers = {'Authorization': 'Bearer '+ access_token}
		r = requests.get(url, headers=call_headers)
		r.raise_for_status()
		server_response = r.json()
		print server_response
		return server_response['organization']['org_id']

	def request_organization_membership(self, access_token,org_id):
		url = self._hostURL +'/api/organizations/membership'
		json_payload_list = {}
		json_payload_list['org_id'] = org_id
		json_payload_list['action'] = 'addrequest'
		print(json_payload_list)
		call_headers = {'Authorization': 'Bearer '+ access_token}
		r = requests.post(url, json=json_payload_list, headers=call_headers)
		r.raise_for_status()
		server_response = r.json()
		print server_response

	def approve_organization_membership(self, access_token,user_id):
		url = self._hostURL +'/api/organizations/membership'
		json_payload_list = {}
		json_payload_list['user_id'] = user_id
		json_payload_list['action'] = 'approve'
		print(json_payload_list)
		call_headers = {'Authorization': 'Bearer '+ access_token}
		r = requests.post(url, json=json_payload_list, headers=call_headers)
		r.raise_for_status()
		server_response = r.json()
		print server_response

	def get_pending_membership_state(self, access_token):
		url = self._hostURL +'/api/organizations/membership'
		json_payload_list = {}
		json_payload_list['status'] = 'addrequest'
		print(json_payload_list)
		call_headers = {'Authorization': 'Bearer '+ access_token}
		r = requests.get(url, json=json_payload_list, headers=call_headers)
		r.raise_for_status()
		server_response = r.json()
		return server_response

	def approve_organization_membership_for_all(self, access_token):
		pending_membership_state = self.get_pending_membership_state(access_token)
		print pending_membership_state['pending']
		for i in pending_membership_state['pending']:
			print 'Approving Membership For Id: ' + str(i['user_id'])
			self.approve_organization_membership(access_token, i['user_id'])

	def update_organization(self, access_token,name,address,tax_registration,tax_commissionar):
		url = self._hostURL +'/api/organizations'
		json_payload_list = {}
		json_payload_list['name'] = name
		json_payload_list['address'] = address
		json_payload_list['tax_registration'] = tax_registration
		json_payload_list['tax_commissionar'] = tax_commissionar
		print(json_payload_list)
		call_headers = {'Authorization': 'Bearer '+ access_token}
		r = requests.put(url, json=json_payload_list, headers=call_headers)
		r.raise_for_status()
		server_response = r.json()
		print server_response
