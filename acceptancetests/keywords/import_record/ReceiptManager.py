__version__ = '0.1'
import requests
import json

class ReceiptManager(object):

    def __init__(self, host, port=8000):
        self._host = host
        self._port = port
        self._hostURL = 'http://' + host + ':' + port

    def create_receipt(self, bill_details, access_token):
        url = self._hostURL +'/api/receipts'
        print url
        bill_detaials_array = bill_details.split(',')
        json_payload_list = {}
        for param_val in bill_detaials_array:
                param_val_array = param_val.split(':')
                if len(param_val_array) == 2:
                        key = param_val_array[0]
                        key = key.lower().replace(" ","_").strip()
                        value = param_val_array[1]
                        json_payload_list[key] = value.strip()


        print json_payload_list

        call_headers = {'Authorization': 'Bearer '+ access_token}
        r = requests.post(url, json=json_payload_list, headers=call_headers)
        server_response = r.json()
        print server_response
        return server_response

    def update_receipt(self, bill_number, fields_to_update, access_token):
        url = self._hostURL +'/api/receipts/' + bill_number
        print url
        fields_to_update_array = fields_to_update.split(',')
        json_payload_list = {}
        for param_val in fields_to_update_array:
                param_val_array = param_val.split(':')
                if len(param_val_array) == 2:
                        key = param_val_array[0]
                        key = key.lower().replace(" ","_").strip()
                        value = param_val_array[1]
                        json_payload_list[key] = value.strip()


        print json_payload_list

        call_headers = {'Authorization': 'Bearer '+ access_token}
        r = requests.put(url, json=json_payload_list, headers=call_headers)
        server_response = r.json()
        print server_response
        return server_response

    def get_receipt(self, bill_number, access_token):
        url = self._hostURL +'/api/receipts/' + bill_number
        print url

        call_headers = {'Authorization': 'Bearer '+ access_token}
        r = requests.get(url, headers=call_headers)
        server_response = r.json()
        print server_response
        return server_response

    def get_audit_trail(self, bill_number, access_token):
        url = self._hostURL +'/api/audit/' + bill_number
        print url

        call_headers = {'Authorization': 'Bearer '+ access_token}
        r = requests.get(url, headers=call_headers)
        server_response = r.json()
        print server_response
        return server_response

    def get_receipts_on_or_after_bill_date(self, bill_date, access_token):
        url = self._hostURL +'/api/receipts/search' + bill_number
        print url
        payload = {'bill_date': {'value': bill_date, 'operation': '>='}}
        call_headers = {'Authorization': 'Bearer '+ access_token}
        r = requests.post(url, headers=call_headers, json=payload)
        server_response = r.json()
        print server_response
        return server_response

