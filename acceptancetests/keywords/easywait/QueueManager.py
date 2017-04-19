__version__ = '0.1'
import requests
import json

class QueueManager(object):

    def __init__(self, host, port=8000):
        self._host = host
        self._port = port
        self._hostURL = 'http://' + host + ':' + port

    def create_queue(self, access_token , qname ):
        url = self._hostURL +'/api/queue'
        print url
        json_payload_list = {}
        json_payload_list['name'] = qname
        print json_payload_list

        call_headers = {'Authorization': 'Bearer '+ access_token}
        r = requests.post(url, json=json_payload_list, headers=call_headers)
        server_response = r.json()
        print server_response
        r.raise_for_status()
        return server_response["id"]

    def perform_queue_action(self, access_token , qid , action ):
        url = self._hostURL +'/api/queue/'+ str(qid)
        print url
        json_payload_list = {}
        json_payload_list['action'] = action
        print json_payload_list

        call_headers = {'Authorization': 'Bearer '+ access_token}
        r = requests.post(url, json=json_payload_list, headers=call_headers)
        server_response = r.json()
        print server_response
        r.raise_for_status()
        return server_response["position"]

    def set_queue_preferences(self, access_token , qid ,  initial_free_slots , recurring_free_slot):
        url = self._hostURL +'/api/queue/'+ str(qid) + '/preferences'
        print url
        json_payload_list = {}
        json_payload_list['initial_free_slots'] = initial_free_slots
        json_payload_list['recurring_free_slot'] = recurring_free_slot
        print json_payload_list

        call_headers = {'Authorization': 'Bearer '+ access_token}
        r = requests.post(url, json=json_payload_list, headers=call_headers)
        server_response = r.json()
        print server_response
        r.raise_for_status()

    def start_session(self, access_token , qid ):
        self.perform_queue_action( access_token , qid , 'reset' )
        self.set_appointment_status(access_token , qid ,  'reset')
        self.set_appointment_status(access_token , qid ,  'open')

    def set_appointment_status(self, access_token , qid ,  action):
        url = self._hostURL +'/api/queue/'+ str(qid)+'/appointment'
        print url
        json_payload_list = {}
        json_payload_list['action'] = action
        print json_payload_list

        call_headers = {'Authorization': 'Bearer '+ access_token}
        r = requests.post(url, json=json_payload_list, headers=call_headers)
        server_response = r.json()
        print server_response
        r.raise_for_status()


    def book_appointment(self, access_token , qid , reference):
        url = self._hostURL +'/api/queue/'+ str(qid)+'/appointment'
        print url
        json_payload_list = {}
        json_payload_list['action'] = 'book'
        json_payload_list['reference'] = str(reference)
        print json_payload_list

        call_headers = {'Authorization': 'Bearer '+ access_token}
        r = requests.post(url, json=json_payload_list, headers=call_headers)
        server_response = r.json()
        print server_response
        r.raise_for_status()
        return server_response["position"]

    def retrieve_appointments(self, access_token , qid ):
        url = self._hostURL +'/api/queue/'+ str(qid)+'/appointment'
        print url
        json_payload_list = {}
        print json_payload_list

        call_headers = {'Authorization': 'Bearer '+ access_token}
        r = requests.get(url, json=json_payload_list, headers=call_headers)
        server_response = r.json()
        print server_response
        r.raise_for_status()


    def cancel_appointment(self, access_token , qid ,  booked_position):
        url = self._hostURL +'/api/queue/'+ str(qid)+'/appointment'

        print url
        json_payload_list = {}
        json_payload_list['action'] = 'cancel'
        json_payload_list['position'] = str(booked_position)
        print json_payload_list

        call_headers = {'Authorization': 'Bearer '+ access_token}
        r = requests.post(url, json=json_payload_list, headers=call_headers)
        server_response = r.json()
        print server_response
        r.raise_for_status()

    def finish_session(self, access_token , qid ):
        self.perform_queue_action( access_token , qid , 'reset' )
        self.set_appointment_status(access_token , qid ,  'reset')
        self.set_appointment_status(access_token , qid ,  'close')
