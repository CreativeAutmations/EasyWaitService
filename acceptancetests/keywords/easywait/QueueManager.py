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
